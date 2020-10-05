<?php 
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
require_once('GlobalFunctions.php');
require_once('county_details.php');

if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$UserID = $_SESSION['UserID'];

if (isset($_REQUEST['msg']))
{
	$msg = $_REQUEST['msg'];	
}

$ApplicationID='';
$CustomerName='';
$CustomerID="";
$ServiceName ='';
$ServiceID='';
$Charges=0;
$Notes='';
$ServiceState="";
$CurrentStatus="";
$NextStatus="";
$Customer;
$SubCountyName;
$BusinessZoneID;
$WardName;
$CustomerType="";
$RegNo="";
$PostalAddress="";
$PostalCode="";
$Pin="";
$Vat="";
$Town="";
$Country="";
$Telephone1="";
$Mobile1="";
$Telephone2="";
$Mobile2="";
$Mobile1="";
$url="";
$Email="";
$ServiceHeaderType="";
$SubSystemID=1;
$ApplicationDate='';
$today=date("d/m/Y");
$DateLine=$cosmasRow['SBPDateline'];
$DateLine=date('d/m/Y',strtotime($DateLine));
$ApplicationDate=date('d/m/Y');	
$BusinessIsOld=0;
$NextStatusID=0;
$PermitCost=0;
$ConservancyCost=0;
$PermitYear=date("Y");
$InvoiceNo=0;

$ServiceCost=0;

$MonthsLate=0;
$sql="set dateformat dmy select datediff(month,'$DateLine',getdate()) Mnths";
$result=sqlsrv_query($db,$sql);

while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
	$MonthsLate=$rw['Mnths'];
}

$today=date('Y-m-d H:i:s');
$FirstDec=date(date('Y')."-12-01 00:00:00");
if($today>$FirstDec){
	$MonthsLate=0;
	$PermitYear=date("Y")+1;
}



if (isset($_REQUEST['ApplicationID'])) 
{
	$ApplicationID = $_REQUEST['ApplicationID']; 
	
	/* $sql="select distinct Value BussinessID from fnFormData ($ApplicationID) where FormColumnID=4184";
	$rslt=sqlsrv_query($db,$sql);
	while($rw=sqlsrv_fetch_array($rslt,SQLSRV_FETCH_ASSOC))
	{
		$BusinessIsOld=1;
	} */
	
	//select BusinessRegistrationNumber from Customer where CustomerID='192978'
	
	$sql="select isnull(BusinessID,'') BusinessID from Customer where CustomerID in (select CustomerId from ServiceHeader where serviceheaderid='$ApplicationID')";
	$rslt=sqlsrv_query($db,$sql);
	while($rw=sqlsrv_fetch_array($rslt,SQLSRV_FETCH_ASSOC))
	{
		if($rw['BusinessID']!==''){
			$BusinessIsOld=1;
		}
	}
	
}

