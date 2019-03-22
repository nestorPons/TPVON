<?php namespace app\core;
/**
 * Se crean todos los métodos necesarios para las diferentes peticiones a la base de datos 
 */
class Query extends Conn{
    public
        $type = MYSQLI_ASSOC,
        $multi_query = false, //Si esta activada se acumulan los querys en la variable sql 
        $table;
    private
	    $sql = "",
        $log = false,
        $return = false,
        $logs = ['data','usuarios'],
	    $users,
        $conf,
        $conn;

    public function __construct($db, $table, $user = 'root') {     
        $this->conn = parent::__construct( $db, $table,  $user );
        die();
     }
    public function getConnect () {
        return $this->conn ;
     }
    protected function query(string $sql = null){
        $sql = $sql??$this->sql;
        $this->sql = null;
        $result = $this->conn->query($sql);
        return $result;
     }
    public function getAll ( string $return = '*'  ) {
        $this->sql = "SELECT $return FROM {$this->table}"; 
        $query = $this->query($this->sql);
        return $query->fetch_all($this->type);
     }
    public function getById ( int $id , string $return = '*' ) {
        $this->sql = "SELECT $return FROM {$this->table} WHERE id = $id LIMIT 1;" ;
        $query = $this->query($this->sql);
        return ($query)
            ?$query->fetch_assoc()??false
            :false;
     }
    // retorna un array
    public function getBy ( array $args , string $return = '*' ) {
        //varios valores ponerlos en un array
        $filters = '';
        $i = 0;
        foreach($args as $column => $value){
            $filters .= ($i!=0) ? ' AND ' : '' ;
            $i++;
            $value = $this->scape($value) ;
            $filters .= (string)$column ." = '".(string)$value ."'";
        }
        $this->sql = "SELECT $return FROM {$this->table} WHERE $filters ;";
        $query = $this->query($this->sql);
        $result = $query->fetch_all($this->type); 
        if ($return != '*' && count(explode(',', $return)) <= 1)
            $result = $this->converToArray($result);
        /*/
        }else if (count($result) == 1) {
            $result = $result[0];
        }
        */
        return $result ; 
     }
    public function getBySQL ( string $sql ) {
        $this->sql = "SELECT * FROM {$this->table} WHERE " . $sql;
        $query = $this->query($this->sql);
        $result = $query->fetch_all($this->type); 
             
        return $result; 
     }
    public function getId () {
        return $this->conn->insert_id();
     }
    public function getOneBy ( $column, $value, string $return = '*', $typeNum = false, $desc = false) {
        $order = $desc?'ORDER BY "id" DESC':'';
        $value = $this->scape($value) ;
        $sql = "SELECT $return FROM {$this->table} WHERE $column = '$value' $order LIMIT 1;" ;
        $query = $this->conn->query($sql) ;
       if ($return != '*'){
            $result = $query->fetch_row();
            if(!empty($result) && count($result)<=1)$result = $result[0];
        }else{
            $result = $typeNum? $query->fetch_row(): $query->fetch_assoc() ; 
        }          
        
        return $result;
     }
    public function getBetween ( string $column, $val1, $val2, string $args = null ){
        $sql = "SELECT * FROM {$this->table} WHERE $column BETWEEN '$val1' AND '$val2' $args;" ;
        return $this->conn->all($sql) ;
     }
    public function count() {
        $sql = "SELECT * FROM {$this->table};";
        $result = $this->conn->num($sql);      
        return (int)$result;
     }
    public function multi_query(){
        $r = $this->conn->multi_query($this->sql);
        $this->sql = '';
        $this->multi_query = false;
        return $r;
     }
    // Guarda un registro nuevo (id = -1) o edita registro (id > -1)
    // $args == [$column => $value]
    public function saveById ( int $id , array $args = null ) {
        
        $columns = null ; 
        $values = null ;
        if ( $id == -1) {
            if (!is_null($args)){
                unset($args['id']);
                foreach ($args as $column => $value ) {
                    $columns .=  $column . ',' ;
                    $values .= '"' . $value . '",' ; 
                 }    
             }
            $columns = trim( $columns , ',' ) ;
            $values = trim( "'" . $values , "'," ) ;
            $this->sql .= "INSERT INTO {$this->table} ( $columns ) VALUES ( $values );" ;
        } else {
            $this->sql .= $this->updateSql($args , $id);
        }
      if(!$this->multi_query){
            $this->return = $this->query();
            return $this->return;
      }
     }
    public function saveBy(array $filter , array $args){
        $columns = "";
        $values ="";
        $fName = key($filter);
        $fValue = $filter[$fName];
        foreach ($args as $column => $value ) {
            $columns .=  $column . ',' ;
            $values .= '"' . $value . '",' ; 
         } 
        $columns = trim( $columns , ',' ) ;
        $values = trim( "'" . $values , "'," ) ;
        if ($id = $this->getOneBy($fName , $fValue, 'id')) 
            $this->sql .= $this->updateSql($args , $id);
        else 
            $this->sql .= "INSERT INTO {$this->table} ( $columns ) VALUES ( $values );" ;
      if(!$this->multi_query){
            $this->return = $this->query();
            return $this->return;
       }
     }
    public function saveAll( array $args = null ){
        $this->sql .= $this->updateSql($args);
      if(!$this->multi_query){
            $this->return = $this->query();
            return $this->return;
       }
     } 
    // Funcion que elimina caracteres no deseados 
    // Str valor a escapar y $discard si queremos omitir algun valor
    private function scape (string $str, string $discard = null) {
	
		if(!$this->error){
			$replace = ['=',"'",'"','/','#','*',"<",">",":","{","}","?","|","&"];
            
			if (!$discard) {
                foreach ($replace as $k => $v){
                    if ($v == $discard) {
                        unset($replace[$k]);
                        break; 
                    }
                }
            }
        	$str = str_replace($replace, '' , $str);
        	$str = trim($str);        
			return $this->conn->real_escape_string($str) ;
		}else{
			return false ;
		}
	 }
    public function deleteById ( $id ) {
        $this->sql .= "DELETE FROM {$this->table} WHERE id =  $id ; ";
        if(!$this->multi_query){
            $this->return = $this->query();
            return $this->return;
         }
     }
    public function deleteBy ( $column , $value ) {
        $this->sql .= "DELETE FROM {$this->table} WHERE $column = $value;";
           
      if(!$this->multi_query){
            $this->return = $this->query();
            return $this->return;
       }
     }
    public function copyTableById($new_table , $id  ){
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
        
     }
    private function converToArray($multi){
        $array = array();
        foreach ($multi as $key => $value){
            $value = array_values($value);
            $array[] = $value[0] ;
        }
        return $array;
     }
}