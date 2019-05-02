<?php namespace app\models;

class Company extends \app\core\Query{

    private $id, $nombre, $fecha, $sector, $plan, $ultimo_acceso, $code;

    function __construct(){
        $this->table  = 'empresas';
        $this->prefix = 'admin_';
        return $this->connecTo('empresas');
    }
    /**
     * Crea una nueva aplicación
     * Dividimos los datos de las dos tablas la de empresas y la base datos de la app
     * Guardamos los datos de la bd empresas
     * Creamos la base datos de la aplicacion
     * Creamos la carpeta para los archivos de configuración de la aplicación
     */
    public function new(Object $Data){
        // Validamos los datos del formulario
        if(!$Data->valid(['nombre_empresa', 'nif' ,'sector', 'nombre_usuario', 'email', 'password'], true));
        $this->nombre = $Data->nombre_empresa; 
        $this->nif = $Data->nif; 
        $this->sector = $Data->sector; 
        $this->code = $Data->normalize($this->nombre);
       
        // Registro de la tabla empresas
       $this->id =  $this->add([
            'nombre' => $this->nombre, 
            'nif' => $this->nif, 
            'sector' => $this->sector
            ]);

        // Creamos la base de datos
        try{
            $this->createDb();
            //Añadimos el usuario administrador

            $Data->addOne('nombre', $Data->nombre_usuario); 
            $Data->addOne('nivel', 2); 
            $User = new User();
            $User->connecTo($this->code); 
            if (!$User->new($Data)) throw new \Exception('E019'); 
            // Creamos carpeta con configuración y archivos
            if (!$this->createFolder()) throw new \Exception('E017');
            return true; 

        } catch( \Exception $e){
            return \app\core\Error::array($e->getMessage());
        }
    }
    // getters y setters
    public function nombre(string $arg = null){
        if($arg) $this->nombre = $arg; 
        return $this->nombre; 
    }

    private function createFolder(){
        $folder = \FOLDERS\COMPANIES . $this->nombre;
        if (!file_exists($folder)){
            mkdir($folder, 0750);
            copy(\FOLDERS\CONFIG . 'template.ini', \FOLDERS\COMPANIES . $this->nombre . '/config.ini');
            return true;
        }
        return false;
    }
    // Extraemos el prefijo por defecto para las bbdd de la aplicación
    // Creamos la base de datos con el nombre correspondiente
    private function createDb(){
        $config = parse_ini_file(\FOLDERS\CONFIG . 'conn.ini');
        if(!$this->query('CREATE DATABASE '. $config["prefix"]  . $this->code . ' COLLATE utf8_spanish2_ci;')) throw new \Exception('E013');
        $newConn = new \app\core\Query; 
        $newConn->prefix = $config["prefix"];
        $newConn->connecTo($this->code);
        $newConn->pdo->beginTransaction();
            $newConn->query(file_get_contents(\FOLDERS\DB . 'app/usuarios.sql'));
            $newConn->query(file_get_contents(\FOLDERS\DB . 'app/direcciones.sql'));
            $newConn->query(file_get_contents(\FOLDERS\DB . 'app/telefonos.sql'));
            $newConn->query(file_get_contents(\FOLDERS\DB . 'app/articulos.sql'));
            $newConn->query(file_get_contents(\FOLDERS\DB . 'app/login.sql'));
            $newConn->query(file_get_contents(\FOLDERS\DB . 'app/tipo_iva.sql'));
            $newConn->query(file_get_contents(\FOLDERS\DB . 'app/tickets.sql'));
            $newConn->query(file_get_contents(\FOLDERS\DB . 'app/lineas.sql'));
            $newConn->query(file_get_contents(\FOLDERS\DB . 'app/historial.sql'));
        
        if(!$newConn->pdo->commit()) throw new \Exception('E014');
        return true;
    }
}