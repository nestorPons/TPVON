<?php
//phpinfo();die();

require_once dirname(__DIR__) . '/app/config/vars.php';
require_once dirname(__DIR__) . '/app/config/app.php';
require_once dirname(__DIR__) . '/vendor/autoload.php';  
require_once \FOLDERS\CORE . 'minify.php';
require_once \FOLDERS\CORE . 'less.php';

new \app\core\Router($_REQUEST);