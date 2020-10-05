<?php 

	require 'DB_PARAMS/connect.php';
	require_once('utilities.php');
	require_once('GlobalFunctions.php');
	if (!isset($_SESSION))
	{
		session_start();
	}
	
	$ApplicationID='1064';
	$UserdID=1;
	echo GenerateInvoice($db,$ApplicationID,$UserID='')

?>



