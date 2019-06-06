<?php namespace app\views\components\controllers;
/**
 * Clase de input de tipo numero
 */
class Select extends Component{
    public $options; 
    function __construct(Array $data = []){
        if(!(isset($data['options']))) die("Faltan datos para construir el objeto select");

        $this->options = $data['options'];
        parent::__construct($data);
        $this->printSelect($data);
    }
    function printSelect(){
        $id = $this->id; 
        $idSel = 'select_' .$this->id; 
        $printName = $this->printName(); 
        $printTitle = $this->printTitle();
        $required = $this->printRequired();
        $options = $this->options; 
        $label = $this->printLabel();
        include \VIEWS\COMPONENTS . 'view/select.phtml';
    }
}