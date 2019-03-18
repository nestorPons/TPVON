<?php
require dirname(__DIR__) . '/app/config/vars.php';
require dirname(__DIR__) . '/vendor/autoload.php';  
require \FOLDERS\CORE . 'minify.php';

new \app\controllers\Router($_POST);