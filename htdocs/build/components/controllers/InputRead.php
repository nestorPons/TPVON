<?php namespace app\views\components\controllers;
/**
 * Clase de input de tipo numero
 */
class InputRead extends Input{

    function __construct(Array $data = [],  bool $collapse = true){
        $this->type = 'text';
        $this->readonly = true;
        parent::__construct($data);
    }
}