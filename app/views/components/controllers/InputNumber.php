<?php namespace app\views\components\controllers;
/**
 * Clase de input de tipo numero
 */
class Number extends Component{
    const TYPE = 'number';
    const COLLAPSE = false;

    function __construct(Array $data = []){
        parent::__construct($data);
        $this->print($data);
    }
    private function print(){
        $id = $this->id; 
        $idSel = 'input_' .$this->id; 
        $label = $this->label;
        $name = $this->printName(); 
        $title = $this->printTitle();
        $required = $this->printRequired();
        $class = $this->class();
        $minlength = $this->printMinlength();
        $maxlength = $this->printMaxlength();
        $pattern = $this->printPattern();
        include \FOLDERS\COMPONENTS . 'view/input.phtml';
    }
}