<?php
use MatthiasMullie\Minify;
$minifier = new Minify\JS(\FOLDERS\JS . 'index.js');
$minifier->minify(\FOLDERS\BUILD . 'index.min.js');
