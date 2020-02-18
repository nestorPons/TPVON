<?php

namespace app\controllers;

use\app\libs\{Auth};
use\app\models\{Tokens, User, Company, ZoneUser, Tickets};
use\app\core\{Error, Query, Data, Controller, Security};

/**
 * Controla la vista y la recepción de los datos del formulario de login
 */
class Login extends Controller
{
    protected $restrict = false;

    private
        $company, $password, $email,
        $folders = \VIEWS\LOGIN,
        $level_admin = LEVEL_ADMIN,
        $level_user = LEVEL_USER,
        $db = CONN['db'];

    function __construct(String $action = null, $data)
    {

        // Comprobamos que la empresa existe si no enviamos el formulario de nueva empresa
        $c = new Query(null, $this->db);

        if ($c->isConnected()) {
            $this->company = new Company();
            parent::__construct($action, null, $data);
        } else parent::__construct('view', 'index', ['page' => 'newcompany']);
    }
    function confirmation()
    {

        if (isset($_GET['args'])) {
            $data = Tokens::decode($_GET['args']);
            $User = new User($data->id);
            $User->activate();
            $this->view(['page' => 'useractivate', 'name_company' => $this->company->nombre()]);
        } else die('token obligatorio');
    }
    protected function reset(Data $Data)
    {
        $User = new User($Data->email);
        if ($User->resetPassword()) {
            return $this->printView($this->folders . 'newuseraccount.phtml');
        }
    }
    /**
     * Devuelve la vista de reseteo de contraseña
     * Obligado que obtenga un token pasado por get 
     */
    protected function activatePassword(Data $Data)
    {
        $this->controller = 'user';
        $User = new User((int) $Data->id);
        $Data->password = $User->password_hash($Data->password);
        if ($User->saveById($Data->toArray())) {
            return $this->printView($this->folders . 'passwordactivate.phtml', ['name_company' => $this->company->nombre()]);
        } else return false;
    }
    protected function newpassword()
    {
        $d = Tokens::decode($_GET['args']);
        $this->view(['page' => 'resetpassword', 'name_company' => $this->company->nombre(), 'idUser' => $d->id]);
    }
    /**
     * Autentifica el usuario
     * Carga de controlador adecuado
     * Devuelve la vista
     */
    protected function auth(Data $Data)
    {
        if ($Data->isEmail('email')) $this->email = $Data->email;
        if ($Data->isString('password', 250)) $this->password = $Data->password;

        $this->User = new User($Data->email);

        if ($this->verify($this->User->password())) {
            if ($this->isActive()) {
                // Cargamos JWT para autentificación de la sesión
                $jwt = Auth::SignIn(
                    [
                        'id' => $this->User->id,
                        'access' => true
                    ],
                    (60 * 12)
                );

                if ($this->isAdmin()) {
                    $Admin = new Admin($this->User);
                    $Admin->jwt($jwt);
                    return $Admin->loadView();
                } else if ($this->isUser()) {
                    $Clients = new Clients;
                    return $Clients->loadView();
                }
            } else return Error::array('E023');
        } else return Error::array('E026');
    }
    protected function newuser(Data $Data)
    {
        $User = new User;
        $User->new($Data);
        return $this->printView($this->folders . 'newuseraccount.phtml');
    }
    private function verify($save_password)
    {
        return password_verify($this->password, $save_password);
    }
    private function isAdmin()
    {
        return $this->User->nivel() >= $this->level_admin;
    }
    private function isUser()
    {
        return $this->User->nivel() >= $this->level_user;
    }
    protected function view($data = null)
    {
        // Valor predeterminado de la vista
        if (!is_array($data)) {
            if ($data->isEmpty('page')) {
                $data = $this->company->toArray();
                $data['page'] = 'login';
            }
        }

$data['perro'] = ['animal', 'canino'];
        return $this->printView(\FOLDERS\VIEWS . 'index.phtml', $data);
    }
    private function isActive()
    {
        return $this->User->estado() == 1;
    }
}
