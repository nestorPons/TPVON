<?php

namespace app\core;

/**
 * Clase de madre de los componentes html
 */
class Components extends Tag
{
    const FOLDER_COMPONENTS = \APP\VIEWS\MYCOMPONENTS;

    function __construct(String $type, array $data = null, string $content = null)
    {
        if ($data) {
            foreach ($data as $key => $val) {
                // Atributos booleanos
                if ($key == 'required') $val = 'required';
                if ($key == 'disabled') $val = 'disabled';
                if ($key == 'readonly') $val = 'readonly';
                if ($key == 'checked')  $val = 'checked';

                $val = ltrim($val, '<?=');
                $val = rtrim($val, '?>');

                $this->attrs([$key => $val]);
            }
        }
        $this->prefix = $type;
        $this->type = $type;

        // Obtenemos el componente en crudo
        $this->element(file_get_contents(self::FOLDER_COMPONENTS . "$type.phtml"));

        // añadimos el id al componente
        $id = $this->attrs('id') ?? uniqid($this->prefix());
        $this->id($id);

        // Buscamos la palabra reservada --content y la cambiamos por el contenido 
        $this->replace('--content', $content);
        $this
            ->sintax()
            ->style_scoped()
            ->script_scoped()
            ->clear()
            ->search_components();

        echo ($this->element());
    }
    private function clear(): self
    {
        $this->preg("/[\r\n|\n|\r|\s]+/", " ");
        return $this;
    }
    // Procesa la sintaxis de la plantillas 
    private function sintax(): self
    {
        $this->replace('--id', $this->id());
        // Procesando condicional if
        $this->sintax_if();
        // Bucle for 
        $this->sintax_for();
        // Imprimiendo las variables de la clase a plantilla 
        // Modificando las propiedades o tags de los elementos html
        if (
            $len = preg_match_all('#\$\$(\w+\-?\w*)#is', $this->body(), $matches)
        ) {
            for ($i = 0; $i < $len; $i++) {
                $prop = $matches[1][$i];
                if (!is_null($this->attrs($prop))) {
                    $value = $this->attrs($prop) ?? '';
                    $this->replace($prop, $value);
                } else {
                    // En caso que no exista la propiedad la eliminamos 
                    $regex = "#\w+?\s*=\s*[\"']\s*\\$\\$$prop\b\"#";
                    $this->preg($regex, '');
                    $this->replace("\$\$$prop", '');
                }
            }
        }
        return $this;
    }
    private function sintax_for(): self
    {
        $regex_conditional = '/@for\s*?\((.*?)\)(.*?)@endfor/sim';
        if (
            preg_match_all($regex_conditional, $this->body(), $matches)
        ) {
            for ($i = 0; $i < count($matches[0]); $i++) {
                //m-select m-table
                $prop = trim($matches[1][$i], '$$');
                $content = '';

                if (\property_exists($this, $prop)) {
                    // valor predeterminado
                    $arr = (is_array($this->attrs($prop)))
                        // Se convierte el valor en un array
                        ? $this->attrs($prop)
                        : json_decode($this->attrs($prop));

                    $cont = $matches[2][$i];
                    foreach ($arr as $key => $value) {
                        $option = str_replace('$$key', $key, $cont);
                        if (!is_null($this->attrs($value)) && $this->attrs('value') == $value) {
                            $option = preg_replace('#\>#', ' selected>', $option);
                        }
                        $option = str_replace('$$value', $value, $option);
                        $content .= $option;
                    }
                    $this->replace($matches[0][$i], $content);
                } else {
                    // Si no existe la propiedad quitamos el elemento
                    $this->replace($matches[0][$i], '');
                }
            }
        }
        return $this;
    }
    private function sintax_if(): self
    {
        $regex_conditional = '/@if\s*?\((.*?)\)(.*?)@endif/sim';
        $has = preg_match_all($regex_conditional, $this->body(), $matches);
        if ($has) {
            for ($i = 0; $i < count($matches[0]); $i++) {
                $prop = trim($matches[1][$i], '$$');

                if (\property_exists($this, $prop)) {
                    $condition = $this->attrs($prop);
                    $valcon = false;
                    eval('if ($condition) { $valcon = true; }');
                    if ($valcon)
                        $this->replace($matches[0][$i], $matches[2][$i]);
                    else
                        $this->replace($matches[0][$i], '');

                    // Quitamos los espacios en blanco
                    $this->replace("[\n|\r|\n\r]", "");
                } else {
                    // Si no existe la propiedad quitamos el elemento
                    $this->replace($matches[0][$i], '');
                }
            }
        }

        return $this;
    }

