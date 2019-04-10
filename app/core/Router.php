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
        // Archivo configuración por defecto 
        $default = parse_ini_file(\FOLDERS\CONFIG . 'routes.ini');
        // Si hemos ingresado segundo parametro en la url buscamos una empresa
        $db = $GET['company'] ?? $default['db']; 
        // Si queremos un controlador determinado o mandamos al login o a la web principal
        $controller = $GET['controller'] ?? (isset($GET['company']) ? $default['app_gate'] : $default['controller']);
        // Que queremos hacer si es una petición ajax si no por defeccto estamos buscando una vista
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