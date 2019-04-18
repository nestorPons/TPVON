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
        $data, 
        $db, 
        $controller, 
        $action;

    function __construct($params = []){

        // Valores por defecto
        $this->db = $params['db'] ?? null; 
        $this->controller =  $params['controller'] ?? null; 
        $this->action =  $params['action'] ?? null; 
        $this->data = $params['data'] ?? null;

        !$this->isGet() && $this->isPost();

        //$this->getParams($GET);       
        //$this->isView(); 
            
    }
    private function isGet(){
        if(empty($this->db)){
            if (empty($this->controller)) $this->controller = 'main';
            $this->loadController($this->controller); 
        } else {
            // Si hemos ingresado segundo parametro en la url y existe buscamos la empresa
            if (file_exists(\FOLDERS\COMPANIES . $this->db)){   
                // Si queremos un controlador determinado o a la web principal

                if (empty($this->controller)) {
                    $this->action = 'view'; 
                    $this->loadController('login');
                }; 
            } else {
                Error::toString('E018');
            } 
            
        } 
    }
    private function isPost(){

    }
    private function getParams($GET){
        // si pasamos primer parametro por url debemos comprobar que la empresa exista antes de rootear la dir 

    }
    private function isController(string $arg){
        $class = ucwords($arg); 
        // Carga la clase controladora
        return (file_exists ( \FOLDERS\CONTROLLERS . $class . '.php'));
    }
    private function loadController(string $arg){
        $arg = ucwords($arg);
        $this->controller = strtolower(trim($arg));   
        $nameClass = '\\app\\controllers\\' . $arg;
        if($this->isController($this->controller))
            new $nameClass($this->action, $this->db);
        else 
            new \app\controllers\Controller($this->action, $this->controller, $this->data);
        
        return true; 
    }
    /**
     * Comprueba que se pueda realizar la acción
     */
    private function isView(){
        if ($this->action == 'view'){
            if(file_exists(\FOLDERS\VIEWS . strtolower($this->controller) . '.phtml')){
                $this->loadController(strtolower($this->controller));
            } else {
                $this->loadController($this->default['gate']);
            }
            return  true; 
        } else {
            return false;
        }

    }
}