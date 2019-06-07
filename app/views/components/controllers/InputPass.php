<?php namespace app\views\components\controllers;
/**
 * Clase de input de tipo numero
 */
class InputPass extends Input{
    function __construct(Array $data = []){
        $this->type = 'password';
        $this->MINLENGTH = 6;
        $this->MAXLENGTH = null;
        parent::__construct($data);
    }
}