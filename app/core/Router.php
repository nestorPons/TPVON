<?php namespace app\core; 

/***  
 * Clase controlador principal.
 * Routea la url a otros controladores secundarios si existieran y 
 * si no el mismo controlara el enrutamiento. 
 */
// http://localhost/company/controller/action

class Router{
    protected $controller = 'login'; 
    protected $action = 'view';

    function __construct($args = []){
        // Extraemos los datos para el enrutamiento desde la url
        $arr_rute = explode('/',trim($_SERVER['REQUEST_URI'])); 

        if (count($arr_rute) >= 3){
            $controller =  $arr_rute[2]??null;
            $action = $arr_rute[3]??null; 
 
            if ($controller){    
                $this->controller = strtolower($controller); 
                if ($action) $this->action = strtolower($action); 
            }
        }
        
        $class = ucwords($this->controller); 

        if($class != 'Controller' && file_exists ( \FOLDERS\CONTROLLERS . $class . '.php')){
            new $class($this->controller , $this->action);
        }
        
     }

    private function testController(){
        return file_exists ( \FOLDERS\CONTROLLERS . $this->controller . 'php'); 
     }
}