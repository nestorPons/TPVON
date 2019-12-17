<?php namespace app\controllers\components;/**
 * Clase de input de tipo numero
 */
class Select extends Component{
    public $options, $option_default;
    
    function __construct(Array $data = []){
       // if(!(isset($data['options']))) die("Faltan datos para construir el objeto select");
        $this->COLLAPSE = true;
        $this->selected = $data['selected'] ?? null; 
        $this->options = $data['options'] ?? null;
        $this->default = ''; 
        $this->type = 'select';
        $this->option_default = true;

        parent::__construct($data);
        $this->print('select');
    }
}