<?php namespace app\controllers\components;/**
 * Clase de input de tipo numero
 */
class ButtonCloseApp extends Component{
    protected $icon; 
    
    function __construct(){
        $this->icon = 'power-switch';
        $this->type = 'buttonClose';
        parent::__construct();
        $this->print('closeapp');
    }
}