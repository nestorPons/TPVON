<?php namespace app\models;
use \app\core\{Query, Data, Error};
class Tickets extends Query{
    public $id, $id_empleado, $id_cliente, $estado, $fecha, $hora;
    protected $table = 'tickets';
    function __construct(int $id = null){
        parent::__construct();
        $data = ($id) ? $this->getById($id) : $this->getLast();
        $date = new \DateTime($data[0]['fecha']);
        $data['hora'] = $date->format('H:i'); 
        $data['fecha'] = $date->format('d/m/Y');
        $this->loadData($data);
    }
}