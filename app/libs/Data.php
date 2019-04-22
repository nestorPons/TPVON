<?php namespace app\libs;
/**
 * Clase gestión de datos
 */
class Data {
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
}