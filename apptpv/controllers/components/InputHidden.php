<?php namespace app\controllers\components;/**
 * Clase de input de tipo numero
 */
class InputHidden extends Input{

    function __construct(Array $data = []){
        $this->type = 'text';
        $this->hidden ='hidden';

        parent::__construct($data);
    }
}