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


//get the customer Details

if (isset($_REQUEST['save']) && $_REQUEST['NextStatus']!='')
{

	$ApplicationID=$_REQUEST['ApplicationID'];
	$CustomerID=$_REQUEST['CustomerID'];
	$CurrentStatus=$_REQUEST['CurrentStatus'];
	$NextStatus=$_REQUEST['NextStatus'];
	$Notes=$_REQUEST['Notes'];
	$NextStatusID=$NextStatus;
	$BillAmount=$_REQUEST['RatesPayable'];
	$plotno=$_REQUEST['plotno'];
	$lrn=$_REQUEST['lrn'];
	$localAuthorityID=$_REQUEST['localAuthorityID'];
	$upn=$_REQUEST['upn'];
	
	$Penalty=0;
	$PhysicalAddress='';
	
/* 	print_r($_REQUEST);
	exit; */
	
	$lrn=trim($lrn);
	$plotno=trim($plotno);
	
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

	$BillYear=date('Y');
	$mDescription='Bill '.$BillYear;	
	

	if ($NextStatus=='')
	{
		break;		
	}
	$records=0;
	$sql="select 1 from landReceipts where upn=$upn and DocumentNo='$mDescription' and BillSent=1 and exists(select 1 from invoiceheader where invoiceheaderid='$mDescription')";
	$s_result=sqlsrv_query($db,$sql,$params,$options);
	//echo $s_sql;
	if ($s_result)
	{					
		$records=sqlsrv_num_rows($s_result);
	}
	
	//echo $mDescription.'<BR>'.$LastBillNumber;

	if($records>0)
	{
		$msg="This bill has already been sent to the customer";		
	}else
	{		
		$Authority='96';
	
		$sql="select value Authority from dbo.fnFormData($ApplicationID) where FormColumnID='13270'";
		$result=sqlsrv_query($db,$sql);
		if ($result)
		{	
			while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
			{
				$Authority=$row['Authority'];			
			}
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
				}
			}
			
			if ($ex=='0'){
				$msg="The Plot is not Registered, please Register";
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
					while ($row = sqlsrv_fetch_array( $invoices, SQLSRV_FETCH_ASSOC))
					{		
						$SC=str_pad($row['SubCountyID'],2,'0',STR_PAD_LEFT);
						$WD=str_pad($row['WardID'],2,'0',STR_PAD_LEFT);
						$ICount=str_pad($row['InvoiceCount'],4,'0',STR_PAD_LEFT);
						
						$InvoiceNo=$SC.$WD.$ICount;
					}
				}

				$sql="select value PhysicalAddress from dbo.fnFormData($ApplicationID) where FormColumnID=13272";
				$phAdd=sqlsrv_query($db,$sql);
				//echo $sql;
				if ($phAdd)
				{	
					//echo 'invoices';
					while ($row = sqlsrv_fetch_array( $phAdd, SQLSRV_FETCH_ASSOC))
					{		
						$PhysicalAddress=$row['PhysicalAddress'];				
					}
				}		
				
				$Location=$SubCounty.'/'.$WardName.'/'.$Zone;
				
				$Description='(Block '.$lrn.'Plot No: '.$plotno.'UPN: '.$upn.'),'.$PhysicalAddress;
				
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
				//echo 'insert actions';
				$s_result = sqlsrv_query($db, $initQry);
				
				if ($s_result) 
				{	
					
					if ($NextStatusID=='')
					{
						exit;	
					}		
					
					if($NextStatusID==5)
					{
						
						if(sqlsrv_begin_transaction($db)===false)
						{
							$msg=sqlsrv_errors();
							$Sawa=false;
						}	

						if($InvoiceNo=='')
						{
							$msg="You must enter the Invoice Number";
						}else
						{
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
								$msg="The cost of the service is not set, the process therefore aborts";
							}else
							{					
								$BillAmount=str_replace(',','',$BillAmount);
								$s_sql="set dateformat dmy insert into InvoiceHeader (InvoiceDate,InvoiceNo,CustomerID,CreatedBy) Values('$InvoiceDate','$InvoiceNo',$CustomerID,'$UserID')";
								$s_result1 = sqlsrv_query($db, $s_sql);
								//echo 'invoiceheader done';		
								if ($s_result1)
								{	
									//echo 'after invoiceheader';
									//get the invoiceheader
									$s_sql="set dateformat dmy select InvoiceHeaderID from InvoiceHeader where CustomerID=$CustomerID and InvoiceDate='$InvoiceDate'";
									$s_result0=sqlsrv_query($db,$s_sql);
									if ($s_result0)
									{					
										while ($row = sqlsrv_fetch_array( $s_result0, SQLSRV_FETCH_ASSOC))
										{			
											$InvoiceHeaderID=$row['InvoiceHeaderID'];
										}
									}
									
									//echo "Insert into invoiceLines";
						
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

									$s_sql="select RatesPayable as CurrentYear, Balance-(PenaltyBalance+RatesPayable) as RatesArrears,PenaltyBalance as Penalty  
											from land where LaifomsUPN='$upn' and LocalAuthorityID='$AuthorityID'";									
												
									$s_result3 = sqlsrv_query($db, $s_sql);
									//echo $s_sql;
									while($rw=sqlsrv_fetch_array($s_result3,SQLSRV_FETCH_ASSOC))
									{
										//current year
										$sql="insert into LandInvoices(InvoiceNo,lrn,plotno,upn,LocalAuthorityID,LandPropertyID,Amount)
											  Values ('$InvoiceHeaderID','$lrn','$plotno','$upn','$AuthorityID','1','$rw['CurrentYear']')";
										$result=sqlsrv_query($db,$sql)
										if(!$result){
											echo 'Current Year Failed';
										}
										//Rent Arrears
										$sql="insert into LandInvoices(InvoiceNo,lrn,plotno,upn,LocalAuthorityID,LandPropertyID,Amount)
											  Values ('$InvoiceHeaderID','$lrn','$plotno','$upn','$AuthorityID','2','$rw['RatesArrears']')";
										$result=sqlsrv_query($db,$sql)
										if(!$result){
											echo 'Rent Arrears Failed';
										}
										
										//Penalty Arrears
										$sql="insert into LandInvoices(InvoiceNo,lrn,plotno,upn,LocalAuthorityID,LandPropertyID,Amount)
											  Values ('$InvoiceHeaderID','$lrn','$plotno','$upn','$AuthorityID','3','$rw['Penalty']')";
										$result=sqlsrv_query($db,$sql)
										if(!$result){
											echo 'penalty Failed';
										}
									}
									
								} 
								
								//echo '<br>'.$s_result1 .'<br>'. $s_result2 .'<br>'.  $s_result3 .'<br>'. $s_result4;
								
								if($s_result1)
								{
									$Remark=$Description;
									$feedBack=createInvoice($db,$ApplicationID,$cosmasRow,$Remark,$CustomerName,$InvoiceHeaderID);
									$msg=$feedBack[1];
									$mail=true;
									
									sqlsrv_commit($db);
									$Sawa=true;
								}else
								{
									sqlsrv_rollback($db);
									$Sawa=false;
								}
							}				
						}
					}else
					{
						$msg='Action Didnt complete Successfully!';
						$Sawa=true;
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
	}
}
if (isset($_REQUEST['register']))
{
	$Authority=96;
	$Size=0;
	$excess=0;
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );	
	
	if (isset($_REQUEST['lrno'])) { $lrno = $_REQUEST['lrno']; }
	if (isset($_REQUEST['plotno'])) { $plotno = $_REQUEST['plotno']; }
	if (isset($_REQUEST['MotherPlotNo'])) { $MotherPlotNo = $_REQUEST['MotherPlotNo']; }
	if  (isset($_REQUEST['SiteValue'])) { $SiteValue = $_REQUEST['SiteValue']; }
	if  (isset($_REQUEST['TitleYear'])) { $TitleYear = $_REQUEST['TitleYear']; }
	if  (isset($_REQUEST['ApplicationID'])) { $ApplicationID = $_REQUEST['ApplicationID']; }
	

	/*$sql="select 1 from Land where lrn='$lrno' and plotno='$plotno'";	
	$result = sqlsrv_query($db, $sql,$params,$options);
 	echo $sql; 
	$recs=sqlsrv_num_rows($result);

	if ((double)$recs==0)
	{	
		$msg = "The Plot Does not Exits";			
	} else
	{*/
		$Balance=0;
		$PenaltyBalance=0;
		$sql="select 
			(select Value from dbo.fnFormData($ApplicationID) where FormColumnID='13270') Authority,
			(select Value from dbo.fnFormData($ApplicationID) where FormColumnID='135') Size";
		$rst=sqlsrv_query($db,$sql);
		while ($rw = sqlsrv_fetch_array($rst, SQLSRV_FETCH_ASSOC)){
			$Authority=$rw['Authority'];
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
		
		/* $sql="insert into land (lrn,PlotNo,RatesPayable,SiteValue,TitleYear,Balance,PenaltyBalance) values('$lrno','$plotno','$RatesPayable','$SiteValue','$TitleYear','$Balance','$PenaltyBalance')"; */
		$sql="if exists(select 1 from Land where lrn='$lrno' and plotno='$plotno') begin Update land set RatesPayable=$RatesPayable,LocalAuthorityID=$Authority,TitleYear='$TitleYear',SiteValue='$SiteValue',Balance=0 where lrn='$lrno' and plotno='$plotno' END else BEGIN			
		insert into land (lrn,PlotNo,RatesPayable,SiteValue,TitleYear,Balance,PenaltyBalance) values('$lrno','$plotno','$RatesPayable','$SiteValue','$TitleYear','$Balance','$PenaltyBalance') END";
		
		//echo $sql;
		//$msg = "Rates Payable: ".$RatesPayable.'<br> Penalty Payable: '.$PenaltyPayable;	
		
		$result=sqlsrv_query($db,$sql);
		if($result){			
			$msg="Plot registered and billed successfully";			
		}else{
			DisplayErrors();
			//echo $sql;
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

$s_sql="select c.*,bt.BusinessTypeName,sh.ServiceStatusID,sh.ServiceHeaderType,s.ServiceName,S.ServiceCategoryID,la.plotno,la.lrn,l.upn,l.LocalAuthorityID,isnull(l.laifomsUPN,l.upn)upnn,l.RatesPayable,l.Balance,la.Registered
	from Customer c 
	join ServiceHeader sh on sh.CustomerID=c.CustomerID
	join services s on sh.ServiceID=s.ServiceID
	left join LandApplication la on la.ServiceHeaderID=sh.ServiceHeaderID
	left join BusinessType bt on bt.BusinessTypeID=c.BusinessTypeID
	left join Land l on la.lrn=l.lrn AND LA.plotno=l.plotno 
	where sh.ServiceHeaderID=$ApplicationID and (l.localauthorityID is null or l.localauthorityID=$Authority)";

$s_result=sqlsrv_query($db,$s_sql);
//echo $s_sql;

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
		//$Authority=$row['LocalAuthorityID'];
	}
	
	

	/* $sql="	select value upn from fnFormData($ApplicationID) where FormColumnID='12265'";
	$result=sqlsrv_query($db,$sql);
	if ($result)
	{	
		while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
		{
			$upn=$row['upn'];			
		}
	}  */	
	
	//echo $Registered;
	
	/* $sql="exec spBillPlot '$lrn','$plotno'";
	$result=sqlsrv_query($db,$sql);
	if(!$result)
	{
		DisplayErrors();
	} */

	$sql="select RatesPayable,PenaltyBalance,Balance from land where lrn='$lrn' and plotno='$plotno' and upn='$upn'";
	$result=sqlsrv_query($db,$sql);
	if ($result)
	{	
		while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
		{
			$Balance=$row['Balance'];
			$RatesPayable=$row['RatesPayable'];
			$PenaltyBalance=$row['PenaltyBalance'];
		}
	} 
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
		<table width="75%">
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
				<td width="50%">Rates Per Year: </td>
				<td width="50%"><b><?php echo number_format($RatesPayable,2); ?></b></td>
			</tr>
			<tr>
				<td width="50%">Current Balance (Payable): </td>
				<td width="50%">
					  <div class="input-control text" data-role="input-control">
						  <input name="Balance" type="text" id="Balance" value="<?php echo number_format($Balance,2); ?>" placeholder="">						  
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
			<td width="50%"></td>
			<td>
				<input name="Button" type="button" onclick="loadmypage('land_registration.php?save=1&ApplicationID=<?php echo $ApplicationID ?>&CustomerID=<?php echo $CustomerID ?>&CurrentStatus=<?php echo $CurrentStatus ?>&ServiceHeaderType=<?php echo $ServiceHeaderType ?>&NextStatus='+this.form.NextStatus.value+'&RatesPayable='+this.form.Balance.value+'&plotno=<?php echo $plotno ?>&lrn=<?php echo $lrn ?>&upn=<?php echo $upn ?>&Notes='+this.form.Notes.value,'content','loader','listpages','','LAIFOMS_LAND','<?php echo $ApplicationID ?>')" value="Save">
								
				<input name="Button" type="button" onclick="loadpage('plots.php?add=1&ApplicationID=<?php echo $ApplicationID ?>','content','<?php echo $ApplicationID ?>')" value="Register">
			</td>
			</tr>			
		</table>
		
		<hr>
		<legend>Current Owner(s)</legend> 		
		<table class="table striped hovered dataTable" id="dataTables-1">
			<thead>
				<tr>
					<th class="text-left"><a href="#" onclick="loadmypage('clients_list.php?save=1&ApplicationID=<?php echo $ApplicationID ?>&CustomerID=<?php echo $CustomerID ?>&CurrentStatus=<?php echo $CurrentStatus ?>&NextStatus='+this.form.NextStatus.value+'&Notes='+this.form.Notes.value,'content','loader','listpages','','applications','<?php echo $_SESSION['RoleCenter'] ?>')">Approve for <?php echo $CustomerName; ?></a></th>					
				</tr>
				<tr>
					<th width="14%" class="text-left">OwnerName</th>
					<th width="12%" class="text-left">Town</th>
					<th width="20%" class="text-left">Location</th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table> 
		
	</div>
	</form>
</body>


