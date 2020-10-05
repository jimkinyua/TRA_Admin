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
$UserID = $_SESSION['UserID'];

if (isset($_REQUEST['ApplicationID'])) { $ApplicationID = $_REQUEST['ApplicationID']; }

$HouseNumber='';
$EstateID='';
$EstateName='';



if (isset($_REQUEST['refresh']))
{

	$ApplicationID=$_REQUEST['ApplicationID'];
	$CustomerID=$_REQUEST['CustomerID'];
	$CurrentStatus=$_REQUEST['CurrentStatus'];
	$NextStatus=$_REQUEST['NextStatus'];
	$Notes=$_REQUEST['Notes'];
	$NextStatusID=$NextStatus;
	
	$BillAmount=$_REQUEST['RentPayable'];
	$EstateID=$_REQUEST['EstateID'];
	$HouseNumber=$_REQUEST['HouseNumber'];
	$uhn=$_REQUEST['uhn'];
	
	$sql="EXEC spRefreshHousing '$HouseNumber'";
	$result=sqlsrv_query($db,$sql);

	if ($result)
	{
		$msg="Refresh Done for the House Statement";
	}else{
		DisplayErrors();
	}	
}

if (isset($_REQUEST['save']) && $_REQUEST['NextStatus']!='')
{

	$ApplicationID=$_REQUEST['ApplicationID'];
	$CustomerID=$_REQUEST['CustomerID'];
	$CurrentStatus=$_REQUEST['CurrentStatus'];
	$NextStatus=$_REQUEST['NextStatus'];
	$Notes=$_REQUEST['Notes'];
	$NextStatusID=$NextStatus;
	
	$BillAmount=$_REQUEST['RentPayable'];
	$EstateID=$_REQUEST['EstateID'];
	$HouseNumber=$_REQUEST['HouseNumber'];
	$uhn=$_REQUEST['uhn'];
	
	$Penalty=0;
	
	$InvoiceNo='';
	
/* 	print_r($_REQUEST);
	exit; */
	
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

	$BillYear=date('Y');
	$BillMonth=(int)date('m');
	$mDescription='Bill '.$BillMonth.'-'.$BillYear;	
	

	if ($NextStatus=='')
	{
		break;		
	}

	$sql="select UHN from Tenancy where EstateID='$EstateID' and HouseNumber='$HouseNumber'";
	$s_result=sqlsrv_query($db,$sql);
	
	if ($s_result)
	{					
		while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
		{			
			$uhn=$row['UHN'];
		}
	}

	$sql="exec spBillHouse_One '$uhn'";
	$result=sqlsrv_query($db,$sql);

	$sql="EXEC spRefreshHousing '$HouseNumber'";
	$result=sqlsrv_query($db,$sql);
	
	$sql="select UHN,Balance Balance from Tenancy where EstateID='$EstateID' and HouseNumber='$HouseNumber'";
	$s_result=sqlsrv_query($db,$sql);
	//echo $sql;
	if ($s_result)
	{					
		while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
		{			
			$uhn=$row['UHN'];


			$Balance=$row['Balance'];
		}
	}
	
	$records=0;
	$sql="select 1 from HouseReceipts where uhn='$uhn' and DocumentNo='$mDescription' and BillSent=1 and exists(select 1 from invoiceheader where invoiceheaderid='$mDescription')";
	$s_result=sqlsrv_query($db,$sql,$params,$options);

	if ($s_result)
	{					
		$records=sqlsrv_num_rows($s_result);
	}
	
	//echo $mDescription .'<br>'.$LastBillNumber.'<br>'.$sql;	
	//exit;
	
	if($records>0)
	{
		$msg="This Bill is already sent to the tenant ";		
	}else
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

		$s_sql="select EstateName from Estates where EstateID=$EstateID";
		$s_result=sqlsrv_query($db,$s_sql);
		while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
		{			
			$EstateName=$row['EstateName'];
		}		
		
		//$Location=$SubCounty.'/'.$WardName.'/'.$Zone;
		//$Description='(House No'.$HouseNumber.'Estate: '.$EstateName.'),'.$Location;

		$Description='(House No'.$HouseNumber.'-'.$mDescription.')';
		
		$s_sql="select * from Customer where CustomerID=$CustomerID";
		$s_result=sqlsrv_query($db,$s_sql);
		//echo $s_sql;
		if ($s_result)
		{					
			while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
			{			
				$CustomerEmail=$row['Email'];
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
				
				$InvoiceHeader="";
				$InvoiceDate= date("d/m/Y");
				$Chargeable=0;
				$Sawa=true;
				$msg='';		
				
				$s_sql="select sh.ServiceID
				 from  serviceheader sh where sh.ServiceHeaderID=$ApplicationID";
				
				$s_result=sqlsrv_query($db,$s_sql);
				
				if ($s_result)
				{						
					while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
					{						
						$ServiceID=$row['ServiceID'];
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
					$s_sql="set dateformat dmy insert into InvoiceHeader (InvoiceDate,InvoiceNo,ServiceHeaderID,CustomerID,Amount,CreatedBy) 
					Values('$InvoiceDate','$InvoiceNo','$ApplicationID',$CustomerID,'$BillAmount','$UserID') SELECT SCOPE_IDENTITY() AS ID";
					$s_result1 = sqlsrv_query($db, $s_sql);
					//echo 'invoiceheader done';		
					if ($s_result1)
					{	
						//echo 'after invoiceheader';
						$InvoiceHeaderID=lastid($s_result1);
						
						//insert into invoiceLines
			
						$s_sql="set dateformat dmy 
						insert Into InvoiceLines (InvoiceHeaderID,ServiceHeaderID,ServiceID,Description,Amount,CreatedBy) 
						Values($InvoiceHeaderID,$ApplicationID,$ServiceID,'$Description',$BillAmount,'$UserID')";						
						$s_result2 = sqlsrv_query($db, $s_sql);
						
						//echo 'invoiceheader lines done';	
						
						if ($s_result2)
						{
							$Sawa=false;
						}else
						{
							echo $s_sql;
							echo '<br>'.DisplayErrors();
							$Sawa=false;
						}
						
						$sql="Set dateformat dmy update Tenancy set Balance=$Balance,CustomerID=$CustomerID where HouseNumber='$HouseNumber' and EstateID='$EstateID'";
						$s_result3 = sqlsrv_query($db, $sql);
						if ($s_result3)
						{
							$Sawa=true;
						}else
						{									
							$Sawa=false;
							DisplayErrors();
						}
						
						$sql="insert into HouseInvoices(InvoiceHeaderID,EstateID,HouseNumber,Amount)
									  Values ('$InvoiceHeaderID','$EstateID','$HouseNumber','$BillAmount')";
						
						//echo $sql;
						$s_result4 = sqlsrv_query($db, $sql);
						if ($s_result4)
						{
							$Sawa=true;
						}else
						{									
							$Sawa=false;
							//echo $sql;
							DisplayErrors();
						}
						
					}else{
						echo $s_sql.'<br>';
						DisplayErrors();
					}
					if($s_result1 && $s_result2 &&  $s_result3 && $s_result4 )
					{
						
						sqlsrv_commit($db);

						$ViewBtn  = '<a href="reports.php?rptType=Invoice&ServiceHeaderID='.$ApplicationID.'&InvoiceHeaderID='.$InvoiceHeaderID.'" target="_blank">Click to View</a>';

						$msg="Invoice No $InvoiceHeaderID Created Successfully. $ViewBtn";

						//$msg="Invoice Created Successfully";
						$Sawa=true;							
						// $Remark=$Description;
						// $feedBack=createInvoice($db,$ApplicationID,$cosmasRow,$Remark,'',$InvoiceHeaderID);
						// $msg=$feedBack[1];
						$mail=true;					

					}else
					{
						sqlsrv_rollback($db);
						$Sawa=false;
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

//get the customer Details

$s_sql="select distinct c.*,bt.BusinessTypeName,sh.ServiceStatusID,s.ServiceName,
	S.ServiceCategoryID,ha.HouseNumber,ha.EstateID,es.EstateName,tn.MonthlyRent,tn.Balance,ht.HouseTypeName HouseType
	from Customer c 
	join ServiceHeader sh on sh.CustomerID=c.CustomerID
	join services s on sh.ServiceID=s.ServiceID
	join HouseApplication ha on ha.ServiceHeaderID=sh.ServiceHeaderID
	join Estates es on ha.estateid=es.EstateID
	left join BusinessType bt on bt.BusinessTypeID=c.BusinessTypeID 
	left join Tenancy tn on ha.EstateID=tn.EstateID AND ha.HouseNumber=tn.HouseNumber
	join Houses h on tn.EstateID=h.EstateID and tn.HouseNumber=h.HouseNumber
	left join HouseTypes ht on h.HouseTypeID=ht.HouseTypeID
	where sh.ServiceHeaderID=$ApplicationID";
	//echo $s_sql;
	$s_result=sqlsrv_query($db,$s_sql);


if ($s_result){
	
	while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC)){			
		$BusinessType=$row['CustomerTypeName'];
		$CustomerID=$row['CustomerID'];
		$CustomerName=$row['CustomerName'];
		$ServiceID=$row['ServiceID'];
		$ServiceName=$row['ServiceName'];
		$CurrentStatus=$row['ServiceStatusID'];
		$ServiceCategoryID=$row['ServiceCategoryID'];
		$HouseNumber=$row['HouseNumber'];
		$EstateID=$row['EstateID'];
		$EstateName=$row['EstateName'];
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
		$MonthlyRent=$row['MonthlyRent'];
		$HouseType=$row['HouseType'];
	}
}


?>
    <link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
<body class="metro">
	<form>
	<div class="example">        
		<legend>Applicant Details</legend>
		<table width="75%">
			<tr>
				<td width="50%">Applicant Name: </td>
				<td width="50%"><?php echo $CustomerName; ?> </td>
			</tr>
			<tr>
				<td width="50%">ESTATE: </td>
				<td width="50%"><?php echo $EstateName; ?> </td>
			</tr>
			<tr>
				<td width="50%">HOUSE Number: </td>
				<td width="50%"><?php echo $HouseNumber; ?> </td>
			</tr>
			<tr>
				<td width="50%">HOUSE Type: </td>
				<td width="50%"><?php echo $HouseType; ?> </td>
			</tr>
			<tr>
				<td width="50%">Rant Per month: </td>
				<td width="50%"><b><?php echo number_format($MonthlyRent,2); ?></b></td>
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
					<a href='housestatement.php?EstateID=<?php echo $EstateID ?>&HouseNumber=<?php echo $HouseNumber ?>' class='popupwindow' target='_blank'>House Statement</a>
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
				<input name="Button" type="button" onclick="loadmypage('house_registration.php?save=1&ApplicationID=<?php echo $ApplicationID ?>&CustomerID=<?php echo $CustomerID ?>&CurrentStatus=<?php echo $CurrentStatus ?>&NextStatus='+this.form.NextStatus.value+'&RentPayable='+this.form.Balance.value+'&EstateID=<?php echo $EstateID ?>&HouseNumber=<?php echo $HouseNumber ?>&Notes='+this.form.Notes.value,'content','loader','listpages','','LAIFOMS_HOUSE','<?php echo $ApplicationID ?>')" value="Save">
				
				<input name="Button" type="button" onclick="loadmypage('house_registration.php?refresh=1&ApplicationID=<?php echo $ApplicationID ?>&CustomerID=<?php echo $CustomerID ?>&CurrentStatus=<?php echo $CurrentStatus ?>&NextStatus='+this.form.NextStatus.value+'&RentPayable='+this.form.Balance.value+'&EstateID=<?php echo $EstateID ?>&HouseNumber=<?php echo $HouseNumber ?>&Notes='+this.form.Notes.value,'content','loader','listpages','','LAIFOMS_HOUSE','<?php echo $ApplicationID ?>')" value="Refresh Statement">
			</td>
			</tr>			
		</table>
		
		<hr>
		<legend>Current Tenant(s)</legend> 		
		<table class="table striped hovered dataTable" id="dataTables-1">
			<thead>
				<tr>
					<th class="text-left"><a href="#" onclick="loadmypage('clients_list.php?save=1&ApplicationID=<?php echo $ApplicationID ?>&CustomerID=<?php echo $CustomerID ?>&CurrentStatus=<?php echo $CurrentStatus ?>&NextStatus='+this.form.NextStatus.value+'&Notes='+this.form.Notes.value,'content','loader','listpages','','applications','<?php echo $_SESSION['RoleCenter'] ?>')">Approve for <?php echo $CustomerName; ?></a></th>
					<th colspan="5" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
				</tr>
				<tr>
					<th width="14%" class="text-left">Monthly Rent</th>
					<th width="12%" class="text-left">Balance</th>
					<th width="20%" class="text-left">Current Tenant</th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table> 
		
	</div>
	</form>
</body>


