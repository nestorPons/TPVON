<?php namespace app\controllers;
use \app\models\{Items, Tickets, User, Company, Control, Config, Family};
use \app\core\{Query, Data, Controller};

/**
 * Controla la vista y la recepción de los datos del formulario de login
 */
class Admin extends Controller{

    function __construct(){
    }
    function loadView(){
        $Config = new Config();
        $Company= new Company();
        $Ticket = new Tickets; 
        $Items  = new Items;
        $Promos = new Query('promos'); 
        $User   = new User;
        $Fam    = new Family;
        $data['config']  = json_encode($Config->getAll()); // para js

        $lastTicket = $Ticket->getLast();
        $data['tickets_new_id'] = $lastTicket['id'] + 1 ;
        $data['tickets_hora'] = '';
        $data['today'] = date('d/m/Y'); 
        
        $data['jsonServices'] = json_encode($Items->getAll()); // para js
        
        $data['Company'] = $Company;
        $data['data_company'] = json_encode($Company->getAll());
        
        $data['promos'] = json_encode($Promos->getAll()); 

        $data['Users'] = $User->all();
        $data['jsonUsers'] = json_encode($data['Users']); // para js

        $data['families'] = json_encode($Fam->getAll());
        return $this->printView( \VIEWS\ADMIN . 'index.phtml', $data);
    }
}