<?php namespace app\models;

class Companies extends \app\core\Query{

    private $id, $nombre, $fecha, $sector, $plan, $ultimo_acceso;

    function __construct(){
        $this->prefix = 'admin';
        $this->table  = 'empresas';
        return $this->conectDB('empresas');
    }
    public function new(Object $dataJSON){
        if(!(isset($dataJSON->nombre) || isset($dataJSON->sector))) return false;
    
        $this->id = $this->add((array)$dataJSON);
    
        $this->company = new Company($this->id);

        return $this->id;
    }
    // getters y setters
    public function nombre( string $arg = ''){
        if($arg != '') {
            $this->nombre = $arg; 
        }
        return $this->nombre; 
    }
    
}