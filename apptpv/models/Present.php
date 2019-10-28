<?php namespace app\models;
use \app\core\{Query, Data};

class Present extends Query{
    public $id, $fecha; 
    protected $table = 'lineas_regalo';
    private $viewTickets, $Ticket, $viewLines; 

    function __construct($args = null){
        $this->viewTickets = new Query('vista_tickets_regalo');
        $this->Ticket = new Query('tickets_regalo');
        $this->viewLines = new Query('vista_lineas_regalo');
        parent::__construct();
    }
    function addTicket($idTicket, $fechaven){
        return $this->Ticket->add(['id'=> $idTicket, 'fecha_vencimiento' => $fechaven], false);
    }
    function addLine($idLine){
        return $this->add(['id'=> $idLine], false); 
    }
    function load($id){
        $d = $this->getBy(['id'=>$id]); 
        $this->loadData($d);
        return $d; 
    }
    private function getTicket($Data){

        $T = new Tickets();
        $t = $T->get(['regalo'=>1]);

        return $this->getBy(['id' => $Data->id]);
    }
    function next($Data){
        $T = new Tickets((int)$Data->id);
        $T->next(null, 'AND regalo=1'); 
        return $T->toArray();
    }
    function prev($Data){
        $T = new Tickets((int)$Data->id);
        $T->prev(null, 'AND regalo=1');
        return $T->toArray();
    }
    function get(){
        $tickets = $this->viewTickets->getAll(); 
     
        if($tickets){
            foreach ($tickets as $key => $value) {
                $lines = $this->viewLines->getBy(['id_ticket' => $value['id']]);
                $tickets[$key]['lines'] = $lines;
            }
            return $tickets;
        } else return false; 

    }
    function delete($data){
        $this->loadData($data);
        $this->fecha = null;
        return $this->saveById();
    }
    function addDate($data){
        $this->loadData($data);
        return $this->saveById();
    }
    function expiration(Data $Post){
        $this->Ticket->id = $Post->id;
        return $this->Ticket->saveById($Post->toArray());
    }
}