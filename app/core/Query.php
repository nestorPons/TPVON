<?php namespace app\core;
use app\core\Data;
/**
 * Se crean todos los métodos necesarios para las diferentes peticiones a la base de datos 
 */
class Query extends Conn{
    protected  
        $conn, 
        $table;

    function __construct(string $table = null, string $db = null, string $user = null){

            // Parametros predeterminados para la conexión
            $credentials = parse_ini_file(\FILE\CONN);
            if($table) $this->table = $table;
    
            $this->db = $db ?? CONN['db'];
            $this->user = $user??$credentials['current_user'];
            $dsn = 'mysql:dbname=' . $this->db . ';host=' . $credentials["host"] . ';port='. $credentials["port"];

            try{
                $this->conn = $this->connect($dsn, $credentials[$this->user]);
                return gettype($this->conn) === 'object';
          
            } catch (\Exception $e){
                return false;
            }
    }
    function __destruct(){
        $this->conn = null;
     }

     /**
      * Prepara el string sql para enviar a hacer la consulta 
      * añadimos si queremos que sean ordenados de forma inversa
      */
    private function sendQuery(String $sql, bool $desc = false){
        $order = $desc?'ORDER BY id DESC':'';
        $sql = str_replace('order_by', $order, $sql); 
        return $this->query($sql);
    }
    // Devolvemos la conexión
    public function getConnect() {
        return $this->conn;
     }

