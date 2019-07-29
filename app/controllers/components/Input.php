<?php namespace app\controllers\components;/**
 * Clase de input de tipo numero
 */
class Input extends Component{

    function __construct(Array $data = []){
        $this->COLLAPSE = false;
        $this->MINLENGTH = null;
        $this->MAXLENGTH = null; 
        parent::__construct($data);
        $this->print('input');
    }
}