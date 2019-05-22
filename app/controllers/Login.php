<?php namespace app\controllers;
use \app\models\Tokens;
use \app\models\User; 
use \app\models\Company;
use \app\core\Error;
use \app\core\Data;
/**
 * Controla la vista y la recepción de los datos del formulario de login
 */
class Login extends Controller{
    private 
        $company, $zone, $password, $email,  
        $folders = \FOLDERS\LOGIN, 
        $level_admin = LEVEL_ADMIN, 
        $level_user = LEVEL_USER;

    function __construct(String $action = null, String $db = null, $data){

        $this->company = new Company($db);
        if($this->company->id()){
            parent::__construct($action, null, $data);
        }else{
           die('Empresa no encontrada');
        }
    }
    function confirmation(){
    
        if(isset($_GET['args'])){

            $data = Tokens::decode($_GET['args']);
            $User = new User($data->id); 
            $User->activate();
            $this->view(['page' => 'useractivate', 'name_company' =>$this->company->nombre()]); 
            
        } else die('token obligatorio');
    
    }
    protected function reset(Object $Data){
        $User = new User($Data->email);
        if($User->resetPassword()){
            return $this->require($this->folders . 'newuseraccount.phtml');
        }
    }
    /**
     * Devuelve la vista de reseteo de contraseña
     * Obligado que obtenga un token pasado por get 
    */
    protected function activatePassword($Data){
        $this->controller = 'user'; 
        if($this->update($Data)){
            return $this->require($this->folders . 'passwordactivate.phtml', ['name_company' => $this->company->nombre()] );
        } else return false;
    }
    protected function newpassword(){
        $Data =Tokens::decode($_GET['args']); 
        $this->view(['page' => 'resetpassword', 'name_company' => $this->company->nombre(), 'idUser'=> $Data->id]); 
    }
    protected function auth(Object $Data){

        if($Data->isEmail('email')){
            $this->email = $Data->email; 
        }
        if ($Data->isString('password', 250)){
            $this->password = $Data->password;
        };

        $this->User = new User($Data->email);

        if($this->verify($this->User->password())){
            return require($this->zone());
        } else return Error::array('E026');
        
    }
    protected function newuser($Data){
        $User = new User; 
        $User->new($Data); 
        return $this->require($this->folders . 'newuseraccount.phtml');
    }
    private function verify($save_password){
        return password_verify($this->password, $save_password);
    }
    private function zone(){
        $folder = ($this->isAdmin())?\FOLDERS\ADMIN : \FOLDERS\USER;
        return $this->zone = $folder . 'index.phtml'; 
    }
    private function isAdmin(){
        return $this->User->nivel() >= $this->level_admin; 
    }
    private function isUser(){
        return $this->User->nivel() >= $this->level_user; 
    }
    protected function view( $data = null){
        // VAlor predeterminado de la vista
        if (!$data) $data = ['page' => 'login', 'data' => $this->company->data()];
        return $this->require( \FOLDERS\VIEWS. 'index.phtml', $data );
    }
}