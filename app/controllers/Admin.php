<?php namespace app\controllers;
use \app\models\{Items, Tickets, User};
use \app\core\{Error, Data};

/**
 * Controla la vista y la recepción de los datos del formulario de login
 */
class Admin extends Controller{

    function __construct(){
    }
    function loadView(){

        $Ticket = new Tickets;
        //$Ticket->last(); 
        $data = $Ticket->toArray(true);

        $Items = new Items;
        $data['Services'] = $Items->allData($Items);

        $User = new User;
        $data['Employees'] = $User->allEmployees();
        $data['Users'] = $User->allData($User);
        return $this->printView( \FOLDERS\ADMIN . 'index.phtml', $data);
    }
}