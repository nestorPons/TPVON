<?php namespace app\controllers; 
/**
 * Controla la vista y la recepción de los datos del formulario de login
 */
class Login extends Controller{
    private 
        $company, $zone,  
        $folders = \FOLDERS\VIEWS, 
        $level_admin = LEVEL_ADMIN, 
        $level_user = LEVEL_USER; 

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
            return require($this->folders . $this->zone() . '.phtml');
        } else return \app\core\Error::array('E026');
        
    }
    private function verify($User){
        $this->User = $User; 
        return password_verify($this->data->password, $User->password());
    }
    private function zone(){
        if($this->isAdmin()){
            return $this->zone = 'appadmin';
        }else if($this->isUser()){
            return $this->zone = 'appuser'; 
        }
    }
    private function isAdmin(){
        return $this->User->nivel() >= $this->level_admin; 
    }
    private function isUser(){
        return $this->User->nivel() >= $this->level_user; 
    }
    protected function getView( Array $data = []){
        return $this->require($this->folders . 'index.phtml', ['page' => 'login', 'data' => $this->company->data()] );
    }
    public function getModel(){}
    public function setModel(){}
}