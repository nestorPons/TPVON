<?php namespace app\models;
use \app\core\{Query, Data, Error};

class Tickets extends Query{
    public $id, $iva, $id_usuario, $id_cliente, $estado, $fecha, $hora, $lines;
    protected $table = 'tickets';
    private $total = 0.0; 

    function __construct($args = null){
        parent::__construct();
        if(is_int($args)){
            $this->loadData(
                $this->get($args)
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
    function new(Data $Post){

        //Comprobamos si existe
        if($Post->id == -1 || !$this->getById($Post->id)){
            $lines = $Post->lines; 
            $end_date = $Post->fecha_vencimiento; 
            $isPresent = !empty($Post->fecha_vencimiento); 

            // Limpiamos post de datos indeseados
            $Post->filter(new Tickets);
            $DateTime = new \DateTime;
            $Post->fecha = $DateTime->format('Y-m-d H:i');
            // Guardamos el id generado 

            $this->id = $this->add($Post->toArray(['lines']));
                // Si es regalo guardamos la fecha de vencimiento en la tabla ticket_regalo
                if($isPresent) {
                    $Present = new Present;
                    $Present->addTicket($this->id, $end_date);              

                foreach($lines as $line){
                    $Line = new Lines;
                    $idLine = $Line->add([
                        'id_ticket' => $this->id,
                        'articulo'  => intval($line['articulo']),
                        'precio'    => floatval($line['precio']),
                        'cantidad'  => intval($line['cantidad']), 
                        'dto'       => floatval($line['dto'])
                    ]);
                    if($isPresent) $Present->addLine($idLine);                     
                }  
                return ['id' => $this->id]; 
            } else throw new Error('E053');

        }
        
    }
    // Método genérico de captura de tickets con sus lineas
    // Data puede ser el id o un objeto con el id
    // Se añade una propiedad total que se crea en tiempo de ejecución
    function get($data, bool $all = false){
        $id = is_object($data) ? $data->id : $data;
        $Data = new Data($this->getById($id));
        // Se usa la clase para buscar todas las lineas
        $Lines = new Lines; 
        $lines = $Lines->getBy(['id_ticket'=>$id]);
        
        foreach($lines as $k => $v){
            $Present = new Present($v['id']); 
            $lines[$k]['fecha_regalo'] = $Present->fecha;

            // Se crea una linea
            $Line = new Lines($v); 
            // Se añade el total de la linea al total del ticket
            $this->total($Line->total());
        }

        $Data->addItem($lines, 'lines');
        $Data->addItem($this->total(), 'total');
        
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
    function prev(Data $Data = null, String $filter = ''){
        $id = ($Data) ? $Data->id : $this->id; 
        $arr = $this->query("SELECT * FROM $this->table WHERE id < $id AND estado = 1 $filter ORDER BY id DESC  LIMIT 1;");
        if(!empty($arr)) {
            $this->loadData(
                $this->get($arr[0]['id'])
            );
            return $this->toArray();
        }
        else return false;
    }
    function next(Data $Data = null, String $filter = ''){
        $id = ($Data) ? $Data->id : $this->id; 
        $arr = $this->query("SELECT * FROM $this->table WHERE id > $id AND estado = 1 $filter ORDER BY id ASC  LIMIT 1;");
        if(!empty($arr)) {
            $this->loadData(
                $this->get($arr[0]['id'])
            );
            return $this->toArray();
        }
        else return false;
    }
    function lines(Data $d = null){
        if($d) {
            $Lines = new Lines; 
            $lines = $Lines->getBy(['id_ticket'=>$d->id]);
            $d->addItem($lines, 'lines');
        } 
        return $this->lines; 
    }
    function total(float $val = null) : float {
        if ($val) $this->total += $val;
        return  round($this->total,2); 
    }
}