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

if ($folder = opendir(\FOLDERS\JS)) {
    while (false !== ($file = readdir($folder))) {
        // Comprobamos que sea un archivo js 
        $ext = explode('.',$file);
        if(isset($ext[1]) && $ext[1] === 'js'){
            if(checkedCompile(\FOLDERS\JS . $file, \FOLDERS\JS . "{$ext[0]}.min.js")){
                $minifier_JS = new Minify\JS(\FOLDERS\JS . $file);
                $minifier_JS->minify(\FOLDERS\JS . "{$ext[0]}.min.js");
            }
        }
    }
}