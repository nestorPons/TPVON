<?php namespace app\models;
use \app\core\{Query, Data, Error};
class Config extends Query{
    
    protected $table;
    function __construct($args = null){
        
        parent::__construct();
        $tI = $this->getDefault();       
        $this->ivaNombre = $tI['nombre'];
        $this->ivaId = $tI['id'];
        $this->iva = $tI['valor'];
    }
    
    