if (isset($_REQUEST['save']))
{
	// echo '<pre>';
	//  print_r($_REQUEST);
	// exit; 
	$ApplicationID=$_REQUEST['ApplicationID'];
	$CustomerID=$_REQUEST['CustomerID'];
	$CurrentStatus=$_REQUEST['CurrentStatus'];
	$Notes=$_REQUEST['Notes'];
	$NextStatusID=5; //$_REQUEST['NextStatus'];
	$InvoiceNo=$_REQUEST['InvoiceNo'];
	$PermitNo=$_REQUEST['PermitNo'];

	
	// print_r($_REQUEST); exit;
	
	$sql="select f.serviceheadertype from Forms f 
	  join ServiceHeader sh on sh.FormID=f.formid 
	  where sh.ServiceHeaderID='$ApplicationID'";
	$s_result=sqlsrv_query($db,$sql);
	//echo $s_sql;
	if ($s_result)
	{					
		while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
		{			
			$ServiceHeaderType=$row['serviceheadertype'];
		}
	}

		
	  
	
	$s_sql="select * from Customer where CustomerID=$CustomerID";
	$s_result=sqlsrv_query($db,$s_sql);
	//echo $s_sql;
	if ($s_result)
	{					
		while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
		{			
			$CustomerEmail=$row['Email'];
			$CustomerName=$row['CustomerName'];
		}
	}
		
	$s_sql="select ServiceStatusID from ServiceStatus where ServiceStatusID='$NextStatusID'";
	//echo $s_sql;
	$s_result=sqlsrv_query($db,$s_sql);

	if ($s_result){
		while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
		{			
			$NextStatusID=$row['ServiceStatusID'];
		}
	}
	
	$initQry="Insert into ServiceApprovalActions(ServiceHeaderID,ServiceStatusID,NextServiceStatusID,Notes,CreatedBy) 
	Values ($ApplicationID,$CurrentStatus,$NextStatusID,'$Notes','$UserID')";	
	//echo 'insert actions';

	$s_result = sqlsrv_query($db, $initQry);
	
	if ($s_result) 
	{
		// die('Status is :'.$NextStatusID);
		
		if ($NextStatusID=='')
		{
			echo 'Step Checking..';
			exit;	
		}		
		
		if($NextStatusID==5)
		{	

			


			// if($InvoiceNo=='' and 1==2) // this condition should be deleted
			// {
				// $msg="You must enter the Invoice Number";
			//}else
			//{
								

				$InvoiceHeader="";
				$ServiceAmount=10000;
				$InvoiceAmount=0;
				$InvoiceDate= date("d/m/Y");
				$Chargeable=0;
				$Sawa=true;
				$msg='';
				//Check whether the service is chargable and the chargeamount
				
				//get the subsystem
				
				// $sql="select fd.ServiceHeaderID,fd.FormColumnID,fd.Value,fc.FormColumnName from 
				// 	FormData fd join FormColumns fc on fd.FormColumnID=fc.FormColumnID
				// 	where fc.FormColumnID=12237 and fd.ServiceHeaderID=$ApplicationID";
				$sql="select fd.LicenceId,fd.FormColumnID,fd.Value,fc.FormColumnName from 
				LicenceRenewalFormData fd join LicenceRenewalColumns fc on fd.FormColumnID=fc.LicenceRenewalFormColumnID
				where  fd.LicenceId=$ApplicationID";
				// echo ($sql);
				// exit;
				$res=sqlsrv_query($db,$sql);
				while($row=sqlsrv_fetch_array($res,SQLSRV_FETCH_ASSOC))
				{
					$SubSystemID=$row['Value'];
				}
			
				
				$s_sql="select sc.amount,s.chargeable,sh.ServiceID,sh.CreatedDate
				 from servicecharges sc 
				 inner join services s on sc.serviceid=s.serviceid 
				 inner join serviceheader sh on sh.serviceid=s.serviceid
				 join FinancialYear fy on sc.FinancialYearId=sc.FinancialYearId
				where sh.ServiceHeaderID=$ApplicationID and fy.isCurrentYear=1 and sc.SubSystemID='$SubSystemID'";
				
				$s_result=sqlsrv_query($db,$s_sql);
				// echo $s_sql;
				if ($s_result)
				{
						
					while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
					{						
						$ServiceID=$row['ServiceID'];
						$Chargeable=$row['chargeable'];						
						
					}
				}else
				{
					DisplayErrors();
				}		
				$Chargeable =10000;
				if ($Chargeable==0) // $ServiceHeaderType!=1
				{
					$msg='The Service is set not to have charges, hence cannot be invoiced';
					$Sawa=false;
				}
				else
				{
				
					//echo 'Service Cost Level Ok';
					$sql1="select * from fnServiceCost($ServiceID,$SubSystemID)";

					$rs=sqlsrv_query($db,$sql1);
					if ($rs)
					{
						while($row=sqlsrv_fetch_array($rs,SQLSRV_FETCH_ASSOC))
						{									
							$ServiceAmount=$row['Amount'];
							$PermitCost=$ServiceAmount;
						}	
					}
					
					if ($ServiceAmount<=0)
					{
						$msg="The cost of the service is not set, the process therefore aborts";
					}else
					{

						if(sqlsrv_begin_transaction($db)===false)
						{
							$msg=sqlsrv_errors();
							$Sawa=false;
						}				
						
						$s_sql="set dateformat dmy insert into InvoiceHeader (InvoiceDate,InvoiceNo,CustomerID,CreatedBy,ServiceHeaderID) Values('$InvoiceDate','$InvoiceNo',$CustomerID,'$UserID','$ApplicationID') SELECT SCOPE_IDENTITY() AS ID";
						$s_result1 = sqlsrv_query($db, $s_sql);
						//echo 'invoiceheader done';		
						if ($s_result1)
						{
							$InvoiceHeader=lastid($s_result1);				
											
							//insert into invoiceLines
				
							$s_sql="set dateformat dmy insert into InvoiceLines (InvoiceHeaderID,ServiceHeaderID,ServiceID,Description,Amount,CreatedBy) 
									Values($InvoiceHeader,$ApplicationID,$ServiceID,' Year $PermitYear',$ServiceAmount,'$UserID')";						
							$s_result2 = sqlsrv_query($db, $s_sql);
								
							$loopOkey=true;
							$PermitCost=$ServiceAmount;
							$InvoiceAmount+=$ServiceAmount;
							if ($s_result2)
							{								
								//Application Fees
								$sql="select s.ServiceID,s.ServiceName, Amount 
										from ServiceCharges sc
										join services s on sc.ServiceID=s.serviceid									
										join FinancialYear fy on sc.FinancialYearId=fy.FinancialYearID										
										and fy.isCurrentYear=1
										and sc.SubSystemId=$SubSystemID
										and sc.serviceid=281";

								//ECHO $sql;

								$s_result = sqlsrv_query($db, $sql);
								while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
								{									
									$ServiceAmount=$row["Amount"];
									$ServiceID=$row['ServiceID'];
									$InvoiceAmount+=$ServiceAmount;
									
									$s_sql="set dateformat dmy insert into InvoiceLines (InvoiceHeaderID,ServiceHeaderID,ServiceID,Amount,CreatedBy) 
											Values($InvoiceHeader,$ApplicationID,$ServiceID,$ServiceAmount,$UserID)";
									$result3 = sqlsrv_query($db, $s_sql);
									if (!$result3)
									{
										///echo 'Carrier Chrges Failed';
										DisplayErrors();
										$loopOkey=false;
										break;
									}else{
										//echo 'Carrier Chrges Worked';
									}									
								}
								if($loopOkey==true)
								{
									$mail=true;																	
								}

								//Conservancy Fees
								$sql1="select * from fnConservancyCost($PermitCost,$SubSystemID)";

								$rs=sqlsrv_query($db,$sql1);
								if ($rs)
								{
									while($row=sqlsrv_fetch_array($rs,SQLSRV_FETCH_ASSOC))
									{									
										$ServiceAmount=$row["Amount"];										
										$InvoiceAmount+=$ServiceAmount;
										$ServiceID=1696;
										
										$s_sql="set dateformat dmy insert into InvoiceLines (InvoiceHeaderID,ServiceHeaderID,ServiceID,Amount,CreatedBy) 
												Values($InvoiceHeader,$ApplicationID,$ServiceID,$ServiceAmount,$UserID)";
										$result4 = sqlsrv_query($db, $s_sql);
										if (!$result4)
										{
											DisplayErrors();
											$loopOkey=false;
											break;
										}else{
											//echo 'Conservancy Fees Done';
										}
									}	
								}

							}else
							{
								//echo $sql;
								
								DisplayErrors().'<BR>';
								$Sawa=false;
								
							}

							//Application Charges
						    $sql="select distinct s1.ServiceID,s1.ServiceName ,sc.Amount 
						            from ApplicationCharges sc 
						            join ServiceHeader sh on sh.serviceheaderid=sc.serviceheaderid 
						            join Services s1 on sc.ServiceID=s1.ServiceID 
						            where sh.ServiceHeaderID=$ApplicationID";

						    $result=sqlsrv_query($db,$sql);
						    while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
						    {
						        $ServiceAmount=$row["Amount"];
								$ServiceID=$row['ServiceID'];
								$InvoiceAmount+=$ServiceAmount;
								
								$s_sql="set dateformat dmy insert into InvoiceLines (InvoiceHeaderID,ServiceHeaderID,ServiceID,Amount,CreatedBy) 
										Values($InvoiceHeader,$ApplicationID,$ServiceID,$ServiceAmount,$UserID)";
								$result3 = sqlsrv_query($db, $s_sql);
								if (!$result3)
								{
									DisplayErrors();
									$loopOkey=false;
									//echo $s_sql;
									break;
								}else{
									//echo 'Invoice Lines Worked';
								}

						    }
							
							//penalties
						 //    $mnths=0;
							// $sql="set dateformat dmy select datediff(month,'$DateLine',getdate()) Mnths";
							// $result=sqlsrv_query($db,$sql);

							// while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
							// 	$mnths=$rw['Mnths'];
							// }

							
							//echo 'checking Penalty';
							 //if(strtotime($DateLine)<strtotime($ApplicationDate)){ 
							 if($MonthsLate>0)
							 { 
								//echo 'Iko Penalty';

								$CPenalty=0;
								$ServiceID='283';
								$penalty=0.50*(double)$PermitCost;

								//how late?

								$sql="set dateformat dmy select datediff(MONTH,'$DateLine',getdate()) Months";
								$dt=sqlsrv_query($db,$sql);
								$rw=sqlsrv_fetch_array($dt,SQLSRV_FETCH_ASSOC);

								$months=$rw['Months'];
								while($months>0){
									$CPenalty+=(.03*($PermitCost+$CPenalty));
									$months-=1;
								}

								$CPenalty=roundUpToAny($CPenalty,$x=5);

								$penalty+=$CPenalty;

								$InvoiceAmount+=$ServiceAmount;
								$s_sql="set dateformat dmy insert into InvoiceLines (InvoiceHeaderID,ServiceHeaderID,ServiceID,Amount,CreatedBy) 
												Values($InvoiceHeader,$ApplicationID,$ServiceID,$penalty,$UserID)";
								//echo $s_sql;
								$rslt=sqlsrv_query($db,$s_sql);
								if(!$rslt){
									$Sawa=false;
								}else{
									//echo 'Invoice Lines Worked';
								}
							}else{
								//echo 'No Penalties';
							}
							
													
						}
						
						$s_sql="set dateformat dmy update InvoiceHeader set Amount='$InvoiceAmount' where InvoiceHeaderID='$InvoiceHeader'";
						
						$s_result3=sqlsrv_query($db,$s_sql);
						if(!$s_result3)
						{							
							$Sawa=false;
						}else
						{
							DisplayErrors();
						}

						$s_sql="set dateformat dmy update ServiceHeader set ServiceStatusID=11 where ServiceHeaderID='$ApplicationID'";
						
						$s_result3=sqlsrv_query($db,$s_sql);
						if(!$s_result3)
						{							
							$Sawa=false;
						}else
						{
							DisplayErrors();
						}

						// $sql="Update ServiceHeader set Printed=0 where ServiceHeaderID=$ApplicationID";	
						// $s_result = sqlsrv_query($db, $sql);	

						$validity=date('Y');
						if($today>$FirstDec){
							$validity=date('Y')+1;
						}else{
							$validity=date('Y');
						}
						$expiryDate="31/12/$validity";
						
						$mdate=date('d/m/Y');

						
						$permitNo=randomNumber();//time();				
						$expiryDate="31/12/{$validity}";

						$sql="set dateformat dmy insert into Permits(permitNo,ServiceHeaderID,Validity,ExpiryDate,CreatedBy,InvoiceHeaderID,Printed) 
						values('$permitNo',$ApplicationID,'$validity','$expiryDate','$UserID','$InvoiceHeader',0)";
						
						$s_result1 = sqlsrv_query($db, $sql);						
						
						if($s_result1)//&& $s_result2 && $s_result3 && $s_result4 && $loopOkey==true && $mail==true
						{

							$rst=SaveTransaction($db,$UserID," Created a Permit Invoice Number ".$InvoiceHeader);				
							

							sqlsrv_commit($db);
							$msg="Invoice Created Successfully";
														
							$Sawa=true;
						}else
						{
							sqlsrv_rollback($db);
							$Sawa=false;
						}
					}
				}
			//}
		}
		else if ($NextStatusID==6)
		{
			//Inform the customer of the rejection
			$txt=$Notes;//"Your Service application have been rejected. Contact the county for the explanation";			
			if($txt=="")
			{
				$msg="Kindly State the reason for rejection";
				$Sawa=false;					
			}else
			{
				$result=php_mailer($CustomerEmail,$cosmasRow['Email'],$cosmasRow['CountyName'],'Service Rejection',$txt,'','','Message');
				$msg=$result[1];
				$Sawa=true;
			}				
		}	
		else if ($NextStatusID==7)
		{
			$Balance=0;	
			
			$sql="Select Balance From vwPayments where ServiceHeaderID=$ApplicationID and InvoiceHeaderID in (select InvoiceHeaderID from Permits where ServiceHeaderID='$ApplicationID')";
			echo $sql;
			exit;
			$s_result = sqlsrv_query($db, $sql);
			if ($s_result)
			{	
				while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
				{							
					$Balance=$row['Balance'];
				}
			}
			if($Balance>0)
			{
				$msg= "The service is not fully paid for";
				$Sawa=false;
			}else
			{
				
				/* if(sqlsrv_begin_transaction($db)===false)
				{
					$msg=sqlsrv_errors();
					$Sawa=false;
				} */
				
				
				//update the serviceheader table, generate the permit.
				
				/* if (ServiceHeaderType==4) //if it's permit
				{ */	
					
				
				// if($today>$FirstDec){
				// 	$validity=date('Y')+1;
				// }else{
				// 	$validity=date('Y');
				// }

				// $expiryDate="31/12/$validity";
				
				// $mdate=date('d/m/Y');
				
				// $sql="set dateformat dmy update Permits set Validity='$Validity',IssueDate='$mdate',ExpiryDate='$expiryDate',CreatedBy='$UserID' where ServiceHeaderID='$ApplicationID'";
				// $s_result1 = sqlsrv_query($db, $sql);
				// if ($s_result1)
				// {	
				// 	$sql="Update ServiceHeader set ServiceStatusID=$NextStatusID where ServiceHeaderID=$ApplicationID";
			
				// 	$s_result = sqlsrv_query($db, $sql);

				// 	$rst=SaveTransaction($db,$UserID," Renewed Permit for Application Number ".$ApplicationID);				

				// 	$msg="Permit Renewed Successfully";					
					
				// }
				// if($s_result1 && $mail==true)
				// {						
				// 	//sqlsrv_commit($db);
				// 	$Sawa=true;
				// }else
				// {
				// 	//echo 'Issues';
				// 	DisplayErrors();
				// 	//sqlsrv_rollback($db);
				// 	$Sawa=false;
				// }
				//}				
			}						
		}else
		{
			
			$msg='Approval Successful';
			$Sawa=true;
		}		
		//move to the next status
		if($Sawa==true)
		{
					
			$sql="Update ServiceHeader set ServiceStatusID=$NextStatusID where ServiceHeaderID=$ApplicationID";
				
			$s_result = sqlsrv_query($db, $sql);	
			exit('Sawa');
		}			
	}else
	{
		exit('Too Bad');
		DisplayErrors();
		$msg="Transaction failed to initialize";
	}
}

