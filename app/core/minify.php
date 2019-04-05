<?php
// Script para comprimir los ficheros css y js
// Carpetas de trabajo /htdocs/css y /htdocs/js
// Carpeta con archivos compilados /htdocs/build

use MatthiasMullie\Minify;

$minifier_CSS = new Minify\CSS(\FOLDERS\CSS . 'main.css');
$minifier_CSS->minify(\FOLDERS\CSS . 'main.min.css');

$minifier_JS = new Minify\JS();
if ($folder = opendir(\FOLDERS\JS)) {
    while (false !== ($file = readdir($folder))) {
        // Comprobamos que sea un archivo js 
        $ext = explode('.',$file);
        if(isset($ext[1]) && $ext[1] === 'js'){
            $minifier_JS->add(\FOLDERS\JS . $file);
            $minifier_JS->minify(\FOLDERS\JS . "{$ext[0]}.min.js");
        }

    }
}

