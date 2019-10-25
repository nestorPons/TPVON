<?php namespace app\controllers\components;/**
 * Clase de input de tipo numero
 */
class Input extends Component{
    public $classLabel = null; 
    
    function __construct(Array $data = []){
        $this->COLLAPSE = false;
        $this->MINLENGTH = null;
        $this->MAXLENGTH = null; 
        $this->row = 'row';
        
        parent::__construct($data);
        $this->print('input');
    }
}