$s_sql="select c.*,f.ServiceHeaderType,bt.CustomerTypeName,iif(sh.ServiceStatusID= 7,4,sh.ServiceStatusID)ServiceStatusID,sh.ServiceHeaderID,bz.ZoneName,w.WardName,sc.SubCountyName,s.ServiceName,sh.ServiceID,sh.CreatedDate,sh.SubSystemID,S.ServiceCategoryID
	from Customer c 
	join ServiceHeader sh on sh.CustomerID=c.CustomerID
	join services s on sh.ServiceID=s.ServiceID
	join Forms f on sh.FormID=f.FormID
	left join CustomerType bt on bt.CustomerTypeID=c.BusinessTypeID 
	left join BusinessZones bz on sh.BusinessZoneID=bz.ZoneID
	left join Wards w on bz.wardid=w.wardid
	left join subcounty sc on w.subcountyid=sc.subcountyid
	
	where sh.ServiceHeaderID=$ApplicationID";
	//echo $s_sql; 

$s_result=sqlsrv_query($db,$s_sql);


if ($s_result)
{
	
	while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC)){	
		
		$CustomerType=$row['CustomerTypeName'];
		$CustomerID=$row['CustomerID'];
		$CustomerName=$row['CustomerName'];
		$ServiceID=$row['ServiceID'];
		$ServiceName=$row['ServiceName'];
		$CurrentStatus=$row['ServiceStatusID'];
		$ServiceCategoryID=$row['ServiceCategoryID'];
		$RegNo=$row['RegistrationNumber'];
		$PostalAddress=$row['PostalAddress'];
		$PostalCode=$row['PostalCode'];
		$ServiceHeaderTypeID=$row['ServiceHeaderType'];
		$ServiceHeaderID=$row['ServiceHeaderID'];
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
		$SubCountyName=$row['SubCountyName'];
		$WardName=$row['WardName'];
		$BusinessZone=$row['ZoneName'];
		$SubSystemID=$row['SubSystemID'];		
		$ApplicationDate=date('d/m/Y');	
	}
}

