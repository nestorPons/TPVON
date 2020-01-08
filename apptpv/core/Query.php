<?php namespace app\core;

/**
 * Se crean todos los métodos necesarios para las diferentes peticiones a la base de datos 
 */
class Query
{
    protected 
        $conn, 
        $pdo, 
        $sqlPrepare,
        $sql,
        $credentials,
        $params = [], 
        $user,
        $db,
        $table,
        $error;

    function __construct(string $table = null, string $db = null, string $user = null)
    {

        // Parametros predeterminados para la conexión
        $credentials = parse_ini_file(\FILE\CONN);
        if ($table) $this->table = $table;

        $this->db = $db ?? CONN['db'];
        $this->user = $user ?? $credentials['user'];
        $dsn = 'mysql:dbname=' . $this->db . ';host=' . $credentials["host"] . ';port=' . $credentials["port"];

        try {
            $this->conn = $this->connect($dsn, $credentials[$this->user]);
            return gettype($this->conn) === 'object';
        } catch (\Exception $e) {
            return false;
        }
    }
    private function init($sql, $params = null){

        // pr('SQL', $sql, $params);
        // pr($this->pdo);  
         try {
            $this->sqlPrepare = $this->pdo->prepare($sql);
            $this->bindMore($params);
            
            if (!empty($this->params)) {
                foreach ($this->params as $param => $value) {
                    if(is_numeric($value[1])) {
                        $type = \PDO::PARAM_INT;
                    } else if(is_bool($value[1])) {
                        $type = \PDO::PARAM_BOOL;
                    } else if(is_null($value[1]) || $value[1] == '') {
                        $value[1] = null;
                        $type = \PDO::PARAM_NULL;
                    } else {
                        $type = \PDO::PARAM_STR;
                    }

                    $this->sqlPrepare->bindValue($value[0], $value[1], $type);
                    }
            }
           return $this->sqlPrepare->execute();
        }
        catch (\PDOException $e) {
            die ($e->getMessage());
        }
        
        $this->params = [];
    }
    /**
     *	@void
     *	
     *	Agrega más parámetros al arreglo de parámetros
     *	@param array $parray
     */
    private function bindMore($parray){
        if (is_array($parray)) {
            $columns = array_keys($parray);
            foreach ($columns as $i => &$column) {
               $this->params[sizeof($this->params)] = [":" . $column, $parray[$column]];
            }
        }
    }
    /**
     *  Si la consulta SQL contiene un SELECT o SHOW, devolverá un arreglo conteniendo todas las filas del resultado
     *	Si la consulta SQL es un DELETE, INSERT o UPDATE, retornará el número de filas afectadas
     *
     *  @param  string $sql
     *	@param  array  $params
     *	@param  int    $fetchmode
     *	@return mixed
     */
    
