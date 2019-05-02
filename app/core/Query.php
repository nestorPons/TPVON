<?php namespace app\core;
/**
 * Se crean todos los métodos necesarios para las diferentes peticiones a la base de datos 
 */
class Query extends Conn{
    protected $conn;
        
    function __construct(string $db = null, string $table = null, $user = 'root') {   
        $this->table = $table;  
    }
    public function connecTo(String $db = null, String $user = 'root'){
        $this->db = $db??(app\libs\Data::normalize(NAME_COMPANY));
        $this->conn = parent::__construct($this->db, $user);
        return gettype($this->conn) === 'object';
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
        $query = $this->query($sql);
        $len = count($query);
        switch (true){
            case $len > 1 : return $query; // Devolvemos array multidimensional si se encontró más de una respuesta
            case $len == 1: return $query[0]; // Devolvemos un array con los datos solicitados si solo hay una respuesta 
            case $len == 0: return false; // Devolvemos false si no hay respuesta
        }
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
        return $this->sendQuery("SELECT $return FROM {$this->table} WHERE id = $id LIMIT 1;");
     }
    // Devuelve datos de una peticion por algun campo del registro
    public function getBy(array $args , string $return = '*', bool $desc = false){
        $filters = '';
        foreach($args as $column => $value){
            $filters .= (string)$column ." = '".(string)$value ."' AND ";
        }
        $filters = trim($filters,"AND ");
        return $this->sendQuery("SELECT $return FROM {$this->table} WHERE $filters  order_by;", $desc);   
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
        );
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

        }
            return $this->lastInsertId();
     }
    // Edita registro mediante su id
    public function saveById ( Int $id , Array $args = null ) {
        $sql = $this->getSQLUpdate($args, "id=$id");
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
}