<?php namespace app\controllers;
/**
 * Clase para ser expansión de otras subclases o clases dedicadas 
 * Tiene lo mínimo para la creación de una subclase: 
 *  Método para requerir una vista
 *  Método para requerir datos a los modelos (abstracto)
 *  Método para añadir/editar/borrar datos a los modelos (abstracto)
 */
class Controller{
    protected 
        $conn,
        $controller,
        $action;
    
    function __construct(String $action, String $controller = null, String $data = null){
        $this->action = $action;
        $this->controller = $controller ?? $this->getController();  
        
        // Constructor alternativo básico
        switch($this->action){
            case 'new': 
                $this->new($data);
                break;
            case 'del': 
                $this->del();
                break;
            case 'save': 
                $this->save();
                break;
            case 'get':
                $this->setModel();
                break;
            case 'set':
                $this->getModel();
                break;
            case 'view':
                $this->getView();
                break; 
            default: 
                die('Accion no permitida!!');
        }
    }
    protected function setConnection($db){
        return $this->Query = new \app\core\Query($db, $this->controller);
    }
    protected function getView( Array $data = []){
        return $this->require(\FOLDERS\VIEWS . strtolower($this->controller) . '.phtml', $data); 
    }
    protected function require(String $route, Array $data = []){
        return require_once $route;  
    }
    protected function new(String $dataJSON = ''){
        $data = json_decode($dataJSON, true);
 
        $nameModel = '\\app\\models\\' . ucfirst($this->controller);
        $fileModel = \FOLDERS\MODELS . ucfirst($this->controller) . '.php';
        if(file_exists($fileModel)){
            $model = new $nameModel($this->action);
            return $model->new($data); 
        }

        return false;
    }
    protected function setModel(){

    }
    private function getController(){
        $arr_controller= explode('\\',get_class($this));
        $controller = end($arr_controller);
        return strtolower($controller);
    }
}