<?php namespace app\libs;
/**
 * Clase gestión de datos
 */
class Data {
    // Creamos los atributos en el constructor
    function __construct(Array $data){
        foreach($data as $key => $value){
            if(is_array($value)){
                foreach($value as $k => $v){
                    $this->{$k} = $v;
                }
            } else {
                $this->{$key} = $value;  
            }
        }
    }
    function addOne($key, $value){
        return $this->{$key} = $value;
    }
    /**
     * Validador de los datos
     * $args es un array de los atributos que se quieren validar
     */
    function validate(array $args = [], bool $err = false){
        foreach($args as $value){
            if (!isset($this->{$value})) 
                if ($err) return \app\core\Error::array('E005'); 
                else return false;
        }
        return true; 
    }
    function toArray(){
        return (array)$this; 
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
        unset($this->{$arg});
    }
    // Usa un atributo y lo destruye 
    function use(string $arg){
        $attr =  $this->{$arg};
        $this->delete($arg);
        return $attr;
    }
    function normalizeAttr(string $attr){
        $this->{$attr} = $this->normalize($this->{$attr});
        return $this->{$attr};
    }

    function codifyAttr(string $attr){
        $this->{$attr} = $this->codify($this->{$attr});
        return $this->{$attr};
    }
}   