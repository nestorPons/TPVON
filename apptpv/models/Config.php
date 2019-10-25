<?php namespace app\models;
use \app\core\{Error, Query, Data};

class Config extends Query{
    public $id = 1, $iva, $dias; 
    protected $table = 'config';

    function __construct(){
        parent::__construct($this->table);
    }
    function save(Data $Post){
        $this->loadData(get_object_vars($Post));
        return $this->saveById($this->toArray());
    }
}