//get the serviceCost

if ($ServiceHeaderTypeID==1)
{

	$BSql="select l.RatesPayable from LandApplication la join land l on la.PlotNo=l.PlotNo and la.LRN=l.LRN where la.ServiceHeaderID=$ApplicationID";
	$rsult=sqlsrv_query($db,$BSql);
	//echo $BSql;
	if ($rsu=sqlsrv_fetch_array($rsult,SQLSRV_FETCH_ASSOC))
	{
		$ServiceCost=$rsu['RatesPayable'];							
	}else
	{
		$ServiceCost=0;
	}	
}else
{


	//get the subsystem

	$sql="select fd.ServiceHeaderID,fd.FormColumnID,fd.Value,fc.FormColumnName from 
		FormData fd join FormColumns fc on fd.FormColumnID=fc.FormColumnID
		where fc.FormColumnID=12237 and fd.ServiceHeaderID=$ServiceHeaderID";
	$res=sqlsrv_query($db,$sql);
	while($row=sqlsrv_fetch_array($res,SQLSRV_FETCH_ASSOC))
	{
		$SubSystemID=$row['Value'];
	}	
	//echo $SubSystemID.'<BR>';
	$sql="select * from fnServiceCost($ServiceID,$SubSystemID)";
	//echo $sql;
	$result=sqlsrv_query($db,$sql);
	if ($result)
	{
		while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
		{									
			$ServiceCost=$row['Amount'];
		}

		//Conservancy
		$sql1="select * from fnConservancyCost($ServiceCost,$SubSystemID)";
		//echo $sql1;
		$rs=sqlsrv_query($db,$sql1);
		if ($rs)
		{
			while($row=sqlsrv_fetch_array($rs,SQLSRV_FETCH_ASSOC))
			{									
				$ConservancyCost=$row["Amount"];										
			}	
		}

		//penalty
		//echo 'The Business is '.$BusinessIsOld;
		
		if($MonthsLate>0){			
			//$penalty=0.50*(double)$ServiceCost; //Activate this later...

			$CPenalty=0;
	        //$ServiceID='283';
	        $penalty=0.50*(double)$ServiceCost;

	        //how late?

	        $sql="set dateformat dmy select datediff(MONTH,'$DateLine',getdate()) Months";
	        $dt=sqlsrv_query($db,$sql);
	        //echo $sql;
	        $rw=sqlsrv_fetch_array($dt,SQLSRV_FETCH_ASSOC);

	        $months=$rw['Months'];
	        //echo $months;
	        while($months>0){
	            //echo (double)$PermitCost+(double)$CPenalty;
	            $CPenalty+=(.03*((double)$ServiceCost+(double)$CPenalty));
	            $months-=1;
	        }

	        $CPenalty=roundUpToAny($CPenalty,$x=5);
	        
	        //$penalty+=$CPenalty;
	        //echo $ServiceCost;

			// echo 'ServiceCost '.$ServiceCost.'<BR>';
			// echo 'OtherCharge '.$OtherCharge.'<BR>';
			// echo 'penalty '.$penalty.'<BR>';
			// echo 'ApplicationCharge '.$ApplicationCharge.'<BR>'; 
			// echo 'ConservancyCost '.$ConservancyCost.'<BR>'; 
			// echo 'CPenalty '.$CPenalty;
			$ServiceCost=$ServiceCost+$OtherCharge+$penalty+$ApplicationCharge+$ConservancyCost+$CPenalty;
		}
		else{
			$penalty=0;
		}
		
		$OtherCharge=0;
		//Application Fees
		$sql="select Amount from ServiceCharges sc									
				join FinancialYear fy on sc.FinancialYearId=fy.FinancialYearID																	
				and fy.isCurrentYear=1
				and sc.SubSystemId=$SubSystemID
				and sc.serviceid=281";
		
		$s_result = sqlsrv_query($db, $sql);
		while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
		{							
			$OtherCharge=$row["Amount"];												
		}
		
		//Application Charges
		$ApplicationCharge=0;
	    $sql="select sum(sc.Amount) Amount 
	            from ApplicationCharges sc 
	            join ServiceHeader sh on sh.serviceheaderid=sc.serviceheaderid 
	            join Services s1 on sc.ServiceID=s1.ServiceID 
	            where sh.ServiceHeaderID=$ServiceHeaderID";

	    $result=sqlsrv_query($db,$sql);
	    while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
	    {
	        $ApplicationCharge=$row['Amount'];
	    }

	
	

	// echo 'ServiceCost '.$ServiceCost.'<BR>';
	// echo 'OtherCharge '.$OtherCharge.'<BR>';
	// echo 'penalty '.$penalty.'<BR>';
	// echo 'ApplicationCharge '.$ApplicationCharge.'<BR>'; 
	// echo 'ConservancyCost '.$ConservancyCost.'<BR>'; 
	$ServiceCost+=$ApplicationCharge+$OtherCharge;
	}
}

