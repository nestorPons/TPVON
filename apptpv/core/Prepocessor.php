<?php  namespace app\core;
use MatthiasMullie\Minify;

class Prepocessor{
    const 
        BUILD = \FOLDERS\HTDOCS . 'build/',
        CACHE_FILE = \FOLDERS\CORE  . 'cache_views.ini',
        FOLDERS_NATIVE_VIEWS = \FOLDERS\NATIVE_VIEWS,
        MAIN_PAGE = \FOLDERS\NATIVE_VIEWS . 'index.phtml',
        NAMESPACE_COMPONENTS = 'app\controllers\components';

    private
        $cache_class_js = null,  
        $cache,
        $isModified = false,
        $content,
        $queue,
        $loadeds = [],
        $components = null; 
    
    function __construct(bool $cacheable = true){
        // Guardar en variable que componentes tenemos
        $this->searchComponents();

        $this->queue = "<script src='./build/".\FILE\JS."'></script>";
        $this->cacheable = $cacheable;
        $this->cache = (file_exists(self::CACHE_FILE)) ? parse_ini_file(self::CACHE_FILE) : [];

        // Reseteamos el archivo de construccion build de js para agrupar todas las clases
        if(file_exists(\FILE\BUNDLE_JS)) unlink(\FILE\BUNDLE_JS);

        if(!file_exists(self::BUILD)) mkdir(self::BUILD, 0775, true);

        // Inicia compilacion de los archivos
        $this->showFiles(self::FOLDERS_NATIVE_VIEWS);
        $this->cache_record($this->cache);
    }
    private function sintax_if() : void {
        $regex_conditional = '/@if(\s)*?\((.)*?\)(.)*?@endif/sim'; 
        $start_condition = '/@if(\s)*?\((.)*?\)/sim';
        $end_condition = '/@endif/i'; 
        $has = preg_match_all($regex_conditional, $this->content, $matches);

        if($has){
            foreach ($matches[0] as $key => $value) {
                // Se obtiene la condición
                if(preg_match($start_condition, $value, $matches)){
                    $condition = preg_replace('/@if(\s)*?\(/sim','',$matches[0]);
                    $condition = preg_replace('/\)$/','',$condition);
                    if(empty($condition)) $condition = null;
                    $valcon = false; 
                    eval('if ($condition) { $valcon = true; }');
                   if($valcon){
                       // Imprimimos el contenido dentro del condicional
                        $replace = preg_replace($start_condition,'',$value); 
                        $replace = preg_replace($end_condition,'',$replace);
                        $this->content = str_replace($value, $replace, $this->content);
                    } else {
                        // Eliminamos todo el condicional 
                        $this->content = str_replace($value, '', $this->content);
                    }
                }
              
            } 
        }
    }
    // Funcion que aplica una sintaxis propia  a las vistas
    // Todos los comandos de las vista deben enpezar por --
    private function sintax(){
        $this->autoId();
        $this->includes();
        $this->sintax_if();
        $this->sintax_vars();
        $this->style_scoped();
        $this->script_scoped(); 
        $this->components();
    }
    // Funcion auxiliar de reemplazo de content
    private function replace ($regex, $replace) : object{
        $has = preg_match_all($regex, $this->content, $matches);
        if($has){
            foreach ($matches[0] as $key => $value) {
                $this->content = str_replace($value, $replace, $this->content);
            } 
        }
        return $this;
    }
    private function arg(String $tag){
        $return = [];

        $pos_tag_open = strpos($this->content, "<$tag");  
        $pos_end_tag_open = strpos($this->content, '>',$pos_tag_open);
        $tag_style_open = substr($this->content, $pos_tag_open, $pos_end_tag_open - $pos_tag_open); 
        
        $args = str_replace("<$tag", '', $tag_style_open); 
        $args = explode(' ',$args);
        foreach($args as $value){
            if(!empty($value)) {
                $arg = explode('=' , $value);
                if(!empty($arg[1])){
                     $v = trim($arg[1],'"');
                     $v = trim($v,"'");
                     $k = trim($arg[0]);
                    $return[$k] = $v;
                }
            }
        }

        return $return;
    }
    private function compress_code($code){  
        $search = array(
        '/\>[^\S ]+/s',  // remove whitespaces after tags
        '/[^\S ]+\</s',  // remove whitespaces before tags
        '/(\s)+/s'       // remove multiple whitespace sequences
        );

        $replace = array('>','<','\\1');
        $code = preg_replace($search, $replace, $code);
        return $code;
    }
    // Extrae el tag del html (Solo el primero)
    private function extract($tag): Array {
        $attr = []; 
        $pos_tag_open       = strpos($this->content, "<$tag"); 
        $pos_tag_open_end   = strpos($this->content, ">",$pos_tag_open);
        $pos_tag_close      = strpos($this->content, "</$tag>");
        $pos_end_tag_open   = strpos($this->content, '>',$pos_tag_open);
        $pos_end_tag_open += 1 ;

        $str_tag = substr($this->content, $pos_tag_open, $pos_tag_open_end); 
        if(preg_match_all('/[A-Za-z]*\s*=\s*".*?"[\s]*?/', $str_tag, $matches)){
            foreach($matches[0] as $match ){
                $arr = explode('=', $match); 
                $a = trim($arr[1],'"'); 
                $attr[$arr[0]] = trim($a,"'");
            }
        }
        return [
            'content' => trim(substr($this->content, $pos_end_tag_open, $pos_tag_close - $pos_end_tag_open)),
            'attr'    => $attr
        ];
    }
    // Añade atributos a la etiqueta
    private function addAttr($tag, $attr, $value){
        $regex = "/<\s*{$tag}.*?>/";
        if(preg_match($regex, $this->content, $matches)){
            $search = substr($matches[0], 0 ,-1); 
            $replace = "{$search} {$attr}='{$value}'";
            $this->content = str_replace($search, $replace, $this->content);
        };  
    }
    private function getContent(String $file) : String{
        return $this->content = file_get_contents($file);
    }
    // Elimina los comentarios html
    function removeHTMLComments() : String{
        return $this->content = preg_replace('/<!--(.|\s)*?-->/', '', $this->content);
    }
    private function less(String $content){
        //COMPILAMOS LESS
        $less = new \lessc;
        $content_less = $less->compile($content);  

        // MINIMIFICAMOS
        $minifier = new Minify\CSS;
        $minifier->add($content_less);
        $content_min = $minifier->minify();
        $this->content = str_replace($content, $content_min, $this->content);
    }  
    // Generador de ids únicos
    private function uniqid(){
        // Se le añade unprefijo para que siempre empieze por una letra
        return uniqid('id');
    }
    // Devuelve todos los argumentos de un tag
    private function args($tag){
        $return = []; 
        $regex = '#(\w)+="(.*?)"#';
        $has = preg_match_all($regex, $tag, $matches);
        foreach($matches[0] as $value ){
            $arr = explode('=',$value); 
            $return[$arr[0]] = trim( $arr[1], '"');    
        }
        return $return;
    }
    private function includes(){
        $regex = '#<include(.)*?[^<]*>#';
        $has = preg_match_all($regex, $this->content, $matches);
        if($has){
            foreach($matches[0] as $value ){
                $args = $this->args($value);
                $str = "<?php include({$args['src']})?>";
                $this->content = str_replace($value,$str,$this->content);
            }
        }
        return $has;
    }
    // Busca sibolo $ para y lo reemplaza por variables php
    private function sintax_vars(){
        $has = preg_match_all('#\$\$(\w+)#is', $this->content, $matches);
        if($has){
            for($i = 0; $i < count($matches[0]); $i++) {
                $str = '<?=$' . trim($matches[1][$i], '\$') . '?>';
                $this->content = str_replace($matches[0][$i], $str, $this->content);
            }
        }
    }
    // Comando --id -> Genera un id único para todo el documento.
    private function autoId() : string{
        $id = $this->uniqid();
        $this->content = str_ireplace('--id', $id, $this->content);
        return $this->content;
    }
    //  Comportamiento scoped para script-> individualiza el style en el objeto contenedor
    private function script_scoped() : string{
        $has_scoped = preg_match_all('/<script[^>]*scoped>(.*?)<\/script>/si', $this->content, $matches);
        if($has_scoped){
            // Quitar los scopes 
            foreach ($matches[0] as $key => $value) {
                // Quitamos el comando scope
                $noscope = str_replace(' scoped', '', $value);
                $this->content = str_replace($value, $noscope, $this->content);
            }
            foreach ($matches[1] as $key => $value) {
                // encapsular en contenido en una funcion autoejecutable js
                $content =  '(function(){'. $value .'})();'; 
                $this->content = str_replace($value, $content, $this->content);
            }
        }
        return $this->content;
    }
    // Comando scoped para style-> individualiza el style en el objeto contenedor
    private function style_scoped() : string{
        $has_scoped = preg_match('/<style(.)*?scoped[^<]*>/', $this->content, $matches);

        if($has_scoped) {
            // Comprobamos si es un componente o una sección
            $tag = strpos($this->content, '<component') !== false ? 'component' : 'section'; 
            // Quitamos el comando scope
            $noscope = str_replace('scoped', '', $matches[0]); 
            $this->content = str_replace($matches[0], $noscope, $this->content);
            // Buscamos el id del elemento contenedor, o es un componente o una sectión
            // Si no tiene id se crea uno y se coloca al style y al componente
            $id = $this->extract($tag)['attr']['id'] ?? null; 
            if(!$id){
                $id = $this->uniqid(); 
                $this->addAttr($tag,'id',$id);    
            } 
            // Quitamos las reglas principales
            $content = $this->extract('style')['content']; 
            $content = preg_replace('/@import.*?;/', '', $content);  
            $content = preg_replace('/@charser.*?;/', '', $content);  
            // Se coloca el id a los estilos 
            $this->content = str_replace($content, "#{$id}{{$content}}", $this->content);
        };
        return $this->content;
    }
    private function showFiles(String $path){
        $dir = opendir($path);
        // Lee archivos de directorios y los directorios anidados
        while ($current = readdir($dir)){
            if( $current != "." && $current != "..") {
                
                $build_path = str_replace(self::FOLDERS_NATIVE_VIEWS, '', $path.$current);
                $file = $path.$current;
                $file_build =  self::BUILD . $build_path;

                if(is_dir($file)) {
                    // DIRECTORIOS 
                    if(!file_exists($file_build)) mkdir($file_build, 0775, true);
                    $this->showFiles($file.'/');
                } else {
                    // ARCHIVOS
                    $this->getContent($file);
                    
                    // Quitamos los comentarios 
                    $this->removeHTMLComments();
                    // Transformamos la nueva sintaxis en las vistas 
                    // No se la aplicamos a los componentes para que mantengan la encapsulación
                    if($path != \APP\VIEWS\COMPONENTS && $path != \APP\VIEWS\MYCOMPONENTS) $this->sintax();
                
                    $a = $this->arg('style');

                    if(isset($a['lang']) && $a['lang'] == 'less') 
                        $this->less( $this->extract('style')['content'] );
                    
                    $this->build_js( $this->extract('script')['content'] );
                    
                    if( $file == self::MAIN_PAGE ) $this->queue();

                    //Añadimos nombre de espacio a todos los archivos 
                    $this->add_name_space();

                    // Compresión salida html
                    if(!ENV) $this->content  = $this->compress_code($this->content);
                    
                    file_put_contents($file_build, $this->content, LOCK_EX);
                }
            }
        } 
    }
    // Carga de los componentes creados en la carpeta
    private function searchComponents(){
        $str = "";
        $this->components = []; 
        $folder = \VIEWS\MYCOMPONENTS;
        if (!file_exists($folder)) mkdir($folder, 0777, true);
        $gestor = opendir($folder);
        $regex_components = ''; 
        // Busca los componentes en la carpeta de vistas componentes
        while (($file = readdir($gestor)) !== false)  {
            if ($file != "." && $file != "..") {
                $arr = explode('.', $file);
                $this->components[] = $arr[0];
            }
        }
    }
    // Busca y trata componentes personalizados en las plantillas 
    private function components(){
        // Buscar componentes existentes en el directorio componentes
        foreach($this->components as $component ){
            $regex = "/<($component){1}?\s+([^>]*)(>(.*)<\/($component){1}?>|\/>)/";
            $count = preg_match_all($regex, $this->content, $matches);
            if($count){
                // Si encuentra alguno lo transforma en una clase componente
                $len = count($matches[0]); 
                for($i = 0; $i < $len; $i++){
                    // Convertimos la cadena en arreglos para pasar los datos al componente
                    $regex = '#(.+?)\s*=\s*["\'](.+?)["\'](\s|$)+?#';
                    $count = preg_match_all($regex, $matches[2][$i], $matches_component);
                    $str_data = '';
                    if($count){
                        $len_c = count($matches_component[0]); 
                        for($j = 0; $j < $len_c; $j++){
                            $str_data .= "'". trim($matches_component[1][$j]) . "'=>'". trim($matches_component[2][$j]) . "',";  
                        }
                    }
                    $str_data = trim($str_data, ',');
                    // Creamos la la instancia de clase 
                    $typeComponent = $matches[1][$i]; 
                    $arg_data = ($str_data != '') ? ", Array($str_data)" : '';
                    $replace = "<?php new \app\core\Components('$typeComponent' $arg_data) ;?>";
                    $this->content = str_replace($matches[0][$i], $replace, $this->content); 
                }
            }
        }
    }
    // Añade nombre de espacio componentes para no referenciarlos en la construcción
    // (desuso) Eliminar en la version 2.0 
    private function add_name_space(){
        $this->content = "<?php namespace " . self::NAMESPACE_COMPONENTS ."?>" . $this->content; 
    }
    private function queue(){
        $content = $this->content; 
        $new_content = str_replace('</head>', $this->queue . '</head>', $content);
        $this->content = $new_content;
    }
    private function include(){
        
        $regex = '/include\(([^()]*[^()]*)\)/';
        $b = preg_match_all($regex, $this->content, $r);

        if($b){
            for($i = 0; $i < $b; $i++){
                eval('$file =' . $r[1][$i] . ';');
                $out =file_get_contents($file);
                $this->content = str_replace($r[0][$i], $out, $this->content);  
            }
        } 
    }
    /**
     * Extraemos las clases de los componentes 
     * y las cargamos en un ambito global
     */
    private function build_js($class_js){

        $strFile = file_exists(\FILE\BUNDLE_JS) 
            ? \file_get_contents(\FILE\BUNDLE_JS) 
            : ''; 
        // Buscamos clases js en el archivo
        $regex = '/class [A-Za-z0-9]{1,150}/';
        if(preg_match($regex, $class_js, $r)){
            // Buscamos clases padre y las agregamos a la carga principal de la aplicación 
            $regex_extends = '/extends [A-Za-z0-9]*/';
            if(preg_match($regex_extends, $class_js, $match)){
    
                $main_class = explode(' ',$match[0])[1];
                $src = "<script src='./js/$main_class.min.js'></script>";
                if (!strpos($this->queue, $src)) $this->queue = $src . $this->queue; 
        
            }

            // Creamos el archivo de la clase
            $ac = explode(' ',$r[0]);
            $nameClass= $ac[1];

            // MINIMIFICAMOS JS
            $minifier = new Minify\JS;
            $minifier->add($class_js);
            $strFile .= $minifier->minify();
            file_put_contents(\FILE\BUNDLE_JS, $strFile);            
                       
            // Eliminamos el tag script del documento
            $this->content = str_replace($class_js,'', $this->content);

            // Registramos la clase como cargada 
            $this->loadeds[] = $nameClass;

            // Añadimos una cola para agregar los enlaces en el index o puerta principal 
            //$this->queue .= "<script src='./build/js/$nameClass.js'></script>";
            
            // Buscamos la clase en la sesión
            if(!empty($this->cache_class_js) && in_array($ac[1], $this->cache_class_js)){
                // si ya esta cargada
                $this->content = str_replace($class_js, '', $this->content);
            } else {
                // Cargamos en la cache de la sesión
                $this->cache_class_js[] = $ac[1];
            }
        }
        // MINIMIFICAMOS JS
        $minifier = new Minify\JS;
        $minifier->add($class_js);
        $replace = $minifier->minify();
        return  str_replace($class_js, $replace, $this->content);
    }
    private function cache_record(Array $cache){
        if($this->isModified){
            $out = '';
            foreach($cache as $k => $v) {
                $out .= $k.' = "'.$v.'"'. "\n";
            }
            file_put_contents(self::CACHE_FILE, $out, LOCK_EX  );
            return true;
        } else return false;
    }
    private function isModified($file){
        return true;
        
        if($this->cacheable){
            // Comprobamos si han habido modificaciones
            if (!isset($this->cache[$file]) || $this->cache[$file] != filectime($file)){
                $this->isModified = true;
                $this->cache[$file] = filectime($file);
                return true;
            } else return false;
        } else return true;
    }
}