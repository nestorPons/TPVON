<?php

namespace app\core;

/**
 * Clase gestión de datos
 */
class Data
{
    // Creamos los atributos en el constructor

    function __construct($data = null)
    {
        if ($data) {
            if (is_object($data)) $data = get_object_vars($data);
            foreach ($data as $key => $value) {
                $this->addItem($value, $key);
            }
        }
    }
    /**
     * Añade un valor y una clave a la clase contenedora de datos 
     * @param mix valores a guardar
     * @param mix llave 
     * @return mix valor de lo guardado 
     */
    function addItem($value, $key = null)
    {
        // Si vamos a pasar un array numerado creamos todos los métodos para extraer los atributos
        // creeamos el método para la extraccion de datos 

        if ($key) return $this->{$key} = $value;
        else {
            if (is_object($value)) return $this->{get_class($value)} = $value;
            else return $this->{$value} = trim($value);
        }
    }
    /**
     * Añade array de items
     * @param array items a guardar
     */
    function addItems(array $params) : int
    {
        $count = 0; 
        foreach ($params as $key => $value){
            $this->addItem($value, $key);
            $count++; 
        }
       
        return $count;
    }
    /**
     * Devuelve todos los datos guardados 
     * @return array de datos 
     */
    function getAll() : array
    {
        return $this->toArray();
    }

    /**
     * Validador de los datos
     * Si añadimos un tipo a los datos este también seré validado
     * @param array atributos que se quieren validar
     * @param bool lanza una excepción
     * @return bool o exception
     */
    function validate(array $args = [], bool $err = false) 
    {
        function err($err)
        {
            if ($err) return \app\core\Error::array('E005');
            else return false;
        }

        foreach ($args as $value) {
            if (is_array($value)) {
                if (!isset($this->{key($value)}) || gettype($this->{key($value)}) != $value[key($value)]) return err($err);
            } else {
                if (!isset($this->{$value})) return err($err);
            }
        }
        return true;
    }
    /**
     * Comprueba si el dato es un email 
     * @param string llave del dato a comprobar
     */
    function isEmail(string $key_email) : bool 
    {
        return (
            isset($this->{$key_email}) && filter_var($this->{$key_email}, 
            FILTER_VALIDATE_EMAIL)
        );
    }
    /**
     * Comprueba si es valor contenido es menor que len
     * @param string llave del dato a comprobar
     * @param int tamaño máximo del string
     */
    function isSmaller(string $key, int $len) : bool
    {
        return (isset($this->{$key}) && strlen($this->{$key}) < $len);
    }
    /**
     * Combierte objeto contenedor de datos en un array 
     */
    function toArray() : array
    {
        return (array) $this;
    }
    /**
     * Eliminamos datos del objeto
     * @param array de claves de los elementos a eliminar.
     * @return int devuelve el numero de eliminados 
    */ 
    function delete(array $args)
    {
        $count = 0; 
        foreach ($args as $arg) {
            if (property_exists($this, $arg)) {
                unset($this->{$arg});
                $count++;
            }
        }
        return $count;
    }
    /**
     * Quita las propiedades del contenedor que no existen en el obj argumento
     * @param Object el objeto a comparar
     */
    function filter(object $obj) : int
    {
        $count = 0; 
        foreach ($this as $key => $val) {
            if (!property_exists($obj, $key)) {
                unset($this->{$key});
                $count++; 
            }
        }
        return $count;
    }
    /**
     * Comprueba si el objeto o alguna propiedad esta vacia
     * @param string llave del atributo a comprobar
     */
    function isEmpty(String $key = null): bool
    {
        if ($key) {
            if (isset($this->{$key})) {
                return empty($this->{$key});
            } else return true;
        } else {
            return empty(get_object_vars($this));
        }
    }
}
