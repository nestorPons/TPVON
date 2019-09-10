<?php namespace app\views\components\controllers;?><?php namespace app\controllers\components;/**
 * Clase de input de tipo numero
 */
class Checkbox extends Input{

    function __construct(Array $data = []){
        $this->type = 'checkbox';
        parent::__construct($data);
    }
}