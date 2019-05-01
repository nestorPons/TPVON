<?php namespace app\models;

class User extends \app\core\Query{
    private 
        $id, $dni, $nombre, $apellidos, $fecha_nacimiento, $estado, $nivel, $password, $intentos,
        $table = 'usuarios'; 
    function __construct(){
        $this->conectDB( , 'root');
    }
    function new(Object $Data){
        return $this->add((array)$Data);
    }
}