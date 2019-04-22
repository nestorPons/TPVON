<?php namespace app\controllers;
/**
 * Clase para ser expansión de otras subclases o clases dedicadas 
 * Tiene lo mínimo para la creación de una subclase: 
 *  Método para requerir una vista
 *  Método para requerir datos a los modelos (abstracto)
 *  Método para añadir/editar/borrar datos a los modelos (abstracto)
 */
class Controller{
    protected $conn, $controller, $action, $data;
    public $result = null;
    
    function __construct(String $action, String $controller = null, Object $dataJSON = null){
        $this->action = strtolower($action);
        $this->controller =strtolower($controller ?? $this->getController());  
        $this->data = $dataJSON; 

        // Constructor alternativo básico
        switch($this->action){
            case 'new':  $this->result = $this->new($this->data); break;
            case 'del':  $this->result = $this->del($this->data); break;
            case 'save': $this->result = $this->save($this->data); break;
            case 'get':  $this->result = $this->setModel(); break;
            case 'set':  $this->result = $this->getModel(); break;
            case 'view': $this->result = $this->getView(); break;
            default: 
                die('Accion no permitida!!');
        }
    }
    protected function setConnection($db){
        return $this->Query = new \app\core\Query($db, $this->controller);
    }
    protected function getView( Array $data = []){
        return $this->require(\FOLDERS\VIEWS . $this->controller . '.phtml', $data);
    }
    protected function require(String $route, $arrData = null){
        $data = new \app\libs\Data($arrData);
        return require_once $route;
    }
    protected function new(Object $dataJSON = null){
        $return = false; 
        $nameModel = '\\app\\models\\' . ucfirst($this->controller);
        $fileModel = \FOLDERS\MODELS . ucfirst($this->controller) . '.php';
        if(file_exists($fileModel)){
            $model = new $nameModel($this->action);
            $respond = $model->new($dataJSON);
        }
        return $respond;
    }
    protected function setModel(){

    }
    private function getController(){
        $arr_controller= explode('\\',get_class($this));
        $controller = end($arr_controller);
        return strtolower($controller);
    }
}