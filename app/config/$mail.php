<?php 
// Constantes para el envio de emails

const EMAIL_FROM = 'admin@lebouquet.es';
const EMAIL_PASS = 'QQasw2!!';
const EMAIL_NAME = 'TpvLebouquet.com';
const EMAIL_HOST = 'mail.gandi.net';        // 'smtp.sd3.gpaas.net';  //smtp.gmail.com';
const EMAIL_USER = 'EMAIL_FROM';
const EMAIL_PORT = 25;

//Server settings
$Mail->SMTPDebug =1;            // Enable verbose debug output
//$Mail->isMail();              // Set mailer to use SMTP
$Mail->Host = EMAIL_HOST;       // Specify main and backup SMTP servers
$Mail->SMTPAuth = false;        // Enable SMTP authentication
$Mail->Username = '';           // SMTP username
$Mail->Password = '';           // SMTP password
$Mail->SMTPSecure = 'TLS';      // Enable TLS encryption, `ssl` also accepted
$Mail->Port = 25;
 
$Mail->From =  EMAIL_FROM;
$Mail->FromName =  EMAIL_NAME;
//Recipients        
//$Mail->AddReplyTo(EMAIL_FROM,EMAIL_NAME);
//config 
$Mail->CharSet = 'UTF-8';
//$Mail->isHTML = true;  
//Attachments
//$Mail->AddEmbeddedImage(URL_LOGO, 'logoimg', 'logo.jpg');
//$Mail->AddEmbeddedImage(URL_BACKGROUND, 'backgroundimg', 'background.jpg');

/*
<?php 
//Definir que vamos a usar SMTP

//Esto es para activar el modo depuración. En entorno de pruebas lo mejor es 2, en producción siempre 0
// 0 = off (producción)
// 1 = client messages
// 2 = client and server messages
$Mail->SMTPDebug  = 0;
//Ahora definimos gmail como servidor que aloja nuestro SMTP
$Mail->Host       = 'smtp.gmail.com';
//El puerto será el 587 ya que usamos encriptación TLS
$Mail->Port       = 587;
//Definmos la seguridad como TLS
$Mail->SMTPSecure = 'tls';
//Tenemos que usar gmail autenticados, así que esto a TRUE
$Mail->SMTPAuth   = true;
//Definimos la cuenta que vamos a usar. Dirección completa de la misma
$Mail->Username   = "nestorpons@gmail.com";
//Introducimos nuestra contraseña de gmail
$Mail->Password   = "PP09ol.__";
//Definimos el remitente (dirección y, opcionalmente, nombre)
$Mail->SetFrom('nestorpons@gmail.com', 'Nestor Pons');
//Esta línea es por si queréis enviar copia a alguien (dirección y, opcionalmente, nombre)
//$Mail->AddReplyTo('replyto@correoquesea.com','El de la réplica');

*/
