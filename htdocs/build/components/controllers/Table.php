<?php namespace app\views\components\controllers;?><?php namespace app\controllers\components;/**
 * Clase de input de tipo numero
 */
class Table extends Component{
    protected $type = 'table';
    
    function __construct(Array $data = []){
        if(!(isset($data['columns']))) die("Faltan datos para construir el objeto select");
        $this->type = 'table';
        parent::__construct($data);        
        $this->print('table');
    }
    function idEl(){
        return $this->id;
    }
}