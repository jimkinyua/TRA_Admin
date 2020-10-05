<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
require_once('GlobalFunctions.php');
require_once('county_details.php');
require_once('utilities.php');

if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];
if (isset($_REQUEST['ApplicationID'])) { $ApplicationID = $_REQUEST['ApplicationID']; }

$plotno='';
$lrn='';
$ServiceHeaderType='';
$CustomerName='';
$Authority='96';

$WaiverPeriod=0;
$PenaltyWaived=0;

$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );


//get the customer Details

if (isset($_REQUEST['save']) && $_REQUEST['NextStatus']!='')
{
	$ApplicationID=$_REQUEST['ApplicationID'];
	$CustomerID=$_REQUEST['CustomerID'];
	$CurrentStatus=$_REQUEST['CurrentStatus'];
	$NextStatus=$_REQUEST['NextStatus'];
	$Notes=$_REQUEST['Notes'];
	$NextStatusID=$NextStatus;
	$AnualRent=$_REQUEST['RatesPayable'];
	$BillAmount=$_REQUEST['Balance'];
	$plotno=$_REQUEST['plotno'];
	$lrn=$_REQUEST['lrn'];
	$Authority=$_REQUEST['authority'];
	$upn=$_REQUEST['upn'];
	$PenaltyWaived=$_REQUEST['PenaltyWaived'];
	
	$Penalty=0;
	$PhysicalAddress='';
	
	$AnualRent=str_replace(',','',$AnualRent);
	$BillAmount=str_replace(',','',$BillAmount);
	
	//print_r($_REQUEST);
	/*exit; */
	
	$lrn=trim($lrn);
	$plotno=trim($plotno);
	$records=0;
	
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

	$BillYear=date('Y');
	$mDescription='Bill '.$BillYear;

	//check if there is a waiver declared
	$sql="select 1 from WaiverPeriods where getdate()>=StartDate and getDate()<=EndDate";
	$s_result = sqlsrv_query($db, $sql,$params,$options);

	$rows=sqlsrv_num_rows($s_result);
	if($rows>0){
		$WaiverPeriod=1;
	}


	if($Authority==''){
		$msg="The plot has no Local Authority set. The Customer must state between Municipality and Wareng";			
	}else
	{
		
		$ex='0';
		$sql="select 1 Ex from land where lrn=ltrim('$lrn') and plotno=ltrim('$plotno') and LocalAuthorityID='$Authority'";
		$exist=sqlsrv_query($db,$sql);
		//echo $sql;
		if ($exist)
		{	
			//echo 'invoices';
			while ($row = sqlsrv_fetch_array( $exist, SQLSRV_FETCH_ASSOC))
			{		
				$ex='1';
				//$upn=$row['upn'];
			}
		}
		
		if ($ex=='0'){
			$msg="The Plot is not Registered, please Register";
			//echo $sql;
			//return;
		} else
			
		{		
			$sql="if not exists
			(select sbc.SubCountyName,sbc.SubCountyID,w.WardID,isnull(w.WardName,'')WardName,isnull(bz.ZoneName ,'')ZoneName
			from ServiceHeader sh
			join Customer c on sh.CustomerID=c.CustomerID				
			join BusinessZones bz on c.BusinessZone=bz.ZoneID
			join Wards w on bz.WardID=W.WardID
			join subcounty sbc on w.SubCountyID=sbc.SubCountyID	
			where sh.ServiceHeaderID='$ApplicationID') 
			begin
				select sbc.SubCountyName,sbc.SubCountyID,w.WardID,isnull(w.WardName,'')WardName,isnull(bz.ZoneName ,'')ZoneName
				from ServiceHeader sh			
				join BusinessZones bz on sh.BusinessZoneID=bz.ZoneID
				join Wards w on bz.WardID=W.WardID
				join subcounty sbc on w.SubCountyID=sbc.SubCountyID	
				where sh.ServiceHeaderID='$ApplicationID'
			end else
			begin
				select sbc.SubCountyName,sbc.SubCountyID,w.WardID,isnull(w.WardName,'')WardName,isnull(bz.ZoneName ,'')ZoneName
				from ServiceHeader sh
				join Customer c on sh.CustomerID=c.CustomerID				
				join BusinessZones bz on c.BusinessZone=bz.ZoneID
				join Wards w on bz.WardID=W.WardID
				join subcounty sbc on w.SubCountyID=sbc.SubCountyID	
				where sh.ServiceHeaderID='$ApplicationID'
			end";
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
					$Zone=$row['ZoneName'];
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
				while ($row = sqlsrv_fetch_array($invoices, SQLSRV_FETCH_ASSOC))
				{		
					$SC=str_pad($row['SubCountyID'],2,'0',STR_PAD_LEFT);
					$WD=str_pad($row['WardID'],2,'0',STR_PAD_LEFT);
					$ICount=str_pad($row['InvoiceCount'],4,'0',STR_PAD_LEFT);
					
					$InvoiceNo=$SC.$WD.$ICount;
				}
			}					
			
			$Location=$SubCounty.'/'.$WardName.'/'.$Zone;
			
			$Description='(Block '.$lrn.'Plot No: '.$plotno.'UPN: '.$upn.'),'.$PhysicalAddress;
			
			// $s_sql="if exists (select CustomerID from land where upn=$upn and CustomerID in (select CustomerID from Customer)) 
			// 	select * from Customer where CustomerID in (select CustomerID from land where upn=$upn) else
			// 	select * from Customer where CustomerID=$CustomerID";

			$s_sql="select * from Customer where CustomerID=$CustomerID";

			$s_result=sqlsrv_query($db,$s_sql);
			//echo $s_sql;
			if ($s_result)
			{					
				while ($row = sqlsrv_fetch_array($s_result, SQLSRV_FETCH_ASSOC))
				{			
					$CustomerEmail=$row['Email'];
					$CustomerID=$row['CustomerID'];
					$CustomerName=$row['CustomerName'];					
				}
			}
			
			$s_sql="select ServiceStatusID from ServiceStatus where ServiceStatusID='$NextStatus'";
			$s_result=sqlsrv_query($db,$s_sql);

			if ($s_result){
				while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
				{			
					$NextStatusID=$row['ServiceStatusID'];
				}
			}
			
			$initQry="Insert into ServiceApprovalActions(ServiceHeaderID,ServiceStatusID,NextServiceStatusID,Notes,CreatedBy) 
			Values ($ApplicationID,$CurrentStatus,$NextStatusID,'$Notes','$UserID')";	
			
			$s_result = sqlsrv_query($db, $initQry);
			
			if ($s_result) 
			{	
				
				if ($NextStatusID=='')
				{
					$msg='Next Status Error';
					$sawa=false;
				}else if($NextStatusID==5)
				{
					
					if(sqlsrv_begin_transaction($db)===false)
					{
						$msg=sqlsrv_errors();
						$Sawa=false;
					}	

					$InvoiceNo='';
					
					
					$InvoiceHeaderID="";
					$InvoiceDate= date("d/m/Y");
					$Chargeable=0;
					$Sawa=true;
					$msg='';//Approval Successful';		
				
					
					$s_sql="select sh.ServiceID
					 from  serviceheader sh where sh.ServiceHeaderID=$ApplicationID";
					
					$s_result=sqlsrv_query($db,$s_sql);
					
					if ($s_result)
					{						
						while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
						{						
							$ServiceID='1603';//$row['ServiceID'];
						}
					}else
					{
						DisplayErrors();
					}

					if ($BillAmount<=0)
					{
						$msg="The Plot has no billable balance, it is cleared";
					}	
					else if ($AnualRent<=0)
					{
						$msg="The cost of the service is not set, the process therefore aborts";
					}else
					{					
						$BillAmount=str_replace(',','',$BillAmount);
						$BillAmount=$BillAmount<=0?$AnualRent:$BillAmount;

						$BillAmount=$BillAmount-$PenaltyWaived;

						$FormID=2;


						$s_sql="set dateformat dmy insert into ServiceHeader (CustomerID,ServiceID,FormID,ServiceStatusID,ServiceHeaderType,SubSystemID,BusinessZoneID,WardID,CreatedBy) 
						Values('$CustomerID','$ServiceID','$FormID','$NextStatusID','1','$SC','$ZoneID','$WD','$CreatedUserID') 
						SELECT SCOPE_IDENTITY() AS ID";



						$s_result1 = sqlsrv_query($db, $s_sql);

						if($s_result1){
							$ApplicationID=lastid($s_result1);
						}

						//echo $InvoiceHeaderID; exit;

						$s_sql="set dateformat dmy insert into InvoiceHeader (InvoiceDate,InvoiceNo,ServiceHeaderID,CustomerID,Description,Amount,CreatedBy) 
						Values('$InvoiceDate','$InvoiceNo','$ApplicationID',$CustomerID,'$Description','$BillAmount','$UserID') 
						SELECT SCOPE_IDENTITY() AS ID";


						$s_result1 = sqlsrv_query($db, $s_sql);

						if($s_result1){

						}else{
							DisplayErrors();
						}
	
						if ($s_result1)
						{	

							$InvoiceHeaderID=lastid($s_result1);

							
				
							$s_sql="set dateformat dmy insert into InvoiceLines (InvoiceHeaderID,ServiceHeaderID,ServiceID,Description,Amount,CreatedBy) 
									Values($InvoiceHeaderID,$ApplicationID,$ServiceID,'$Description',$BillAmount,'$UserID')";						
							$s_result2 = sqlsrv_query($db, $s_sql);

							if ($s_result2)
							{
								$Sawa=false;
							}else
							{
								$Sawa=false;
							}
							
							$s_sql="Update LandReceipts set BillSent=1,InvoiceNo='$InvoiceHeaderID' where LaifomsUPN='$upn' and LocalAuthorityID='$Authority' and DocumentNo='$mDescription'";						
							$s_result3 = sqlsrv_query($db, $s_sql);
							//echo $s_sql;
							if ($s_result3)
							{
								$Sawa=false;
							}else
							{
								$Sawa=false;
							}

							$s_sql="select iif(Balance-(PenaltyBalance+GroundRentBalance+OtherChargesBalance)<=RatesPayable,
									Balance-(PenaltyBalance+GroundRentBalance+OtherChargesBalance),RatesPayable) as CurrentYear, 
									Balance-(PenaltyBalance+GroundRentBalance+OtherChargesBalance)-RatesPayable RatesArrears,
									GroundRentBalance,OtherChargesBalance,PenaltyBalance as Penalty  
									from land where upn='$upn' and LocalAuthorityID='$Authority'";									
										
							$s_result3 = sqlsrv_query($db, $s_sql);
							//echo $s_sql.'<br>';
							while($rw=sqlsrv_fetch_array($s_result3,SQLSRV_FETCH_ASSOC))
							{
								$CurrentYear=(double)$rw['CurrentYear'];
								$RatesArrears=(double)$rw['RatesArrears']<0?0:(double)$rw['RatesArrears'];
								
								$Penalty=$rw['Penalty'];

								if($WaiverPeriod==1){   //if it is an active penalty period, apply it in the invoice
									$Penalty=0;
								}									

								$GroundRentBalance=$rw['GroundRentBalance'];
								$OtherChargesBalance=$rw['OtherChargesBalance'];

								//echo $RatesArrears;

								$InvoiceTotal=$CurrentYear+$RatesArrears+$Penalty+$GroundRentBalance+$OtherChargesBalance;

								//current year
								$sql="insert into LandInvoices(InvoiceHeaderID,lrn,plotno,upn,LocalAuthorityID,LandPropertyID,Amount,Month,Year)
									  Values ('$InvoiceHeaderID','$lrn','$plotno','$upn','$Authority','1','$CurrentYear',month(getDate()),year(getdate()))";
								
								$s_result5=sqlsrv_query($db,$sql);
								 if(!$result){
									echo "Current Year Failed";
								}									
								 //Rent Arrears
								$sql="insert into LandInvoices(InvoiceHeaderID,lrn,plotno,upn,LocalAuthorityID,LandPropertyID,Amount,Month,Year)
									  Values ('$InvoiceHeaderID','$lrn','$plotno','$upn','$Authority','2','$RatesArrears',month(getDate()),year(getdate()))";
								$result=sqlsrv_query($db,$sql);
								if(!$result){
									echo 'Rent Arrears Failed';
								}
								
								//Penalty Arrears
								$sql="insert into LandInvoices(InvoiceHeaderID,lrn,plotno,upn,LocalAuthorityID,LandPropertyID,Amount,Month,Year)
									  Values ('$InvoiceHeaderID','$lrn','$plotno','$upn','$Authority','3','$Penalty',month(getDate()),year(getdate()))";
								$result=sqlsrv_query($db,$sql);
								if(!$result){
									echo 'penalty Failed';
								}
								
								//GroundRent
								$sql="insert into LandInvoices(InvoiceHeaderID,lrn,plotno,upn,LocalAuthorityID,LandPropertyID,Amount,Month,Year)
									  Values ('$InvoiceHeaderID','$lrn','$plotno','$upn','$Authority','4','$GroundRentBalance',month(getDate()),year(getdate()))";
								$result=sqlsrv_query($db,$sql);
								if(!$result){
									echo 'penalty Failed';
								}
								
								//Penalty Arrears
								$sql="insert into LandInvoices(InvoiceHeaderID,lrn,plotno,upn,LocalAuthorityID,LandPropertyID,Amount,Month,Year)
									  Values ('$InvoiceHeaderID','$lrn','$plotno','$upn','$Authority','5','$OtherChargesBalance',month(getDate()),year(getdate()))";
								$result=sqlsrv_query($db,$sql);
								if(!$result){
									echo 'penalty Failed';
								}
							}
							
						} 							
						//echo '<br>'.$s_result1 .'<br>'. $s_result2 .'<br>'.  $s_result3 .'<br>'. $s_result4;
						
						if($s_result1)
						{
							$Remark=$Description;

							$ViewBtn  = '<a href="reports.php?rptType=Invoice&ServiceHeaderID='.$ApplicationID.'&InvoiceHeaderID='.$InvoiceHeaderID.'" target="_blank">Click to View</a>';

							$msg="Invoice No $InvoiceHeaderID Created Successfully. $ViewBtn";

							//$msg="Invoice Created successfully";
							
							// $feedBack=createInvoice($db,$ApplicationID,$cosmasRow,$Remark,$CustomerName,$InvoiceHeaderID);
							// $msg=$feedBack[1];
							// $mail=true;

							$rst=SaveTransaction($db,$UserID," Created Invoice Number ".$InvoiceHeaderID);

							// if ($rst[0]==0){
							// 	$msg=$rst[1];
							// }else{
							// 	$msg=$rst[1];
							// }
							
							sqlsrv_commit($db);
							$Sawa=true;
						}else
						{
							sqlsrv_rollback($db);
							$Sawa=false;
						}
					}				
					
				}else
				{				
					$Sawa=false;
				}		
				//move to the next status
				if($Sawa==true)
				{			
					$sql="Update ServiceHeader set ServiceStatusID=$NextStatus where ServiceHeaderID=$ApplicationID";	
					$s_result = sqlsrv_query($db, $sql);	
				}			
			}else
			{
				DisplayErrors();
				$msg="Transaction failed to initialize";
			}
		}
	}

	if ($NextStatus=='')
	{
		break;		
	}


	$sql="select (select value Authority from dbo.fnFormData($ApplicationID) where FormColumnID='13270') Authority,
		 (select value PhysicalAddress from dbo.fnFormData($ApplicationID) where FormColumnID=13272) PhysicalAddress";
	$result=sqlsrv_query($db,$sql);
	if ($result)
	{	
		while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
		{
			$Authority=$row['Authority'];
			$PhysicalAddress=$row['PhysicalAddress'];
		}
	} 
	
	
	
}
if (isset($_REQUEST['register']))
{
	$Authority=96;
	$LaifomsUPN='';
	$Size=0;
	$excess=0;
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );	
	$Upn=0;
	
	if (isset($_REQUEST['lrno'])) { $lrno = $_REQUEST['lrno']; }
	if (isset($_REQUEST['plotno'])) { $plotno = $_REQUEST['plotno']; }
	if (isset($_REQUEST['MotherPlotNo'])) { $MotherPlotNo = $_REQUEST['MotherPlotNo']; }
	if  (isset($_REQUEST['SiteValue'])) { $SiteValue = $_REQUEST['SiteValue']; }
	if  (isset($_REQUEST['GroundRent'])) { $GroundRent = $_REQUEST['GroundRent']; }
	if  (isset($_REQUEST['GroundRentBalance'])) { $GroundRentBalance = $_REQUEST['GroundRentBalance']; }
	if  (isset($_REQUEST['OtherCharges'])) { $OtherCharges = $_REQUEST['OtherCharges']; }
	if  (isset($_REQUEST['OtherChargesBalance'])) { $OtherChargesBalance = $_REQUEST['OtherChargesBalance']; }
	if  (isset($_REQUEST['Penalty'])) { $Penalty = $_REQUEST['Penalty']; }
	if  (isset($_REQUEST['TitleYear'])) { $TitleYear = $_REQUEST['TitleYear']; }
	if  (isset($_REQUEST['ApplicationID'])) { $ApplicationID = $_REQUEST['ApplicationID']; }
	if  (isset($_REQUEST['Authority'])) { $Authority = $_REQUEST['Authority']; }
	if  (isset($_REQUEST['FarmID'])) { $FarmID = $_REQUEST['FarmID']; }
	if  (isset($_REQUEST['OwnerName'])) { $OwnerName = $_REQUEST['OwnerName']; }
	if  (isset($_REQUEST['CustomerID'])) { $CustomerID = $_REQUEST['CustomerID']; }

	$sql="select c.CustomerName 
	from customer c 
	join ServiceHeader sh on sh.CustomerID=c.CustomerID 
	where sh.ServiceHeaderID=$ApplicationID";

	$result=sqlsrv_query($db,$sql);
	while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
		$OwnerName=$row['CustomerName'];
	}
	
	//  print_r($_REQUEST);
	// exit; 
	
		$Balance=0;
		$PenaltyBalance=0;
		$sql="select 
			(select Value from dbo.fnFormData($ApplicationID) where FormColumnID='13270') Authority,
			(select Value from dbo.fnFormData($ApplicationID) where FormColumnID='135') Size";
		$rst=sqlsrv_query($db,$sql);
		while ($rw = sqlsrv_fetch_array($rst, SQLSRV_FETCH_ASSOC)){			
			$Size=$rw['Size'];
		}	
		
		if($Authority==96 || $Authority==800){
			$RatesPayable=0.02*(double)$SiteValue;					
		}else{
			$RatesPayable=60;
			$excess=0;
			$acres=(double)$Size*2.4765;
			if((double)$acres>5){
				$excess=ceil((double)$acres-5)*10;				
			}
			$RatesPayable+=(double)$excess; 
		}
		
		$PenaltyPayable=.03*(double)$RatesPayable;	
		
		if ($Authority=='0' || $Authority==''){
			$msg="The LocalAuthority for the plot is not properly set";
		}else
		{
			$Balance=(double)$PenaltyBalance+(double)$GroundRentBalance+(double)$OtherChargesBalance;
			$sql="if exists(select 1 from Land where lrn='$lrno' and plotno='$plotno') 
				BEGIN 
					Update land set RatesPayable=$RatesPayable,LocalAuthorityID='$Authority',TitleYear='$TitleYear',SiteValue='$SiteValue',OtherCharges='$OtherCharges',GroundRent='$GroundRent',OtherChargesBalance='$OtherChargesBalance',GroundRentBalance='$GroundRentBalance',Balance=0,CustomerID='$CustomerID',LaifomsOwner='$OwnerName',FirmID='$FarmID' where lrn='$lrno' and plotno='$plotno' 
				END 
			else 
			BEGIN			
				insert into land (lrn,PlotNo,RatesPayable,SiteValue,GroundRent,OtherCharges,TitleYear,Balance,PenaltyBalance,LocalAuthorityID,LaifomsOwner,CustomerID,FirmID) values('$lrno','$plotno','$RatesPayable','$SiteValue','$GroundRent','$OtherCharges','$TitleYear','$Balance','$PenaltyBalance','$Authority','$OwnerName','$CustomerID','$FarmID' ) 
			END SELECT SCOPE_IDENTITY() AS ID";
			
			// echo $sql;
			//$msg = "Rates Payable: ".$RatesPayable.'<br> Penalty Payable: '.$PenaltyPayable;	
			
			$result=sqlsrv_query($db,$sql);
			if($result){

				$Upn=lastid($result);

				$rst=SaveTransaction($db,$CreatedUserID,"Registered/Modified a New Plot,Upn Number ".$Upn);

				if ($rst[0]==0){
					$msg=$rst[1];
				}else{
					$msg=$rst[1];
				}

				$msg="Plot registered and billed successfully";			
			}else{
				DisplayErrors();
				echo $sql;
			}
		}

		
	//}	
}

