
<?php

include ("Sendinblue\Mailin.php");

/*
 * This will initiate the API with the endpoint and your access key.
 *
 */


/** Prepare variables for easy use **/ 
$to = array("cngeno11@gmail.com"=>"to whom!"); //mandatory
$subject = "My subject"; //mandatory
$from = array("info@attain-es.com","from email!"); //mandatory
$html = "This is the <h1>HTML</h1>"; //mandatory
$text = "This is from a function";
$cc = array("cc@example.net"=>"cc whom!"); 
$bcc = array("bcc@example.net"=>"bcc whom!");
$replyto = array("info@attain-es.com","reply to!"); 
$attachment = array(); //provide the absolute url of the attachment/s 


//echo sendEmail($to,$subject,$from,$html,$text,$cc,$bcc,$replyto,$attachment);


function sendEmail($to,$subject,$from,$html,$text,$cc,$bcc,$replyto,$attachment)
{
	$accessKey="hCYWPq6LwSamKp7x";
	$mailin = new Mailin('https://api.sendinblue.com/v2.0',$accessKey);

	$body = "$text";
	$headers = array("Content-Type"=> "text/html; charset=iso-8859-1","X-Ewiufkdsjfhn"=> "hello","X-Custom" => "Custom");

	$result=$mailin->send_email($to,$subject,$from,$html,$body,$cc,$bcc,$replyto,$attachment,$headers);
}