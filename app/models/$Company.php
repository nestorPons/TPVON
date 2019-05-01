<?php namespace app\models;
/**
 * Clase empresa que controla eventos de empresas
 * Lee archivo de configuración de la empresa y conecta con su base de datos
 */
class Company extends \app\core\Query{
    private $nif, $nombre, $email, $web, $code; 
    public $data;

    function __construct(string $code = null){
        $this->code = $code;

        // Conecta con la base de datos
        if($this->conectDB($code, 'root')){
            //Exite la empresa en la base de datos
            $this->loadData();
        } 
    }
    private function confirmation(){
        // Enviar mensaje de confirmación
        return true; 
    }
    protected function loadData(){
        // Array de datos
        $config = parse_ini_file(\FOLDERS\COMPANIES . $this->code . 'config.ini');
    
        // Se crean los atributos de clase 
        foreach($config as $key => $value){
             $this->{$key} = $value; 
        }
    } 
    function new(Object $Data){
       // Añadimos las tablas          
        
        $this->id = $this->add([
            'nombre' => $this->nombre, 
            'email' => $this->email, 
            'nif' => $this->nif, 
            'web' => $this->web
        ]);
        
    }

}