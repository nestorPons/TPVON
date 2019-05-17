<?php namespace app\controllers;
use \app\models\Company as CompanyModel; 
use \app\core\Error;

/**
 * Controla la vista y la recepción de los datos del formulario de login
 */
class Company extends Controller{
    protected function new(Object $Data = null){
        $Model = new CompanyModel; 
        if($Model->new($Data)){
            return $this->require(\FOLDERS\LOGIN . 'newcompanycreated.phtml');
        } else Error::array('');

    }
}