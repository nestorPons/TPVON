<?php namespace app\core;
/**
 * Clase gestión de datos
 */
class Data {
    // Creamos los atributos en el constructor
    public $data = [];
    
    function __construct(Array $data = null){
        if($data){
            $this->data = $data; 
            foreach($data as $key => $value){
                if(is_array($value)){
                    foreach($value as $ke => $val){
                        if(is_array($val)) foreach($val as $k => $v) $this->addItem($v, $k);
                        else $this->addItem($val, $ke);
                    }
                } else $this->addItem($value, $key);  
            }
        }
    }
    function addItem($value, $key = null){
       
        // Si vamos a pasar un array numerado creamos todos los métodos para extraer los atributos
        // creeamos el método para la extraccion de datos 

        if($key){
            $this->${$key} = $value; 
            return $this->data[$key] = $value;
        }
        else return $this->data[] = $value;
    }
    function set($arg1, $arg2 = null){
        if($arg2) return $this->addItem($arg2, $arg1);
        else return $this->addItem($arg1);
    }
    function get($attr){
        if(is_object(reset($this->data))){
            $arr = []; 
            foreach($this->data as $obj){
                $arr[$obj->id] = $obj->{$attr}; 
            }
            return $arr;
        }else {
            if(array_key_exists($attr, $this->data)){
                return $this->data[$attr];
            }else{
                return false;
            }
        };
    }
    function getAll(){
        return $this->toArray();
    }
    /**
     * Validador de los datos
     * $args es un array de los atributos que se quieren validar
     * Si añadimos un tipo a los datos este también seré validado
     */
    function validate(array $args = [], bool $err = false){
        function err($err){
            if ($err) return \app\core\Error::array('E005'); 
            else return false;   
        }

        foreach($args as $value){
            if(is_array($value)){
                if (!isset($this->{key($value)}) || gettype($this->{key($value)}) != $value[key($value)]) return err($err);
            } else {
                if (!isset($this->{$value})) return err($err);
            }
        }
        return true; 
    }
    function isEmail(string $arg){
        if(!(isset($this->data[$arg]) && filter_var($this->data[$arg], FILTER_VALIDATE_EMAIL))) \app\core\Error::die('E009', $this->data[$arg]??null);
        return true;
    }
    function isString(string $arg, int $len){
        if(!(isset($this->data[$arg]) && strlen($this->data[$arg]) < $len)) \app\core\Error::die('E009', $this->data[$arg]??null);
        return true;
    }
 
    function toArray(){ 
        return $this->data;
    }
    
    function toJSON(){
        return json_encode($this->toArray());
    }
    static function codify(string $arg){
        $originals = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
        $modify = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
        $arg = str_replace(' ', '_', trim($arg));
        $arg = utf8_decode($arg);
        $arg = strtr($arg, utf8_decode($originals), $modify);
        $arg = strtolower($arg);
        return utf8_encode($arg);
    }
    static function normalize(string $arg){
        $arg = str_replace('_', ' ', trim($arg));
        $arg = ucwords($arg);
        return $arg; 
     }
    function delete(string $arg){ 
        unset($this->data[$arg]);
    }
    // Usa un atributo y lo destruye 
    function use(string $arg){
        $attr =  $this->data[$arg];
        $this->delete($arg);
        return $attr;
    }
    function normalizeAttr(string $attr){
        return $this->data[$attr] = $this->normalize($this->data[$attr]);
    }

    function codifyAttr(string $attr){
        return $this->data[$attr] = $this->codify($this->data[$attr]);
    }
}   