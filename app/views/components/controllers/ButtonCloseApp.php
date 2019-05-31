<?php namespace app\views\components\controllers;
/**
 * Clase de input de tipo numero
 */
class ButtonCloseApp extends Component{
    protected $icon; 
    
    function __construct(){
        $this->icon = 'power-switch';
        $this->prefix = 'buttonClose';
        parent::__construct();
        $this->printView('closeapp');
    }
}