//authority

$sql="select value from dbo.fnFormData($ApplicationID) WHERE FormColumnID='13270'";
$result=sqlsrv_query($db,$sql);
while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
{
	$Authority=$rw['value'];
}

if($Authority==0){
	$Authority=96;
}

$sql="select value from dbo.fnFormData($ApplicationID) WHERE FormColumnID='12265'";
$result=sqlsrv_query($db,$sql);
while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
{
	$laifomsUPN=$rw['value'];
}

$s_sql="select c.*,sh.ServiceStatusID,sh.ServiceHeaderType,s.ServiceName,S.ServiceCategoryID,la.plotno,la.lrn, 
		l.upn,l.LocalAuthorityID,isnull(l.laifomsUPN,l.upn)upnn,l.RatesPayable,l.Balance,la.Registered,l.PenaltyBalance,
		l.GroundRentBalance,l.OtherChargesBalance 
		from ServiceHeader sh  
		join services s on sh.ServiceID=s.ServiceID 
		left join LandApplication la on la.ServiceHeaderID=sh.ServiceHeaderID 	
		left join Land l on la.lrn=l.lrn AND LA.plotno=l.plotno 
		left join LandOwner lo on la.lrn=lo.lrn AND LA.plotno=lo.plotno
		join Customer c on isnull(lo.CustomerID,sh.CustomerID)=c.CustomerID 
		where sh.ServiceHeaderID=$ApplicationID and (l.localauthorityID is null or l.localauthorityID=$Authority) 
		and (l.LaifomsUPN is null or l.laifomsUPN='$laifomsUPN' or '$laifomsUPN'='')";

