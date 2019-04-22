<?php namespace app\controllers; 
/**
 * Controla la vista y la recepción de los datos del formulario de login
 */
class Main extends Controller{

    function __construct(String $view = null){
        $this->view = $view; 
        $this->loadView['page'] = $this->view ?? 'login';  
        $this->getView();
    }
    protected function getView( Array $data = []){
        return $this->require(\FOLDERS\VIEWS . 'index.phtml', ['page'=>'main']);
    }
    public function getModel(){}
    public function setModel(){}
}