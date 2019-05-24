<?php namespace app\views\components\controllers;
/**
 * Clase de input de tipo numero
 */
class InputSearch extends Component{

    function __construct(Array $data = [],  bool $collapse = true){
        $this->TYPE = 'search';
        $this->COLLAPSE = $collapse;
        $this->MINLENGTH = null;
        $this->MAXLENGTH = null;
        
        parent::__construct($data);
        $this->list = 'datalist_' . $this->id; 
        
        $this->printInput();
        $this->printDatalist();
    }
    protected function printDatalist(){
        $TYPE = $this->TYPE;
        $id = $this->list; 
        $options = $this->options??null;

        include \FOLDERS\COMPONENTS . 'view/datalist.phtml';
    }
}