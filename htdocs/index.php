<?php
//phpinfo();die();
new PDO('mysql:host=localhost;port=3306;dbname=mydb', 'root', 'test');
die();
require_once dirname(__DIR__) . '/app/config/vars.php';
require_once dirname(__DIR__) . '/app/config/app.php';
require_once dirname(__DIR__) . '/vendor/autoload.php';  
require_once \FOLDERS\CORE . 'minify.php';




new \app\core\Router($_REQUEST);