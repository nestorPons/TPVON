<?php namespace app\controllers; 
/**
 * Controla la vista y la recepción de los datos del formulario de login
 */
class Login extends Controller{

    function __construct(String $db, String $controller, String $action){    
        parent::__construct($controller, $action);
        // $this->setConnection($db); // inicializa $this->Query
    }
    protected function getView( Array $data = []){
        return require_once \FOLDERS\VIEWS . 'index.phtml';
    }
    public function getModel(){}
    public function setModel(){}
}