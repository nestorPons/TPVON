<?php namespace app\views\components\controllers;
/**
 * Clase de input de tipo numero
 */
class InputPass extends Component{
    const 
        TYPE = 'password',
        COLLAPSE = true,
        MINLENGTH = 6,
        MAXLENGTH = null; 

    function __construct(Array $data = []){
        parent::__construct($data);
        $this->print($data);
    }
    private function print(){
        $id = $this->id; 
        $idSel = 'input_' .$this->id; 
        $name = $this->printName(); 
        $title = $this->printTitle();
        $required = $this->printRequired();
        $label = $this->printLabel();
        $class = $this->class();
        $minlength = $this->printMinlength(self::MINLENGTH);
        $maxlength = $this->printMaxlength(self::MAXLENGTH);
        $pattern = $this->printPattern();
        include \FOLDERS\COMPONENTS . 'view/input.phtml';
    }
}