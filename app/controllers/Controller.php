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
        $this->Data = $Data; 
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
     * Método genérico para guardar registros comprueba que es nuevo o edicion y envia los datos al metodo apropiado
     */
    protected function save(Object $Data){
        if($Data->id == -1 ) return $this->new($Data);
        else return $this->update($Data);
    }
        /**
     * Método por defecto de agregación de registros a la base de datos
     */
    protected function new(Object $Data = null){
        return $this->exec('new', 'add');
    }
    /**
     * Método genérico para actualizar registros
     */
    protected function update(Object $Data){
        return $this->exec('save', 'saveById'); 
    }
    /**
     * Método por defecto de consulta de datos 
     */
    protected function get(Object $Data = null){
        return $this->exec('get', 'getById');
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
     * Método por defecto de consulta de datos entre parametros
     */
    protected function getBetween(Object $Data){
        return $this->exec('between', 'getBetween');
    }
    /**
     * Método por defecto de eliminación de registros
     */
    protected function del($arg){
        return $this->exec('del', 'deleteById');
    }
    private function getController(){
        $arr_controller= explode('\\',get_class($this));
        $controller = end($arr_controller);
        return strtolower($controller);
    }
    protected function getModel($arg = null){
        return (file_exists(\FOLDERS\MODELS . ucfirst($this->controller) . '.php'))
            ? '\\app\\models\\' . ucfirst($this->controller)
            : '\\app\\core\\Query';
    }
    private function exec (String $method, String $method_generic){
        $name_model = $this->getModel(); 
        $model = new $name_model($this->controller);
        if (method_exists($model, $method)){
            return  $model->{$method}($this->Data);
        } else {
            $model->id = $this->Data->id;
            return $model->{$method_generic}($this->Data->toArray());
        }
    }
}