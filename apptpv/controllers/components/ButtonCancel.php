<?php namespace app\controllers\components;/**
 * Clase de input de tipo numero
 */
class ButtonCancel extends Component{
    protected 
        $spinner = false,
        $mainclass = 'secondary',
        $type = 'button',
        $caption;

    function __construct(Array $data = []){
        $this->caption = $data['caption']??'Cancelar';
        parent::__construct($data);
        $this->print('button');
    }
}