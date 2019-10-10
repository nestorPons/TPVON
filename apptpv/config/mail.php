<?php 
//Server settings
$mail->SMTPDebug = 0;                                       // Enable verbose debug output
$mail->isSMTP();                                            // Set mailer to use SMTP
$mail->Host       = 'mail.XXX.net';                       // Specify main and backup SMTP servers
$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
$mail->Username   = 'MAIL@MAIL.COM';                           // SMTP username
$mail->Password   = 'PASSWORD';                               // SMTP password
$mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
$mail->Port       = 587;                                    // TCP port to connect to

$mail->From =  'MAIL@MAIL.COM';
$mail->FromName =  'TPVONLINE';

//config 
$mail->CharSet = 'UTF-8';
$mail->isHTML(true);  
