<?php namespace app\views\components\controllers;?><?php namespace app\views\components\controllers;
/**
 * Clase de input de tipo numero
 */
class InputText extends Input{

    function __construct(Array $data = [],  bool $collapse = true){
        $this->type = 'text';
        parent::__construct($data);
    }
}