<?php
	$headers = "From: edmond.korir.attain-es,com\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	//echo"The $to";
	if (mail('cngeno11@gmail.com',$subject, 'This is alwanga', $headers)){ 
		$Status = "Sent";
		echo"Sent";
	} else {
		echo"Not";
	}
	
	
?>