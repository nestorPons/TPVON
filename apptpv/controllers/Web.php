<?php namespace app\controllers; 
use \app\core\{Controller};
/**
 * Controla la vista y la recepción de los datos del formulario de login
 */
class Web extends Controller{

    function __construct(){    
        $this->action = 'view';
        $this->getView();
    }
    protected function getView( Array $data = []){
        return $this->require(\FOLDERS\VIEWS . 'index.phtml', ['page' => 'web.phtml']);
    }
    public function getModel(){}
    public function setModel(){}
}