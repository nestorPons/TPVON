<?php namespace app\controllers; 
/**
 * Controla la vista y la recepción de los datos del formulario de login
 */
class Login extends Controller{
    private $company; 

    function __construct(String $action = null, String $db = null, $data){

        $this->company = new \app\models\Company($db);
        if($this->company->id()){
            parent::__construct($action, null, $data);
        }else{
           die('Empresa no encontrada');
        }
    }
    protected function auth(){
        
        $d = $this->data;
        $d->isEmail('email');
        $d->isString('password', 200);

        $User = new \app\models\User($this->data->email);
        if($this->verify($User)){
            return require(\FOLDERS\VIEWS . 'appadmin.phtml');
        } else return false;
        
    }
    private function verify($User){
        return password_verify($this->data->password, $User->password());
    }
    protected function getView( Array $data = []){
        return $this->require(\FOLDERS\VIEWS . 'index.phtml', ['page' => 'login', 'data' => $this->company->data()] );
    }
    public function getModel(){}
    public function setModel(){}
}