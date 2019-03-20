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
    protected $controller = 'index'; 
    protected $action = 'view';

    function __construct($GET = []){
        if(isset($GET['company'])){
            $controller = $GET['controller']??'login';
            $action = $GET['action']??'view';
    
            $this->controller = strtolower(trim($controller));
            $this->action = strtolower(trim($action)??'view');        
                    
            $class = ucwords($this->controller); 
    
            if($class != 'Controller' && file_exists ( \FOLDERS\CONTROLLERS . $class . '.php')){
               $nameClass = '\\app\controllers\\' . $class ; 
                new $nameClass( $this->action );
    
            }
            
         }
        }

    private function testController(){
        return file_exists ( \FOLDERS\CONTROLLERS . $this->controller . 'php'); 
     }
}