<?php namespace \app\controllers;

class Controllers {
    protected $controller = 'login'; 
    protected $action = 'view';

    function __construct($controller, $action){
        if($action == 'save'){
            $this->sendModel( );
        }else{
            $this->sendView( $controller ); 
        }
    }

    protected function sendView(){
        require_once \FOLDERS\VIEW . 
    }

    protected function sendModel(){

    }
}