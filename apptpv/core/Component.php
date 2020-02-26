<?php

namespace app\core;

/**
 * Clase de madre de los componentes html
 */
class Component extends Tag
{
    use Sintax, ToolsComponents;
    const FOLDER_COMPONENTS = \VIEWS\MYCOMPONENTS;

    function __construct(string $type, $data = null, string $content = null)
    {
        if ($data) {
            if (is_string($data)) {
                // Tratamos el texto si lleva tags de php
                $data = self::search_globals_vars($data);
                $data = json_decode($data);
            }
            foreach ($data as $key => $val) {
                // Atributos booleanos
                if ($key == 'required') $val = 'required';
                if ($key == 'disabled') $val = 'disabled';
                if ($key == 'readonly') $val = 'readonly';
                if ($key == 'checked')  $val = 'checked';

                $this->attrs($key, $val);
            }
        }
        $this->prefix = $type;
        $this->type = $type;

        // Obtenemos el componente en crudo
        $this->element(file_get_contents(self::FOLDER_COMPONENTS . "$type.phtml"));

        // añadimos el id al componente
        $id = $this->attrs('id') ?? uniqid($this->prefix());
        $this->id($id);

        // Tratamos el texto si lleva tags de php
        $content = stripcslashes($content);
        $content = self::search_globals_vars($content);

        // Buscamos la palabra reservada --content y la cambiamos por el contenido 
        $this->replace('--content', $content);
        $this
            ->sintax()
            ->style_scoped()
            ->script_scoped()
            ->clear();

        // Buscamos componentes anidados
        foreach ($this->search_components($this->body()) as $tag) {
            $nested = new Component($tag->type(), $tag->attrs(), $tag->body());
            $this->replace($tag->code(), $nested->element());
        }
    }
    public function print(): void
    {
        print($this->element());
    }
    // Procesa la sintaxis de la plantillas 
    private function sintax(): self
    {
        // Añadimos el id a la plantilla si se pone --id
        $this->replace('--id', $this->id());

        // Imprimiendo las variables de la clase a plantilla 
        // Modificando las propiedades o tags de los elementos html

        // Procesando condicional if

        $this->sintax_if();
        // Bucle for 
        $this->sintax_for();

        if (
            $len = preg_match_all('/\$\$(\w+\-?\w*)/is', $this->element(), $matches)
        ) {

            for ($i = 0; $i < $len; $i++) {
                $prop = $matches[1][$i];
                if (!is_null($this->attrs($prop))) {
                    $value = $this->attrs($prop) ?? '';
                    $this->replace('$$' . $prop, $value);
                } else {
                    // En caso que no exista la propiedad la eliminamos 
                    $regex = "/\w+?\s*=\s*[\"']\s*\\$\\$$prop\b\"/";
                    $this->preg($regex, '');
                    $this->replace("\$\$$prop", '');
                }
            }
        }
        return $this;
    }
    /**
     * Busqueda de variables globales $_FILES que son cargadas en los controladores 
     * para pasar variables a las vistas
     */
    private static function search_globals_vars(string $txt = null): ?string
    {
        if (
            $len = preg_match_all('/<\?=\$_FILES\[\"(.*?)\"\]\?>/', $txt, $matches)
        ) {
            for ($i = 0; $i < $len; $i++) {
                $var = $_FILES[$matches[1][$i]];
                $val = is_array($var) ? json_encode($var) : $var;
                $txt = str_replace($matches[0][$i], $val, $txt);

                // Quitar las comillas en los arrays y objetos json
                $txt = str_replace('"[', '[', $txt);
                $txt = str_replace(']"', ']', $txt);
                $txt = str_replace('}"', '}', $txt);
                $txt = str_replace('"{', '{', $txt);
            }
        }

        return $txt;
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
}
