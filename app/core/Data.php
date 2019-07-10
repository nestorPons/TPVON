<?php namespace app\core;
use app\core\Error; 
/**
 * Clase gestión de datos
 */
class Data {
    // Creamos los atributos en el constructor
    
    function __construct(Array $data = null){
        if($data){
            foreach($data as $key => $value){
                $this->addItem($value, $key);
/*                 if(is_array($value)){
                    foreach($value as $ke => $val){
                        if(is_array($val)) foreach($val as $k => $v) $this->addItem($v, $k);
                        else $this->addItem($val, $ke);
                    }
                } else $this->addItem($value, $key);   */
            }
        }
    }
    function dateFormat($atr, $format){
        $this->{$atr} =  date_format(date_create($this->{$atr}), $format);
        return $this->{$atr}; 
    }
    function addItem($value, $key = null){
       
        // Si vamos a pasar un array numerado creamos todos los métodos para extraer los atributos
        // creeamos el método para la extraccion de datos 
        
        if($key) return $this->{$key} = $value;  
        else {
            if(is_object($value)) return $this->{get_class($value)} = $value;
            else return $this->{$value} = $value;
        } 
        
    }
    function set($arg1, $arg2 = null){
        if($arg2) return $this->addItem($arg2, $arg1);
        else return $this->addItem($arg1);
    }
    function getAll(){
        return $this->toArray();
    }
    function getArray($attr, Array $filter = null){

        if(is_object(reset($this))){
            $arr = []; 
            foreach($this as $obj){
                if(!$filter) $arr[$obj->id] = $obj->{$attr}; 
                // Filtramos los datos de salida con un argumento dado en tipo de array clave => valor
                else if ($obj->{key($filter)} == $filter[key($filter)]) $arr[$obj->id] = $obj->{$attr}; 
            }
            return $arr;
        }else {
            if(array_key_exists($attr, (array)$this)){
                return $this->data[$attr];
            }else{
                return false;
            }
        };
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
        if(!(isset($this->{$arg}) && filter_var($this->{$arg}, FILTER_VALIDATE_EMAIL))) Error::die('E009', $this->{$arg}??null);
        return true;
    }
    function isString(string $arg, int $len){
        if(!(isset($this->{$arg}) && strlen($this->{$arg}) < $len)) Error::die('E009', $this->{$arg}??null);
        return true;
    }
    function toArray(){ 
        return (array)$this;
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

        if(property_exists($this, $arg)){
            unset($this->{$arg});
            return true; 
        } else return false;
    }
    // Usa un atributo y lo destruye 
    function use(string $arg){
        if(isset($this->{$arg})){
            $attr =  $this->{$arg};
            $this->delete($arg);
            return $attr;
        } else return false;
    }
    function normalizeAttr(string $attr){
        return $this->{$attr} = $this->normalize($this->{$attr});
    }

    function codifyAttr(string $attr){
        return $this->{$attr} = $this->codify($this->{$attr});
    }
    function filter(Object $obj){
        foreach($this as $key => $val){
            if(!property_exists($obj, $key)){
                unset($this->{$key});
            }
        }
        return $this;
    }

}   