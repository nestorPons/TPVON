<?php namespace app\views\components\controllers;
/**
 * Clase de input de tipo numero
 */
class Modal extends Component{

    function __construct(Array $data = [],  bool $collapse = true){
        $this->type = 'modal';
        parent::__construct($data);
        $this->print( $this->type);
    }
}