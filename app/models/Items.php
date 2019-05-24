<?php namespace app\models;
use \app\core\{Query, Data};

class Items extends Query{
    protected $id, $codigo,	$nombre, $descripcion, $precio, $coste, $id_iva, $tipo, $valor, $estado,
    $table = 'articulos';
    function __construct(int $id = null){
        parent::__construct();
        if($id) $this->loadData($this->getById($id));
    }
}