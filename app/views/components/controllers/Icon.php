<?php namespace app\views\components\controllers;
/**
 * Clase de input de tipo numero
 */
class Icon extends Component{
    protected $icon; 
    
    function __construct(string $data){
        $this->icon = $data;
        $this->prefix = 'icon';
        parent::__construct();
        $this->printView('icon');
    }
}