if (isset($_REQUEST['approve']))
{	
	$input=array_slice($_REQUEST,2,count($input)-1);	
	foreach ($input AS $id => $value)
	{	
		$newID=substr($id,3,strlen($id)-3);	
			
		$sql="if exists(select * from FormData where FormColumnID=$newID)
				Update FormData set Value='$value' where FormColumnID=$newID and ServiceHeaderID=$ApplicationID
			  else
				insert into FormData (FormColumnID,ServiceHeaderID,Value)
			    values($newID,$ApplicationID,'$value')";
				
		$result=sqlsrv_query($db,$sql);
		
		if(!$result)
		{
			DisplayErrors();
			continue;
		}		

	}	
}
if (isset($_REQUEST['change']))
{	
	$ApplicationID=$_REQUEST['ApplicationID'];
	$FromServiceID=$_REQUEST['FromServiceID'];
	$ToServiceID=$_REQUEST['ToServiceID'];
	$CurrentStatus=$_REQUEST['CurrentStatus'];
	$Notes=$_REQUEST['Notes'];
	
	if($FromServiceID==$ToServiceID){
		$msg="You have made not change in the application";
	}else if($CurrentStatus>4){
		$msg="The Application Cannot Be modified at this stage";
	}
	else
	{

		$sql="Update ServiceHeader Set ServiceID=$ToServiceID where ServiceHeaderID=$ApplicationID";
		$result=sqlsrv_query($db,$sql);
		if($result){
			$sql="Insert into ServiceHeaderChange(ServiceHeaderID,FromServiceID,ToServiceID,CreatedBy,Notes)
			Values ($ServiceHeaderID,$FromServiceID,$ToServiceID,$UserID,'$Notes')";
			
			$result1=sqlsrv_query($db,$sql);
			if($result1){
				$msg ="Application Changed Successfully";
			}else{
				DisplayErrors();
			}
			
			$sql="Update InvoiceLines Set ServiceID=$ToServiceID where ServiceHeaderID=$ApplicationID and ServiceHeaderID=$FromServiceID";
			
			$result2=sqlsrv_query($db,$sql);
			if($result2){
				$msg ="Application Changed Successfully";
			}else{
				DisplayErrors();
			}
		}	
	}
}   


?>
<script type="text/javascript">
    	$("#viewCharges").on('click', function(ev){
    		var url = 'application_charges.php?ApplicationID=' 
    			+ ev.target.dataset.appId + '&SubSystemID=' + ev.target.dataset.ssId+ '&ServiceID=' + ev.target.dataset.sId
    		console.log(url)
    		$.get(url, function(res) {
				$.Dialog({
			        shadow: true,
			        overlay: false,
			        draggable: true,
			        icon: '<span class="icon-rocket"></span>',
			        title: 'Application Charges',
			        width: 500,
			        padding: 10,
			        content: res
			    });
			})
		    
		});
		$("#addCharges").on('click', function(ev){
    		var url = 'add_charge.php?ApplicationID=' 
    			+ ev.target.dataset.appId + '&SubSystemID=' + ev.target.dataset.ssId+ '&ServiceID=' + ev.target.dataset.sId+ '&Renew=1'
    		//console.log(url)
    		$.get(url, function(res) {
				$.Dialog({
			        shadow: true,
			        overlay: false,
			        draggable: true,
			        icon: '<span class="icon-rocket"></span>',
			        title: 'Application Charges',
			        width: 500,
			        padding: 10,
			        content: res
			    });
			})
		    
		});
 </script>

