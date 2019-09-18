<?php namespace app\controllers\components;/**
 * Clase de input de tipo numero
 */
class InputDate extends Input{

    function __construct(Array $data = []){
        $this->type = 'date';
        parent::__construct($data);
    }
}