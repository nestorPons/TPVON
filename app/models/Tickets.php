<?php namespace app\models;
use \app\core\{Query, Data, Error};
class Tickets extends Query{
    public $id, $id_empleado, $id_cliente, $estado, $fecha, $hora;
    protected $table = 'tickets';
    function __construct(int $id = null){
        parent::__construct();
        if($id){
            $this->loadData(
                $this->getById($id)
            );
        }else{
            $this->new();
        }
/*         $data = ($id) ? $this->getById($id) : $this->getLast();
        $date = new \DateTime($data[0]['fecha']);
        $data['hora'] = $date->format('H:i'); 
        $data['fecha'] = $date->format('d/m/Y');
        $this->loadData($data); */
    }
    function last(){
        $data = $this->getLast();
        $this->loadData($data);
        $date = new \DateTime($data[0]['fecha']);
        $this->hora = $date->format('H:i'); 
        $this->fecha = $date->format('d/m/Y');
    }
    function new(){
        $date = new \DateTime();
        $this->hora = $date->format('H:i'); 
        $this->fecha = $date->format('d/m/Y');
        
        $this->id = (int)$this->getLast('id') + 1;
        $this->id_empleado = 0; 
        $this->id_cliente = 0; 
        $this->estado = 1;

    }
}