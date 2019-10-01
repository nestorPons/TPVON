<?php namespace app\models;
use \app\core\{Query};

class Control extends Query{
    public $id, $id_linea, $fecha; 
    protected $table = 'control';

    function __construct($arg = null){
        parent::__construct();
        if($arg) $this->load($arg);
    }
    function load($id){
        $d = $this->getBy(['id_linea'=>$id]); 
        $this->loadData($d);
        return $d; 
    }
    private function getTicket($Data){

        $T = new Tickets();
        $t = $T->get(['regalo'=>1]);

        return $this->getBy(['id_linea' => $Data->id]);
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
        $tickets = [];
        $T = new Tickets();
        $t = $T->getBy(['regalo'=>1], 'id');
        
        foreach($t as $v){
            $tickets[] = $T->get($v['id']);
        }
        return $tickets; 
    }
    function delete($data){
        $d = $this->getOneBy(['id_linea' => $data->id_linea]);
        $this->loadData($d);
        $d['fecha'] = '';
        return $this->saveById($d);
    }
    function addDate($data){

        $d = $this->getOneBy(['id_linea' => $data->id_linea]);
        $this->loadData($d);
        $d['fecha'] = $data->fecha;
        return $this->saveById($d);
    }
}