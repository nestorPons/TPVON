<?php namespace app\controllers;
use \app\models\{Items, Tickets, User, Company, Config, Control};
use \app\core\{Query, Data};

/**
 * Controla la vista y la recepción de los datos del formulario de login
 */
class Admin extends Controller{

    function __construct(){
    }
    function loadView(){
        $Config = new Config; 
        $data['iva'] = $Config->iva;
        
        $Ticket = new Tickets; 
        $lastTicket = $Ticket->getLast();
        $data['tickets_new_id'] = $lastTicket['id'] + 1 ;
        $data['tickets_hora'] = '';
        $data['today'] = date('d/m/Y'); 
        
        $Items = new Items;
        $data['jsonServices'] = json_encode($Items->getAll()); // para js
        
        $Company = new Company();
        $data['Company'] = $Company;
        $data['data_company'] = json_encode($Company->getAll());
        
        $Promos = new Query('promos'); 
        $data['promos'] = json_encode($Promos->getAll()); 

        $User = new User;
        $data['Users'] = $User->all();
        $data['jsonUsers'] = json_encode($data['Users']); // para js



        return $this->printView( \VIEWS\ADMIN . 'index.phtml', $data);
    }
}