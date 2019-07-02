<?php namespace app\core;
/**
 * Clase de conexión con la base de datos con \PDO
 */
class Conn{
    
    protected 
        $pdo, 
        $sqlPrepare,
        $sql,
        $credentials,
        $params = [], 
        $user,
        $db,
        $table,
        $error;
        
    /**
     *	Genera la conexión a a la base de datos
     */
    protected function connect($dsn, $pass){
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
    function __destruct(){
		if(!empty($this->error)){
			echo $this->error;
         }
		if($this->pdo) $this->pdo = null;
	 }

    private function init($sql, $params = null){

/* echo $sql;pr($params);
pr($this->pdo);
 */
         try {
            $this->sqlPrepare = $this->pdo->prepare($sql);
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

                    $this->sqlPrepare->bindValue(
                        $value[0], 
                        empty($value[1]) ? NULL : $value[1], 
                        $type);
                }
            }
           return $this->sqlPrepare->execute();
        }
        catch (\PDOException $e) {
            return ($e->getMessage());
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
     *	Devuelve un arreglo que representa una columna específica del resultado 
     *
     *	@param  string $sql
     *	@param  array  $params
     *	@return array
     */
     
    public function column($sql, $params = null){
        $this->Init($sql, $params);
        $Columns = $this->sqlPrepare->fetchAll(\PDO::FETCH_NUM);
        
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
        $result = $this->sqlPrepare->fetch($fetchmode);
        $this->sqlPrepare->closeCursor(); // Libera la conexión para evitar algún conflicto con otra solicitud al servidor
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
        $result = $this->sqlPrepare->fetchColumn();
        $this->sqlPrepare->closeCursor(); // Libera la conexión para evitar algún conflicto con otra solicitud al servidor
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
        return $this->sqlPrepare->rowCount();
    }
}