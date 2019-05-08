<?php namespace app\core; 

/***  
 * Clase controlador seguridad.
 * Registra y da permisos para aceder a diferentes partes de la aplicación
 */
class Security{
    private 
    function __construct(){
        $this->access = parse_ini_file(\FOLDERS\CONFIG . 'access.ini');
    }
}