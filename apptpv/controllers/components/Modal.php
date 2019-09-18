<?php namespace app\controllers\components;
use \app\core\Data; 
/**
 * Clase de input de tipo numero
 */
class Modal extends Component{

    function __construct(Array $args = [],  bool $collapse = true, Data $Data = null){
        $this->type = 'modal';
        parent::__construct($args);
        $this->print($this->type, $Data);
    }
}