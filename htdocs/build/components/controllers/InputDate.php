<?php namespace app\views\components\controllers;
/**
 * Clase de input de tipo numero
 */
class InputDate extends Input{

    function __construct(Array $data = []){
        $this->type = 'date';
        parent::__construct($data);
    }
}