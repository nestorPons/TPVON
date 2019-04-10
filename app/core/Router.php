<?php namespace app\core; 

/***  
 * Clase controlador principal.
 * Routea la url a otros controladores secundarios si existieran y 
 * si no el mismo controlara el enrutamiento. 
 */
/**
 * ej: 
 * http://localhost/company/controller/action
 * 
 * Comprueba y enruta la peticuiOn por url
 */

class Router{
    function __construct($GET = []){
        $default = parse_ini_file(\FOLDERS\CONFIG . 'routes.ini');
        $db = $GET['company'] ?? $default['db']; 
        $controller = $GET['controller'] ?? $default['controller'];
        $action = $GET['action'] ?? $default['action'];

        $controller = strtolower(trim($controller));
        $action = strtolower(trim($action)??'view');        
                
        $class = ucwords($controller); 
        if($class != 'Controller' && file_exists ( \FOLDERS\CONTROLLERS . $class . '.php')){
            $nameClass = '\\app\controllers\\' . $class;
            new $nameClass($db, $controller,$action);
        }  
    }
}