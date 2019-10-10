<?php namespace app\controllers\components;/**
 * Clase de input de tipo numero
 */
class Icon extends Component{
    protected $icon, $label; 

    function __construct(String $nameIcon, Array $args = []){
        $this->type = 'icon';
        $this->icon = $nameIcon;
        $this->label = false;

        parent::__construct($args);
        $this->print('icon');
    }
}