    public function query($sql, $params = null, $fetchmode = \PDO::FETCH_ASSOC){

        $this->sql = trim(str_replace("\r", " ", $sql)); 

        $respond = $this->init($this->sql, $params);
        $rawStatement = explode(" ", preg_replace("/\s+|\t+|\n+/", " ", $this->sql));
        # Determina el tipo de SQL 
        $statement = strtolower($rawStatement[0]); 
        
        if ($statement === 'select' || $statement === 'show') {
            return $this->sqlPrepare->fetchAll($fetchmode);
        } elseif ($statement === 'insert' || $statement === 'update' || $statement === 'delete') {
            return $this->sqlPrepare->rowCount();
        } else {
            return $respond;
        }
    }
    /**
     *	Genera la conexión a a la base de datos
     */
    protected function connect($dsn, $pass)
    {
        try {
            $this->pdo = new \PDO(
                    $dsn, 
                    $this->user, 
                    $pass,  
                    [
                        \PDO::ATTR_PERSISTENT => false, //sirve para usar conexiones persistentes https://es.stackoverflow.com/a/50097/29967
                        \PDO::ATTR_EMULATE_PREPARES => false, //Se usa para desactivar emulación de consultas preparadas
                        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, //correcto manejo de las excepciones https://es.stackoverflow.com/a/53280/29967
                        \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'" //establece el juego de caracteres a utf8mb4 https://es.stackoverflow.com/a/59510/29967
                    ]
                );
           return $this->pdo; 
        }
        catch (\PDOException $e){ 
            throw new \PDOException($e->getMessage());
        }
    }
    function __destruct()
    {
        $this->conn = null;
    }
    /**
     * Prepara el string sql para enviar a crear la consulta 
     * añadimos si queremos que sean ordenados de forma inversa
     */
    private function sendQuery(String $sql, bool $desc = false)
    {
        $order = $desc ? 'ORDER BY id DESC' : '';
        $sql = str_replace('order_by', $order, $sql);
        return $this->query($sql);
    }
    // Devuelve todos los registros de una tabla
    public function getAll(string $return = '*', String $order = 'id')
    {
        return $this->sendQuery("SELECT $return FROM {$this->table} ORDER BY $order ASC;");
    }
    // Devuelve datos de una peticion por id
    // param puede ser array con una clave id o un integer que hace referencia a un id
    public function getById($param, string $return = '*')
    {
        $id = $param['id'] ?? $param; 

        $r = $this->sendQuery("SELECT $return FROM {$this->table} WHERE id = $id LIMIT 1;");
        return $r ? $r[0] : null;
    }
    // Devuelve datos de una peticion por algun campo del registro
    // Args puede ser String (1.1) o un array clave =>valor (1.0)
    public function getBy($args, string $return = '*', bool $unique = false,  bool $desc = false)
    {
        $filters = '';

        if (is_string($args)) $filters = $args;
        else {
            foreach ($args as $column => $value) {
                $filters .= (string) $column . " = '" . (string) $value . "' AND ";
            }
            $filters = trim($filters, "AND ");
        }
        $r = $this->sendQuery("SELECT $return FROM {$this->table} WHERE $filters  order_by;", $desc);
        return   $unique ? $r[0] : $r;
    }
    // Devuelve datos de una peticion por una consulta sql
    public function getBySQL(string $sql, bool $desc = false)
    {
        return $this->sendQuery("SELECT * FROM {$this->table} WHERE $sql order_by", $desc);
    }
    // Devuelve un solo registro de una peticion por campo del registro
    public function getOneBy(array $params, string $return = '*', bool $desc = false)
    {

        $column = key($params);
        $value = $params[$column];
        return $this->sendQuery(
            "SELECT $return FROM {$this->table} WHERE $column = '$value' order_by LIMIT 1;",
            $desc
        )[0] ?? false;
    }
    // Devuelve el último registro
    public function getLast()
    {
        $r = $this->sendQuery("SELECT * FROM {$this->table} order_by LIMIT 1;", true);
        return $r ? $r[0] : null;
    }
    // Devuelve los registros con el valor entre los dos valores proporcionados de un campo
    public function getBetween(string $column, $val1, $val2, string $args = null, bool $desc = false)
    {
        return $this->sendQuery(
            "SELECT * FROM {$this->table} WHERE $column BETWEEN '$val1' AND '$val2' $args order_by;",
            $desc
        );
    }
    // Carga el siguiente registro
    function getNext(int $arg = null)
    {
        $id = $arg ?? $this->id;
        return $this->getBySQL("id>$id DESC LIMIT 1", true);
    }
    // Carga el siguiente registro
    function getPrev(int $arg = null, $filter = '')
    {
        $id = $arg ?? $this->id;
        return $this->getBySQL("id<$id $filter ORDER BY id DESC LIMIT 1");
    }
    // Cuenta los registros de la tabla
    public function count()
    {
        $this->query("SELECT * FROM {$this->table}");
        return $this->sqlPrepare->rowCount();
    }
    // Añadimos un registro devuelve el id del registro
    public function add(array $params, $del_id = true)
    {
        if($del_id) unset($params['id']);
        $strCol = '';
        $strPre = '';
        foreach ($params as $col => $val) {
            $strCol .=  $col . ',';
            $strPre .= ':' . $col . ',';
        }
        $strCol = trim($strCol, ',');
        $strPre = trim($strPre, ',');

        if ($this->query("INSERT INTO {$this->table} ($strCol) VALUES ($strPre);", $params)) {
            return (int)$this->pdo->lastInsertId();
        } else return false;
    }
    // Funcion para mostrar solo los atributos publicos desde dentro de la clase
    public function getVars(){
        return array_diff_key(get_object_vars($this), get_class_vars(get_parent_class($this)));
    }
    // Guarda registro mediante su id
    public function saveById(array $args = null)
    {
        $id = array_key_exists('id', $args) ? $args['id'] : $this->id();
        
        if(!$args) $args = $this->getVars();
        $sql = $this->getSQLUpdate($args, "id=" . $id);

        return $this->query($sql, $args);
    }
    /**
     * Guarda usando como filtro alguno/s de los campos de la base de datos 
     * comprobamos si existe y si existe editamos si no creamos uno nuevo
     */
    public function saveBy(array $filter, array $args)
    {
        $columns = "";
        $values = "";
        $fName = key($filter);
        $fValue = $filter[$fName];
        $sql = '';

        if ($id = $this->getOneBy($filter))
            $sql = $this->getSQLUpdate($args, "$fName=$fValue");
        else {
            foreach ($args as $column => $value) {
                $columns .=  $column . ',';
                $values .= '"' . $value . '",';
            }
            $columns = trim($columns, ',');
            $values = trim("'" . $values, "',");
            $sql .= "INSERT INTO {$this->table} ($columns) VALUES ($values);";
        }
        return $this->query($sql, $args);
    }
    // Edita todos los campos de la tabla
    public function saveAll(array $args = null)
    {
        return $this->query($this->getSQLUpdate($args), $args);
    }
    // Eliminamos mediante id
    public function deleteById(array $data)
    {
        $id = $data['id'];

        return $this->query("DELETE FROM {$this->table} WHERE id = $id;");
    }
    // Eliminamos mediante un campo concreto
    public function deleteBy(array $params)
    {
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
    private function getSQLUpdate(array $args, String $filter = '')
    {
        $params = $this->getPrepareParams($args);
        return "UPDATE {$this->table} SET $params WHERE $filter;";
    }
    private function getPrepareParams(array $args)
    {
        $sql = '';
        foreach ($args as $key => $value) {
            $sql .= $key . '= :' . $key . ',';
        }
        return trim($sql, ',');
    }
    //getters setters
    function db(string $arg = null)
    {
        if ($arg) {
            $this->loadCredentials();
            $this->{__FUNCTION__} = $this->credentials['prefix'] . $arg;
        }
        return $this->{__FUNCTION__};
    }
    //getters setters
    function table(string $arg = null)
    {
        if ($arg) $this->{__FUNCTION__} = $arg;
        return $this->{__FUNCTION__};
    }
    // setter genérico para la inserción de datos en los atributos de la clase hija
    function loadData($data)
    {
        if ($data) {
            // Normalización de los datos para direfentes casos de uso
            if (is_object($data)) $data = (array) $data;
            if (isset($data[0])) $data = $data[0];
            // Agregacion de los datos a los atributos de clase
            $public_props = $this->getVars();
            foreach ($data as $key => $val) {
                if (array_key_exists($key, $public_props)) {
                    $this->{$key} = $val ?? null;
                }
            }
            return true;
        } return false;
    }
    function toArray(bool $nameSpace = false)
    {
        $prefix = ($nameSpace) ? $this->table . '_' : '';
        $arr = [];
        foreach ((array) $this as $key => $val) {
            // No pasamos a array los objetos 
            if (!strpos($key, '*')) {
                $arr[$prefix . $key] = $val;
            }
        }
        return $arr;
    }
    // getter generico
    function id(int $arg = null)
    {
        if ($arg) $this->id = $arg;
        return $this->id;
    }
    // El método de eliminación genérico para las clases hijas
    // Método genérico de eliminación de registros
    function del()
    {
        return $this->saveById(['estado' => 0]);
    }
    function isConnected()
    {
        return !is_null($this->conn);
    }
}
