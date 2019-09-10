<?php namespace app\controllers\components;/**
 * Clase de input de tipo numero
 */
class InputSearch extends Input{

    function __construct(Array $data = [],  bool $collapse = true){
        $this->type = 'search';
        $this->id = $this->randomid();
        $this->list = 'datalist_' . $this->id; 
        parent::__construct($data);
        $this->printDatalist();
    }
    protected function printDatalist(){
        $type= $this->type;
        $id = $this->list; 
        $options = $this->options??null;

        include \VIEWS\COMPONENTS . 'view/datalist.phtml';
    }
}