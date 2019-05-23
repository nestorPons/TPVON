<?php namespace app\controllers; 
/**
 * Controla la vista y la recepción de los datos del formulario de login
 */
class Main extends Controller{
    private $companies = []; 

    function __construct(String $view = null){
        $this->view = $view; 
        $this->loadView['page'] = $this->view ?? 'login'; 
        $this->result = $this->getView($this->getCompanies());
    }
    protected function getView( Array $data = null){ 
        return $this->printView(\FOLDERS\VIEWS . 'index.phtml', ['page'=>'main', 'companies' => $data]);
    }
    public function getModel(){}
    public function setModel(){}
    private function getCompanies(){
        $Companies = new \app\models\Company;
        return $this->companies($Companies->getAll('nombre'));
    }
    function companies ($arg = null){
        if($arg) $this->companies = $arg;
        return $this->companies; 
    }
}