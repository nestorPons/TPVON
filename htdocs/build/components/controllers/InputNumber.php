<?php namespace app\views\components\controllers;
/**
 * Clase de input de tipo numero
 */
class InputNumber extends Input{

    function __construct(Array $data = []){
        $this->type = 'number';
        parent::__construct($data);
    }
}