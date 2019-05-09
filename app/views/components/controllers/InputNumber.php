<?php namespace app\views\components\controllers;
/**
 * Clase de input de tipo numero
 */
class Number extends Component{

    function __construct(Array $data = []){
        $this->TYPE = 'number';
        $this->COLLAPSE = false;
        $this->MINLENGTH = null;
        $this->MAXLENGTH = null;

        parent::__construct($data);
        $this->printInput($data);
    }
}