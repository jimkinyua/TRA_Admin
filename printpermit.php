<?php

	require 'DB_PARAMS/connect.php';
	require_once('county_details.php');
	require_once('GlobalFunctions.php');
	require_once('utilities.php');

	if (!isset($_SESSION))
	{
		session_start();   
	}

	//echo 'fgg' ; exit;

	if (date('H')==13 || (date('H') ==16 && date('i')>45) || date('H')>=17) 
	{		
		echo  PrintPermits($db);
	}

	function PrintPermits($db)
	{
		//$sql="Select ServiceHeaderID From vwPayments where ServiceHeaderType=4 and Printed=0 and balance<=0 and ServiceHeaderID=2298716";

		$sql="select 1 from serviceheader where ServiceHeaderType=69";
	    
	 	  $sql="select * from (
				select  ServiceHeaderID 
				from vwPermits where year(ExpiryDate)=year(getdate()) 
				and year(issuedate)=year(getdate()) 
				and Balance<=0 and Printed=0 and ServiceHeaderID=1908904449 and ServiceHeaderID not in (
				select ServiceHeaderID from Permits 
				where year(ExpiryDate)=year(getdate())  
				and year(issuedate)=year(getdate())
				group by ServiceHeaderID
				having count(ServiceHeaderID)>1) ) A

				union

				(Select serviceheaderid From vwPayments 
		where ServiceHeaderType=4 and year(CreatedDate)=year(getdate())
		and balance<=0 and ServiceHeaderID=1908904449 and ServiceHeaderID 
		not in (select ServiceHeaderID from Permits) )  order by 1"; 

		$sql="exec SpPrintPermits";
		
		
		
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
