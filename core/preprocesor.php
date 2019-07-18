<?php 
use MatthiasMullie\Minify;

const BUILD = \FOLDERS\HTDOCS . 'build/';

if(!file_exists(BUILD)) mkdir(BUILD, 0775, true);
 
showFiles(\FOLDERS\NATIVE_VIEWS);

function showFiles($path){

    $dir = opendir($path);
    $files = array();
    while ($current = readdir($dir)){
        if( $current != "." && $current != "..") {

            $build_path = str_replace(\FOLDERS\NATIVE_VIEWS, '', $path.$current);
            if(is_dir($path.$current)) {

                if(!file_exists(BUILD . $build_path)) mkdir(BUILD . $build_path, 0775, true);
                showFiles($path.$current.'/');
            }
            else {
                                   
                $con = file_get_contents($path.$current);

                $style_open = strpos($con, '<style'); 
                $pos_tag_close = strpos($con, '</style>'); 
                $pos_end_tag_open = strpos($con, '>',$style_open);
                $tag_style_open = substr($con, $style_open, $pos_end_tag_open - $style_open); 
                $pos_end_tag_open += 1 ;

                $args = str_replace('<style', '', $tag_style_open); 
                $args = explode(' ',$args);
                // Obtenemos los argumentos de la etiqueta style 
                foreach($args as $val){
                    $arg = explode('=' , $val); 

                    if(isset($arg[1]) && trim($arg[1],'"') == 'less'){
                        //COMPILAMOS LESS
                        $less = new lessc;
                        $content = substr($con, $pos_end_tag_open, $pos_tag_close - $pos_end_tag_open);
                        $content_less = $less->compile($content);  

                        // MINIMIFICAMOS
                        $minifier = new Minify\CSS;
                        $minifier->add($content_less);
                        $content_min = $minifier->minify(); 
//pr(htmlentities($content));                        
//pr(htmlentities($content_less));
//pr(htmlentities($content_min));
//prs(htmlentities($content_less));
// Modificamos archivo de salida

                        $content_replace = str_replace($content, $content_less, $con);

                    }
                }
                // Creamos, guardamos archivo de salida
                file_put_contents(BUILD . '/' . $build_path, $content_replace??$con);
            }
        }
    }
}
