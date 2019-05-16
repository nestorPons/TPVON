<?php namespace app\views\components\controllers;
/**
 * Clase de input de tipo numero
 */
class InputHidden extends Component{

    function __construct(Array $data = []){
        $this->TYPE = 'hidden';
        $this->COLLAPSE = false;
        $this->MINLENGTH = null;
        $this->MAXLENGTH = null; 
        parent::__construct($data);
        $this->printInput($data);
    }
}