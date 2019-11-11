<?php namespace app\controllers\components;/**
 * Clase de input de tipo numero
 */
class ButtonSuccess extends Component{
    protected 
        $spinner = true,
        $mainclass = 'tertiary',
        $type = 'submit',
        $caption;
    
    function __construct(Array $data = []){
        $this->caption = $data['caption']??'Aceptar';
        parent::__construct($data);
        $this->print('button');
    }
}