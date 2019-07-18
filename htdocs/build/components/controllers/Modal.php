<?php namespace app\views\components\controllers;

/**
 * Clase de input de tipo numero
 */
class Modal extends Component{

    function __construct(Array $args = [],  bool $collapse = true, Object $Data = null){
        $this->type = 'modal';
        parent::__construct($args);
        $this->print($this->type, $Data);
    }
}