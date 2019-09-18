<?php namespace app\models;
use PHPMailer\PHPMailer\{ PHPMailer,  Exception};
use \app\core\{
    Error,
    Query, 
    Data
};

class Company extends Query{

    public 
        $id = 1, $nombre, $fecha, $ultimo_acceso, $nif,
        $email, $telefono, $calle, $numero, $piso, $escalera, $poblacion, $CP, $provincia, $pais; 
    protected 
        $data = null ,
        $table = 'empresa', 
        $db = CONN['db']; 

    function __construct(){ 
        return !parent::__construct('empresa', $this->db);
    }
    function save(){
        $this->ultimo_acceso(date("Y-m-d H:i:s"));

        return $this->saveById($this->toArray());
    }
    /**
     * Crea una nueva aplicación
     * Dividimos los datos de las dos tablas la de empresas y la base datos de la app
     * Guardamos los datos de la bd empresas
     * Creamos la base datos de la aplicacion
     * Creamos la carpeta para los archivos de configuración de la aplicación
     */
    public function new(Data $Data){
   
        $Data->validate(['nombre_empresa', 'nif' ,'sector', 'nombre_usuario', 'email', 'password'], true);
        $Data->codifyAttr('nombre_empresa');
        $Data->set('nombre', $Data->nombre_empresa);
        $this->loadData($Data->getAll());
    
        // Creamos la base de datos
        try{         
    
            $this->createDb($this->db);
            $this->createTables();
            
            // Cargamos los datos de la empresa
            $this->fecha(date("Y-m-d H:i:s"));
            $this->ultimo_acceso(date("Y-m-d H:i:s"));
            $new = new Query($this->table, $this->db); 
            $data = $this->toArray();
            unset($data['config']);
            
            // VAlores para registros de muestra
            $data['telefono'] = '123456789';
            $data['calle'] = 'Calle empresa';
            $data['numero'] = 1;
            $data['piso'] = 0;
            $data['escalera'] = '1'; 
            $data['poblacion'] = 'Población';  
            $data['provincia'] = 'Provincia';
            $data['CP'] = '12345';
            $data['pais'] = 'ES';      
            
            $new->add($data);
            $ser = new Items();
            $ser->add([
                'codigo'        => 'SER001',
                'nombre'        => 'Servicio 001',
                'descripcion'   => 'Esto es un servicio de muestra',
                'precio'        => 4,
                'coste'         => 2,
                'id_iva'        => 1,
                'tipo'          => 1,
                'estado'        => 1     
            ]);

            //Añadimos el usuario administrador
            $Data->set('nombre', $Data->nombre_usuario); 
            $Data->set('nivel', 2);
            $User = new User;
            if (!$User->new($Data)) throw new \Exception('E019');

            
            //$this->add(['nombre' => $this->nombre, 'email' => $this->email]);
            // Creamos carpeta con configuración y archivos
            $this->createFolder();
            
            return true; 
        } catch( \Exception $e){
            return Error::array($e->getMessage());
        }
    }
    private function createFolder(){
        $folder = \PUBLICF\COMPANIES . $this->nombre;
        if (!file_exists($folder)){
            mkdir($folder, 0750);
            copy(\PUBLICF\TEMPLATE . 'config.ini', $folder . '/config.ini' );
        } else{
            throw new Error('E017');
        }
    }
    // Extraemos el prefijo por defecto para las bbdd de la aplicación
    // Creamos la base de datos con el nombre correspondiente
    private function createDb($db){
        $credentials = parse_ini_file(\FILE\CONN);
        $dsn =  'mysql:host=' . $credentials["host"] . ';port='. $credentials["port"];
        // Creamos la conexión
        $this->conn = $this->connect($dsn, $credentials[$this->user]);
        if(!$this->query("CREATE DATABASE $db COLLATE utf8_spanish2_ci;")) throw new Error('E013');
        else return true;  
    }
    private function createTables(){
        $newConn = new Query(null, $this->db);
        $newConn->pdo->beginTransaction();
            $newConn->query(file_get_contents(\FOLDERS\DB . 'app/usuarios.sql'));
            $newConn->query(file_get_contents(\FOLDERS\DB . 'app/articulos.sql'));
            $newConn->query(file_get_contents(\FOLDERS\DB . 'app/login.sql'));
            $newConn->query(file_get_contents(\FOLDERS\DB . 'app/tipo_iva.sql'));
            $newConn->query(file_get_contents(\FOLDERS\DB . 'app/tickets.sql'));
            $newConn->query(file_get_contents(\FOLDERS\DB . 'app/lineas.sql'));
            $newConn->query(file_get_contents(\FOLDERS\DB . 'app/historial.sql'));
            $newConn->query(file_get_contents(\FOLDERS\DB . 'app/control.sql'));
            $newConn->query(file_get_contents(\FOLDERS\DB . 'app/promos.sql'));
            $newConn->query(file_get_contents(\FOLDERS\DB . 'app/tokens.sql'));
            $newConn->query(file_get_contents(\FOLDERS\DB . 'app/empresa.sql'));
        if(!$newConn->pdo->commit()) throw new Error('E014');
        return true;
    }
    // getters y setters
    function id(int $arg = null){
        if($arg) $this->{__FUNCTION__} = $arg; 
        return $this->{__FUNCTION__}; 
    }
    function nombre($arg = null){
        if($arg) $this->{__FUNCTION__} = $arg; 
        return $this->{__FUNCTION__}; 
    }
    function fecha($arg = null){
        if($arg) $this->{__FUNCTION__} = $arg; 
        return $this->{__FUNCTION__}; 
    }
    function ultimo_acceso($arg = null){
        if($arg) $this->{__FUNCTION__} = $arg; 
        return $this->{__FUNCTION__}; 
    }
    function data(){
        return new Data($this);
    }
}