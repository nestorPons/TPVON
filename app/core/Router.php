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
        $id = null, 
        $data, 
        $db,
        $nameDb, 
        $controller, 
        $action;

    function __construct($params = []){

        // Valores por defecto
        $this->db = strtolower($params['db'] ?? null); 
        $this->controller =  strtolower($params['controller'] ?? null); 
        $this->action =  strtolower($params['action'] ?? null); 

        !$this->isGet() && $this->isPost($params);

        //$this->getParams($GET);       
        //$this->isView(); 
            
    }
    private function isGet(){
        if(strtoupper($_SERVER['REQUEST_METHOD']) === 'GET') { 
            if(empty($this->db)){
                // Si no encontramos la base datos vamos a la pagina principal
                if (empty($this->controller)) $this->controller = 'main';
                $this->loadController($this->controller); 
            } else {
                // Si hemos ingresado segundo parametro en la url y existe buscamos la empresa
                $Company = new \app\models\Company(); 
                $company = $Company->getBy(['nombre' => $this->db]); 
            
                if ( $company ){   
                    $this->id = $company['id'];
                    $this->nameDb = $company['nombre'];
                    // Si esta vacio controlador nos envia al login
                    if (empty($this->controller)) {
                        $this->action = 'view'; 
                        $this->loadController('login');
                    }
                } else {
                    Error::toString('E018');
                } 
            } 
            return true;
        } else return false;
        
    }
    private function isPost($params){
        if(strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') { 
            
            // Pasamos los datos de json a objeto Data
            $this->data = new \app\libs\Data((array)json_decode($params['data']) ?? null);
            
 
            $respond = $this->loadController(); 
            if($respond === true) $respond = ['success'=> true];
            echo json_encode($respond);
            return true; 
        } else return false;
    }
    private function getParams($GET){
        // si pasamos primer parametro por url debemos comprobar que la empresa exista antes de rootear la dir 

    }
    // Comprobamos que exista la clase controladora
    private function isController(string $class){
        return (file_exists ( \FOLDERS\CONTROLLERS . $class . '.php'));
    }
    // Carga controladores
    // Si se le pasa argumentos cambia el controlador asignado
    private function loadController(string $controller = null){
        if(!empty($controller)) $this->controller = ucwords($controller); 
        $nameClass = '\\app\\controllers\\' . $this->controller;
        $cont = $this->isController($this->controller)
            ? new $nameClass($this->action, $this->id)
            : new \app\controllers\Controller($this->action, $this->controller, $this->data);
        
        return $cont->result; 
    }
    /**
     * Comprueba que se pueda realizar la acción
     */
    private function isView(){
        if ($this->action == 'view'){
            if(file_exists(\FOLDERS\VIEWS . $this->controller . '.phtml')){
                $this->loadController($this->controller);
            } else {
                $this->loadController($this->default['gate']);
            }
            return  true; 
        } else {
            return false;
        }

    }
}