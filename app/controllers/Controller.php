<?php namespace app\controllers;
/**
 * Clase para ser expansión de otras subclases o clases dedicadas 
 * Tiene lo mínimo para la creación de una subclase: 
 *  Método para requerir una vista
 *  Método para requerir datos a los modelos (abstracto)
 *  Método para añadir/editar/borrar datos a los modelos (abstracto)
 */
class Controller{
    protected 
        $conn,
        $controller,
        $action;
    
    function __construct(String $action){
        $this->action = $action;
        $this->getController(); 

        // Constructor alternativo básico
        switch($this->action){
            case 'get':
                $this->setModel();
                break;
            case 'set':
                $this->getModel();
                break;
            case 'view':
                $this->getView();
                break; 
            default: 
                die('Accion no permitida!!');
        }
    }
    protected function setConnection($db){
        return $this->Query = new \app\core\Query($db, $this->controller);
    }
    protected function getView( Array $data = []){
        return $this->require(\FOLDERS\VIEWS . strtolower($this->controller) . '.phtml', $data); 
    }
    protected function require(String $route, Array $data = []){
        return require_once $route;  
    }
    protected function getModel(){

    }
    protected function setModel(){

    }
    private function getController(){
        $arr_controller= explode('\\',get_class($this));
        $controller = end($arr_controller);
        return $this->controller = strtolower($controller);
    }
}