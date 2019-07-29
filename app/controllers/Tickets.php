<?php namespace app\controllers;
use \app\models\Tickets as Model;
use \app\core\{Query, Data};

/**
 * Controla la vista y la recepción de los datos del formulario de login
 */
class Tickets extends Controller{

    function __construct(String $action,  $Data = null){
        $this->controller = 'Tickets';
        $this->result =  $this->{$action}($Data);
    }
    function getLast($Data){
        $Model = new Model; 
        return $Model->getLastUser($Data);
    }

}