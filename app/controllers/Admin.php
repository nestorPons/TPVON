<?php namespace app\controllers;
use \app\models\{Items, Tickets, User, Company, Config};
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
        
        $Items = new Items;
        $data['Services'] = $Items->allData($Items, 'codigo'); // para php
        $data['jsonServices'] = json_encode($Items->getAll()); // para js

        $data['Company'] = new Company(NAME_COMPANY);

    
        $User = new User;
        $data['Users'] = $User->all();
        $data['jsonUsers'] = json_encode($data['Users']); // para js
        return $this->printView( \VIEWS\ADMIN . 'index.phtml', $data);
    }
}