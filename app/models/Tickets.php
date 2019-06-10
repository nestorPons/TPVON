<?php namespace app\models;
use \app\core\{Query, Data, Error};
class Tickets extends Query{
    public $id, $id_empleado, $id_cliente, $estado, $fecha, $hora;
    protected $table = 'tickets';
    function __construct($args = null){
        parent::__construct();
        if(is_int($args)){
            $this->loadData(
                $this->getById($args)
            );
        }else{
            //$this->new();
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
        $date = new \DateTime($data['fecha']);
        $this->hora = $date->format('H:i'); 
        $this->fecha = $date->format('d/m/Y');
    }
    function new(Object $Data){

    /*     $this->id = $this->add([
            'id_empleado' => $Data['employee'],
            'fecha' => $Data['date'],
            'id_cliente' => $Data['client'],
            'estado' => 1
        ]);
        foreach($Data->lines as $data){
            $Line = new Lines;
            $Line->add([
                'id_tiket' => $this->id,
                'articulo' => $Data['cod'],
                'precio'   => $Data['pri'],
                'dto'      => $Data['dto'],
                'precio'   => $Data['precio'],
                'cantidad' => $Data['qua'],
                'iva'      => $Data['iva']

            ]);
        }   */
            
    }
}