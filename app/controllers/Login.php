<?php namespace app\controllers; 
/**
 * Controla la vista y la recepción de los datos del formulario de login
 */
 
class Login extends Controller{
    function __construct(String $action) {
        $var = explode('-',str_replace('\\', '-', __CLASS__));    
        $this->controller = $var[2];

        parent::__construct( $action );
    }
    public function getModel(){}
    public function setModel(){}
}