<?php namespace app\models;
use \app\core\{Query};

class Lines extends Query{
    public $id_ticket, $articulo, $precio, $cantidad, $iva, $dto; 
    protected $table = 'lineas';

    function __construct($arg = null){
        parent::__construct();
        if(is_array($arg)){
            // Si se pasa un array se cargan los datos a la clase Linea 
            $this->loadData($arg);
        } else if(is_int($arg)){
            // Si se pasa un id se busca la Linea
            $this->loadData($this->getById($arg));
        }
    }
    function total(){
        return ($this->cantidad * $this->precio) * (1 - ($this->dto / 100));
    }
}