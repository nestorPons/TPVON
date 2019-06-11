<?php namespace app\views\components\controllers;
/**
 * Clase de input de tipo numero
 */
class Select extends Component{
    public $options; 
    function __construct(Array $data = []){
        if(!(isset($data['options']))) die("Faltan datos para construir el objeto select");

        $this->options = $data['options'];
        $this->type = 'select';
        parent::__construct($data);
        $this->print('select');
    }
}