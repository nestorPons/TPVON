<?php namespace CONFIG; 
// Entorno de desarrollo 
define('ENV', TRUE);
define('SEND_MAIL', TRUE);

$conn = parse_ini_file(dirname(__DIR__) . '/config/conn.ini');
define('CONN', $conn);
// Constantes de la aplicación
if(isset(CONN['db'])){
    define('CODE_COMPANY', filter_var((strtolower(CONN['db'])), FILTER_SANITIZE_SPECIAL_CHARS));
    define('NAME_COMPANY', ucwords(CODE_COMPANY));
    
} else {
    die('No hay asignada una base de datos!!');
}

define('KEY_JWT', 'elahs93uojeqkjpmi3io23');

// Usuarios de la aplicacion con sus niveles
define('LEVEL_USER', 0);
define('LEVEL_ADMIN', 1);