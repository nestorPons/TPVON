<?php namespace app\views\components\controllers;
/**
 * Clase de input de tipo numero
 */
class InputText extends Component{
    const 
        TYPE = 'text',
        COLLAPSE = true,
        MINLENGTH = null,
        MAXLENGTH = null; 

    function __construct(Array $data = []){
        $this->style_collapse = true; 
        parent::__construct($data, self::COLLAPSE);
        $this->print();
    }
    private function print(){
        $id = $this->id; 
        $idSel = 'input_' .$this->id; 
        $name = $this->printName(); 
        $title = $this->printTitle();
        $required = $this->printRequired();
        $label = $this->printLabel();
        $class = $this->class(self::COLLAPSE);
        $minlength = $this->printMinlength(self::MINLENGTH);
        $maxlength = $this->printMaxlength(self::MAXLENGTH);
        $pattern = $this->printPattern();
        include \FOLDERS\COMPONENTS . 'view/input.phtml';
    }
}