<?php
    require 'DB_PARAMS/connect.php';
	require_once('utilities.php');
	
	require_once('GlobalFunctions.php');
	require_once('county_details.php');
	

	$UserID = $_SESSION['UserID'];
	
	$rptType=$_REQUEST['rptType'];
	
	if($rptType=='Invoice')
	{
		$ApplicationID=$_REQUEST['ServiceHeaderID'];
		$InvoiceHeaderID=$_REQUEST['InvoiceHeaderID'];
		$Remark='';		
		createInvoice($db,$ApplicationID,$cosmasRow,$Remark,'',$InvoiceHeaderID);
	}else if($rptType=='Receipt')
	{		

		$hid=$_REQUEST['InvoiceHeaderID'];
		$rid=$_REQUEST['ReceiptID'];

		viewreceipt_2($db,$cosmasRow,$rid,$hid) ;

	}else if($rptType=='vehicles'){		
		 $CustomerID=$_REQUEST['CustomerID'];
		 $CustomerName=$_REQUEST['CustomerName'];
		
		//checkd($db,$cosmasRow,'',$CustomerID,$CustomerName);

		customervehicles($db,$cosmasRow,$CustomerID,$CustomerName);

		$msg=$feedBack[1];
	}else if($rptType=='permit'){		
		$PermitNo=$_REQUEST['PermitNo'];
		// print_r($_REQUEST);
		// exit;	
		$feedBack=reCreatePermit($db,$PermitNo,$cosmasRow,$UserID);
		$msg=$feedBack[1];
		echo $msg;
	}

	
?>
