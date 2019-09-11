<?php
use \app\core\{
    Router,
    Prepocessor
};

 //phpinfo();die();
/* session_start(); */
require_once dirname(__DIR__) . '/app/config/app.php';
require_once dirname(__DIR__) . '/app/config/folders.php';
require_once dirname(__DIR__) . '/vendor/autoload.php';  

// Desarrollo
if( ENV ){
    error_reporting(E_ALL);
    ini_set('display_errors', '1');

    require_once \FOLDERS\CORE  . 'dev.php';
    require_once \FOLDERS\CORE  . 'less.php';
    require_once \FOLDERS\CORE  . 'minify.php';
    new Prepocessor(false);
}  

new Router($_REQUEST);
