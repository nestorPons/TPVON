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
 * Comprueba y enruta la peticuin por url
 */

class Router{

    function __construct($GET = []){
        if(isset($GET['company'])){
            $db = $GET['company']; 
            $controller = $GET['controller']??'login';
            $action = $GET['action']??'view';

            $controller = strtolower(trim($controller));
            $action = strtolower(trim($action)??'view');        
                    
            $class = ucwords($controller); 
    
            if($class != 'Controller' && file_exists ( \FOLDERS\CONTROLLERS . $class . '.php')){
               $nameClass = '\\app\controllers\\' . $class ; 
                new $nameClass($db, $controller,$action);
            }  
        }
    }
}