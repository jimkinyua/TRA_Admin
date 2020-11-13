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

$s_sql="select c.*,f.ServiceHeaderType,bt.CustomerTypeName,sh.ServiceStatusID,sh.ServiceHeaderID,bz.ZoneName,w.WardName,sc.SubCountyName,s.ServiceName,sh.ServiceID,sh.SubSystemID,S.ServiceCategoryID
	from Customer c 
	join ServiceHeader sh on sh.CustomerID=c.CustomerID
	join services s on sh.ServiceID=s.ServiceID
	join Forms f on sh.FormID=f.FormID
	left join CustomerType bt on bt.CustomerTypeID=c.BusinessTypeID 
	left join BusinessZones bz on sh.BusinessZoneID=bz.ZoneID
	left join Wards w on bz.wardid=w.wardid
	left join subcounty sc on w.subcountyid=sc.subcountyid
	
	where sh.ServiceHeaderID=$ApplicationID";

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
		console.log("from here", $(".popupwindow").popupwindow);
   	});
</script>


 


<div class="example">
	<?php 


if(isset($_POST['search'])){
	$searchItem = $_POST['search'];
}


 	

 	$sql= "select c.CustomerName, ic.AverageScore 
		as Rating, c.Website, c.PhysicalAddress, c.Email, c.Mobile1,sh.ServiceCategoryID 
		from ServiceHeader sh 
		join Inspections ins on sh.ServiceHeaderID = ins.ServiceHeaderID 
		join ChecklistResults cr on cr.InspectionID = ins.InspectionID 
		join Customer c on c.CustomerID = sh.CustomerID 
		join InspectionComments ic on ic.InspectionID = ins.InspectionID
		where ServiceCategoryID = 2033 and ServiceStatusID = 4 
		and CustomerName like '%$searchitem%' 
		Group By c.CustomerName,c.Website,c.PhysicalAddress,c.Email,
		c.Mobile1,sh.ServiceCategoryID,ic.AverageScore"; 

	// echo $sql;
	$result = sqlsrv_query($db, $sql);
	if($result){
	?>
<!-- 	<form action="" method="post" name="form1">
		<input type="text" name="search">
		<input type="submit" name="search">
	</form> -->
		<table class="table table-striped" id="example">
			<th width="20%">Name</th>
			<th width="10%">The Score</th>
			<th width="20%">Website</th>
			<th width="10%">Location</th>
			<th width="30%">Email</th>
			<th width="20%">Phone No</th>
			
	<?php
	while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
		$CustomerName = $row['CustomerName'];
		$Rating = $row['Rating'];
		$Website = $row['Website'];
		$Location = $row['Location'];
		$Email = $row['Email'];
		$Mobile1 = $row['Mobile1'];
		
		?>
		<tr>
			<td><?php echo $CustomerName; ?></td>
			<td><?php echo $Rating; ?></td>
			<td><?php echo $Website; ?></td>
			<td><?php echo $Location; ?></td>
			<td><?php echo $Email; ?></td>
			<td><?php echo $Mobile1; ?></td>
		</tr>
	

	<?php
	}

	?>
	<tr>
		<td></td>
		<td></td>
		<td><a href="external_grading/">External Classification Site</a></td>
		<td></td>
		<td></td>
	</tr>
</table>
 
	<?php
}
?>
