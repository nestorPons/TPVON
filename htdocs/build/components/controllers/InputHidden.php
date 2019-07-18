<?php namespace app\views\components\controllers;
/**
 * Clase de input de tipo numero
 */
class InputHidden extends Input{

    function __construct(Array $data = []){
        $this->type = 'hidden';

        parent::__construct($data);
    }
}