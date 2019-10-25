<?php namespace app\models;
use \app\core\{Query};

class Present extends Query{
    public $id, $fecha; 
    protected $table = 'lineas_regalo';
    private $connTicket = null, $connLine = null; 

    function __construct(){
        $this->connTicket = new Query('tickets_regalo');
        $this->connLine = new Query('lineas_regalo');
        $this->connview = new Query('vista_lineas_regalo');

    }
    function addTicket($idTicket){
        return $this->connTicket->add(['id'=> $idTicket], false); 
    }
    function addLine($idLine){
//pr('LINEA', $idLine);
        return $this->connLine->add(['id'=> $idLine], false); 
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
        return $this->connview->getAll();
    }
    function delete($data){
        $d = $this->getOneBy(['id' => $data->id]);
        $this->loadData($d);
        $d['fecha'] = '';
        return $this->saveById($d);
    }
    function addDate($data){

        $d = $this->getOneBy(['id' => $data->id]);
        $this->loadData($d);
        $d['fecha'] = $data->fecha;
        return $this->saveById($d);
    }
}