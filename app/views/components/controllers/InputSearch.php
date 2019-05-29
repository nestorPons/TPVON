<?php namespace app\views\components\controllers;
/**
 * Clase de input de tipo numero
 */
class InputSearch extends Component{

    function __construct(Array $data = [],  bool $collapse = true){
        $this->type = 'search';
        $this->COLLAPSE = $collapse;
        $this->MINLENGTH = null;
        $this->MAXLENGTH = null;
        
        parent::__construct($data);
        $this->list = 'datalist_' . $this->id; 
        
        $this->print('input');
        $this->printDatalist();
    }
    protected function printDatalist(){
        $type= $this->type;
        $id = $this->list; 
        $options = $this->options??null;

        include \FOLDERS\COMPONENTS . 'view/datalist.phtml';
    }
}