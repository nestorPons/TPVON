<?php namespace app\controllers; 
/**
 * Controla la vista y la recepción de los datos del formulario de login
 */
 
class Login extends Controller{
    function __construct(String $action) {
        $var = explode('-',str_replace('\\', '-', __CLASS__));
        $this->controller = $var[2];

        $conn = new \app\core\Query('mydb','prueba');
die();
        parent::__construct( $action );
    }
    protected function getView( Array $data = []){
        return require_once \FOLDERS\VIEWS . strtolower($this->controller) . '.phtml'; 
    }
    public function getModel(){}
    public function setModel(){}
}