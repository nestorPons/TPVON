<?php namespace app\controllers;
use \app\models\Company as CompanyModel; 
use \app\core\Error;

/**
 * Controla la vista y la recepción de los datos del formulario de login
 */
class Company extends Controller{
    function __construct($arg1, $arg2){
        parent::__construct($arg1, null, $arg2);
    }
    protected function new(Object $Data = null){
        $Model = new CompanyModel; 
        if($Model->new($Data)){
            return $this->printView(\VIEWS\LOGIN . 'newcompanycreated.phtml');
        } else Error::array('No se ha encontrado NEW Company');
    }
}