<?php namespace app\controllers; 

class Controller{
    protected $controller = 'login'; 
    protected $action = 'view';
    function __construct($args = []){
        if(isset($args['controller'])){
            $this->controller = $args['controller']; 
            if(isset($args['action'])) $this->action = $args['action'];
        }

        echo $this->controller .  ' ' . $this->action ; 
    }
}