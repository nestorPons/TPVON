<?php namespace app\models;

class User extends \app\core\Query{
    private $id, $dni, $nombre, $email, $fecha_nacimiento, $estado, $nivel, $password, $intentos;
    protected $table = 'usuarios'; 

    function __construct($arg = null){
        if($arg){
            $this->connecTo() ;
            if (is_int($arg)) $this->searchById();
            else if (strpos($arg, '@')) $this->searchByEmail($arg);
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
                'password' => $this->password_hash(),
                'intentos' => $this->intentos
            ]);
        } else throw new \Exception('E060');
    }
    function password_hash(string $pass = null){
        $pass = $pass??$this->password(); 
        return password_hash($pass, PASSWORD_DEFAULT);
    }
    function loadData($Data){
        if(is_array($Data)) $Data = new \app\libs\Data($Data);
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
    function searchByEmail(string $arg){
        $data = $this->getBy(['email' => $arg]);
        if($data) return $this->loadData($data);
        \app\core\Error::die('E025');
    }
    //getters setters
    function id(int $arg = null){
        if($arg) $this->{__FUNCTION__} = $arg; 
        return $this->{__FUNCTION__}; 
    }
    function email(int $arg = null){
        if($arg) $this->{__FUNCTION__} = $arg; 
        return $this->{__FUNCTION__}; 
    }
    function nombre(int $arg = null){
        if($arg) $this->{__FUNCTION__} = $arg; 
        return $this->{__FUNCTION__}; 
    }
    function dni(int $arg = null){
        if($arg) $this->{__FUNCTION__} = $arg; 
        return $this->{__FUNCTION__}; 
    }
    function fecha_nacimiento(int $arg = null){
        if($arg) $this->{__FUNCTION__} = $arg; 
        return $this->{__FUNCTION__}; 
    }
    function password(int $arg = null){
        if($arg) $this->{__FUNCTION__} = $arg; 
        return $this->{__FUNCTION__}; 
    }
}