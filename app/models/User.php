<?php namespace app\models;

class User extends \app\core\Query{
    private $id, $dni, $nombre, $email, $fecha_nacimiento, $estado, $nivel, $password, $intentos;
    protected $table = 'usuarios'; 

    function __construct($arg = null){
        if($arg){
            if (!$this->connecTo()) throw new \Exception('E052');
            if (is_int($arg)) $this->searchById();
            else if (strpos($arg, '@')) $this->searchByEmail();
        }
    }
    function new(Object $Data){
        if ($this->loadData($Data)){
            return $this->add([
                'dni' =>  $this->dni,
                'nombre' => $this->nombre,
                'email' => $this->email,
                'fecha_nacimiento' => $this->fecha_nacimiento,
                'estado' => $this->estado,
                'nivel' => $this->nivel, 
                'password' => $this->password,
                'intentos' => $this->intentos
            ]);
        } else throw new \Exception('E060');
    }
    function loadData(Object $Data){
        $this->dni = $Data->dni??null;
        $this->nombre = $Data->nombre;
        $this->email = $Data->email??null;
        $this->fecha_nacimiento = $Data->fecha_nacimiento??null;
        $this->estado = $Data->estado??1; 
        $this->nivel = $Data->nivel??0;
        $this->password = $Data->password??null;
        $this->intentos = $Data->intentos??0;
        return true;
    }
}