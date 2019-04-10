<?php namespace app\core; 

/***  
 * Clase controlador principal.
 * Routea la url a otros controladores secundarios si existieran y 
 * si no el mismo controlara el enrutamiento. 
 */
/**
 * ej: 
 * http://localhost/company/this->controller/this->action
 * 
 * Comprueba y enruta la peticuiOn por url
 */

class Router{
    
    private 
        $default,
        $db, 
        $controller, 
        $action;

    function __construct($GET = []){
        // Archivo configuración por defecto 
        $this->default = parse_ini_file(\FOLDERS\CONFIG . 'routes.ini');
        
        // Valores por defecto
        $this->db = $this->default['db']; 
        $this->controller =  $this->default['controller']; 
        $this->action =  $this->default['action']; 

        $this->getParams($GET);       
        $this->checkRoute(); 

        $this->controller = strtolower(trim($this->controller));
        $this->action = strtolower(trim($this->action));        
        
        $class = ucwords($this->controller); 
        // Carga la clase controladora
        if($class != 'Controller' && file_exists ( \FOLDERS\CONTROLLERS . $class . '.php')){
            $nameClass = '\\app\\controllers\\' . $class;
            new $nameClass($this->db, $this->controller, $this->action);
        }else{
            // Carga de controlador por defecto
            new \app\controllers\Controller($this->controller,$this->action); 
        }
    }
    private function getParams($GET){
        // si pasamos primer parametro por url debemos comprobar que la empresa exista antes de rootear la dir 
        if(isset($GET['ar1'])){
            // Si hemos ingresado segundo parametro en la url y existe buscamos la empresa
            if (file_exists(\FOLDERS\COMPANIES . $GET['ar1'])){
              $this->db = $GET['ar1'];   
              // Si queremos un controlador determinado o a la web principal
              $this->controller = (isset($GET['ar2'])) ? $GET['ar2'] :  $this->default['gate'];
              // Que queremos hacer si es una petición ajax si no por defeccto estamos buscando una vista
              if(isset($GET['ar3'])) $this->action = $GET['ar3'];
            } 
        } 
    }
    /**
     * Comprueba que se pueda realizar la acción
     */
    private function checkRoute(){
        switch ($this->action){
            case 'view':
                if(!file_exists(\FOLDERS\VIEWS . strtolower($this->controller) . '.phtml')){
                    $this->controller =  $this->default['gate'];
                }
            break; 
        }

    }
}