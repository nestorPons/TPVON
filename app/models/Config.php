<?php namespace app\models;
use \app\core\{Query, Data, Error};
class Config extends Query{
    public $id, $nombre, $valor, $pre, $iva, $ivaNombre, $ivaId;
    protected $table = 'tipo_iva';
    function __construct($args = null){
        parent::__construct();
        $tI = $this->getDefault();       
        $this->ivaNombre = $tI['nombre'];
        $this->ivaId = $tI['id'];
        $this->iva = $tI['valor'];
    }
    function getDefault(){
        return $this->getOneBy(['pre'=>1]);
    }
    
}