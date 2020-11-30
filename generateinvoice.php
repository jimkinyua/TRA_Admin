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
$WardID='';

$InvoiceNo=0;

$ServiceCost=0;

if (isset($_REQUEST['ApplicationID'])) { $ApplicationID = $_REQUEST['ApplicationID']; }



if (isset($_REQUEST['save']) && $_REQUEST['NextStatus']!='')
{
	/* print_r($_REQUEST);
	exit; */
	$ApplicationID=$_REQUEST['ApplicationID'];
	$CustomerID=$_REQUEST['CustomerID'];
	$CurrentStatus=$_REQUEST['CurrentStatus'];
	$NextStatus=$_REQUEST['NextStatus'];
	$Notes=$_REQUEST['Notes'];
	$NextStatusID=$NextStatus;
	$InvoiceNo=$_REQUEST['InvoiceNo'];
	
	
}

$s_sql="select c.*,f.ServiceHeaderType,bt.CustomerTypeName,sbs.SubSystemName,sh.ServiceStatusID,sh.ServiceHeaderID,bz.ZoneName,
w.WardName,s.ServiceName,sh.ServiceID,sh.SubSystemID,S.ServiceCategoryID 
from Customer c 
join ServiceHeader sh on sh.CustomerID=c.CustomerID 
join services s on sh.ServiceID=s.ServiceID 
join Forms f on sh.FormID=f.FormID 
left join CustomerType bt on bt.CustomerTypeID=c.BusinessTypeID 
left join BusinessZones bz on sh.BusinessZoneID=bz.ZoneID 
left join Wards w on bz.wardid=w.wardid 
join SubSystems sbs on sbs.SubSystemID = Sh.SubSystemID
where sh.ServiceHeaderID=$ApplicationID";
// echo $s_sql;
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
		$SubSystemName=$row['SubSystemName'];
		$WardName=$row['WardName'];
		$BusinessZone=$row['ZoneName'];
		$SubSystemID=$row['SubSystemID'];
		
	}
}


$sql="select fn.Value, w.WardName from fnFormData($ApplicationID) fn 
join Wards w on fn.Value=w.WardID
where fn.formcolumnid=11204
";
$res=sqlsrv_query($db,$sql);
while($row=sqlsrv_fetch_array($res,SQLSRV_FETCH_ASSOC))
{
	$WardID=$row['Value'];
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

	$sql="select * from fnFormData($ServiceHeaderID) where formcolumnid=12237";
	$res=sqlsrv_query($db,$sql);
	while($row=sqlsrv_fetch_array($res,SQLSRV_FETCH_ASSOC))
	{
		$SubSystemID=$row['Value'];
	}	
	//echo $SubSystemID.'<BR>';
	$sql="select * from fnServiceCost($ServiceID,$SubSystemID)";
	$result=sqlsrv_query($db,$sql);
	if ($result)
	{
		while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
		{									
			$ServiceCost=$row['Amount'];
		}
		//echo $ServiceCost.'<BR>';
		$OtherCharge=0;
		//With other Charges?
		$sql="select sum (distinct sc.amount)Amount
									from ServiceCharges sc
									join ServicePlus sp on sp.service_add=sc.ServiceID
									join FinancialYear fy on sc.FinancialYearId=fy.FinancialYearID
									join ServiceHeader sh on sh.ServiceID=sp.ServiceID
									join services s1 on sp.ServiceID=s1.ServiceID
									join services s2 on sp.service_add=s2.ServiceID
									and sh.ServiceHeaderID=$ServiceHeaderID
									and fy.isCurrentYear=1
									and sc.SubSystemId=$SubSystemID";
		
		$s_result = sqlsrv_query($db, $sql);
		while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
		{							
			$OtherCharge=$row["Amount"];												
		}
	//echo $OtherCharge.'<BR>';
		$ServiceCost=$ServiceCost+$OtherCharge;
	}
}

	/* $ServiceCost=$ServiceCost-OtherCharge;
  echo $ServiceID.'<br>';
  echo $SubSystemID.'<BR>';
  echo $ServiceCost.'<BR>';
  echo $OtherCharge.'<BR>'; */
  

?>
<script type="text/javascript">
$(document).ready(function(){
   		$(".popupwindow").popupwindow(profiles);
		console.log("from he>>>>re", $(".popupwindow").popupwindow);
   	});
</script>
<div class="example">
   <legend>Applied Service Change</legend>
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
                      <input name="UserID" type="hidden" id="UserID" value="<?php echo $UserID; ?>" disabled="disabled" placeholder="">
						  
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
					
                  </td>   
			</tr>
			
			
            		
          </table> 
          
          
          <input type="reset" value="Cancel" onClick="loadmypage('Inspection_date.php?ApplicationID=<?php echo $ApplicationID; ?>&CurrentStatus=<?php echo $CurrentStatus; ?>','content','loader','listpages','','applications','<?php echo $_SESSION['RoleCenter'] ?>')">

<input type="button" value="Send Invoice" onClick="deleteConfirm2('Are you sure you want to send the invoice','service_approval.php?generateinvoice=1&ApplicationID=<?php echo $ApplicationID; ?>UserID=<?php echo $UserID; ?>&CurrentStatus=<?php echo $CurrentStatus; ?>','content','loader','listpages','','applications','<?php echo $_SESSION['RoleCenter'] ?>')"> 


  <!--	<input name="Button" type="button" onclick="loadmypage('pdf.php.php?save=1,'content','loader','clients')" value="View"> -->

      </fieldset>
  </form>                  
