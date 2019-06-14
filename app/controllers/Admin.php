<?php namespace app\controllers;
use \app\models\{Items, Tickets, User, Company};
use \app\core\{Query, Data};

/**
 * Controla la vista y la recepción de los datos del formulario de login
 */
class Admin extends Controller{

    function __construct(){
    }
    function loadView(){

        $Ticket = new Tickets; 
        $lastTicket = $Ticket->getLast();
        $data['tickets_id'] = $lastTicket['id'];
        $data['tickets_hora'] = '';
        
        $Items = new Items;
        $data['Services'] = $Items->allData($Items, 'codigo');
        
        $Iva = new Query('tipo_iva');
        $conf = new Data(['iva' => $Iva->getOneBy(['pre'=>true])]);

        $data['Config'] = $conf;
        
        $data['Company'] = new Company(NAME_COMPANY);

        $User = new User;
        $data['Employees'] = $User->allEmployees();
        $data['Users'] = $User->allData($User);

        return $this->printView( \VIEWS\ADMIN . 'index.phtml', $data);
    }
}