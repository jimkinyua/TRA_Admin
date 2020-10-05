
<?php

	require 'DB_PARAMS/connect.php';
	require_once('utilities.php');
	require_once('county_details.php');
	require_once('GlobalFunctions.php');
	require_once('county_details.php');
	require_once('mailsender.php');
	//require_once('ReportingFunctions.php');


	echo GenerateInvoice($db,23,$UserID);
	exit('hey');


	require 'DB_PARAMS/connect.php';
	require_once('utilities.php');
	//require_once('PHPMailer/class.phpmailer.php');
	//include("PHPMailer/class.smtp.php");
	require_once('county_details.php');
	require_once('smsgateway.php');


	$my_mail="cngeno11@gmail.com";
	$fromEmail="cngeno11@gmail.com";
	$fromName="SENDER";
	$my_subject="TEST";
	$my_message="Phew, This KDB Email settings now works on my Machine but fails in the KDB server!";
	$my_file="";
	$my_path="";

	$result=SendMail($my_mail,$my_subject,$my_message);
	echo $result;
	//$result=php_mailer($my_mail,$fromEmail,$fromName,$my_subject,$my_message,$my_file,$my_path,"Test");	

	