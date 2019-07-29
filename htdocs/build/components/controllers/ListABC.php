<?php namespace app\views\components\controllers;?><?php namespace app\controllers\components;use app\core\Data;
/**
 * Clase de input de tipo numero
 */
class ListABC extends Component{

    function __construct(String $idTable){
        $this->table = $idTable; 
        $this->COLLAPSE = false;
        $this->type = 'list';
        $this->arrABC = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','Ñ','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        parent::__construct();
        $this->print('listabc');
    }
}