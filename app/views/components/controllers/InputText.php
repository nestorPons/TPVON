<?php namespace app\views\components\controllers;
/**
 * Clase de input de tipo numero
 */
class InputText extends Component{

    function __construct(Array $data = []){
        $this->TYPE = 'text';
        $this->COLLAPSE = true;
        $this->MINLENGTH = null;
        $this->MAXLENGTH = null;

        parent::__construct($data);
        $this->printInput();
    }
}