    private function style_scoped(): self
    {
        if (
            preg_match('/<style.*?scoped[^<]*?>(.*?)<\/style>/mis', $this->body(), $matches)
        ) {
            // Quitamos el comando scope
            $tagstyle = str_replace('scoped', '', $matches[0]);
            // Quitamos las reglas principales
            $tagstyle = preg_replace('/@import.*?;/', '', $tagstyle);
            $tagstyle = preg_replace('/@charser.*?;/', '', $tagstyle);

            // Se coloca el id a los estilos 
            $less = new \lessc;
            $content_less = $less->compile('#' . $this->id() . '{' . $matches[1] . '}');

            $tagstyle = str_replace($matches[1], $content_less, $tagstyle);
            $this->replace($matches[0], $tagstyle);
        }
        return $this;
    }
    // Comportamiento scoped para script-> individualiza el style en el objeto contenedor
    private function script_scoped(): self
    {
        $has_scoped = preg_match_all('/<script[^>]*scoped>(.*?)<\/script>/si', $this->body(), $matches);
        if ($has_scoped) {
            // Quitar los scopes 
            foreach ($matches[0] as $key => $value) {
                // Quitamos el comando scope
                $noscope = str_replace(' scoped', '', $value);
                $this->replace($value, $noscope);
            }
            foreach ($matches[1] as $key => $value) {
                // encapsular en contenido en una funcion autoejecutable js
                $env =  '(function(){' . $value . '})();';
                $this->replace($value, $env);
            }
        }
        return $this;
    }
    // Buscar componentes existentes en el contenido 
    private function search_components(): self
    {

        
        $components = [];
        $folder = \APP\VIEWS\MYCOMPONENTS;
        $gestor = opendir($folder);
        // Busca los componentes en la carpeta de vistas componentes
        while (($file = readdir($gestor)) !== false) {
            if ($file != "." && $file != "..") {
                $arr = explode('.', $file);
                $components[] = $arr[0];
            }
        }

        foreach ($components as $type) {
            // Primero buscamos los que contienen tag de cierre ya que pueden contener otros elementos anidados
            if (
                $len = preg_match_all(
                    "/<($type)(\s[^>\/]*)?(>(.*)<\/\\1|\/)>?/si",
                    $this->body(),
                    $matches
                )
            ) {
                for ($i = 0; $i < $len; $i++) {
                    $argData = $this->args_to_array($matches[2][$i]);
                    $cont = $matches[4] ?? null;
                    if ($cont) {
                        // Si encuentra contenido en el componente comprueba que si tiene componentes anidados
                        $str = str_replace('"', "'", $cont[$i]);
                        $content = ', ' .  isset($cont) ? '"' . $str . '"' : '';
                    } else {
                        $content = 'false';
                    }

                    // Se crea nuevo componente Y SE EJECUTA
                    ob_start(); # apertura de bufer

                    file_put_contents(
                        \FOLDERS\VIEWS . "tmp.phtml",
                        "<?php new \app\core\Components('$type',$argData, $content);?>"
                    );

                    include(\FOLDERS\VIEWS . "tmp.phtml");

                    ob_end_clean(); # cierre de bufer
                }
            }
        }
    
        return $this;
    }
    private function args_to_array($content)
    {
        $str_data = '';
        $regex = '#(.+?)\s*=\s*(["\'])(.+?)\g{2}#s';
        if (
            $len = preg_match_all($regex, $content, $matches_component)
        ) {
            // Cambio de comillas para que se adecue a la sintaxis JSON
            for ($j = 0; $j < $len; $j++) {

                $str_key = trim($matches_component[1][$j]);
                $str_value = trim($matches_component[3][$j]);
                $value = str_replace("'", '"', $str_value);
                $key = trim(str_replace("'", '"', $str_key));

                // Si es una variable que me cambie las comillas 
                // Si no que me mantenga la nomenclatura comilla simple para json
                $str_data .= (preg_match('/\$\$/', $value))
                    ? "'$key'=>\"$value\","
                    : "'$key'=>'$value',";
            }
        }

        $str_data = trim($str_data, ',');

        return " Array($str_data) ";
    }
}
