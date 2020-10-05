<?php
require_once 'PHPMailer-master/PHPMailerAutoload.php';

function SendMail($To,$Subject,$Message)
{
	// Send mail
	$mail = new PHPMailer();
	$mail->IsSMTP(); // telling the class to use SMTP	
	//$mail->SMTPDebug = 1;
	$mail->Port = 587;
	$mail->SMTPSecure = 'tls';
	$mail->SMTPAuth = true;
	//$mail->SMTPDebug = 1;
	$mail->Host = "mail.kdb.co.ke";
	$mail->Username = "inspections@kdb.co.ke";
	$mail->Password = "Kdb1958*"; 
			
	$mail->From = "inspections@kdb.co.ke";
	$mail->FromName = "Cosmas";
	$mail->Subject = $Subject;
	$mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
	$mail->AddAddress($To, $Name);
	$mail->AddAddress('mwaurazab@gmail.com', $Name);
	$mail->AddAddress('wamukuru.george@kdb.co.ke', $Name);
	$mail->MsgHTML($Message);
			
	$response= NULL;
	if(!$mail->Send()) 
	{
		$msg = "Mailer Error: " . $mail->ErrorInfo;		
		$Sent = 0;
	} else {
		$msg = "Message sent!";
		$Sent = 1;
	}
	return $Sent;
}	