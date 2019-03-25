<?php namespace app\core;
/**
 * Clase de conexión con la base de datos con \PDO
 */
class Conn{
    
    protected 
        $pdo, 
        $sSQL,
        $sql,
        $credentials,
        $params, 
        $user,
        $db,
        $table,
        $error;
    
    function __construct($database, $table, $user){
        $this->db = $database;
        $this->table = $table;
        $this->user = $user; 
        $this->params = array();
        return $this->connect();
    }
    
    /**
     *	Genera la conexión a a la base de datos
     */
    protected function connect(){
        $this->credentials = include_once '../app/config/conn.php';
        $dsn = 'mysql:dbname=' . $this->credentials["prefix"] . $this->db . ';host=' . $this->credentials["host"] . ';port='. $this->credentials["port"];

        try {
            $this->pdo = new \PDO(
                    $dsn, 
                    $this->user, 
                    $this->credentials[$this->user], 
                    [
                        \PDO::ATTR_PERSISTENT => false, //sirve para usar conexiones persistentes https://es.stackoverflow.com/a/50097/29967
                        \PDO::ATTR_EMULATE_PREPARES => false, //Se usa para desactivar emulación de consultas preparadas
                        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, //correcto manejo de las excepciones https://es.stackoverflow.com/a/53280/29967
                        \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'" //establece el juego de caracteres a utf8 https://es.stackoverflow.com/a/59510/29967
                    ]
                );
            return $this->pdo; 
        }
        catch (\PDOException $e){      

            error_log($this->error = $e->getMessage(),0);
            echo $dsn .'//'. $this->user; 
            return $this->error; 
        }
    }
    function __destruct(){
		if(!empty($this->error)){
			echo $this->error;
		 }
		if($this->pdo) $this->pdo = null;
	 }

    private function init($sql, $params = null){
        try {
            $this->sSQL = $this->pdo->prepare($sql);
            $this->bindMore($params);

            if (!empty($this->params)) {
                foreach ($this->params as $param => $value) {
                    if(is_int($value[1])) {
                        $type = \PDO::PARAM_INT;
                    } else if(is_bool($value[1])) {
                        $type = \PDO::PARAM_BOOL;
                    } else if(is_null($value[1])) {
                        $type = \PDO::PARAM_NULL;
                    } else {
                        $type = \PDO::PARAM_STR;
                    }
                    $this->sSQL->bindValue($value[0], $value[1], $type);
                }
            }
            
            $this->sSQL->execute();
        }
        catch (PDOException $e) {
            error_log($this->error = $e->getMessage(). "\nSQL: ".$sql."\n",0);
        }
        
        $this->params = [];
    }
    /**
     *	@void
     *	
     *	Agrega más parámetros al arreglo de parámetros
     *	@param array $parray
     */
    public function bindMore($parray){
        if (empty($this->params) && is_array($parray)) {
            $columns = array_keys($parray);
            foreach ($columns as $i => &$column) {
                $this->bind($column, $parray[$column]);
            }
        }
    }
    /**
     *	@void 
     *
     *	Agrega un parámetro al arreglo de parámetros
     *	@param string $parametro  
     *	@param string $valor 
     */
    public function bind($param, $value){
        $this->params[sizeof($this->params)] = [":" . $param , $value];
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
    
    function query($sql, $params = null, $fetchmode = \PDO::FETCH_ASSOC){
        $this->sql = trim(str_replace("\r", " ", $sql)); 
        $this->init($this->sql, $params);
        $rawStatement = explode(" ", preg_replace("/\s+|\t+|\n+/", " ", $this->sql));
    
        # Determina el tipo de SQL 
        $statement = strtolower($rawStatement[0]);
        
        if ($statement === 'select' || $statement === 'show') {
            return $this->sSQL->fetchAll($fetchmode);
        } elseif ($statement === 'insert' || $statement === 'update' || $statement === 'delete') {
            return $this->sSQL->rowCount();
        } else {
            return NULL;
        }
    }
    /**
     * Métodos getter setter
     */
    function db (String $arg = null) {
        if ($arg != null) $this->__METHOD__ = $arg; 
        $method  = explode('::',__METHOD__)[1];
        return $this->$method; 
    }
    function table (String $arg = null) {
        if ($arg != null) $this->__METHOD__ = $arg; 
        $method  = explode('::',__METHOD__)[1];
        return $this->$method; 
    }
    
    /**
     *	Devuelve un arreglo que representa una columna específica del resultado 
     *
     *	@param  string $sql
     *	@param  array  $params
     *	@return array
     */
     
    public function column($sql, $params = null){
        $this->Init($sql, $params);
        $Columns = $this->sSQL->fetchAll(\PDO::FETCH_NUM);
        
        $column = null;
        
        foreach ($Columns as $cells) {
            $column[] = $cells[0];
        }
        
        return $column;
        
    }
    /**
     *	Devuelve un arreglo que representa una fila del resultado
     *
     *	@param  string $sql
     *	@param  array  $params
     *  @param  int    $fetchmode
     *	@return array
     */
    public function row($sql, $params = null, $fetchmode = \PDO::FETCH_ASSOC){
        $this->Init($sql, $params);
        $result = $this->sSQL->fetch($fetchmode);
        $this->sSQL->closeCursor(); // Libera la conexión para evitar algún conflicto con otra solicitud al servidor
        return $result;
    }
    /**
     *	Devuelve un valor simple campo o columna
     *
     *	@param  string $sql
     *	@param  array  $params
     *	@return string
     */
    public function single($sql, $params = null){
        $this->Init($sql, $params);
        $result = $this->sSQL->fetchColumn();
        $this->sSQL->closeCursor(); // Libera la conexión para evitar algún conflicto con otra solicitud al servidor
        return $result;
    }
    
    /**
     *  Devuelve el último id insertado.
     *  @return string
     */
    public function lastInsertId(){
        return (int)$this->pdo->lastInsertId();
    }
    
    /**
     * Inicia una transacción
     * @return boolean, true si la transacción fue exitosa, false si hubo algún fallo
     */
    public function beginTransaction(){
        return $this->pdo->beginTransaction();
    }
    
    /**
     *  Ejecuta una transacciónn
     *  @return boolean, true si la transacción fue exitosa, false si hubo algún fallo
     */
    public function executeTransaction(){
        return $this->pdo->commit();
    }
    
    /**
     *  Rollback de una transacción
     *  @return boolean, true si la transacción fue exitosa, false si hubo algún fallo
     */
    public function rollBack(){
        return $this->pdo->rollBack();
    }
    
    protected function rowCount(){
        return $this->sSQL->rowCount();
    }
}