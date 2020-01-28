<?php 
use MatthiasMullie\Minify;
// Script para comprimir los ficheros css y js
// Carpetas de trabajo /htdocs/css y /htdocs/js
// Carpeta con archivos compilados /htdocs/build

$icons = 'https://cdn.linearicons.com/free/1.0.0/icon-font.min.css';

//Funcion que comprueba la fecha de la ultima compilación si es mas nueva que el archivo compilado
function checkedCompile($in, $out) {
    return (!is_file($out) || filemtime($in) > filemtime($out));
}

// Compilación del os css
if(checkedCompile(\PUBLICF\CSS . 'main.css', \PUBLICF\CSS . 'main.min.css')){
    $minifier_CSS = new Minify\CSS(
        \FOLDERS\NODE_MODULES . 'mini.css/dist/mini-default.min.css', 
        \PUBLICF\CSS . 'main.css'
    );
    $minifier_CSS->minify(\PUBLICF\CSS . 'main.min.css'); 
}

function listar($path, $exit_path = \PUBLICF\JS){
    // Desarrollo no minificar
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
                        if(ENV){
                            copy($path . $file, $exit_path . "{$ext[0]}.min.js");
                        }else{
                            $minifier_JS = new Minify\JS($path . $file);
                            $minifier_JS->minify($exit_path . "{$ext[0]}.min.js");
                        }
                    }
                }
            }
        }
    }
}
listar(\FOLDERS\JS);