<?php namespace app\models;
use \app\core\{Query, Data, Error};
class Tickets extends Query{
    protected $id, $id_empleado, $id_cliente, $estado,
        $table = 'tickets';
    function __construct(int $id = null){
        parent::__construct();
        if($id) $this->loadData($this->getById($id));
        else $this->loadData($this->getLast());
    }
}