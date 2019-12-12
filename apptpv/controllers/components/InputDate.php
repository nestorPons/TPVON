<?php namespace app\controllers\components;/**
 * Clase de input de tipo numero
 */
class InputDate extends Input{

    function __construct(Array $data = []){
        $this->type = 'date';
        $this->style = 'text-align: center;';
        parent::__construct($data);
    }
}