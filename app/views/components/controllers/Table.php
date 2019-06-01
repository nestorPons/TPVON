<?php namespace app\views\components\controllers;
/**
 * Clase de input de tipo numero
 */
class Table extends Component{
    protected $type = 'table';
    
    function __construct(Array $data = []){
        if(!(isset($data['columns']))) die("Faltan datos para construir el objeto select");

        parent::__construct($data);        
        $this->print('table');
    }
}