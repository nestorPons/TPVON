<?php namespace app\views\components\controllers;
/**
 * Clase de input de tipo numero
 */
class ButtonCancel extends Component{
    protected 
        $spinner = false,
        $class = 'secondary',
        $type = 'button',
        $caption = 'Cancelar';

    function __construct(Array $data = []){
        parent::__construct($data);
        $this->print('button');
    }
}