<?php
use MatthiasMullie\Minify;
$minifier = new Minify\JS();
$minifier->minify(FOLDER_BUILD . 'index.min.css');