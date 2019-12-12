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
        $loadeds = []; 
    
    function __construct(bool $cacheable = true){
        
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
    private function sintax_if() : object {
        $condition = '/\((.*?)\)/sim';
        $end_condition = '/@endif/i'; 
      
        // Reemplazamos los if
        $regex = '#@if(.+)\)#';
        $has = preg_match_all($regex, $this->content, $matches);
        if($has){
            foreach ($matches[0] as $key => $value) {
                // Se obtiene la condición
                if(preg_match($condition, $value, $matches)){
                    $conditional = $matches[1];
                    $strif = "<?php if($conditional):?>"; 
                    $this->content = str_replace($value, $strif, $this->content);
                }  
            } 
        }

        // Reemplazamos los @else:
        $regex = '#@else#';
        $replace = '<?php else: ?>';
        $this->replace($regex, $replace);
  
        // Reemplazamos los @endif:
        $regex = '#@endif#';
        $replace = '<?php endif ?>';
        $this->replace($regex, $replace);

        return $this;
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
    // Funcion que aplica una sintaxis propia  a las vistas
    // Todos los comandos de las vista deben enpezar por --
    private function sintax(){
        $this->includes();
        $this->autoId();
        $this->sintax_if();
        //$this->sintax_vars();
        $this->style_scoped();
        $this->script_scoped(); 
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
        $con = $this->content; 
        $has = preg_match_all('#\$\$(\w+)#is', $this->content, $matches);
        if($has){
            foreach ($matches[0] as $key => $value) {
                $str = '<?=$' . trim($value, '\$') . '?>';
                $this->content = str_replace($value, $str, $this->content);
            }
        }
        return $con;
    }
    // Comando --id -> Genera un id único para todo el documento.
    private function autoId() : string{
        $id = $this->uniqid();
        $this->content = str_ireplace('--id', $id, $this->content);
        return $this->content;
    }
    // Minificamos el contenido de los tags script 
    private function minify_script() : void{
        $has_script = preg_match_all('/<script[^>]*>(.*?)<\/script>/si', $this->content, $matches);
        if($has_script){
            foreach ($matches[1] as $key => $value) {
                // MINIMIFICAMOS JS
                $minifier = new Minify\JS;
                $minifier->add($value);
                $minified = $minifier->minify();
                // Reemplazamos el contenido
                $this->content = str_ireplace($value, $minified, $this->content);
            }
        }
    }
        // Minificamos el contenido de los tags script 
    private function minify_style() : void{
        $has_style = preg_match_all('/<style[^>]*>(.*?)<\/style>/si', $this->content, $matches);
        if($has_style){
            foreach ($matches[1] as $key => $value) {
               $this->less($value);
            }
        }
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
                    // Transformamos la nueva sintaxis en las vistas 
                    // No se la aplicamos a los componentes para que mantengan la encapsulación
                    if($path != \APP\VIEWS\COMPONENTS) $this->sintax();

                    // Se comprimen las etiquetas script y style
                    $this->minify_script();
                    $this->minify_style();

                    // Seccion componentes personales
                    $this->components();

                    $a = $this->arg('style');

                    if(isset($a['lang']) && $a['lang'] == 'less') 
                        $this->less( $this->extract('style')['content'] );
                    
                    $this->build_js( $this->extract('script')['content'] );
                    
                    if( $file == self::MAIN_PAGE ) $this->queue();

                    //Añadimos nombre de espacio a todos los archivos 
                    $this->add_name_space();

                    // Compresión salida html
                    //$this->content  = $this->compress_code($this->content);
                    
                    file_put_contents($file_build, $this->content, LOCK_EX  );
                }
            }
        } 
    }
    // Busca y trata componentes personalizados en las plantillas 
    private function components(){
        // Guardar en variable que componentes tenemos
        if(!isset($this->components)){
            $str = "";
            $this->components = []; 
            $route = \APP\VIEWS\COMPONENTS;
            $gestor = opendir($route);
            $regex_components = ''; 
            while (($file = readdir($gestor)) !== false)  {
                if ($file != "." && $file != "..") {
                    $arr = explode('.', $file);
                    $this->components[] = $arr[0];
                    $str .= $arr[0] . '|';
                    $this->regcomponents = trim($str, '|'); 
                }
            }
        }

        // Buscar componentes existentes en el archivo
        $regex = "/<(\s)*($this->regcomponents){1}?[^>]*>(.*)<\/($this->regcomponents){1}?>/";
        $count = preg_match_all($regex, $this->content, $matches);

        // Cambiar los componentes por las instancias de clase
        // AKI :: Acabar componentes
    }
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
/*             $folder = \FOLDERS\BUILD_JS;
            file_put_contents($folder.$nameClass.'.js', $class_js); */
            
            // Eliminamos TODO EL BLOQUE JS del componente
/*             
            // Esta busqueda da error en algunas ocasiones se deja para su revision
            $regex = '#\<script((.|\n)*)</script>#';
            $this->content = preg_replace($regex, ' ', $this->content);
 */
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

        return str_replace($class_js, $replace, $this->content);
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