<?php namespace app\views\components\controllers;
use app\models\Company;
use app\core\Data;
/**
 * Clase de input de tipo numero
 */
class Modal extends Component{

    function __construct(Array $data = [],  bool $collapse = true){
        $this->type = 'modal';
        parent::__construct($data);
        $Company = new Company(NAME_COMPANY);
        $Data = new Data($Company->toArray());
        prs($Data->data->nombre_usuario);
        $this->print( $this->type, $Data);
    }
}