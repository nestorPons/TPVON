<?php 
//Server settings
$this->Host         = 'mail.gandi.net';       // Specify main and backup SMTP servers
$this->From         = 'admin@reservatucita.com';
$this->FromName     = 'ReservaTuCita.com';

$this->SMTPDebug =1;           // Enable verbose debug output
$this->isMail();                // Set mailer to use SMTP
$this->SMTPAuth = false;        // Enable SMTP authentication
$this->Username = '';   // SMTP username
$this->Password = '';   // SMTP password
$this->SMTPSecure = 'TLS';      // Enable TLS encryption, `ssl` also accepted
$this->Port = 25;
$this->AddReplyTo($this->From, $this->FromName);
 
//Recipients        
//config 
$this->CharSet = 'UTF-8';
$this->isHTML(true);  
//Attachments
/* $this->AddEmbeddedImage(URL_LOGO, 'logoimg', 'logo.jpg');
$this->AddEmbeddedImage(URL_BACKGROUND, 'backgroundimg', 'background.jpg'); */