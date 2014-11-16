<?php

require_once('class.phpmailer.php');
require_once('class.smtp.php');

class Mail{

	function correo($addressee, $subject, $contenido){
		$mail = new PHPMailer();
		$mail->IsSMTP();

		//Esto es para activar el modo depuración. En entorno de pruebas lo mejor es 2, en producción siempre 0
		// 0 = off (producción)
		// 1 = client messages
		// 2 = client and server messages
		$mail->SMTPDebug  = 0;
		$mail->Host       = 'smtp.gmail.com';
		$mail->Port       = 587;
		$mail->SMTPSecure = 'tls';
		$mail->SMTPAuth   = true;
		
		require('config.ini');

		$mail->Username = $email;
		$mail->Password = $passwordEmail;
		$mail->SetFrom($email, 'Taller Automotriz');
		$mail->AddReplyTo($email, 'Taller Automotriz');

		$mail->AddAddress($addressee, '');

		$mail->Subject = $subject;

		$mail->MsgHTML($contenido);
		//Y por si nos bloquean el contenido HTML (algunos correos lo hacen por seguridad) una versión alternativa en texto plano (también será válida para lectores de pantalla)
		$mail->AltBody = '';

		if(!$mail->Send()) {
			return false;
		  //echo "Error: " . $mail->ErrorInfo;
		} else {
		  return true;
		}
	}
}
?>