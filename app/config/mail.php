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