$s_result=sqlsrv_query($db,$s_sql);
echo $s_sql; 
exit;

	if ($s_result){	
		while ($row = sqlsrv_fetch_array($s_result, SQLSRV_FETCH_ASSOC)){			
			$BusinessType=$row['CustomerTypeName'];
			$CustomerID=$row['CustomerID'];
			$CustomerName=$row['CustomerName'];
			$ServiceID=$row['ServiceID'];
			$ServiceName=$row['ServiceName'];
			$CurrentStatus=$row['ServiceStatusID'];
			$ServiceCategoryID=$row['ServiceCategoryID'];
			$ServiceHeaderType=$row['ServiceHeaderType'];
			$RegNo=$row['RegistrationNumber'];
			$plotno=trim($row['plotno']);
			$lrn=trim($row['lrn']);
			$upn=$row['upn'];
			$PostalAddress=$row['PostalAddress'];
			$PostalCode=$row['PostalCode'];
			$Pin=$row['PIN'];
			$Vat=$row['VATNumber'];
			$Town=$row['Town'];
			$Country=$row['CountyID'];
			$Telephone1=$row['Telephone1'];
			$Mobile1=$row['Mobile1'];
			$Telephone2=$row['Telephone2'];
			$Mobile2=$row['Mobile2'];
			$Mobile1=$row['Mobile1'];
			$url=$row['Url'];
			$Email=$row['Email'];
			$Balance=$row['Balance'];
			$RatesPayable=$row['RatesPayable'];
			$Registered=$row['Registered'];
			$PenaltyBalance=$row['PenaltyBalance'];
			$GroundRentBalance=$row['GroundRentBalance'];
			$OtherChargesBalance=$row['OtherChargesBalance'];			
		}

		
		$params = array();
		$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

		//check if there is a waiver declared
		$sql="select 1 from WaiverPeriods where getdate()>=StartDate and getDate()<=EndDate";
		$s_result = sqlsrv_query($db, $sql,$params,$options);

		$rows=sqlsrv_num_rows($s_result);
		if($rows>0){
			$WaiverPeriod=1;
		}
		

		$qry="exec spBillPlot_test5 '$upn'";
			
		$s_result = sqlsrv_query($db, $qry);

		$qry="exec spRefreshLandStatement5 '$upn'";
		$s_result = sqlsrv_query($db, $qry);

		// //$sql="select * from  fnLastPlotRecord ($upn)";

		// $query=sqlsrv_query($db,$sql);
		// while($row=sqlsrv_fetch_array($query,SQLSRV_FETCH_ASSOC))
		// {
		// 	//$TotalBalance=(double)$row['Balance']<(double)$row['PnAmount']?$row['PnAmount']:$row['Balance'];
		// 	$TotalBalance=(double)$row['Balance'];
		// 	$PenaltyBalance=$row['PenaltyBalance'];

		// 	$sql="update land set balance=$TotalBalance,PenaltyBalance=$PenaltyBalance where upn=$upn";
		// 	$query=sqlsrv_query($db,$sql);
		// 	if($query){
				
		// 	}else{
		// 		DisplayErrors();
		// 	}
		// }

		// $qry="exec spRefreshLandStatement '$upn'";
		// $s_result = sqlsrv_query($db, $qry);

		$sql="select RatesPayable,PrincipalBalance,PenaltyBalance,isnull(Balance,PrincipalBalance) Balance,GroundRentBalance,OtherChargesBalance from land where lrn='$lrn' and plotno='$plotno' and upn='$upn'";
		//echo $sql;
		$result=sqlsrv_query($db,$sql);
		if ($result)
		{
			//echo 'nje';	
			while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
			{
				
				$Balance=$row['Balance'];
				$RatesPayable=$row['RatesPayable'];
				$PenaltyBalance=$row['PenaltyBalance'];
				if($WaiverPeriod==1){
					$PenaltyWaived=$PenaltyBalance;
				}
			}
		} else{
			//echo 'outside';
		}
	}
	
	
	$sql="select FormColumnName ColumnName,Value from fnFormData($ApplicationID)";
	
