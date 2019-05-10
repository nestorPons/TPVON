<?php namespace CONFIG; 
session_start();
if(isset($_GET['db'])) $_SESSION['db'] = $_GET['db'];
define('NAME_COMPANY', $_SESSION['db']??null);
define('HOST', 'localhost');
define('KEY_JWT', 'elahs93uojeqkjpmi3io23');

// Entorno de desarrollo 
define('ENV', TRUE);
// Usuarios de la aplicacion con sus niveles
define('LEVEL_USER', 0);
define('LEVEL_ADMIN', 1);