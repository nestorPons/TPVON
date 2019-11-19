<?php namespace CONFIG; 
// Zona horaria
date_default_timezone_set("UTC");
date_default_timezone_set("Europe/Madrid");

// Entorno de desarrollo 
define('ENV', TRUE);
define('SEND_MAIL', TRUE);


// Comprobamos si se han proporcionado los datos para la conexión
// Si no existe el archivo configuracion se crea uno
// INSTALACIÓN
$file_conf = dirname(__DIR__) . '/config/conn.ini';
if(file_exists($file_conf) ){
    define('CONN', parse_ini_file($file_conf));
    define('KEY_JWT', 'elahs93uojeqkjpmi3io23');
    
    // Usuarios de la aplicacion con sus niveles
    define('LEVEL_USER', 0);
    define('LEVEL_ADMIN', 1);

} else {
    define('CONN', parse_ini_file(dirname(__DIR__) . '/config/conndefault.ini'));
    define('MAIL', parse_ini_file(dirname(__DIR__) . '/config/mail.ini'));

    include_once 'config/index.phtml';
    exit;

} 