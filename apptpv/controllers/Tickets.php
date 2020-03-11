<?php namespace app\controllers;
use \app\models\{Tickets as Model, Debt};
use \app\core\{Query, Data, Controller};

/**
 * Controla la vista y la recepción de los datos del formulario de login
 */
class Tickets extends Controller{

    function __construct(String $action,  $Data = null){
        $this->Model = new Model;
        $this->Data = $Data; 
        $this->controller = 'Tickets';
        $this->result = $this->{$action}($Data);
    }
    function getLast(Data $Data){
        return $this->Model->getLastUser($Data);
    }
    function prev(Data $Data){
        return $this->Model->prev($Data);
    }
    function next(Data $Data){
        return $this->Model->next($Data);
    }
    function last(Data $Data){
        return $this->Model->getLast($Data);
    }
    function getAll(){
        $arr = $this->Model->getAll();
        $all = []; 

        foreach ($arr as $key => $value) {
            $T = new Model(); 
            $all[] = $T->get($value['id']) ;
        }
        return $all; 
    }
    function unpaid(){
        $u = new Query('vista_deudas');
        return $u->getAll();
    }
    function paydebt(){
        $deb = new Debt; 
        $d = $deb->getBy(['id_ticket' => $this->Data->id_ticket]);
        $deb->loadData($d);
        return $deb->saveById(['fecha'=>date('Y-m-d h:i:s')]);
    }
    function debt(){
        return new Debt(['id_ticket'=>$this->Data->id_ticket]);
    }
}