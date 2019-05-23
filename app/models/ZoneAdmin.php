<?php namespace app\models;
use \app\core\{Query, Data, Error};

class ZoneAdmin extends Query{
    private $User ;

    function __construct(Object $User){
        $this->User = $User; 

    }
}