<div class="example">
   <legend>Licence  Renewal</legend>
   <form>
      <fieldset>
          <table width="100%" border="0" cellspacing="0" cellpadding="3">
              <tr>
                <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
            </tr>
              <tr>
                 <td width="50%">
					<label>Customer Name</label>
					  <div class="input-control text" data-role="input-control">
						  <input name="customer" type="text" id="customer" value="<?php echo $CustomerName; ?>" disabled="disabled" placeholder="">
						  
					  </div>                 	
                  </td>
                  <td></td>                  
              </tr>
			  <tr>
                  <td width="50%">
                  <label>Application No</label>
					  <div class="input-control text" data-role="input-control">
						  <input name="ServiceHeaderID" type="text" id="ServiceHeaderID" value="<?php echo $ServiceHeaderID; ?>" disabled="disabled" placeholder="">						  
					  </div>				  
                  </td>
                  <td width="50%">				  
                  </td>   
              </tr>
              <tr>
                  <td width="50%">
                  <label>Service</label>
					  <div class="input-control text" data-role="input-control">
						  <input name="servicename" type="text" id="servicename" value="<?php echo $ServiceName; ?>" disabled="disabled" placeholder="">
						  
					  </div>				  
                  </td>
                  <td width="50%">
				<label>&nbsp;</label>				  
					<!--service_approval.php?ApplicationID='+app_id+'&app_type='+app_type+'&CurrentStatus='+current_status
					<input name="Button" type="button" onclick="loadmypage('service_form.php?save=1&ApplicationID=<?php echo $ApplicationID ?>','content','loader','','')" value="Change">-->
					<input name="Button" type="button" 
					onclick="loadmypage('application_change.php?ApplicationID=<?php echo $ApplicationID; ?>&CurrentStatus=<?php echo $CurrentStatus; ?>','content','loader','','')" value="Change">					
					
                  </td>   
              </tr>	
			  <tr>
				   <td width="50%">
						<label>Service Cost (Ksh.)</label>
						  <div class="input-control text" data-role="input-control">
							  <input name="servicecost" type="text" id="servicecost" value="<?php echo $ServiceCost; ?>" disabled="disabled" placeholder="">
							  
						  </div>                  	
                  </td>
                  <td width="50%">
                  			<label>&nbsp</label>
						  <div class="input-control text" data-role="input-control">
							  <input id="addCharges" name="Button" type="button" 
								data-app-id="<?php echo $ApplicationID; ?>"
								data-ss-id="<?php echo $SubSystemID; ?>"
								data-s-id="<?php echo $ServiceID; ?>"
							 value="Add Charges">
							  
						  </div>
                  </td>
			  </tr>
				<tr>
					<td colspan="2">
						<HR>         
						<div class="tab-control" data-role="tab-clontrol">
						<div class="tab-control" data-role="tab-control">
							<ul class="tabs">
								<li class="active"><a href="#_page_1">Details</a></li>
								<li class=""><a href="#_page_2">Notes</a></li>
								<li class=""><a href="#_page_3">Documents</a></li>
								<li class=""><a href="#_page_4">Applicant's Details</a></li>	
								<li class=""><a href="#_page_5">Other Details</a></li>									
							</ul>							
							<div class="frames">
								<div class="frame" id="_page_4" style="display: none;">
									<fieldset>
										<table width="50%" border="0" cellspacing="0" cellpadding="3">
											<tr>
												<td width="50%">
												   <label>Customer Name</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="CustomerName" type="text" id="CustomerName" value="<?php echo $CustomerName; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td>                 
												<td width="50%">
												   <label>Business Type</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="CustomerType" type="text" id="CustomerType" value="<?php echo $CustomerType; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td>                        
											<tr> 
											<tr>
												<td width="50%">
												   <label>Registration No</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="RegNo" type="text" id="RegNo" value="<?php echo $RegNo; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td> 
												<td width="50%">
												   <label>Pin</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="Pin" type="text" id="Pin" value="<?php echo $Pin; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td>                   
											</tr>
											<tr>
												<td width="50%">
												   <label>Postal Address</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="PostalAddress" type="text" id="PostalAddress" value="<?php echo $PostalAddress; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td> 
												<td width="50%">
												   <label>Postal Code</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="PostalCode" type="text" id="PostalCode" value="<?php echo $PostalCode; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td>                   
											</tr> 
											<tr>
												<td width="50%">
												   <label>Town</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="Town" type="text" id="Town" value="<?php echo $Town; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td> 
												<td width="50%">
												   <label>Country</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="Country" type="text" id="Country" value="<?php echo $Country; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td>                   
											</tr> 
											<tr>
												<td colspan="2">
												   <label>Physical Location</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="Location" type="text" id="Location" value="<?php echo $Town; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td>                    
											</tr>                                     
											 <tr>
												<td width="50%">
												   <label>Telephone 1</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="Telephone1" type="text" id="Telephone1" value="<?php echo $Telephone1; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td>
																		 
												<td width="50%">
												   <label>Telephone 2</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="Telephone2" type="text" id="Telephone2" value="<?php echo $Telephone2; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td>  
																 
											</tr>
											<tr>
												<td width="50%">
												   <label>Mobile 1</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="Mobile1" type="text" id="Mobile1" value="<?php echo $Mobile1; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td> 
												<td width="50%">
												   <label>Mobile 2</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="Mobile2" type="text" id="Mobile2" value="<?php echo $Mobile2; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td>                   
											</tr>
											<tr>
												<td width="50%">
												   <label>Email</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="Email" type="text" id="Email" value="<?php echo $Email; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td> 
												<td width="50%">
												   <label>Url</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="Url" type="text" id="Url" value="<?php echo $url; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td>                   
											</tr>                   
														   
										</table>            
									</fieldset>
								  </div>
								  <div class="frame" id="_page_2" style="display: block;">
									  <table class="hovered" cellpadding="3" cellspacing="1">
										<?php 
											$sql="SELECT SH.ServiceHeaderID, SS.ServiceStatusName, SAA.Notes, SAA.CreatedDate, U.FirstName+' '+U.MiddleName+' '+u.LastName UserFullNames
													FROM dbo.ServiceApprovalActions AS SAA INNER JOIN
													dbo.ServiceHeader AS SH ON SAA.ServiceHeaderID = SH.ServiceHeaderID INNER JOIN
													dbo.Agents AS U ON SAA.CreatedBy = U.AgentId INNER JOIN
													dbo.ServiceStatus AS SS ON SAA.ServiceStatusID = SS.ServiceStatusID
													where SH.ServiceHeaderID=$ApplicationID";

													$s_result=sqlsrv_query($db,$sql);
													
													if ($s_result)
													{
														while($row=sqlsrv_fetch_array($s_result,SQLSRV_FETCH_ASSOC))
														{									
															echo "<tr><td>".$row["ServiceStatusName"]."</td><td>".$row["Notes"]."</td><td>".$row["CreatedDate"]."</td><td>".$row["UserFullNames"]."</td></tr>";
														}
													}
										?>             	
									  </table>              
								  </div>
								  <div class="frame" id="_page_3" style="display: none;">
									<table class="hovered" cellpadding="3" cellspacing="1">
										<?php 
											$sql="select * from Attachments where ApplicationNo=$ApplicationID";

													$s_result=sqlsrv_query($db,$sql);
													// echo '<pre>';
													// print_r($s_result);
													// exit;

													
													if ($s_result){
														while($row=sqlsrv_fetch_array($s_result,SQLSRV_FETCH_ASSOC)){									
															echo "<tr><td> <a href=/revenue".$row["AttachmentName"].">".$row["FilePath"]."</a></td></tr>";
															}
													}
										?>             	
									  </table> 
								  </div>
								  <div class="frame" id="_page_1" style="display: none;">
									<table width="50%">
										<!-- <tr>
											<td width="30%">
												<label>SubCounty</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="SubCounty" type="text" id="SubCounty" value="<?php echo $SubCountyName; ?>" disabled="disabled">													  
												  </div>
											</td>
											<td width="30%">
												<label>WardName</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="WardName" type="text" id="WardName" value="<?php echo $WardName; ?>" disabled="disabled">													  
												  </div>
											</td>
											<td width="30%">
												<label>BusinessZone</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="ZoneName" type="text" id="ZoneName" value="<?php echo $BusinessZone; ?>" disabled="disabled">													  
												  </div>
											</td>											
										</tr> -->
										
										<?php  
											if ($ServiceHeaderTypeID==1)
											{
												$sql=" select lrn,plotno,mplotno,titleno from landapplication where ServiceHeaderID='$ApplicationID'";
												$s_result=sqlsrv_query($db,$sql);
												if ($s_result){
													while($row=sqlsrv_fetch_array($s_result,SQLSRV_FETCH_ASSOC))
													{									
														echo "<tr><td>Lrn No: </td><td> ".$row["lrn"]."</td></tr>";
														echo "<tr><td>Plot No: </td><td> ".$row["plotno"]."</td></tr>";
														echo "<tr><td>Mother PlotNo: </td><td> ".$row["mplotno"]."</td></tr>";
														echo "<tr><td>Title No:</td><td> ".$row["titleno"]."</td></tr>"; 
														
														$lrn=$row["lrn"];
														$plotno=$row["plotno"];
														
														$BSql="select RatesPayable,PrincipalBalance from LAND where lrn='".$row["lrn"]."' and PlotNo='".$row["plotno"]."'";
														//echo ($BSql);
														$rsult=sqlsrv_query($db,$BSql);
														if ($rsu=sqlsrv_fetch_array($rsult,SQLSRV_FETCH_ASSOC))
														{
															$Balance=$rsu['PrincipalBalance'];
															$Rates=$rsu['RatesPayable'];
														}else
														{
															$balance=0;
														}														
													}													
													echo "<tr><td>Rates Payable:</td><td> ".$Rates."</td></tr>";
													echo "<tr><td>Outstanding Balance:</td><td> ".$Balance."</td></tr>";
													echo "<a href='statement.php?popupwindow&lrn=$lrn&plotno=$plotno' class='popupwindow' target='_blank'>Rates Statement</a>";
												}else
												{
													//echo $sql;
												}												
												
											}else if ($ServiceHeaderTypeID==2)
											{
												$sql="select h.HouseNo,e.EstateID,e.EstateName from HouseApplication h join Estates e on h.EstateID=e.EstateID where h.serviceheaderid=$ApplicationID";
												$s_result=sqlsrv_query($db,$sql);
												if ($s_result){
													while($row=sqlsrv_fetch_array($s_result,SQLSRV_FETCH_ASSOC)){									
														echo "<tr><td>Estate Name: </td><td> ".$row["EstateName"]."</td></tr>";
														echo "<tr><td>House No: </td><td> ".$row["HouseNo"]."</td></tr>";														
														}
												}else
												{
													//echo $sql;
												}												
											}else if ($ServiceHeaderTypeID==3)
											{
												$sql="select ha.FromDate,ha.ToDate,s.ServiceName from HireApplication ha 
														join ServiceHeader sh on ha.ServiceHeaderID=sh.ServiceHeaderID
														join Services s on sh.ServiceID=s.ServiceID where ha.ServiceHeaderID=$ApplicationID";
												$s_result=sqlsrv_query($db,$sql);
												if ($s_result){
													while($row=sqlsrv_fetch_array($s_result,SQLSRV_FETCH_ASSOC)){
														echo "<tr><td>Facilty: </td><td> ".$row["ServiceName"]."</td></tr>";
														echo "<tr><td>Start Date: </td><td> ".$row["FromDate"]."</td></tr>";
														echo "<tr><td>End Date: </td><td> ".$row["ToDate"]."</td></tr>";														
														}
												}else
												{
													//echo $sql;
												}												
											}else
											{
												$sql="select FD.ServiceHeaderID,FC.FormColumnName,FD.Value,FD.FormDataID,fc.ColumnDataTypeID,fc.Notes from FormData fd 
											  inner join FormColumns fc on fd.FormColumnID=fc.FormColumnID
											  WHERE FD.ServiceHeaderID=$ApplicationID
											  ORDER BY FC.Priority";

													$s_result=sqlsrv_query($db,$sql);
													
													if ($s_result){
														while($row=sqlsrv_fetch_array($s_result,SQLSRV_FETCH_ASSOC)){
															$rValue=$row['Value'];
															$Notes=$row['Notes'];
															
															if($Notes!='')
															{
																$rsult='';
																$Notes=str_replace("'","''",$Notes);
																//echo $notes; exit;
																$sql="select * from [dbo].[fnColumnDetails] ('$Notes')";									
																$rsult=sqlsrv_query($db,$sql);
																if ($rsult)
																{
																	while($rw=sqlsrv_fetch_array($rsult,SQLSRV_FETCH_ASSOC))
																	{
																		$Table=$rw['table_name'];
																		$displayName=$rw['display_column'];
																		$ColumnName=$rw['column_name'];
																		
																		$myQry="select ".$displayName ." from ".$Table." where ".$ColumnName." =$rValue";
																		$DName=sqlsrv_query($db,$myQry);
																		if($DName)
																		{
																			
																			while($rww=sqlsrv_fetch_array($DName,SQLSRV_FETCH_ASSOC))
																			{
																				$rValue=$rww[$displayName];
																			}
																			
																		}else
																		{

																		}
																		// echo $rValue;
																		
																	}
																}else
																{

																}
															}
															
															
															$dataType=$row['ColumnDataTypeID'];									
																								
															echo "<tr><td>".$row["FormColumnName"]."</td><td>".$rValue."</td></tr>";
															}
													}
											}
										
										?>            	
									</table> 
								  </div>
								  <div class="frame" id="_page_5" style="display: none;">
									<table class="hovered" cellpadding="3" cellspacing="1">
										<?php 
											$sql="select FD.ServiceHeaderID,FC.FormColumnName,FD.Value,FD.FormDataID,fc.ColumnDataTypeID,fc.Notes from FormData fd 
											  inner join FormColumns fc on fd.FormColumnID=fc.FormColumnID
											  WHERE FD.ServiceHeaderID=$ApplicationID
											  ORDER BY FC.Priority";

													$s_result=sqlsrv_query($db,$sql);
													
													if ($s_result){
														while($row=sqlsrv_fetch_array($s_result,SQLSRV_FETCH_ASSOC)){
															$rValue=$row['Value'];
															$Notes=$row['Notes'];
															
															if($Notes!='')
															{
																$rsult='';
																$Notes=str_replace("'","''",$Notes);
																//echo $notes; exit;
																$sql="select * from [dbo].[fnColumnDetails] ('$Notes')";									
																$rsult=sqlsrv_query($db,$sql);
																if ($rsult)
																{
																	while($rw=sqlsrv_fetch_array($rsult,SQLSRV_FETCH_ASSOC))
																	{
																		$Table=$rw['table_name'];
																		$displayName=$rw['display_column'];
																		$ColumnName=$rw['column_name'];
																		
																		$myQry="select ".$displayName ." from ".$Table." where ".$ColumnName." =$rValue";
																		$DName=sqlsrv_query($db,$myQry);
																		if($DName)
																		{
																			
																			while($rww=sqlsrv_fetch_array($DName,SQLSRV_FETCH_ASSOC))
																			{
																				$rValue=$rww[$displayName];
																			}
																			
																		}else
																		{

																		}
																		// echo $rValue;
																		
																	}
																}else
																{

																}
															}
															
															
															$dataType=$row['ColumnDataTypeID'];									
																								
															echo "<tr><td>".$row["FormColumnName"]."</td><td>".$rValue."</td></tr>";
															}
													}
										?>             	
									  </table> 
								  </div>
								</div>
						  </div>
						</div>					
					</td>
				</tr>
				<tr>
					<td width="50%"><label>Notes</label>
					  <div class="input-control textarea" data-role="input-control">
						<textarea name="Notes" type="textarea> id="Notes" placeholder=""><?php //echo $Notes; ?></textarea>  
					  </div>
					</td>                  
					<td width="50%"></td>   
				</tr>
					<?php 
					if($CurrentStatus=="4"){
					?>
					<tr>
						<td><label>Outstanding Balance</label>
						  <div class="input-control text" data-role="input-control">
							<input type="text" name="Balance" id="Balance" placeholder="" ><?php $Balance ?></input>  
						  </div>						
						</td>
						<td>
						</td>					
					</tr>					
					<tr>
						<td><label>Invoice No</label>
						  <div class="input-control text" data-role="input-control">
							<input type="text" name="InvoiceNo" id="InvoiceNo" placeholder="" ><?php $InvoiceNo ?></input>  
						  </div>						
						</td>
						<td>
						</td>					
					</tr>
				<?php }else
				{ ?>
					<tr>
						<td><label>Invoice No</label>
						  <div class="input-control text" data-role="input-control">
							<input type="text" name="InvoiceNo" id="InvoiceNo" placeholder="" disabled="disabled"><?php $InvoiceNo ?></input>  
						  </div>						
						</td>
						<td>
						</td>					
					</tr>
					<tr>
						<td><label>Outstanding Balance</label>
						  <div class="input-control text" data-role="input-control">
							<input type="text" name="Balance" id="Balance" placeholder="" disabled="disabled"><?php $Balance ?></input>  
						  </div>						
						</td>
						<td>
						</td>					
					</tr>					
				<?php				
					
				}

				?>
            <tr>
              <td width="50%">
                <label>Forward To</label>
                <div class="input-control select" data-role="input-control">
                  <select name="NextStatus"  id="NextStatus">                    
                    <?php 
                         
						
						$s_sql="SELECT AlternativeStatusID NextStatusID,[AlternativeStatus] FROM [COUNTYREVENUE].[dbo].[vwAlternativeSteps] 
						where CurrentStatusID= $CurrentStatus 
						and ServiceCategoryID=$ServiceCategoryID order by isDefault desc";						

						
						$s_result = sqlsrv_query($db, $s_sql);
						if ($s_result) 
						{  
						  while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
						  {
							  $s_name = $row["AlternativeStatus"];							  
							  $s_id = $row["NextStatusID"];
                                    
						   ?>
						  <option value="<?php echo $s_id; ?>" <?php echo $selected; ?>><?php echo $s_name; ?></option>
						<?php 
						  }
						}
                          ?>
                  </select> 
                  <?php  //echo $s_sql;  ?>
                </div></td>
                <td width="50%"></td>   
            </tr>                       
            		
          </table> 
          
          <!-- <input name="Button" type="button" onclick="loadmypage('clients_list.php?save=1&ApplicationID=<?php echo $ApplicationID ?>&CustomerID=<?php echo $CustomerID ?>&CurrentStatus=<?php echo $CurrentStatus ?>&NextStatus='+this.form.NextStatus.value+'&Notes='+this.form.Notes.value+'&InvoiceNo='+this.form.InvoiceNo.value,'content','loader','listpages','','applications','<?php echo $_SESSION['RoleCenter'] ?>')" value="Save"> -->
          <input type="reset" value="Cancel" onClick="loadmypage('clients_list.php?i=1','content','loader','listpages','','applications','<?php echo $_SESSION['RoleCenter'] ?>')">
          <!--<input name="Button" type="button" onclick="loadmypage('service_form.php?save=1&ApplicationID=<?php echo $ApplicationID ?>','content','loader','','')" value="ClientForm"> -->
          
		  <input name="Button" type="button" onClick="loadpage('permit_renewal.php?save=1&ApplicationID=<?php echo $ApplicationID ?>&CustomerName=<?php echo $CustomerName ?>&CustomerID=<?php echo $CustomerID ?>&ServiceID=<?php echo $ServiceID ?>&ServiceName=<?php echo $ServiceName ?>&CurrentStatus=<?php echo $CurrentStatus ?>&NextStatus='+this.form.NextStatus.value+'&Notes='+this.form.Notes.value+'&InvoiceNo='+this.form.InvoiceNo.value+'&ServiceCategoryID=<?php echo $ServiceCategoryID ?>','content')" value="Renew">
		  
		  <!--<dd><a href="http://www.ripsdomain.com?popupwindow" class="popupwindow" rel="windowNotNew">Example 1</a> - windowNotNew</dd>-->

          <span class="table_text">
          <input name="ApplicationID" type="hidden" id="ApplicationID" value="<?php echo $ApplicationID;?>" />
  <input name="edit" type="hidden" id="edit" value="<?php echo $edit;?>" />
                  </span>
          <div style="margin-top: 20px">
  </div>

  <!--	<input name="Button" type="button" onclick="loadmypage('pdf.php.php?save=1,'content','loader','clients')" value="View"> -->

      </fieldset>
  </form>                  
