<?php echo (extension_loaded('openssl')?'SSL loaded':'SSL not loaded')."<br>"?>
<?php
require_once '../../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once '../../../vendor/phpmailer/phpmailer/src/Exception.php';
require_once '../../../vendor/phpmailer/phpmailer/src/SMTP.php';
$mail             = new PHPMailer\PHPMailer\PHPMailer();
print_r($_GET);

$mail->SMTPSecure = $_GET['secure_mail'];     
$mail->Port       = $_GET['port_mail'];                   // set the SMTP port for the GMAIL server
$mail->Username   = $_GET['username_mail'];  // GMAIL username
$mail->Password   = $_GET['password_mail'];            // GMAIL password
$mail->Host       = gethostbyname($_GET['host_mail']);            // sets the prefix to the servier

$mail->SMTPOptions = array(
		    'ssl' => array(
		        'verify_peer' => false,
		        'verify_peer_name' => false,
		        'allow_self_signed' => true
		    )
		);

$body             = 'Esto es un mensaje de prueba';
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->IsSMTP(); // telling the class to use SMTP
$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only

$mail->SetFrom($_GET['fromname_mail'], 'Test email enviar desde');

$mail->AddReplyTo('nestorpons@gmail.com', 'Test email ennviar a ');

$mail->Subject    = "PHPMailer Test Subject via smtp (Gmail), basic";

$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

$mail->MsgHTML($body);

$mail->AddAddress($_GET['fromname_mail'], "First Last");

if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
  echo "Message sent!";
}
?>
<m-a href="../index.php">Volver</m-a>