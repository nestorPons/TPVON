<?php 
//Server settings
$mail->SMTPDebug = 0;                                       // Enable verbose debug output
$mail->isSMTP();                                            // Set mailer to use SMTP
$mail->Host       = 'mail.gandi.net';                       // Specify main and backup SMTP servers
$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
$mail->Username   = 'admin@mitpv.pw';                           // SMTP username
$mail->Password   = 'QQasw2!!';                               // SMTP password
$mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
$mail->Port       = 587;                                    // TCP port to connect to

$mail->From =  'admin@mitpv.pw';
$mail->FromName =  'mitpv';

//config 
$mail->CharSet = 'UTF-8';
$mail->isHTML(true);  