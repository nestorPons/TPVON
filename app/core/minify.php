<?php
use MatthiasMullie\Minify;
$minifier = new Minify\JS(FOLDER_JS . 'index.js');
$minifier->minify(FOLDER_BUILD . 'index.min.js');