    // Devuelve todos los registros de una tabla
    public function getAll(string $return = '*', $desc = false){
        return $this->sendQuery("SELECT $return FROM {$this->table} order_by;", $desc);
     }
    // Devuelve datos de una peticion por id
    public function getById(int $id, string $return = '*'){
        $r =  $this->sendQuery("SELECT $return FROM {$this->table} WHERE id = $id LIMIT 1;"); 
        return $r ? $r[0] : null ;
     }
    // Devuelve datos de una peticion por algun campo del registro
    // Args puede ser String (1.1) o un array clave =>valor (1.0)
    public function getBy($args , string $return = '*', bool $unique = false,  bool $desc = false){
        $filters = '';

        if(is_string($args)) $filters = $args;
        else {
            foreach($args as $column => $value){
                $filters .= (string)$column ." = '".(string)$value ."' AND ";
            }
            $filters = trim($filters,"AND ");
         }
        $r = $this->sendQuery("SELECT $return FROM {$this->table} WHERE $filters  order_by;", $desc);
        return   $unique ? $r[0] : $r; 
     }
    // Devuelve datos de una peticion por una consulta sql
    public function getBySQL(string $sql, bool $desc = false){
        return$this->sendQuery("SELECT * FROM {$this->table} WHERE $sql order_by", $desc); 
     }
    // Devuelve un solo registro de una peticion por campo del registro
    public function getOneBy(Array $params, string $return = '*',bool $desc = false){
        $column = key($params);
        $value = $params[$column];
        return $this->sendQuery(
            "SELECT $return FROM {$this->table} WHERE $column = '$value' order_by LIMIT 1;", $desc
        )[0] ?? false;
     }
    // Devuelve el último registro
    public function getLast(){ 
        $r = $this->sendQuery("SELECT * FROM {$this->table} order_by LIMIT 1;", true); 
        return $r ? $r[0] : null ;
     }
    // Devuelve los registros con el valor entre los dos valores proporcionados de un campo
    public function getBetween ( string $column, $val1, $val2, string $args = null, bool $desc = false){
        return $this->sendQuery(
            "SELECT * FROM {$this->table} WHERE $column BETWEEN '$val1' AND '$val2' $args order_by;", $desc
        ) ;
     }
    // Cuenta los registros de la tabla
    public function count() {
        $this->query("SELECT * FROM {$this->table}");
        return $this->rowCount();
     }
    // Añadimos un registro devuelve el id del registro
    public function add(Array $params){
        unset($params['id']);
        $strCol = '';
        $strPre = '';
        foreach ($params as $col => $val) {
            $strCol .=  $col . ',' ;
            $strPre .= ':' . $col . ',' ; 
         }  
        $strCol = trim($strCol , ',') ;
        $strPre = trim($strPre , ',') ;

        if ($this->query("INSERT INTO {$this->table} ($strCol) VALUES ($strPre);", $params)){
            return $this->lastInsertId();
        }else return false;
     }
    // Guarda registro mediante su id
    public function saveById (Array $args = null) {
        $sql = $this->getSQLUpdate($args, "id=". $this->id());
        return $this->query($sql, $args);
     }
    /**
     * Guarda usando como filtro alguno/s de los campos de la base de datos 
     * comprobamos si existe y si existe editamos si no creamos uno nuevo
     */
    public function saveBy(Array $filter , Array $args){
        $columns = "";
        $values ="";
        $fName = key($filter);
        $fValue = $filter[$fName];
        $sql = '';

        if ($id = $this->getOneBy($filter)) 
            $sql = $this->getSQLUpdate($args, "$fName=$fValue");
        else {
            foreach ($args as $column => $value ) {
                $columns .=  $column . ',' ;
                $values .= '"' . $value . '",' ; 
            } 
            $columns = trim( $columns , ',' ) ;
            $values = trim( "'" . $values , "'," ) ;
            $sql .= "INSERT INTO {$this->table} ($columns) VALUES ($values);" ;
        }
prs($sql);
        return $this->query($sql, $args);
     }
    // Edita todos los campos de la tabla
    public function saveAll(array $args = null){
        return $this->query($this->getSQLUpdate($args) , $args);
     } 
    // Eliminamos mediante id
    public function deleteById(Int $id){
        return $this->query("DELETE FROM {$this->table} WHERE id = $id;");
     }
    // Eliminamos mediante un campo concreto
    public function deleteBy(Array $params){
        $prepared = $this->getPrepareParams($params);
        return $this->query("DELETE FROM {$this->table} WHERE $prepared;", $params);
     }
  /*   public function copyTableById($new_table, $id){
        $cols = '';
        $sql = "SELECT * FROM {$this->table}";    
        $query = $this->conn->query($sql) ;
        $info = $query->fetch_fields();
        foreach ($info as $val) {
            $cols .= $val->name . ',' ;
        }
        $cols = trim($cols,',');
         
        $this->sql .= "INSERT INTO $new_table ($cols) SELECT $cols FROM {$this->table} WHERE id = $id;";
        if(!$this->multi_query)
           return $this->query();
     }
    public function copyTableBy($new_table , $column , $value ){
        $cols = '';
        $sql = "SELECT * FROM {$this->table};";
        
        $query = $this->conn->query($sql) ;
        $info = $query->fetch_fields();
        foreach ($info as $val) {
            $cols .= $val->name . ',' ;
         }
        $cols = trim($cols,',');
         
        $this->sql .= "INSERT INTO $new_table ($cols) SELECT $cols FROM {$this->table} WHERE $column = $value;";
        if(!$this->multi_query)
           return $this->query();
        
     } */
    private function getSQLUpdate(Array $args, String $filter = ''){
        $params = $this->getPrepareParams($args);
        return "UPDATE {$this->table} SET $params WHERE $filter;";
    }
    private function getPrepareParams(Array $args){
        $sql = '';
        foreach( $args as $key => $value){
            $sql .= $key . '= :' . $key .',';
        }
        return trim($sql,',');
    }
    //getters setters
    function db(string $arg = null){
        if($arg) {
            $this->loadCredentials();
            $this->{__FUNCTION__} = $this->credentials['prefix'] . $arg;   
        }
        return $this->{__FUNCTION__}; 
    }
    //getters setters
    function table(string $arg = null){
        if($arg) $this->{__FUNCTION__} = $arg; 
        return $this->{__FUNCTION__}; 
    }
    // setter genérico para la inserción de datos en los atributos de la clase hija
    function loadData($data){
        if(!$data) return false;
        // Normalización de los datos para direfentes casos de uso
        if(is_object($data)) $data = (array)$data;
        if(isset($data[0])) $data = $data[0];
        // Agregacion de los datos a los atributos de clase
        foreach($data as $key => $val){        
            if (property_exists($this, $key)){
                $this->{$key} = $val??null; 
            }
        }
        return true;
    }
    function toArray(bool $nameSpace = false){ 
        $prefix = ($nameSpace)?$this->table . '_' : '';
        $arr = [];
        foreach((array)$this as $key => $val){
            // No pasamos a array los objetos 
            if(!strpos($key, '*')){
                $arr[$prefix . $key] = $val;
            }
        }
        return $arr;
    }
    // getter generico
    function id(int $arg = null){
        if ($arg) $this->id = $arg; 
        return $this->id;
    }
    // Descarga todos los dados y retorna objetos data
    function allData(Object $Obj, String $key = null){
        $Data = new Data; 
        $data = $this->getAll();
        $k = $key??'id'; 
        foreach($data as $value){
            $Data->addItem(new $Obj($value), $value[$k]);
        }
        return $Data;
    }
    // El método de eliminación genérico para las clases hijas
    // Método genérico de eliminación de registros
    function del($id){
        return $this->saveById(['estado'=>0]);
    }
    function isConnected(){
        return !is_null($this->conn);
    }
}