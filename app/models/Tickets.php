<?php namespace app\models;
use \app\core\{Query, Data, Error};
class Tickets extends Query{
    public $id, $id_usuario, $id_cliente, $estado, $fecha, $hora;
    protected $table = 'tickets';
    function __construct($args = null){
        parent::__construct();
        if(is_int($args)){
            $this->loadData(
                $this->getById($args)
            );
        }else if(is_object($args)){
            $this->loadData($args);
        }
    }
    function last(){
        $data = $this->getLast();
        $this->loadData($data);
        $date = new \DateTime($data['fecha']);
        $this->hora = $date->format('H:i'); 
        $this->fecha = $date->format('d/m/Y');
        return $data;
    }
    function new(Object $Data){
        $lines = $Data->lines; 

        $Data->filter(new Tickets); 
        $DateTime = new \DateTime;
        $Data->fecha = $DateTime->format('Y-m-d H:i');
        $this->id = $this->add($Data->toArray());
        foreach($lines as $line){
            $Line = new Lines;
            $Line->add([
                'id_ticket' => $this->id,
                'articulo'  => intval($line['articulo']),
                'precio'    => floatval($line['precio']),
                'cantidad'  => intval($line['cantidad']),
                'iva'       => intval($line['iva']), 
                'dto'       => floatval($line['dto'])
            ]);
        }  
        return ['id' => $this->id]; 
    }
    // Método genérico de captura de tickets con sus lineas
    function get(int $id, bool $all = false){
        $Data = new Data($this->getById($id));
        $Lines = new Lines; 
        $lines = $Lines->getBy(['id_ticket'=>$id]);
        $Data->addItem($lines, 'lines');

        if($all) return $Data;
        else if(@$Data->estado == 1) return $Data;
        else return false; 
    }
    function getLastUser(Object $Data){
        return $this->getOneBy(['id_cliente'=>$Data->id], 'fecha', true);
    }
    // Método get de obtención por rando de fechas
    function between(Object $Data){

        $arr_tickets =  $this->getBetween('fecha',$Data->f1 . ' 00:00:00.000', $Data->f2 . ' 23:59:59.999');
        foreach($arr_tickets as $key => $ticket){
            $total = 0; 
            $lines = new Lines; 
            $arr_tickets[$key]['lineas'] = $lines->getBy(['id_ticket'=>$ticket['id']]);
            foreach($arr_tickets[$key]['lineas'] as $k => $line){
                $Art = new Items($line['articulo']);
                $arr_tickets[$key]['lineas'][$k]['articulo'] = $Art->codigo();

                $t = $line['precio'] * $line['cantidad']; 
                $dto = $line['dto']  * $t / 100;
                $arr_tickets[$key]['lineas'][$k]['importe'] = $t - $dto; 
                $total +=  $arr_tickets[$key]['lineas'][$k]['importe']; 
            }
            $arr_tickets[$key]['total'] = $total;
        }
        return $arr_tickets; 
    }
}