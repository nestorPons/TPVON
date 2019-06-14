<?php namespace app\models;
use \app\core\{Query};

class Lines extends Query{
    public $id_ticket, $articulo, $precio, $cantidad, $iva, $dto; 
    protected $table = 'lineas';

    function __construct($arg = null){
        parent::__construct();
        if(is_array($arg)){
            $this->add($arg);
        }
    }
}