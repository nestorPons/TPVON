<?php namespace app\models;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class User extends \app\core\Query{
    private $id, $dni, $nombre, $email, $fecha_nacimiento, $estado, $nivel, $password, $intentos, $company, $token;
    protected $table = 'usuarios'; 

    function __construct($arg = null){
        $this->company = ucwords(NAME_COMPANY)??null; 

        if($arg){
            $this->connecTo();
            if (is_int($arg)) $this->searchById();
            else if (strpos($arg, '@')) $this->searchByEmail($arg);
        }
    }
    function new(Object $Data){
        if ($this->loadData($Data)){
            $this->sendMail();
            /*
            if(!
                $this->add([
                    'dni' =>  $this->dni,
                    'nombre' => $this->nombre,
                    'email' => $this->email,
                    'fecha_nacimiento' => $this->fecha_nacimiento??null,
                    'estado' => $this->estado??0,
                    'nivel' => $this->nivel??1, 
                    'password' => $this->password_hash(),
                    'intentos' => $this->intentos??0
                ])) 
                return \app\core\Error::array('E022');
            else {
                return true;
            }
            */
        } else throw new \Exception('E060');
    }
    private function sendMail(){

        $Mail = new PHPMailer(true);
        // Configuración para mandar emails
        include_once \FOLDERS\CONFIG . 'mail.php';
        $Mail->IsHTML(true);
        $Mail->isSMTP();
        //definimos el destinatario (dirección y, opcionalmente, nombre)
        $Mail->AddAddress($this->email, $this->nombre);
        //Definimos el tema del email
        $Mail->Subject = 'Activacion de la cuenta en ' . $this->company;
        $Mail->Body = 'esto es el cuerpo de la prueba';
        //Para enviar un correo formateado en HTML lo cargamos con la siguiente función. Si no, puedes meterle directamente una cadena de texto.

        
        $Token = new Tokens; 
        $token = $Token->create($this); 
// AKI ::: 
        ob_start(); # apertura de bufer
        include( \FOLDERS\VIEWS . 'mailNewUser.phtml' );
        $htmlStrig = ob_get_contents();
        ob_end_clean(); # cierre de bufer

        prs(
            $Mail->MsgHTML($htmlStrig, dirname(\FOLDERS\VIEWS))
        );
        //Y por si nos bloquean el contenido HTML (algunos correos lo hacen por seguridad) una versión alternativa en texto plano (también será válida para lectores de pantalla)
        $Mail->AltBody = 'This is a plain-text message body';
        return $Mail->Send();
         
/*
        try {
            //Server settings
            $mail->SMTPDebug = 2;                                       // Enable verbose debug output
            $mail->isSMTP();                                            // Set mailer to use SMTP
            $mail->Host       = 'smtp1.example.com;smtp2.example.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'user@example.com';                     // SMTP username
            $mail->Password   = 'secret';                               // SMTP password
            $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
            $mail->Port       = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('from@example.com', 'Mailer');
            $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
            $mail->addAddress('ellen@example.com');               // Name is optional
            $mail->addReplyTo('info@example.com', 'Information');
            $mail->addCC('cc@example.com');
            $mail->addBCC('bcc@example.com');

            // Attachments
            $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
            $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'Here is the subject';
            $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
*/
    }

    function password_hash(string $pass = null){
        $pass = $pass??$this->password(); 
        return password_hash($pass, PASSWORD_DEFAULT);
    }
    function loadData($Data){
        if(is_array($Data)) $Data = new \app\core\Data($Data);
        $this->dni = $Data->dni??null;
        $this->nombre = $Data->nombre;
        $this->email = $Data->email??null;
        $this->fecha_nacimiento = $Data->fecha_nacimiento??null;
        $this->estado = $Data->estado??1; 
        $this->nivel = $Data->nivel??0;
        $this->password = $Data->password??null;
        $this->intentos = $Data->intentos??0;
        return true;
    }
    function searchByEmail(string $arg){
        $data = $this->getBy(['email' => $arg]);
        if($data) return $this->loadData($data);
        \app\core\Error::die('E025');
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
    function fecha_nacimiento(int $arg = null){
        if($arg) $this->{__FUNCTION__} = $arg; 
        return $this->{__FUNCTION__}; 
    }
    function password(int $arg = null){
        if($arg) $this->{__FUNCTION__} = $arg; 
        return $this->{__FUNCTION__}; 
    }
}