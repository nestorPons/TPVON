<?php
//phpinfo();die();
/* session_start(); */
require_once dirname(__DIR__) . '/app/config/app.php';
require_once dirname(__DIR__) . '/app/config/folders.php';
require_once dirname(__DIR__) . '/vendor/autoload.php';  

require_once \FOLDERS\CORE  . 'dev.php'; // Quitar en produccion
require_once \FOLDERS\CORE  . 'less.php';
require_once \FOLDERS\CORE  . 'minify.php';

require_once dirname(__DIR__) . '/core/preprocesor.php';

new \app\core\Router($_REQUEST);