<?php namespace app\views\components\controllers;?><?php namespace app\views\components\controllers;
/**
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