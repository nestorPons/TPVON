<?php 
namespace app\controllers;

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
    private $Model; 
    
    function __construct(String $action, String $controller = null, Object $Data = null){
        $this->action = strtolower($action);
        $this->controller =strtolower($controller ?? $this->getController());

        // Constructor alternativo básico
        if(method_exists($this, $this->action)){
            $this->result = $this->{$this->action}($Data);
        } else {
            die('Accion no permitida!!');
        }
    }
    protected function view($data = null){
        $file = \FOLDERS\VIEWS . $this->controller . '.phtml'; 
        if(!file_exists($file))
            $file = \FOLDERS\LOGIN . $this->controller . '.phtml';
        else if(!file_exists($file))
            $file = \FOLDERS\ADMIN . $this->controller . '.phtml';
        else if(!file_exists($file))
            $file = \FOLDERS\USER . $this->controller . '.phtml';
        else return false; 
        return $this->require($file, $data);
    }
    protected function update(Object $Data){
        $this->Model = $this->getModel(intval($Data->id));
        $this->Model->loadData($Data);
        $Data->delete('id'); 
        return $this->Model->save($Data);
    }
    protected function save(Object $Data){
        $data = $Data->toArray();
        $this->Model->saveById($data);
    }
    protected function require(String $route, $data = null){
        if(isset($_GET['db'])) $Company = new \app\models\Company($_GET['db']);
        if (is_array($data))
            $data = new \app\core\Data($data);
        return require_once $route;
    }
    /**
     * Método por defecto de agregación de registros a la base de datos
     */
    protected function new(Object $Data = null){
        $respond = false; 
        $nameModel = '\\app\\models\\' . ucfirst($this->controller);
        $fileModel = \FOLDERS\MODELS . ucfirst($this->controller) . '.php';
        if(file_exists($fileModel)){
            $model = new $nameModel($this->action);
            $respond = $model->new($Data);
        }
        return $respond;
    }
    private function getController(){
        $arr_controller= explode('\\',get_class($this));
        $controller = end($arr_controller);
        return strtolower($controller);
    }
    private function getModel($arg = null){
        $nameModel = '\\app\\models\\' . ucfirst($this->controller);
        $fileModel = \FOLDERS\MODELS . ucfirst($this->controller) . '.php';
        if(file_exists($fileModel)) return $this->Model = new $nameModel($arg);
        return false;
    }
}