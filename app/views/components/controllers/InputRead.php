<?php namespace app\views\components\controllers;
/**
 * Clase de input de tipo numero
 */
class InputRead extends Component{

    function __construct(Array $data = [],  bool $collapse = true){
        $this->type = 'text';
        $this->COLLAPSE = $collapse;
        $this->MINLENGTH = null;
        $this->MAXLENGTH = null;

        $this->readonly = true;
        parent::__construct($data);
        $this->print('input');
    }
}