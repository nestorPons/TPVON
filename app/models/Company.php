<?php namespace app\models;

class Company extends \app\core\Query{

    private $id, $nombre, $fecha, $sector, $plan, $ultimo_acceso, $code;

    function __construct(){
        $this->prefix = 'admin_';
        return $this->conectDB('empresas');
    }
    /**
     * Crea una nueva aplicación
     * Dividimos los datos de las dos tablas la de empresas y la base datos de la app
     * Guardamos los datos de la tabla empresas
     * Creamos la base datos de la aplicacion
     * Creamos la carpeta para los archivos de configuración de la aplicación
     */
    public function new(Object $Data){
        // Validamos los datos del formulario
        if(!$Data->valid(['nombre_empresa', 'nif' ,'sector', 'nombre_usuario', 'apellidos', 'email', 'password'], true));
        // Registro de la tabla empresas
        $this->nombre = $Data->nombre_empresa; 
        $this->nif = $Data->nif; 
        $this->sector = $Data->sector; 
        $this->code = $Data->normalize($this->nombre);
        
        $this->table  = 'empresas';
/*       $this->id =  $this->add([
            'nombre' => $this->nombre, 
            'nif' => $this->nif, 
            'sector' => $this->sector
            ]); */

/*       $this->table => 'usuarios';
         $this->add([
            'nombre' => $this->nombre, 
            'apellidos' => $this->apellidos,
            'dni' => $this->nif,
            'id_empresa' => $this->id
            ]); */

/*       $this->table => 'emails';
         $this->add([
            'email' => $this->email, 
            'id_empresa' => $this->id
            ]); */       


        // Creamos la base de datos
        if($this->query('CREATE DATABASE '. $this->prefix_comp  . $this->code . ' COLLATE utf8_spanish2_ci;')){
            // Registro la base de datos de la empresa
            //Añadimos tablas
            if($this->createTables()){
                if($this->createFolder()){
                    $this->Comp = new Company($this->code);
                    $this->Comp->new($Data);
                }
            }
        } else throw new \Exception(\app\core\Error::E013, 13);

        // Creamos carpeta con configuración y archivos
        if ($this->createFolder()){
            return $this->id;
        } else return false;
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
    private function createTables(){ 
        $this->pdo->beginTransaction();

        $this->query(file_get_contents(\FOLDERS\DB . 'app/usuarios.sql'));
        $this->query(file_get_contents(\FOLDERS\DB . 'app/direcciones.sql'));
        $this->query(file_get_contents(\FOLDERS\DB . 'app/telefonos.sql'));
        $this->query(file_get_contents(\FOLDERS\DB . 'app/articulos.sql'));
        $this->query(file_get_contents(\FOLDERS\DB . 'app/login.sql'));
        $this->query(file_get_contents(\FOLDERS\DB . 'app/tipo_iva.sql'));
        $this->query(file_get_contents(\FOLDERS\DB . 'app/tickets.sql'));
        $this->query(file_get_contents(\FOLDERS\DB . 'app/lineas.sql'));
        $this->query(file_get_contents(\FOLDERS\DB . 'app/historial.sql'));
        
        if(!$this->pdo->commit()) throw new \Exception(\core\Error::E015, 15);
        return true;
    }
}