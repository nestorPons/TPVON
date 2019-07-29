<?php namespace app\models;

use \app\core\{ Query, Data, Error };
use PHPMailer\PHPMailer\{ PHPMailer,  Exception};

class User extends Query{
    public $id, $dni, $nombre, $email, $fecha_nacimiento, $estado, $nivel, $password, $intentos, $company, $token, $telefonos;
    protected $table = 'usuarios';
    private $allUsers; 
    /**
     * $arg puede ser un string email para buscar por email
     * integer buscar por id de usuario
     * false para no crear conexion
     */
    function __construct($arg = null, bool $conn = true){

        $this->company = NAME_COMPANY??null;
        if($conn) parent::__construct();
        if($arg){
            if (is_array($arg)) $this->loadData($arg);
            else if (is_int($arg)) $this->searchById($arg);
            else if (is_string($arg) && strpos($arg, '@')) $this->searchByEmail($arg);
            else if (is_object($arg)) $this->searchById($arg->id);
        }

    }
    // Devolvemos todos los datos formateados   
    function all(){
        $arr = $this->getAll();
        foreach ($arr as $user){
            unset($user['password']);

            if(!empty($user['fecha_nacimiento']))
                $user['fecha_nacimiento'] = date_format(date_create($user['fecha_nacimiento']), 'd/m/Y');
            $r[] = $user; 
        }
        return $r; 
    }
    // Funcion que realiza el nuevo registro o la edicion según corresponda
    function save(Object $Data){
        $this->loadData($Data);
        $date = str_replace('/', '-', $Data->fecha_nacimiento );
        $Data->fecha_nacimiento = date("Y-m-d", strtotime($date));
        if(property_exists($Data, 'password')) $Data->password = $this->password_hash($Data->password);

        $noAuth = $Data->use('noAuth');
        if($this->id == -1) $this->new($Data);
        else if($this->saveById($Data->toArray()));
        
        return $this->id;
    }
    // Nuevos registros
    function new(Object $Data){
        if ($this->id = $this->loadData($Data->getAll())){  
       
            if(
            $this->id = $this->add([
                'dni' =>  $this->dni??null,
                'nombre' => $this->nombre,
                'email' => $this->email??null,
                'fecha_nacimiento' => $this->fecha_nacimiento??null,
                'estado' => $this->estado??0,
                'nivel' => $this->nivel??0,
                'password' => $this->password_hash(),
                'intentos' => $this->intentos??0
            ])) {

                // Varible para saltarse la activación del usuario
                if(!isset($Data->noAuth)){
                    $Token = new Tokens();
                    $url = HOST . '/'. CODE_COMPANY. "/login/confirmation/{$Token->create($this)}";
                    $body = $this->getFile(\VIEWS\LOGIN . 'mailNewUser.phtml', new Data(['url'=>$url]));
                    return $this->sendMail($body, $this->company . 'Activacion de la cuenta en '. $this->company);
                } else {
                    return $this->id;
                }
            }
            else return Error::array('E022');
            
        } else throw new \Exception('E060');
    }
    function password_hash(string $pass = null){
        $pass = $pass??$this->password();
        return $pass ? password_hash($pass, PASSWORD_DEFAULT) : null;
    }
    function searchById(int $arg){

        $data = $this->getById($arg); 
        if($data) return  $this->loadData($data);
        // en caso que no lo encuentre
        Error::die('E025');
    }
    function searchByEmail(string $arg){
        $data = $this->getBy(['email' => $arg]);
        if($data) return  $this->loadData($data);
        // en caso que no lo encuentre
        else Error::die('E025');
    }
    function activate(){
        return $this->saveById(['estado'=>1]);
    }
    function resetPassword(){
        $Token = new Tokens();
        $url = HOST . '/'. CODE_COMPANY. "/login/newpassword/{$Token->create($this)}";
        $body = $this->getFile(\VIEWS\LOGIN . 'mailresetpassword.phtml', new Data(['url'=>$url]));
        return $this->sendMail($body, $this->company . ' nueva contraseña');
    }
    private function sendMail($body, string $subject){

        $Mail = new PHPMailer(true);
        // Configuración para mandar emails
            include_once \FILE\MAIL;
            $Mail->IsHTML(true);
            $Mail->isSMTP();
            //definimos el destinatario (dirección y, opcionalmente, nombre)
            $Mail->AddAddress($this->email, $this->nombre);
            //Definimos el tema del email
            $Mail->Subject = $subject ;
            $Mail->Body = 'esto es el cuerpo de la prueba';
            //Para enviar un correo formateado en HTML lo cargamos con la siguiente función. Si no, puedes meterle directamente una cadena de texto.
                     
        $Mail->MsgHTML($body, dirname(\FOLDERS\VIEWS));
    
    
    //Y por si nos bloquean el contenido HTML (algunos correos lo hacen por seguridad) una versión alternativa en texto plano (también será válida para lectores de pantalla)
            $Mail->AltBody =  $body;
            return $Mail->Send();
        try {
           
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    }
    private function getFile(String $file, Object $Data = null){
        foreach($Data->getAll() as $key => $val){
            ${$key} = $val;
        }
        ob_start(); # apertura de bufer
        include($file);
        $htmlStrig = ob_get_contents();
        ob_end_clean(); # cierre de bufer
        return $htmlStrig; 
    }
    //getters setters

    function id(int $arg = null){
        if($arg) $this->{__FUNCTION__} = $arg; 
        return $this->{__FUNCTION__}; 
    }
    function email(int $arg = null){
        if($arg) $this->{__FUNCTION__} = $arg; 
        return $this->{__FUNCTION__}; 
    }
    function nombre(int $arg = null){
        if($arg) $this->{__FUNCTION__} = $arg; 
        return $this->{__FUNCTION__}; 
    }
    function dni(int $arg = null){
        if($arg) $this->{__FUNCTION__} = $arg; 
        return $this->{__FUNCTION__}; 
    }
    function nivel(int $arg = null){
        if($arg) $this->{__FUNCTION__} = $arg; 
        return $this->{__FUNCTION__}; 
    }
    function password(int $arg = null){
        if($arg) $this->{__FUNCTION__} = $arg; 
        return $this->{__FUNCTION__}; 
    }

}