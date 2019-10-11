<?php 
define('MAIL', parse_ini_file(dirname(__DIR__) . '/config/mail.ini'));
//Server settings
$mail->SMTPDebug = 0;                                       // Enable verbose debug output
$mail->isSMTP();                                            // Set mailer to use SMTP
$mail->Host       = MAIL['host'];                       // Specify main and backup SMTP servers
$mail->Username   = MAIL['username'];                           // SMTP username
$mail->Password   = MAIL['password'];                               // SMTP password
$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
$mail->SMTPSecure = MAIL['secure'];                                  // Enable TLS encryption, `ssl` also accepted
$mail->Port       = MAIL['port'];                                    // TCP port to connect to

$mail->From       = MAIL['username'];
$mail->FromName   = MAIL['fromname'];

//config 
$mail->CharSet = 'UTF-8';
$mail->isHTML(true);  
