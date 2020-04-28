<?php namespace app\controllers;
use \app\core\{Controller};

/**
 * Controla la vista y la recepción de las gráficas 
 * La zona es restringida por lo que se solicita password de administrador
 */
class Chart extends Controller{

    function __construct(String $action,  $Data = null){
        parent::__construct($action);
    }
}