<?php namespace app\controllers;
use \app\models\Company as CompanyModel; 
use \app\core\{Error, Data, Controller};

/**
 * Controla la vista y la recepción de los datos del formulario de login
 */
class Company extends Controller{
    function __construct($arg1, $arg2){
        parent::__construct($arg1, null, $arg2);
    }
    protected function new(Data $Data = null){
        $Model = new CompanyModel; 
        try {
            $Model->new($Data);
            return $this->printView(\VIEWS\LOGIN . 'newcompanycreated.phtml');
        } catch( \Exception $e){
            return Error::array($e->getMessage());
        }
    }
}