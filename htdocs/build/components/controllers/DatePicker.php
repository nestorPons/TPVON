<?php namespace app\views\components\controllers;
/**
 * Clase de input de tipo numero
 */
class DatePicker extends Component{
    function __construct(Array $data = []){
        $this->type = 'dt'; 
        parent::__construct($data);
        $this->printView('datepicker');
    }
}