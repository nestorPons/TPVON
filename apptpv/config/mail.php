<?php 
//Server settings
$mail->SMTPDebug = 0;                                       // Enable verbose debug output
$mail->isSMTP();                                            // Set mailer to use SMTP
$mail->Host       = CONN['hostemail'];                       // Specify main and backup SMTP servers
$mail->Username   = CONN['username'];                           // SMTP username
$mail->Password   = CONN['password'];                               // SMTP password
$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
$mail->SMTPSecure = CONN['secure'];                                  // Enable TLS encryption, `ssl` also accepted
$mail->Port       = CONN['portemail'];                                    // TCP port to connect to

$mail->From       = CONN['username'];
$mail->FromName   = CONN['fromname'];

//config 
$mail->CharSet = 'UTF-8';
$mail->isHTML(true);  
