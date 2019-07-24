<?php namespace app\views\components\controllers;?><?php namespace app\views\components\controllers;
/**
 * Clase de input de tipo numero
 */
class Checkbox extends Input{

    function __construct(Array $data = []){
        $this->type = 'checkbox';
        parent::__construct($data);
    }
}