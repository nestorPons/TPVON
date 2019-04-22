<?php namespace app\models;
/**
 * Clase empresa que controla eventos de empresas
 */
class Company extends \app\core\Query{
    private $NIF, $nombre, $email, $web; 
    public $data;

    function __construct($id){
        $this->table  = 'empresa';
        if($this->conectDB($id, 'root')){
            //Exite la empresa en la base de datos

            $this->loadData(); 
        } else {
            // No existe la empresa
            // Se crea una nueva

        }
    }
    private function confirmation(){
        // Enviar mensaje de confirmación
        return true; 
    }

    protected function loadData(){
        // Array de datos
        $this->data = $this->getAll();
        // Se crean los atributos de clase 
        foreach($this->data as $key => $value){
             $this->{$key} = $value; 
        }
    }
}