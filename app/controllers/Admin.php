<?php namespace app\controllers;
use \app\models\{Items, Tickets, User};

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
        $data['Services'] = $Items->allData($Items, 'codigo');

        $User = new User;
        $data['Employees'] = $User->allEmployees();
        $data['Users'] = $User->allData($User);

        return $this->printView( \VIEWS\ADMIN . 'index.phtml', $data);
    }
}