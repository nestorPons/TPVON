<?php namespace CONFIG; 
// Entorno de desarrollo 
define('ENV', TRUE);

// Constantes de la aplicación
if(isset($_REQUEST['db'])){
    define('CODE_COMPANY', filter_var((strtolower($_REQUEST['db'])), FILTER_SANITIZE_SPECIAL_CHARS));
    define('NAME_COMPANY', ucwords(CODE_COMPANY));
}
define('HOST', 'localhost');
define('KEY_JWT', 'elahs93uojeqkjpmi3io23');

// Usuarios de la aplicacion con sus niveles
define('LEVEL_USER', 0);
define('LEVEL_ADMIN', 1);