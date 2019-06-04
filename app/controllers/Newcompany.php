<?php namespace app\controllers; 
/**
 * Controla la vista y la recepción de los datos del formulario de login
 */
class Newcompany extends Controller{

    function __construct(String $db, String $controller, String $action){    
        die('Controllador newcomany');
        //parent::__construct($controller, $action);
    }
    protected function getView( Array $data = []){
        return $this->require(\FOLDERS\VIEWS . 'index.phtml', ['page' => 'newcompany.phtml']);
    }
    public function getModel(){}
    public function setModel(){}
}