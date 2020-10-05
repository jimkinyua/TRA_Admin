<?php
	require 'DB_PARAMS/connect.php';
	require_once('utilities.php');
	require_once('GlobalFunctions.php');
	require_once('county_details.php');

	function bill_house($db,$EstateID,$HouseNumber,$BillDate,$ApplicationID)
	{
		$DateReceived;
		$MonthsDue=0;
		$RequestResult='';
		$sql="select top 1 iif([Description]='Monthly Rent',DocumentNo,[Description])LastBillNumber,DateReceived from HouseReceipts 
		where EstateID='$EstateID' and HouseNumber='$HouseNumber' and ([Description]='Monthly Rent' or [Description] like 'Bill%')
		order by DateReceived desc";
		$s_result=sqlsrv_query($db,$sql);
		//echo $sql;
		if ($s_result)
		{					
			while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
			{			
				$LastBillNumber=$row['LastBillNumber'];
				$LastDate=$row['DateReceived'];
			}
		}
	
		//$date = date_create($LastDate);
		$BillYear=date('Y');
		$BillMonth=(int)date('m');
		$BillNumber='Bill '.$BillMonth.'-'.$BillYear;	
		
		$LastDate=date_format(date_create($LastDate),'d/m/Y');
		$CurrentDate=date_format(date_create(date()),'d/m/Y');
		
		$sql="set dateformat dmy Select DateDiff(m,'$LastDate',getDate()) MonthsDue";
		$s_result=sqlsrv_query($db,$sql);
		//echo $sql;
		while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
		{			
			$MonthsDue=$row['MonthsDue'];
		}

		for($i=1;$<=$MonthsDue;$i++)
		{
			$dateValue='02/03/2015';
			$time=strtotime($dateValue);
			$month=date("d",$time);
			$year=date("Y",$time);
				
			$BillYear=date('Y');
			$BillMonth=(int)date('m');
			$BillNumber='Bill '.$BillMonth.'-'.$BillYear;				
		}

		echo 'LastBill: '.$LastBillNumber.'New Bill: '.$BillNumber.' Last Bill Date: '.$LastDate.' Interval: '.$MonthsDue;				
	    exit;
		if ($BillNumber==$LastBillNumber)
		{
			$msg="This house has been billed for this month";
			$RequestResult[0]="201";
			$RequestResult[1]=$msg;
			$RequestResult[2]=$sql;
			return $RequestResult;
		}else
		{
			$msg="We can proceed";
			$RequestResult[0]="200";
			$RequestResult[1]=$msg;
			$RequestResult[2]='';
			return $RequestResult;			
		}
		
		$Sawa=true;
		$msg='';						
			
		
		$sql="select MonthlyRent RentAmount, Balance from Tenancy where EstateID='$EstateID' and HouseNumber='$HouseNumber'";
		$result=sqlsrv_query($db,$sql);
		while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
			$RentAmount=$row['RentAmount'];
			$Balance=$row['Balance'];
		}			
			
		$BillAmount=$RentAmount;							
		$BillDate=date('m/d/Y');
		$Penalty=0;
		
		$Balance=$Balance+$BillAmount;
		
		if(sqlsrv_begin_transaction($db)===false)
		{
			$msg=sqlsrv_errors();
			$Sawa=false;
		}			
		
		$sql="Set dateformat dmy insert into HouseReceipts (uhn,EstateID,HouseNumber,[Description],DocumentNo,Amount,Balance) 
		Values('$uhn','$EstateID','$HouseNumber','$BillNumber','$InvoiceHeaderID','$BillAmount','$Balance')";
							
		$s_result3 = sqlsrv_query($db, $sql);
		if ($s_result3)
		{
			$sql="Set dateformat dmy update Tenancy set Balance=$Balance where HouseNumber='$HouseNumber' and EstateID='$EstateID'";													
			$s_result3 = sqlsrv_query($db, $sql);
			if ($s_result3)
			{
				$msg="Bill Done Successfully";
				$Sawa=true;
			}else
			{									
				$Sawa=false;
				echo $sql;
				DisplayErrors();
			}
			
			
			$sql="set dateformat dmy update Tenancy set CustomerID='$CustomerID' where HouseNumber='$HouseNumber' and EstateID='$EstateID'";													
			$s_result4 = sqlsrv_query($db, $sql);
			if ($s_result4)
			{
				//echo 'landowner';
				$Sawa=true;
			}else
			{							
				$Sawa=false;
			}
		}else
		{						
			$Sawa=false;
		}			
		
		//echo 'three<br>'.  $s_result3 .'four<br>'. $s_result4;
		
		//echo 'Nje';
		if($s_result4 and $s_result3)
		{						
			sqlsrv_commit($db);
			$msg="Bill Done Successfully";
			$RequestResult[0]="200";
			$RequestResult[1]=$msg;
			$RequestResult[2]='';				
			$Sawa=true;							
			$Remark=$Description;
			$feedBack='';//createInvoice($db,$ApplicationID,$cosmasRow,$Remark);
			$msg=$feedBack[1];
			$mail=true;					

		}else
		{
			sqlsrv_rollback($db);
			$msg="Bill Not Done";
			$RequestResult[0]="201";
			$RequestResult[1]=$msg;
			$RequestResult[2]=$sql;				
			$Sawa=false;
		}
			
		return $RequestResult;
		
	}
	function getInvoiceNo($db,$ApplicationID)
	{
		$InvoiceNo='';
		$INo='';
		$sql="select sbc.SubCountyName,sbc.SubCountyID,w.WardID,isnull(w.WardName,'')WardName,isnull(bz.ZoneName ,'')ZoneName
				from ServiceHeader sh 
				join customer c on sh.CustomerID=c.CustomerID 
				left join subcounty sbc on c.SubCounty=sbc.SubCountyID
				left join Wards w on c.Ward=w.WardID
				left join BusinessZones bz on c.BusinessZone=bz.ZoneName
				where sh.ServiceHeaderID='$ApplicationID'";
				
		$s_result=sqlsrv_query($db,$sql);
		
		$Ward='';
		$WD='';
		$SC='';
		
		if ($s_result)
		{					
			while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
			{			
				$SubCounty=$row['SubCountyName'];
				$Ward=$row['WardName'];
				$WardID=$row['WardID'];
				
				$SC=str_pad($SubCountyID,2,'0',STR_PAD_LEFT);
				$WD=str_pad($WardID,2,'0',STR_PAD_LEFT);				
			}
		}

		 $sql="select SubCountyID,WardID,InvoiceCount+1 InvoiceCount from [dbo].[fnInvoiceCount] ($WardID)";
		$invoices=sqlsrv_query($db,$sql);
		//echo $sql;
		if ($invoices)
		{	
			//echo 'invoices';
			while ($row = sqlsrv_fetch_array( $invoices, SQLSRV_FETCH_ASSOC))
			{		
				$SC=str_pad($row['SubCountyID'],2,'0',STR_PAD_LEFT);
				$WD=str_pad($row['WardID'],2,'0',STR_PAD_LEFT);
				$ICount=str_pad($row['InvoiceCount'],4,'0',STR_PAD_LEFT);
				
				$InvoiceNo=$SC.$WD.$ICount;
			}
		} 
		return $InvoiceNo;
	}
?>
