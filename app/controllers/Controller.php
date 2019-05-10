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
    
    function __construct(String $action, String $controller = null, Object $data = null){
        $this->action = strtolower($action);
        $this->controller =strtolower($controller ?? $this->getController());

        // Constructor alternativo básico
        if(method_exists($this, $this->action)){
            $this->result = $this->{$this->action}($data);
        } else {
            die('Accion no permitida!!');
        }
/*         switch($this->action){
            case 'new':  $this->result = $this->new($this->data); break;
            case 'del':  $this->result = $this->del($this->data); break;
            case 'save': $this->result = $this->save($this->data); break;
            case 'get':  $this->result = $this->setModel(); break;
            case 'set':  $this->result = $this->getModel(); break;
            case 'view': $this->result = $this->getView(); break;
            case 'auth': $this->result = $this->auth(); break;
            default: 
                die('Accion no permitida!!');
        } */
    }
    protected function view($data = null){
        return $this->require(\FOLDERS\VIEWS . $this->controller . '.phtml', $data);
    }
    protected function require(String $route, $data = null){
        if(isset($_GET['db'])) $Company = new \app\models\Company($_GET['db']);
        if (is_array($data))
            $data = new \app\core\Data($data);
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
    private function getController(){
        $arr_controller= explode('\\',get_class($this));
        $controller = end($arr_controller);
        return strtolower($controller);
    }
}