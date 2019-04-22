<?php namespace app\controllers; 
/**
 * Controla la vista y la recepción de los datos del formulario de login
 */
class Login extends Controller{

    function __construct(String $action = null, String $db = null){

        $this->company = new \app\models\Company($db);      
        parent::__construct($action);
        // $this->setConnection($db); // inicializa $this->Query
    }
    protected function getView( Array $data = []){
        return $this->require(\FOLDERS\VIEWS . 'index.phtml', ['page' => 'login', 'data' =>$this->company->data] );
    }
    public function getModel(){}
    public function setModel(){}
}