<?php 
	require 'DB_PARAMS/connect.php';

	$msg="";
	
	//require("phpToPDF.php"); 
		$UserID='';
	if (!isset($_SESSION))
	{
		session_start();
	}
	$msg ='';
	$UserID = $_SESSION['UserID'];
	
	$CountyName="";
	$ContyAddress="";
	$CountyTown="";
	$CountyTelephone="";
	$CountyMobile="";
	$CountyEmail="";
	
	
		//County Details
	$sql="SELECT [CountyName],[PostalAddress],[PostalCode],[Town],[Telephone1],[Mobile1],[Email],Url Website,SBPDateline
	FROM CountyDetails";
	$cosmasRow= array();
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$cosmasRow=$row;			
	}	
	
?>
