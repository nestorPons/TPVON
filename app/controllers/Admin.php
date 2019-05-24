<?php namespace app\controllers;
use \app\models\{Items, Tickets, Users};
use \app\core\{Error, Data};

/**
 * Controla la vista y la recepción de los datos del formulario de login
 */
class Admin extends Controller{

    function __construct(){
    }
    function loadView(){
        
        $Ticket = new Tickets;
        $data = $Ticket->toArray(true);
        
        $Items = new Items;
        $data['servicios'] = $Items->getAll('codigo');
        foreach( $data['servicios'] as $key => $val){
            $data['selectServicios'][$val['codigo']] = $val['codigo'];
        }
// AKI :: 
        $Users = new Users; 
        $staff = $Items->getAll();
        foreach( $staff as $key => $val){
            $data['workers'][$val['codigo']] = $val['codigo'];
        }

        return $this->printView( \FOLDERS\ADMIN . 'index.phtml', $data);
    }
}