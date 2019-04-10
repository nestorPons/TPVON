<?php namespace app\controllers; 
/**
 * Controla la vista y la recepción de los datos del formulario de login
 */
class Web extends Controller{

    function __construct(String $db = null, String $controller = null, String $action = null){    
        parent::__construct($controller, $action);
        // $this->setConnection($db); // inicializa $this->Query
    }
    protected function getView( Array $data = []){
        return $this->require(\FOLDERS\VIEWS . 'index.phtml', ['page' => 'web.phtml']);
    }
    public function getModel(){}
    public function setModel(){}
}