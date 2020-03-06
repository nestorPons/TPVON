<?php namespace app\models;
use \app\core\{Query};

class Invoice extends Query{
    public $id_ticket, $fecha; 
    protected $table = 'facturas';

    function __construct($arg = null){
        parent::__construct();
        if(is_array($arg)){
            // Si se pasa un array se cargan los datos a la clase 
            $this->loadData($arg);
            $this->add($arg);
        } else if(is_int($arg)){
            // Si se pasa un id se busca el registro
            $this->loadData($this->getById($arg));
        }
    }
    function date(){
        return $this->fecha; 
    }
}