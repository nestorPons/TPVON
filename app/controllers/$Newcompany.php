<?php namespace app\controllers; 
/**
 * Controla la vista y la recepción de los datos del formulario de login
 */
class Newcompany extends Controller{
    function __construct(String $action, String $nombre){    
             
        parent::__construct($action);
    }
    protected function new(){
        $newcomp = new \app\models\Companies; 
        $newcomp->nombre();
    }  
}