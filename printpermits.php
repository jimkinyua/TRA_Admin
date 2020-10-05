<?php

	require 'DB_PARAMS/connect.php';
	require_once('county_details.php');
	require_once('GlobalFunctions.php');
	require_once('utilities.php');

	if (!isset($_SESSION))
	{
		session_start();   
	}
	
	$ServiceHeaderID=$_REQUEST['id'];
	
	echo  PrintPermits($db,$ServiceHeaderID);
	
	function PrintPermits($db)
	{
		
		
		$sql="select ServiceHeaderID from serviceheader where ServiceHeaderID=$ServiceHeaderID";
		
	    $result=sqlsrv_query($db,$sql);

	    while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
	    { 
	        $ApplicationID=$row['ServiceHeaderID']; 
	        if($ApplicationID==0){
	        	return "None";
	        }

	        //return renewPermit($db,$ApplicationID,$cosmasRow);

	        //exit;

        	$sql="Select distinct CreatedBy,InvoiceHeaderID from InvoiceLines where ServiceHeaderID=$ApplicationID";
	        $result=sqlsrv_query($db,$sql);
	        while($rowss=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
	        	$UserID=$rowss['CreatedBy'];
	        	$InvoiceHeaderID=$rowss['InvoiceHeaderID'];
	        }

	        $today=date('Y-m-d H:i:s');
			$FirstDec=date(date('Y')."-12-01 00:00:00");
			if($today>$FirstDec)
			{
				$PermitYear=date("Y")+1;
			}

			$validity=date('Y');
			if($today>$FirstDec){
				$validity=date('Y')+1;
			}else{
				$validity=date('Y');
			}
			$expiryDate="31/12/$validity";
			
			$mdate=date('d/m/Y');
		
			$permitNo=randomNumber();				
			$expiryDate="31/12/{$validity}";


			$sql="set dateformat dmy if not exists(select 1 from permits where ServiceHeaderID=$ApplicationID and year(ExpiryDate)=year(getdate()))  
			 
				insert into Permits(permitNo,ServiceHeaderID,Validity,ExpiryDate,CreatedBy,InvoiceHeaderID) 
				values('$permitNo',$ApplicationID,'$validity','$expiryDate','$UserID','$InvoiceHeaderID')";

			//echo $sql; exit;
				
			$s_result1 = sqlsrv_query($db, $sql);
			if ($s_result1)
			{

				return renewPermit($db,$ApplicationID,$cosmasRow);
			}	        
	    } 	     
	}

?>
