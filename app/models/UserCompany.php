<?php namespace app\models;

class User extends \app\core\Query{
    private 
        $id, $id_empresa, $nombre, $apellidos, $dni,
        $table = 'usuarios'; 
    function __construct(){

    }
    function new(Object $Data){
        return $this->add((array)$Data);
    }
}