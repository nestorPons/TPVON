<?php namespace app\views\components\controllers;?><?php namespace app\views\components\controllers;
/**
 * Clase de input de tipo numero
 */
class Icon extends Component{
    protected $icon; 

    function __construct(String $nameIcon, Array $args = []){
        $this->type = 'icon';
        $this->icon = $nameIcon;
        
        parent::__construct($args);
        $this->print('icon');
    }
}