<?php namespace app\models;
use \app\core\{Query, Data};

class Items extends Query{
    public $id, $codigo,	$nombre, $descripcion, $precio, $coste, $id_iva, $tipo, $valor, $estado;
    protected $table = 'articulos';

    function __construct($arg = null){
        parent::__construct();
        if (is_array($arg)) $this->loadData($arg);
        else if (is_int($arg)) $this->loadData($this->getById($arg));
    }
}