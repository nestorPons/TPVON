<?php namespace app\models;

class Companies extends \app\core\Query{

    private $id, $nombre, $fecha, $sector, $plan, $ultimo_acceso;

    function __construct(){
        return $this->conectDB();
    }
    private function conectDB(){
        $this->prefix = 'admin'; 
        $db     = 'empresas'; 
        $table  = 'empresas';
        $user   = 'root';
        return parent::__construct($db, $table, $user);
    }
    public function new(array $data){
AKI::: 
        if(!(isset($data['nombre']) || isset($data['sector']))) return false;
        return $this->add($data);

    }
    // getters y setters
    public function nombre( string $arg = ''){
        if($arg != '') {
            $this->nombre = $arg; 
        }
        return $this->nombre; 
    }
    
}