<?php namespace app\views\components\controllers;
/**
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
    function idEl(){
        return $this->id;
    }
}