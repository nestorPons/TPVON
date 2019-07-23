<?php  namespace app\core;
session_start();
use MatthiasMullie\Minify;

class Prepocessor{
    const BUILD = \FOLDERS\HTDOCS . 'build/';
    const CACHE_FILE = \FOLDERS\CORE  . 'cache_views.ini'; 
    const FOLDERS_NATIVE_VIEWS = \FOLDERS\NATIVE_VIEWS; 

    private
        $cache_class_js,  
        $cache,
        $isModified = false,
        $content,
        $queue; 
    
    function __construct(bool $cacheable = true){
        $this->cache_class_js = $_SESSION['cache_class_js']??null;
        $this->cacheable = $cacheable;
        $this->cache = (file_exists(self::CACHE_FILE)) ? parse_ini_file(self::CACHE_FILE) : [];

        if(!file_exists(self::BUILD)) mkdir(self::BUILD, 0775, true);
 
        $this->showFiles(self::FOLDERS_NATIVE_VIEWS);
        $this->cache_record($this->cache);
    }
    private function args(String $tag){
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
    private function extract($tag){
        $pos_tag_open = strpos($this->content, "<$tag"); 
        $pos_tag_close = strpos($this->content, "</$tag>");
        $pos_end_tag_open = strpos($this->content, '>',$pos_tag_open);
        $pos_end_tag_open += 1 ;

        return substr($this->content, $pos_end_tag_open, $pos_tag_close - $pos_end_tag_open);
    }
    private function getContent(String $file){
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
    private function showFiles(String $path){
    
        $dir = opendir($path);
        
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
                    $a = $this->args('style');
//pr($file);          
                    //$this->include();

                    if(isset($a['lang']) && $a['lang'] == 'less') 
                        $this->less( $this->extract('style'));
                    
                    $this->js($this->extract('script'));
                    
                    file_put_contents($file_build, $this->content, LOCK_EX  );
                }
            }
        } 
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
    private function js($content){ 
        // Comprobamos si existe una clase y la cacheamos par no volver a mandarla
        $regex = '/class [A-Za-z0-9]{1,150}/';

        if(preg_match($regex, $content, $r)){

            $folder = \FOLDERS\CLASSES_JS;
            $file_index = \FOLDERS\VIEWS . 'index.phtml';
            $ac = explode(' ',$r[0]);
            $nameClass= $ac[1];
            
            $regex = '#\<script(.*)>((.|\n)*)</script>#';
            $len = preg_match($regex, $this->content, $matches);            
            if(!file_exists($folder)) mkdir($folder, 0775, true);
            file_put_contents($folder.$nameClass.'.js', $matches[2]);
            
            // Creamos el vínculo al archivo de la clase 
            $this->queue .= "<script scr='$folder$nameClass.js'></script>";
//AKI :: añadiendo scripts al index o puerta de entrada principal
            $content = file_get_contents($file_index); 
            $new_content = str_replace('</head>', $tag . '</head>', $content);
            $this->wait_put($folder.$nameClass.'.js', $new_content);
            file_put_contents($file_index, $new_content);    

          /*   for($i = 0; $i < $len; $i++){
                pr(htmlspecialchars($matches[0][$i][1]));
            } */
           
            // Buscamos la clase en la sesión
            if(!empty($this->cache_class_js) && in_array($ac[1], $this->cache_class_js)){
                // si ya esta cargada

                $this->content = str_replace($content, '', $this->content);
            } else {
                // Cargamos en la cache de la sesión
                $this->cache_class_js[] = $ac[1];
                
            }
        }

        // MINIMIFICAMOS JS
        $minifier = new Minify\JS;
        $minifier->add($content);
        $replace = $minifier->minify();

        return str_replace($content, $replace, $this->content);
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