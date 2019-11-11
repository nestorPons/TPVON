<?php namespace app\controllers\components;/**
 * Clase de input de tipo numero
 */
class ButtonAccept extends Component{
    protected 
        $spinner = true,
        $mainclass = 'tertiary',
        $type = 'button',
        $caption = 'Aceptar';
    
    function __construct(Array $data = []){
        parent::__construct($data);
        $this->print('button');
    }
}