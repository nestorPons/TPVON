<?php namespace app\models;
use \app\core\{Query, Data};

class Family extends Query{
    public $id, $nombre, $estado, $mostrar;
    protected $table = 'familias';

    function __construct($arg = null){
        parent::__construct();
        if (is_array($arg)) $this->loadData($arg);
        else if (is_int($arg)) $this->loadData($this->getById($arg));
    }
}