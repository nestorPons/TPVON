<?php namespace app\models;
use \app\core\{Query, Data};

class Items extends Query{
    public $id, $codigo, $descripcion, $precio, $coste, $tipo, $estado;

    function __construct($arg = null){
        $this->table = 'articulos'; 
        parent::__construct();
        if (is_array($arg)) $this->loadData($arg);
        else if (is_int($arg)) $this->loadData($this->getById($arg));
    }
    // Nuevos registros
    function new(Data $Data){

        if ($this->id = $this->loadData($Data->getAll())){  
            if($this->id = $this->add($Data->toArray())) return $this->id;
            else return Error::array('E022');
            
        } else throw new \Exception('E060');
    }
    function save(Data $Data){
        if($this->id == -1) $this->new($Data);
        else ($this->saveById($Data->toArray()));
        
        return $this->id;
    }
    
    // getters y setters
    function nombre(){
        return $this->nombre; 
    } 
    function codigo(){
        return $this->codigo; 
    } 
}