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
    function valid(array $args = [], bool $err = false){
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
    static function normalize($str){
        $originals = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞ
        ßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
        $modify = 'aaaaaaaceeeeiiiidnoooooouuuuy
        bsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
        $str = str_replace(' ', '_', trim($str));
        $str = utf8_decode($str);
        $str = strtr($str, utf8_decode($originals), $modify);
        $str = strtolower($str);
        return utf8_encode($str);
    }
    static function normalizeShow($name){
        $name = str_replace('_', ' ', trim($name));
        $name = ucwords($name);
        return $name; 
     }

}