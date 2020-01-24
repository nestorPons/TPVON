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

    function __construct($params = []){
        $this->data = new \app\core\Data;
        // Valores por defecto
        $this->db = CONN['db'];

        $this->controller =  ucfirst($params['controller'] ?? null); 
        $this->action =  strtolower($params['action'] ?? null); 

        if      (strtoupper($_SERVER['REQUEST_METHOD']) === 'POST') $this->isPost($params);
        elseif  (strtoupper($_SERVER['REQUEST_METHOD']) === 'GET')  $this->isGet(); 
            
    }
    private function isGet(){
        if(empty($this->db)){
            // Si no encontramos la base datos vamos a la pagina principal
            if (empty($this->controller)) $this->controller = 'main';
            $this->controller = $this->controller; 

        } else {
            // Si esta vacio controlador nos envia al login
            if (empty($this->controller)) {
                $this->action = 'view'; 
                $this->controller = 'login';
            }
        } 
        exit ($this->loadController($this->controller));        
    }
    private function isPost($params){
        try
        {

            // Método post recibe siempre 3 parametros 
            // controller => Controlador
            // action => Acción a realizar
            // data => Objeto con los datos a procesar (¡siempre tendrán que estar encapsulados en un objeto JS!)

            // Pasamos los datos de json a objeto Data
            $this->data->addItems($params['data'] ?? null);
        
            $respond = $this->loadController(); 
    
            // Siempre se devuelve un objeto json con un success de respuesta
            if(!(is_array($respond) && isset($respond['success'])))
                $respond = ($respond == true || $respond == 1) 
                    ? ['success'=> true, 'data' => $respond] 
                    : ['success'=> false]; 
            /* ((is_array($respond) && isset($respond['success']) && $respond['success'] == 0)) ? $respond :
            ['success' => 1, 'data' => $respond]); */

            // SALIDA 
        
            exit (json_encode($respond, true));
        }
        catch(\Exceptiion $e)
        {
             exit (json_encode(['success'=>'false', 'mens'=>'error: ' . $e->menssage()], true));
        }

    }
    // Comprobamos que exista la clase controladora
    private function isController(string $class){

        return (file_exists ( \FOLDERS\CONTROLLERS . $class . '.php'));
    }
    // Carga controlador
    // Si se le pasa argumentos cambia el controlador asignado
    private function loadController(string $controller = null){

        // Buscamos controlador
        if(!empty($controller)) $this->controller = ucwords($controller); 
        // Antes de cargar el controlador se comprueba si tiene permsiso para la petición
        if(Security::isRestrict($this->controller)){          
            if ($token = Security::getJWT()){
                $dataToken = Security::GetData($token);
                if(!$dataToken->access) return false;
                $this->data->addItem($dataToken->id, 'idadmin');
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
    /**
     * Comprueba que se pueda realizar la acción
     */
    private function isView(){
        if ($this->action == 'view'){
            if(file_exists(\FOLDERS\VIEWS . $this->controller . '.phtml')){
                $this->loadController($this->controller);
            } else {
                $this->loadController($this->default['gate']);
            }
            return  true; 
        } else {
            return false;
        }

    }
}