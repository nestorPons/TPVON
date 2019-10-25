<?php namespace app\controllers\components;/**
 * Clase de input de tipo numero
 */
class Checkbox extends Component{
    public $classLabel = null;
    function __construct(Array $data = []){
        $this->type = 'checkbox';
        $this->row = '';
    
        parent::__construct($data);
        $this->print('input');
    }
}