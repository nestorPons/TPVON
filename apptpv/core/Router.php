<?php namespace app\core; 
use \app\core\{Security};

/***  
 * Clase controlador principal.
 * Routea la url a otros controladores secundarios si existieran y 
 * si no el mismo controlara el enrutamiento. 
 */
/**
 * ej: 
 * http://localhost/company/this->controller/this->action
 * 
 * Comprueba y enruta la peticuiOn por url
 */

class Router{
    
    private 
        $data, 
        $db,
        $controller, 
        $action;

    function __construct(array $REQUEST = [], string $db = null, array $SERVER = null){
        $this->data = new \app\core\Data;
        // Valores por defecto
        $this->db = $db;

        // Añadimos la mayúscula al controlador por ser una clase
        $this->controller =  ucfirst($REQUEST['controller'] ?? null); 
        $this->action =  strtolower($REQUEST['action'] ?? null); 

        if      (strtoupper($SERVER['REQUEST_METHOD']) === 'POST') $this->isPost($REQUEST);
        elseif  (strtoupper($SERVER['REQUEST_METHOD']) === 'GET')  $this->isGet(); 
            
    }
    /**
     * Devuelve una vista desde una peticion get
     */
    private function isGet(){
        
        if(empty($this->db)){
            // Si no encontramos la base datos vamos a la pagina principal
            if (empty($this->controller)) $this->controller = 'Main'; 

        } else {
            // Si esta vacio controlador nos envia al login
            if (empty($this->controller)) {
                $this->action = 'view'; 
                $this->controller = 'Login';
            }
        } 
        exit ($this->loadController());        
    }
    /**
     * Devuelve una petición ajax 
     */
    private function isPost($REQUEST){
        
        /**
         * Método post recibe siempre 3 parametros
         * controller => Controlador
         * action => Acción a realizar
         * data => Objeto con los datos a procesar (¡siempre tendrán que estar encapsulados en un objeto JS!)
         */
        try
        {
            // Pasamos los datos de json a objeto Data
            $this->data->addItems($REQUEST['data'] ?? []);
        
            $respond = $this->loadController(); 
           
            // Siempre se devuelve un objeto json con un success de respuesta
            if(!(is_array($respond) && isset($respond['success'])))
                $respond = ($respond == true || $respond == 1) 
                    ? ['success'=> true, 'data' => $respond] 
                    : ['success'=> false]; 
                        
            exit  (json_encode($respond, true));
        }
        catch(\Exception $e)
        {
            return (json_encode(['success'=>'false', 'mens'=>'error: ' . $e->menssage], true));
        }

    }
    // Comprobamos que exista la clase controladora
    private function isController(string $class){

        return (file_exists ( \FOLDERS\CONTROLLERS . $class . '.php'));
    }
    // Carga controlador
    // Si se le pasa argumentos cambia el controlador asignado
    private function loadController(){
        // Antes de cargar el controlador se comprueba si tiene permsiso para la petición
        if(Security::isRestrict($this->controller)){          
            if ($token = Security::getJWT()){
                $dataToken = Security::GetData($token);
                if(!$dataToken->access) return false;
                $this->data->addItems(['idadmin' => $dataToken->id]);
            } else {
                // Si no tiene permiso se devuelve al login
                header("Refresh:0; url={$_SERVER['PHP_SELF']}");
                return false;
            }
        };
        $nameClass = '\\app\\controllers\\' . $this->controller;
        $cont = $this->isController($this->controller)
            ? new $nameClass($this->action, $this->data)
            : new \app\core\Controller($this->action, $this->controller, $this->data);
        return $cont->result; 
    }
}