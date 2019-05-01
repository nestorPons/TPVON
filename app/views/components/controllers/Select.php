<?php namespace app\views\components\controllers;
/**
 * Clase de input de tipo numero
 */
class Select extends Component{
    private $options; 
    function __construct(Array $data = []){
        if(!(isset($data['options']))) die("Faltan datos para construir el objeto select");

        $this->options = $data['options'];
        parent::__construct($data);
        $this->print($data);
    }
    private function print(){
        $id = $this->id; 
        $idSel = 'select_' .$this->id; 
        $printName = $this->printName(); 
        $printTitle = $this->printTitle();
        $required = $this->printRequired();
        $options = $this->options; 
        $label = $this->printLabel();
        include \FOLDERS\COMPONENTS . 'view/select.phtml';
    }
}