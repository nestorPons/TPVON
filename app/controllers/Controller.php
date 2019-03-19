<?php namespace app\controllers;
/**
 * Clase para ser expansión de otras subclases o clases dedicadas 
 * Tiene lo mínimo para la creación de una subclase: 
 *  Método para requerir una vista
 *  Método para requerir datos a los modelos (abstracto)
 *  Método para añadir/editar/borrar datos a los modelos (abstracto)
 */
abstract class Controller {
    protected $controller = 'login'; 
    protected $action = 'view';
    
    function __construct( String $action ){
        /**
         * Constructor alternativo básico
         */
        switch($this->action){
            case 'get':
                $this->setModel();
                break;
            case 'set':
                $this->getModel();
                break;
            default:
                $this->getView();
        }
    }
    
    protected function getView( Array $data = []){
        return require_once \FOLDERS\VIEWS . strtolower($this->controller) . '.phtml'; 
    }

    abstract protected function getModel();
    abstract protected function setModel();
}