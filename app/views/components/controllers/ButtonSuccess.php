<?php namespace app\views\components\controllers;
/**
 * Clase de input de tipo numero
 */
class ButtonSuccess extends Component{
    protected 
        $spinner = true,
        $mainclass = 'tertiary',
        $type = 'submit',
        $caption = 'Aceptar';
    
    function __construct(Array $data = []){
        parent::__construct($data);
        $this->print('button');
    }
    function idEl(){
        return $this->id;
    }
}