<?php
// Script para comprimir los ficheros css y js
// Carpetas de trabajo /htdocs/css y /htdocs/js
// Carpeta con archivos compilados /htdocs/build

use MatthiasMullie\Minify;
$minifier_css = new Minify\CSS(\FOLDERS\CSS . 'main.css');
$minifier_CSS->minify(\FOLDERS\BUILD . 'main.min.css');

$minifier_JS = new Minify\JS(\FOLDERS\JS . 'index.js');
$minifier_JS->minify(\FOLDERS\BUILD . 'index.min.js');
