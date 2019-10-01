<?php namespace CONFIG; 
// Entorno de desarrollo 
define('ENV', TRUE);
define('SEND_MAIL', TRUE);

$conn = parse_ini_file(dirname(__DIR__) . '/config/conn.ini');
define('CONN', $conn);

// Comprobamos si se han proporcionado los datos para la conexión
if(CONN['configured'] != 1) {
    include_once 'config/index.phtml';
    exit; 
}

define('KEY_JWT', 'elahs93uojeqkjpmi3io23');

// Usuarios de la aplicacion con sus niveles
define('LEVEL_USER', 0);
define('LEVEL_ADMIN', 1);