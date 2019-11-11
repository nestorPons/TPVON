<?php namespace app\controllers\components;/**
 * Clase de input de tipo numero
 */
class InputRead extends Input{

    function __construct(Array $data = [],  bool $collapse = true){
        $this->type = 'text';
        $this->style = "border: none; cursor: default;"; 
        $this->readonly = 'readonly';
        parent::__construct($data);
    }
}