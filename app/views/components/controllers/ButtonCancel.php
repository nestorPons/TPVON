<?php namespace app\views\components\controllers;
/**
 * Clase de input de tipo numero
 */
class ButtonCancel extends Component{
    const STYLE = 'secondary';
    const TYPE = 'button';
    const CAPTION = 'Cancelar';

    function __construct(Array $data = []){
        parent::__construct($data);
        $this->print($data);
    }
    private function print(){
        include \FOLDERS\COMPONENTS . 'view/btn.phtml';
    }
}