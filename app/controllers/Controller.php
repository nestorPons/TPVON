<?php 
namespace app\controllers;
use \app\models\Company;
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
        
        // Carpetas donde buscar las vistas
        $files[] = \FOLDERS\VIEWS . $this->controller . '.phtml'; 
        $files[] = \VIEWS\LOGIN . $this->controller . '.phtml';
        $files[] = \VIEWS\ADMIN . $this->controller . '.phtml';
        $files[] = \VIEWS\USERS . $this->controller . '.phtml';
        $files[] = \VIEWS\ADMIN\SECTIONS . $this->controller . '.phtml';
        foreach($files as $file){
            if(file_exists($file)) return $this->printView($file, $data);
        }        
    }
    /**
     * Método genérico para actualizar registros
     */
    protected function update(Object $Data){
        $this->Model = $this->getModel(intval($Data->id));
        $this->Model->loadData($Data);
        $Data->delete('id'); 
        return $this->Model->save($Data);
    }
    /**
     * Método genérico para guardar registros
     */
    protected function save(Object $Data){
        $this->getModel();
        $this->Model->saveById($Data->toArray(), $Data->id);
    }
    protected function printView(String $route, array $data = null){  
        if(isset($_GET['db'])) $Company = new Company($_GET['db']);
        if($data){
            foreach($data as $key => $val){
                ${$key} = $val;
            }
        }
        
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
    /**
     * Método por defecto de consulta de datos 
     */
    protected function get(Object $Data = null){
        $respond = false; 
        $nameModel = '\\app\\models\\' . ucfirst($this->controller);
        $fileModel = \FOLDERS\MODELS . ucfirst($this->controller) . '.php';
        if(file_exists($fileModel)){
            $model = new $nameModel($this->action);
            $respond = $model->get($Data->id);
        }
        return $respond;
    }
    /**
     * Método por defecto de eliminación de registros
     */
    protected function del(Object $Data = null){
        $respond = false; 
        $nameModel = '\\app\\models\\' . ucfirst($this->controller);
        $fileModel = \FOLDERS\MODELS . ucfirst($this->controller) . '.php';
        if(file_exists($fileModel)){
            $model = new $nameModel($Data);
            $respond = $model->del();
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