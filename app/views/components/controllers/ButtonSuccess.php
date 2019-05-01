<?php namespace app\views\components\controllers;
/**
 * Clase de input de tipo numero
 */
class ButtonSuccess extends Component{
    const STYLE = 'tertiary';
    const TYPE = 'submit';
    const CAPTION = 'Aceptar';
    
    function __construct(Array $data = []){
        parent::__construct($data);
        $this->print($data);
    }
    private function print(){
        include \FOLDERS\COMPONENTS . 'view/btn.phtml';
    }
}