<?php namespace app\controllers\components;/**
 * Clase de input de tipo numero
 */
class ButtonCancel extends Component{
    protected 
        $spinner = false,
        $mainclass = 'secondary',
        $type = 'button',
        $caption = 'Cancelar';

    function __construct(Array $data = []){
        parent::__construct($data);
        $this->print('button');
    }
    function idEl(){
        return $this->id;
    }
}