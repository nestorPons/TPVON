<?php namespace app\models;
use \app\core\{Query, Data, Error};
class Tickets extends Query{
    public $id, $iva, $id_usuario, $id_cliente, $estado, $fecha, $hora, $regalo = 0;
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
    function new(Data $Data){

        //Comprobamos si existe
        if(!$this->getById($Data->id)){
            $lines = $Data->lines; 
            $esregalo = $Data->regalo; 
            $Data->filter(new Tickets); 
            $DateTime = new \DateTime;
            $Data->fecha = $DateTime->format('Y-m-d H:i');
            $this->id = $this->add($Data->toArray());
    
            foreach($lines as $line){
                $Line = new Lines;
                $idLine = $Line->add([
                    'id_ticket' => $this->id,
                    'articulo'  => intval($line['articulo']),
                    'precio'    => floatval($line['precio']),
                    'cantidad'  => intval($line['cantidad']), 
                    'dto'       => floatval($line['dto'])
                ]);
                if($esregalo) {
                    $Control = new Control();
                    $Control->add([
                        'id_linea' => $idLine 
                    ]); 
                }
            }  
            return ['id' => $this->id]; 
        } else return false;
        
    }
    // Método genérico de captura de tickets con sus lineas
    function get($data, bool $all = false){
        $id = is_object($data) ? $data->id : $data;
        $Data = new Data($this->getById($id));
        $Lines = new Lines; 
        $lines = $Lines->getBy(['id_ticket'=>$id]);
        $Control = new Control; 
        foreach($lines as $k => $v){
            $r = $Control->getBy(['id_linea'=>$v['id']]);
            $lines[$k]['fecha_regalo'] = $r[0]['fecha'];
        }
        $Data->addItem($lines, 'lines');
        if($all) return $Data;
        else if(@$Data->estado == 1) return $Data;
        else return false; 
    }
    function getLastUser(Data $Data){
        return $this->getOneBy(['id_cliente'=>$Data->id], 'fecha', true);
    }
    // Método get de obtención por rando de fechas
    function between(Data $Data){
        $filterUser = (!empty($Data->u)) ? "AND id_cliente = {$Data->u}" : ''; 
        $arr_tickets =  $this->getBetween('fecha',$Data->f1 . ' 00:00:00.000', $Data->f2 . ' 23:59:59.999', $filterUser);
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
    function prev(Data $Data){
        $arr = $this->query("SELECT * FROM $this->table WHERE id < $Data->id   AND estado = 1 ORDER BY id DESC  LIMIT 1;");
        if(isset($arr[0])) {
            $d = new Data($arr[0]); 
            return $this->getLines($d);
        } else return 0;

    }
    function next(Data $d){
        $arr = $this->query("SELECT * FROM $this->table WHERE id > $d->id  AND estado = 1 ORDER BY id  LIMIT 1;");
        if(isset($arr[0])) {
            $d = new Data($arr[0]); 
            return $this->getLines($d);
        } else return ['id'=>-1];
    }
    function getLines(Data $d){
        $Lines = new Lines; 
        $lines = $Lines->getBy(['id_ticket'=>$d->id]);
        $d->addItem($lines, 'lines');
        return $d; 
    }
}