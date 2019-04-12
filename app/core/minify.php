<?php use MatthiasMullie\Minify;
// Script para comprimir los ficheros css y js
// Carpetas de trabajo /htdocs/css y /htdocs/js
// Carpeta con archivos compilados /htdocs/build

function checkedCompile($in, $out) {
    return (!is_file($out) || filemtime($in) > filemtime($out));
}

if(checkedCompile(\FOLDERS\CSS . 'main.css', \FOLDERS\CSS . 'main.min.css')){
    $minifier_CSS = new Minify\CSS(
        \FOLDERS\CSS . 'mini.css',
        \FOLDERS\CSS . 'main.css'
    );
    $minifier_CSS->minify(\FOLDERS\CSS . 'main.min.css'); 
}
function listar($path){
    if ($folder = opendir($path)) {
        while (false !== ($file = readdir($folder))) {
            // Filtramos directorios padres
            if($file != '..' && $file != '.' ){
                // Comprobamos si es un archivo
                if(is_dir($path.$file)) listar($path.$file.'/'); 
                // Comprobamos que sea un archivo js 
                $ext = explode('.',$file);
                if(isset($ext[1]) && $ext[1] === 'js'){
                    if(checkedCompile($path . $file, $path . "{$ext[0]}.min.js")){
                        $minifier_JS = new Minify\JS($path . $file);
                        $minifier_JS->minify($path . "{$ext[0]}.min.js");
                    }
                }
            }
        }
    }
}
listar(\FOLDERS\JS);