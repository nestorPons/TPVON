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
    private $db = CONN['db'];
    
    function __construct(String $action, String $controller = null, $Data = null){
        $this->action = strtolower($action);
        $this->controller =strtolower($controller ?? $this->getController());

        // Constructor alternativo básico
        if(method_exists($this, $this->action)) $this->result = $this->{$this->action}($Data);
        else die('Accion no permitida!!');

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
        try{
            $this->Model = $this->getModel(intval($Data->id));
            $this->Model->loadData($Data);
            $Data->delete('id'); 
            return $this->Model->save($Data);
        } catch (\Exception $e){
            return false;
        }
    }
    /**
     * Método genérico para guardar registros comprueba que es nuevo o edicion y envia los datos al metodo apropiado
     */
    protected function save(Object $Data){
        if($Data->id == -1 ) return $this->new($Data);
        else return $this->update($Data);
    }
    protected function printView(String $route, array $data = null){  
        $Company = new Company($this->db);
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
     * Método por defecto de consulta de datos entre parametros
     */
    protected function getBetween(Object $Data){
        if($model = $this->getModel()) return $model->between($Data);
        else return false;
    }
    /**
     * Método por defecto de eliminación de registros
     */
    protected function del($arg){
        $respond = false; 
        $nameModel = '\\app\\models\\' . ucfirst($this->controller);
        $fileModel = \FOLDERS\MODELS . ucfirst($this->controller) . '.php';
        if(file_exists($fileModel)){
            $model = new $nameModel($arg);
            $respond = $model->del($arg);
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
        if(file_exists($fileModel)) return new $nameModel($arg);
        return die('No existe el modelo');
    }
}