<?php namespace app\views\components\controllers;
/**
 * Clase de input de tipo numero
 */
class InputNumber extends Component{

    function __construct(Array $data = []){
        $this->type = 'number';
        $this->COLLAPSE = false;
        $this->MINLENGTH = null;
        $this->MAXLENGTH = null;

        parent::__construct($data);
        $this->print('input');
    }
}