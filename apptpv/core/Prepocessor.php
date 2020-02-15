<?php

namespace app\core;

use MatthiasMullie\Minify;

class Prepocessor
{
    const
        BUILD = \FOLDERS\HTDOCS . 'build/',
        CACHE_FILE = \FOLDERS\CACHES  . 'cache_views.ini',
        FOLDERS_NATIVE_VIEWS = \FOLDERS\NATIVE_VIEWS,
        MAIN_PAGE = \FOLDERS\NATIVE_VIEWS . 'index.phtml', 
        FOLDER_COMPONENTS = \APP\VIEWS\MYCOMPONENTS; // Carpeta contenedora de los componentes

    private
        $el, // clase Tag -> Elemento html del archivo procesado
        $cache,
        $isModified = false,
        $content,
        $queue,
        $queueJS = [],
        $loadeds = [],
        $components = null,
        $bc;                        // var de proteccion para bucles

    function __construct(bool $cacheable = true)
    {
        // Se eliminan todos los archivos de la carpeta build (reinicializa)
        $this->deleteDirectory(self::BUILD);

        // Guardar en variable que componentes tenemos
        $this->search_exist_components();

        //Añade el link a bundle
        $this->queue = "<script src='./build/" . \FILE\JS . "'></script>";

        // Indicamos si cacheamos el proceso
        $this->cacheable = $cacheable;
        $this->cache = (file_exists(self::CACHE_FILE)) ? parse_ini_file(self::CACHE_FILE) : [];

        // Reseteamos el archivo de construccion build de js para agrupar todas las clases
        if (file_exists(\FILE\BUNDLE_JS)) unlink(\FILE\BUNDLE_JS);
        if (!file_exists(self::BUILD)) mkdir(self::BUILD, 0775, true);

        // Inicia compilacion de los archivos
        // Primero aseguramos la carga de los componentes
        $this->show_files(self::FOLDER_COMPONENTS);
        $this->show_files(self::FOLDERS_NATIVE_VIEWS);

        $this->cache_record($this->cache);
    }
    // Funcion preprocesadora de los archivos
    // Lee archivos de directorios y los directorios anidados
    private function show_files(String $path)
    {
        $dir = opendir($path);
        while ($current = readdir($dir)) {
            if ($current != "." && $current != "..") {
                $build_path = str_replace(self::FOLDERS_NATIVE_VIEWS, '', $path . $current);
                $file = $path . $current;
                $this->file = $file;
                $file_build =  self::BUILD . $build_path;
                $this->path = $path;    
                // Creamos carpeta si no existe         
                $build_folder = self::BUILD . str_replace(self::FOLDERS_NATIVE_VIEWS, '', $path);
                if (!file_exists($build_folder)) mkdir($build_folder, 0775, true);

                if (is_dir($file)) {
                    // DIRECTORIOS 
                    if (!file_exists($file_build)) mkdir($file_build, 0775, true);
                    $this->show_files($file . '/');
                } else {
                    // ARCHIVOS
                    if(!file_exists($file_build)){
                        $this->build($file, $file_build);
                    }
                }
            }
        }

        // Cargamos las clases js hijas que no se pudieron cargar anteriormente
        $this->load_class_childrens();
    }
    /**
     * Publica la aplicación en la carpeta build
     */
    private function build($file, $file_build) : self
    {
        // Obtenemos el ontenido del archivo se instancia Tag
        $this->get_content($file);

        // Quitamos los comentarios 
        $this->clear();

        // No se la aplicamos a los componentes para que mantengan la encapsulación
        if (!$this->isComponent()) $this->sintax();

        // Construimos el build.js con todos las clases
        $this->build_js();

        if ($file == self::MAIN_PAGE) $this->queue();

        // Compresión salida html
        if (!ENV) $this->compress_code();

        file_put_contents($file_build, $this->el->element());
        
        return $this;
    }
    /**
     * Limpia el contenido de comentarios
     */
    private function clear(): self
    {
        $con = $this->el->content();
        $this->el->content(preg_replace('/(<!--(.|\s)*?-->|[^\:]\/\/(.*))/', '', $con));
        return $this;
    }
    // Obtiene el contenido del archivo y crea el tag principal 
    private function get_content(String $file): self
    {
        $this->el = new Tag(file_get_contents($file));
        return $this;
    }
    // Funcion que aplica una sintaxis propia  a las vistas
    // Proceso de compilación de las plantillas
    private function sintax()
    {
        // Añadimos el id al documento
        $this->el->replace('--id', $this->el->id());
        $this
            ->sintax_if()
            ->sintax_for()
            ->includes()
            ->search_components()
            ->sintax_vars();
        // Encapsulación de los estilos
        foreach ($this->tags('style') as $tag) {
            $this->add_style_scope($tag);

            if ($tag->get('lang') == 'less') {
                $this->less($tag->content());
            }
            // eliminamos el argumento scoped
            $tag->del('scoped');
            $tag->del('lang');
        }
        // Encapsulación de los scripts
        foreach ($this->tags('script') as $tag) {
            $this->add_script_scope($tag);
            $tag->del('scoped');
        }
    }
    /**
     * Sintaxis para @if() ... @endif
     */
    private function sintax_if(): self
    {

        $regex_conditional = '/@if(\s)*?\((.)*?\)(.)*?@endif/sim';
        $start_condition = '/@if(\s)*?\((.)*?\)/sim';
        $end_condition = '/@endif/i';;

        if (
            preg_match_all($regex_conditional, $this->el->content(), $matches)
        ) {
            foreach ($matches[0] as $value) {
                // Se obtiene la condición
                if (preg_match($start_condition, $value, $matches)) {
                    $condition = preg_replace('/@if(\s)*?\(/sim', '', $matches[0]);
                    $condition = preg_replace('/\)$/', '', $condition);
                    if (empty($condition)) $condition = null;
                    $valcon = false;
                    eval('if ($condition) { $valcon = true; }');
                    if ($valcon) {
                        // Imprimimos el contenido dentro del condicional
                        $replace = preg_replace($start_condition, '', $value);
                        $replace = preg_replace($end_condition, '', $replace);
                        $this->replace($value, $replace);
                    } else {
                        // Eliminamos todo el condicional 
                        $this->replace($value, '');
                    }
                }
            }
        }
        return $this;
    }
    /**
     * Comprueba si es un componente comparando su ruta
     */
    private function isComponent(): bool
    {
        return ($this->path == \APP\VIEWS\COMPONENTS || $this->path == \APP\VIEWS\MYCOMPONENTS);
    }
    private function add_script_scope(Tag $tag): self
    {
        if ($tag->get('scoped')) {
            $lastContent = $tag->content();
            $tag->content(
                "(function(){
                   $lastContent
               })()"
            );
            $this->replace($lastContent, $tag->content());
        }
        return $this;
    }
    private function add_style_scope(Tag $tag): self
    {
        if ($tag->get('scoped')) {
            $lastContent = $tag->content();
            $nameTag = $this->search_first_tag();
            $first = $this->tags($nameTag)[0];

            // Quitamos las reglas principales
            $content = $tag->content();
            $content = preg_replace('/@import.*?;/', '', $content);
            $content = preg_replace('/@charser.*?;/', '', $content);

            // Se coloca el id a los estilos 
            $tag->content("#{$first->id()}{{$content}}");

            $this->replace($lastContent, $tag->content());
        };
        return $this;
    }
    /**
     * Funcion auxiliar para reemplazar el contenido de la pagina
     */
    private function replace($arg, $val = null): self
    {
        switch (gettype($arg)) {
            case 'string':
                $this->el->content(str_replace($arg, $val, $this->el->content()));
                break;
            case 'array':
                foreach ($arg as $key => $val) {
                    $this->el->content(str_replace($key, $val, $this->el->content()));
                }
                break;
        }
        return $this;
    }
    /**
     * Procesado de la sintaxis @for() ... @endfor
     */
    private function sintax_for(): self
    {
        if (
            $len = preg_match_all('/@for\s*\((.*?)\)(.*?)@endfor/sim', $this->el->content(), $matches)
        ) {
            for ($i = 0; $i < $len; $i++) {
                $res = '';
                $cond = $matches[1][$i];
                $body = $matches[2][$i];
                $struct = $matches[0][$i];

                // Formato {"a":1,"b":2,"c":3,"d":4,"e":5} sin comillas exteriores
                if (is_string($cond)) $arr = json_decode($cond);

                foreach ($arr as $key => $value) {
                    $str = str_replace('$$value', $value, $body);
                    $res .= str_replace('$$key', $key, $str);
                }
                $this->replace($struct, $res);
            }
        }
        return $this;
    }
    private function compress_code(): self
    {
        $search = array(
            '/\>[^\S ]+/s',  // remove whitespaces after tags
            '/[^\S ]+\</s',  // remove whitespaces before tags
            '/(\s)+/s'       // remove multiple whitespace sequences
        );

        $replace = array('>', '<', '\\1');
        $this->el->element(preg_replace($search, $replace, $this->el->element()));
        return $this;
    }
    private function less(String $content)
    {
        //COMPILAMOS LESS
        $less = new \lessc;

        $content_less = $less->compile($content);

        // MINIMIFICAMOS
        $minifier = new Minify\CSS;
        $minifier->add($content_less);
        $content_min = $minifier->minify();

        $this->replace($content, $content_min);
    }
    private function search_first_tag()
    {
        $regex = "/\<(\w*?) ([^>]*?)>(.*?)<\/\\1>/si";
        preg_match($regex, $this->el->content(), $matches);
        return $matches[1] ?? false;
    }
    /**
     *   Devuelve todos los argumentos de un tag
     *  @return array de la clase Tag
     */
    private function tags(string $tag): array
    {
        $regex = "/\<($tag) ([^>]*?)>(.*?)<\/\\1>/si";
        /**
         * 0 -> Todo
         * 1 -> tag
         * 2 -> argimentos
         * 3 -> contenido
         */
        if (
            $len = preg_match_all($regex, $this->el->content(), $matches)
        ) {
            for ($i = 0; $i < $len; $i++) {
                $a[$i] = new Tag($matches[0][$i]);
            }
        }
        return $a ?? [];
    }
    // Procesa la sintaxis de los elementos @include()
    private function includes(): self
    {
        if (
            $len = preg_match_all('/\s\@include\s*\((.*?)\)\s/', $this->el->content(), $matches)
        ) {
            for ($i = 0; $i < $len; $i++) {
                $this->el->content(
                    str_replace(
                        $matches[0][$i],
                        "<?php include({$matches[1][$i]})?>",
                        $this->el->content()
                    )
                );
            }
        }
        return $this;
    }
    // Busca sibolo $ para y lo reemplaza por variables php
    private function sintax_vars(): self
    {
        $content = $this->el->content();
        if (
            preg_match_all('#\$\$(\w+\-?\w*)#is', $content, $matches)
        ) {
            for ($i = 0; $i < count($matches[0]); $i++) {
                $str = '<?=$' . trim($matches[1][$i] ?? null, '\$') . '?>';
                $content = str_replace($matches[0][$i], $str, $content);
            }
            $this->el->content($content);
        }

        return $this;
    }
    // Carga de los componentes creados en la carpeta
    private function search_exist_components()
    {
        $this->components = [];
        $folder = \APP\VIEWS\MYCOMPONENTS;
        if (!file_exists($folder)) mkdir($folder, 0777, true);
        $gestor = opendir($folder);
        // Busca los componentes en la carpeta de vistas componentes
        while (($file = readdir($gestor)) !== false) {
            if ($file != "." && $file != "..") {
                $arr = explode('.', $file);
                $this->components[] = $arr[0];
            }
        }
    }
    // Buscar componentes existentes en el contenido 
    // el parametro ha de ser enviado por referencia
    private function search_components(): self
    {
        foreach ($this->components as $component) {
            // Primero buscamos los que contienen tag de cierre ya que pueden contener otros elementos anidados
            if (
                $len = preg_match_all(
                    "#<($component)(\s[^>\/]*)?>(.*?)<\/\g{1}>#si",
                    $this->el->content(),
                    $matches
                )
            ) {
                $this
                    ->process_components($matches)
                    ->search_components();
            }
            // Después buscamos los que no tienen tag de cierre
            if (
                preg_match_all(
                    "/<\s*($component)(\s.*?|\s*?)\/>/s",
                    $this->el->content(),
                    $matches
                )
            ) {
                $this->process_components($matches, $this->el->content());
            }
        }
        return $this;
    }
    /**
     * Procesa los componentes personalizados de las plantillas 
     */
    private function process_components($matches): self
    {
        // Transforma en una clase componente
        $len = count($matches[0]);

        for ($i = 0; $i < $len; $i++) {
            // Convertimos la cadena en arreglos para pasar los datos al componente
            $argData = $this->args_to_array($matches[2][$i]);
            // Creamos la la instancia de clase 
            $typeComponent = $matches[1][$i];

            // Comprobamos si el componente alberga contenido

            // Si existe lo preprocesamos
            if (isset($matches[3])) {
                // Si encuentra contenido en el componente comprueba que si tiene componentes anidados
                $str = str_replace('"', "'", $matches[3][$i]);
                $component_content = ', ' .  isset($matches[3]) ? '"' . $str . '"' : '';
            } else {
                $component_content = 'false';
            }
            // Instanciamos la clase de componentes

            ob_start(); # apertura de bufer
            file_put_contents(
                \FOLDERS\VIEWS . "tmp.phtml",
                str_replace(
                    $matches[0][$i],
                    "<?php new \app\core\Components('$typeComponent',$argData, $component_content);?>",
                    $this->el->content(),
                    $count
                )
            );
            include(\FOLDERS\VIEWS . "tmp.phtml");
            $this->el->content(ob_get_contents());
            ob_end_clean(); # cierre de bufer
        }
        return $this;
    }
    private function args_to_array($content)
    {
        $str_data = '';
        $regex = '#(.+?)\s*=\s*(["\'])(.+?)\g{2}#s';
        if (
            preg_match_all($regex, $content, $matches_component)
        ) {
            $len_c = count($matches_component[0]);
            // Cambio de comillas para que se adecue a la sintaxis JSON
            for ($j = 0; $j < $len_c; $j++) {
                $str_key = trim($matches_component[1][$j]);
                $str_value = trim($matches_component[3][$j]);

                $value = str_replace("'", '"', $str_value);
                $key = trim(str_replace("'", '"', $str_key));
                $str_data .=  "'$key'=>'$value',";
            }
        }

        $str_data = trim($str_data, ',');
        return " Array($str_data)";
    }
    private function queue(): self
    {
        $this->replace('</head>', $this->queue . '</head>');
        return $this;
    }
    /**
     * Extraemos las clases de los componentes 
     * y las cargamos en un ambito global
     */
    private function build_js()
    {
        $this->bc = 0;
        if (
            $tags = $this->el->search('script')
        ) {
            foreach ($tags as $tag) {
                $class_js = $tag->content();
                // Si contiene alguna clase la enviamos al archivo bundle.js
                if (preg_match_all('/ class (\w*?).*{/i', $class_js, $matches)) {
                    if (
                        // Comprueba si la clase extiende de alguna otra
                        $len = preg_match_all('/ (\w*?) extends (\w*?)\s*{/i', $class_js, $matches)
                    ) {
                        for ($i = 0; $i < $len; $i++) {
                            if (in_array($matches[2][$i], $this->loadeds)) {
                                $this->load_class_js($class_js);
                            } else {
                                /*
                                0 => nombre de la clase
                                1 => nombre de la clase padre 
                                2 => todo el contenido
                                */
                                $this->queueJS[] = [$matches[1][$i], $matches[2][$i], $class_js];
                            }
                        }
                    } else {
                        $this->load_class_js($class_js);
                    }
                    // Eliminamos la clase del documento html
                    $this->el->unset($tag);
                }
            }
        }
        $this->load_queueJS();
    }
    private function load_queueJS()
    {
        do {
            $this->bc++;
            foreach ($this->queueJS as $key => $value) {
                if (in_array($value[1], $this->loadeds)) {
                    $this->load_class_js($value[2]);
                    unset($this->queueJS[$key]);
                }
            }
            // Mensaje de error de clase extendida no encontrada
            if ($this->bc > 10) {
                die("ERROR!! <br> La clase js {$value[0]}, no ha podido ser cargada!!");
                break;
            }
        } while (count($this->queueJS) > 0);
    }
    /**
     * Carga las clases al archivo bundle.js
     */
    private function load_class_js($class_js)
    {
        if (preg_match('/class (\w*){1}/si', $class_js, $matches)) {
            if (!in_array($matches[1], $this->loadeds)) {
                // MINIMIFICAMOS JS
                $minifier = new Minify\JS;
                $minifier->add($class_js);
                file_put_contents(\FILE\BUNDLE_JS, $minifier->minify(), FILE_APPEND);

                // Registramos la clase como cargada 
                $this->loadeds[] = $matches[1];
            }
        }
    }
    /**
     * Carga de las clases hijas js
     */
    private function load_class_childrens()
    {
        foreach ($this->queueJS as $key => $value) {
            if (in_array($key, $this->loadeds)) {
                // MINIMIFICAMOS JS
                $minifier = new Minify\JS;
                $minifier->add($value);
                $class_min = $minifier->minify();
                file_put_contents(\FILE\BUNDLE_JS, $class_min, FILE_APPEND);
                // Registramos la clase como cargada 
                $this->loadeds[] = $key;
                unset($this->queueJS[$key]);
            }
        }
    }
    private function cache_record(array $cache)
    {
        if ($this->isModified) {
            $out = '';
            foreach ($cache as $k => $v) {
                $out .= $k . ' = "' . $v . '"' . "\n";
            }
            file_put_contents(self::CACHE_FILE, $out, LOCK_EX);
            return true;
        } else return false;
    }
    private function deleteDirectory($dir)
    {
        if (!$dh = @opendir($dir)) return;
        while (false !== ($current = readdir($dh))) {
            if ($current != '.' && $current != '..') {
                if (!@unlink($dir . '/' . $current))
                    $this->deleteDirectory($dir . '/' . $current);
            }
        }
        closedir($dh);
        rmdir($dir);
    }
}
