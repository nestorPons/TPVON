<?php namespace app\controllers;
use \app\models\User; 
use \app\core\{Query, Data, Controller};

class Users extends Controller{

    function __construct(String $action,  $Data = null){
        $this->user = new User($Data->idadmin); 
        parent::__construct($action);
    }
    protected function view($data = null){
        $data['admin'] = $this->user->isAdmin(); 
        parent::view($data);        
    }
    
}