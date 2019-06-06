<?php namespace app\models;
use \app\core\{
    Error,
    Query,
    Data
};

class Company extends Query{

    protected 
        $id, $nombre, $fecha, $sector, $plan, $ultimo_acceso, $nif,
        $data = null;

    function __construct($arg = null){
        // Cambiamos la tabla y prefijo para referirnos a la tabla de administrcion de empresas
        parent::__construct('empresas', 'admin_empresas');
        if($arg){
            if (is_int($arg)){

                $this->data = $this->getById($arg);
            } else if (is_string($arg)){
                $this->data = $this->getBy(['nombre'=>$arg]);
            }
            if($this->data) $this->loadData($this->data);
        }
    }

    /**
     * Crea una nueva aplicación
     * Dividimos los datos de las dos tablas la de empresas y la base datos de la app
     * Guardamos los datos de la bd empresas
     * Creamos la base datos de la aplicacion
     * Creamos la carpeta para los archivos de configuración de la aplicación
     */
    public function new(Object $Data){

        $Data->validate(['nombre_empresa', 'nif' ,'sector', 'nombre_usuario', 'email', 'password'], true);
        $Data->codifyAttr('nombre_empresa');
        $Data->set('nombre', $Data->get('nombre_empresa'));
        $this->loadData($Data->getAll());
        $this->config = parse_ini_file(\FILE\CONN);
        $this->db = $this->config["prefix"]  . $this->nombre; 
        
        // Definimos la base de datos por defecto
        define('CODE_COMPANY', $this->nombre);
        define('NAME_COMPANY', ucwords(CODE_COMPANY));

        // Registro de la tabla empresas
        if(
            $this->id =  $this->add([
                 'nombre' => $this->nombre, 
                 'nif' => $this->nif, 
                 'sector' => $this->sector
             ])
        ){
            // Creamos la base de datos
            
            try{
                $this->createDb();
                $this->createTables();
                //Añadimos el usuario administrador
                $Data->set('nombre', $Data->get('nombre_usuario')); 
                $Data->set('nivel', 2); 

                $User = new User;
                if (!$User->new($Data)) throw new \Exception('E019'); 
                // Creamos carpeta con configuración y archivos
                $this->createFolder();
                return true; 
            
            } catch( \Exception $e){
                return Error::array($e->getMessage());
            }
        } else {
            return Error::array('E011');    
        }
    }

    private function createFolder(){
        $folder = \FPUBLIC\COMPANIES . $this->nombre;
        if (!file_exists($folder)){
            mkdir($folder, 0750);
            copy(\FPUBLIC\TEMPLATE . 'config.ini', $folder . '/config.ini' );
        } else{
            throw new Error('E017');
        }
    }
    // Extraemos el prefijo por defecto para las bbdd de la aplicación
    // Creamos la base de datos con el nombre correspondiente
    private function createDb(){
        if(!$this->query('CREATE DATABASE '. $this->db . ' COLLATE utf8_spanish2_ci;')) throw new Error('E013');  
    }
    private function createTables(){
        $newConn = new Query(null, $this->db);
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
            $newConn->query(file_get_contents(\FOLDERS\DB . 'app/tokens.sql'));
        
        if(!$newConn->pdo->commit()) throw new Error('E014');
    }

    // getters y setters
    function id(int $arg = null){
        if($arg) $this->{__FUNCTION__} = $arg; 
        return $this->{__FUNCTION__}; 
    }
    function nombre(string $arg = null){
        if($arg) $this->{__FUNCTION__} = $arg; 
        return ucwords($this->{__FUNCTION__}); 
    }
    function data(array $arg = null){
        if($arg) $this->{__FUNCTION__} = $arg; 
        return $this->{__FUNCTION__}; 
    }
    
}