<?php namespace app\models;

use \app\core\{Query, Data, Error, Security};
use \PHPMailer\PHPMailer\{PHPMailer,  Exception};

class User extends Query
{
    public
        $id,
        $codigo,
        $dni,
        $nombre,
        $email,
        $fecha_nacimiento,
        $fecha_alta,
        $fecha_baja,
        $estado, 
        $nivel, 
        $password, 
        $aplicar_promos, 
        $enviar_emails,
        $obs, 
        $promos, 
        $emails;

    private $Conf;

    /**
     * $arg puede ser un string email para buscar por email
     * integer buscar por id de usuario
     * false para no crear conexion
     */
    function __construct($arg = null, bool $conn = true)
    {
        $this->table = 'usuarios';
        $C = new Company;
        $this->company = $C->nombre();
        $this->Conf = new Query('usuarios_config');

        if ($conn) parent::__construct();
        if ($arg) {
            if (is_array($arg)) $this->loadData($arg);
            else if (is_int($arg)) $this->searchById($arg);
            else if (is_string($arg) && strpos($arg, '@')) $this->searchByEmail($arg);
            else if (is_object($arg)) $this->searchById($arg->id);
            $config = $this->Conf->getById($this->id);
          
            if($config){
                $this->loadData($config);
            }
        }

    }
    // Devolvemos todos los datos formateados   
    function all() : array
    {   
        $View = new Query('vista_usuarios');
        $arr = $View->getAll('nombre');

        foreach ($arr as $user) {
            unset($user['password']);

            if (!empty($user['fecha_nacimiento']))
                $user['fecha_nacimiento'] = date_format(date_create($user['fecha_nacimiento']), 'd/m/Y');
            $r[] = $user;
        }
        return $r;
    }
    // Funcion que realiza el nuevo registro o la edicion según corresponda
    function save(Data $Data)
    {
        // Recuperamos datos del usuario que genera la acción
        $token = Security::getJWT();
        $dataToken = Security::GetData($token); 
        $User = new User($dataToken->id);

        // Si intenta cambiar el nivel a uno superior se deniega el registro 
        if($Data->nivel > $User->nivel){
            return ['success'=>false, 'mens'=>'Error no tiene permiso para realizar la operación'];
        } else {
            // Carga de datos al objeto
            $this->loadData($Data);

            if ((!$Data->isEmpty('fecha_nacimiento'))) {
                $date = str_replace('/', '-', $Data->fecha_nacimiento);
                $Data->fecha_nacimiento = date("Y-m-d", strtotime($date));
            }
            if (property_exists($Data, 'password')) $Data->password = $this->password_hash($Data->password);
            
            // Quitamos estos datos que no existen en la tabla usuarios para que no de error al guardar.
            $Data->delete(['promos', 'emails']);

            if ($this->id == -1) $this->id = $this->new($Data);
            else {
                $this->saveById($Data->toArray()); 

                $this->Conf->saveById([
                    'id' => $this->id, 
                    'promos' => $this->promos, 
                    'emails' => $this->emails
                ]);
            };

            return $this->id;
        }
    }
    // Nuevos registros
    function new(Data $Data, bool $noauth = false)
    {
        if ($this->id = $this->loadData($Data->getAll())) {

            if (
                $this->id = $this->add([
                    'dni' =>  $this->dni ?? null,
                    'nombre' => $this->nombre,
                    'email' => $this->email ?? null,
                    'fecha_nacimiento' => $this->fecha_nacimiento ?? '',
                    'estado' => $this->estado ?? 1,
                    'nivel' => $this->nivel ?? 0,
                    'password' => $this->password_hash()
                ])
            ) { 
                $this->Conf->add(['id' => $this->id], false);
                // Varible para saltarse la activación del usuario
                // Eliminamos el envio de emails de activación en esta app no hay pq aplicarlo
                return $this->id;
                
            } else return Error::array('E022');
        } else throw new \Exception('E060');
    }
    // Codifica el password 
    function password_hash(string $pass = null)
    {
        $pass = $pass ?? $this->password();
        return $pass ? password_hash($pass, PASSWORD_DEFAULT) : null;
    }
    // Buscamos usuario por id 
    function searchById(int $id) : bool
    {
        $data = $this->getById($id);
        if ($data) return  $this->loadData($data);
        // en caso que no lo encuentre
        Error::die('E025');
    }
    function searchByEmail(string $email)
    {
        $data = $this->getBy(['email' => $email]);
        if ($data) return  $this->loadData($data);
        // en caso que no lo encuentre
        else Error::die('E025');
    }
    function activate()
    {
        return $this->saveById(['estado' => 1]);
    }
    function resetPassword()
    {
        $Token = new Tokens();
        $url = $_SERVER['HTTP_HOST'] . "/tpv/login/newpassword/{$Token->create($this)}";
        $body = $this->getFile(\VIEWS\LOGIN . 'mailresetpassword.phtml', new Data(['url' => $url]));

        return $this->sendMail($body, $this->company . ' nueva contraseña');
    }
    public function sendMail( String $body, String $subject) : bool    {
        $mail = new PHPMailer(true);
        // Configuración para mandar emails
        try {
            if (SEND_MAIL) {
                include_once \FILE\MAIL;

                //definimos el destinatario (dirección y, opcionalmente, nombre)
                $mail->AddAddress($this->email, $this->nombre);
                //Definimos el tema del email
                $mail->Subject = $subject;
                $mail->Body = 'esto es el cuerpo del mensaje';
                //Para enviar un correo formateado en HTML lo cargamos con la siguiente función. Si no, puedes meterle directamente una cadena de texto.
                $mail->MsgHTML($body, dirname(\FOLDERS\VIEWS));
                //Y por si nos bloquean el contenido HTML (algunos correos lo hacen por seguridad) una versión alternativa en texto plano 
                // (también será válida para lectores de pantalla)
                $mail->AltBody =  $body;
                return $mail->Send();
            } else return false;
        } catch (\Exception $e) {
            return false;
        }
    }
    private function getFile(string $file, Data $Data = null) : string 
    {
        foreach ($Data->getAll() as $key => $val) {
            ${$key} = $val;
        }
        ob_start(); # apertura de bufer
        include($file);
        $htmlStrig = ob_get_contents();
        ob_end_clean(); # cierre de bufer
        return $htmlStrig;
    }
    public function isAdmin() : bool
    {
        return $this->nivel >= 2; 
    }
    //getters setterS
    function id(int $arg = null)
    {
        if ($arg) $this->{__FUNCTION__} = $arg;
        return $this->{__FUNCTION__};
    }
    function email(int $arg = null)
    {
        if ($arg) $this->{__FUNCTION__} = $arg;
        return $this->{__FUNCTION__};
    }
    function nombre(int $arg = null)
    {
        if ($arg) $this->{__FUNCTION__} = $arg;
        return $this->{__FUNCTION__};
    }
    function dni(int $arg = null)
    {
        if ($arg) $this->{__FUNCTION__} = $arg;
        return $this->{__FUNCTION__};
    }
    function nivel(int $arg = null)
    {
        if ($arg) $this->{__FUNCTION__} = $arg;
        return $this->{__FUNCTION__};
    }
    function password(int $arg = null)
    {
        if ($arg) $this->{__FUNCTION__} = $arg;
        return $this->{__FUNCTION__};
    }
    function estado(int $arg = null)
    {
        if ($arg) $this->{__FUNCTION__} = $arg;
        return $this->{__FUNCTION__};
    }
}
