<?php namespace app\views\components\controllers;?><?php namespace app\controllers\components;/**
 * Clase de input de tipo numero
 */
class InputNumber extends Input{

    function __construct(Array $data = []){
        $this->type = 'number';
        parent::__construct($data);
    }
}