//echo $sql;

	$result=sqlsrv_query($db,$sql);
	while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
	{
		$mdata.='<tr>
			<td>'.$row['ColumnName'].'</td>
			<td>'.$row['Value'].'</td>
			</tr>';
	}

?>
    <link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/prettify/prettify.css" rel="stylesheet">
		<script>
		//alert('hre');
	</script>
<body class="metro">
	<form>
	<div class="example">        
		<legend>Applicant Details</legend>
		<table width="75%" class="table striped hovered dataTable">
			<tr>
				<td colspan="2" class="text-center" style="color:#F00"><?php echo $msg; ?></td>
			</tr>
			<tr>
				<td width="50%">ApplicationID: </td>
				<td width="50%"><?php echo $ApplicationID; ?> </td>
			</tr>			
			<tr>
				<td width="50%">Owner Name: </td>
				<td width="50%"><?php echo $CustomerName; ?> </td>
			</tr>
			<tr>
				<td width="50%">Block LR Number: </td>
				<td width="50%"><?php echo $lrn; ?> </td>
			</tr>
			<tr>
				<td width="50%">Plot Number: </td>
				<td width="50%"><?php echo $plotno; ?> </td>
			</tr>			
			<tr>
				<td width="50%">Current Balance </td>
				<td width="50%"><b><?php echo number_format($Balance,2); ?></b></td>
			</tr>
			<tr>
				<td width="50%">Waiver to Apply </td>
				<td width="50%"><b><?php echo number_format($PenaltyWaived,2); ?></b></td>
			</tr>
			<tr>
				<td width="50%">Anual rates </td>
				<td width="50%">
					  <div class="input-control text" data-role="input-control">
						  <input name="RatesPayable" type="text" id="RatesPayable" value="<?php echo number_format($RatesPayable,2); ?>" placeholder="" disabled="disabled">						  
					  </div> 				
				</td>
			</tr>	
			<tr>
				<td></td>
				<td>
					<a href='statement.php?popupwindow&lrn=<?php echo $lrn ?>&plotno=<?php echo $plotno ?>&upn=<?php echo $upn ?>&authority=<?php echo $Authority ?>' class='popupwindow' target='_blank'>Rates Statement</a>
				</td>
			</tr>
            <tr>
              <td width="50%">Approval Action</td>
               <td width="50%">
			        <div class="input-control select" data-role="input-control">
						<select name="NextStatus"  id="NextStatus">
							<option value="5" selected>Approve</option>
							<option value="6">Reject</option>
					  </select>                   
					</div>
			   </td>   
            </tr>

			<tr>
				<td width="50%">Notes
				</td>                  
				<td width="50%">
				  <div class="input-control textarea" data-role="input-control">
					<textarea name="Notes" type="textarea> id="Notes" placeholder=""><?php //echo $Notes; ?></textarea>  
				  </div>				
				</td>   
			</tr>
			<tr>
				<td colspan="2">
					<legend>Form Details</legend> 		
					<table class="table striped hovered dataTable" width="100%">
						<?php
							echo $mdata;
						?>
					</table> 
				</td>
			</tr>
			<tr>
			<td width="50%"></td>
			<td>

				<?php
                 	$PageID=53;
					$myRights=getrights($db,$CreatedUserID,$PageID);						
					if ($myRights['Add']==1){  ?>

				<input name="Button" type="button" onclick="loadmypage('land_invoicing.php?save=1&ApplicationID=<?php echo $ApplicationID ?>&CustomerID=<?php echo $CustomerID ?>&CurrentStatus=<?php echo $CurrentStatus ?>&ServiceHeaderType=<?php echo $ServiceHeaderType ?>&NextStatus='+this.form.NextStatus.value+'&RatesPayable='+this.form.RatesPayable.value+'&Balance=<?php echo $Balance; ?>&PenaltyWaived=<?php echo $PenaltyWaived; ?>&plotno=<?php echo $plotno ?>&lrn=<?php echo $lrn ?>&upn=<?php echo $upn ?>&authority=<?php echo $Authority ?>&Notes='+this.form.Notes.value,'content','loader','listpages','','LAIFOMS_LAND','<?php echo $ApplicationID ?>')" value="Bill">
								
				<input name="Button" type="button" onclick="loadpage('plots.php?add=1&ApplicationID=<?php echo $ApplicationID ?>&CustomerID=<?php echo $CustomerID ?>&OwnerName=<?php echo $CustomerName ?>&register=1','content','<?php echo $ApplicationID ?>')" value="Register">
				<?php } ?>
			</td>
			</tr>			
		</table>		
		
	</div>
	</form>
</body>


