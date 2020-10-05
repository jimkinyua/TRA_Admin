<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];

if (isset($_REQUEST['ApplicationID'])) { $ApplicationID = $_REQUEST['ApplicationID']; }

$PlotNo='';
$LRN='';
$ServiceHeaderType='';

//get the customer Details

$s_sql="select c.*,bt.BusinessTypeName,sh.ServiceStatusID,sh.ServiceHeaderType,s.ServiceName,S.ServiceCategoryID,la.FromDate,la.ToDate
	from Customer c 
	join ServiceHeader sh on sh.CustomerID=c.CustomerID
	join services s on sh.ServiceID=s.ServiceID
	join HireApplication la on la.ServiceHeaderID=sh.ServiceHeaderID
	left join BusinessType bt on bt.BusinessTypeID=c.BusinessTypeID 
	where sh.ServiceHeaderID=$ApplicationID";
//echo $s_sql;
$s_result=sqlsrv_query($db,$s_sql);

if ($s_result){
	
	while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC)){			
		$BusinessType=$row['CustomerTypeName'];
		$CustomerId=$row['CustomerID'];
		$CustomerName=$row['CustomerName'];
		$ServiceID=$row['ServiceID'];
		$ServiceName=$row['ServiceName'];
		$CurrentStatus=$row['ServiceStatusID'];
		$BusinessName=$row['BusinessName'];
		$ServiceCategoryID=$row['ServiceCategoryID'];
		$ServiceHeaderType=$row['ServiceHeaderType'];
		$FromDate=$row['FromDate'];
		$ToDate=$row['ToDate'];
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
	}
}


?>
    <link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/prettify/prettify.css" rel="stylesheet">
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
				<td width="50%">Service Applied: </td>
				<td width="50%"><?php echo $ServiceName; ?> </td>
			</tr>
			<tr>
				<td width="50%">From: </td>
				<td width="50%"><?php echo $FromDate; ?> </td>
			</tr>			
			<tr>
				<td width="50%">To: </td>
				<td width="50%"><?php echo $ToDate; ?> </td>
			</tr>			
			<tr>
				<td colspan="2"></td>
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
				<input name="Button" type="button" onclick="loadmypage('clients_list.php?save=1&ApplicationID=<?php echo $ApplicationID ?>&CustomerID=<?php echo $CustomerID ?>&CurrentStatus=<?php echo $CurrentStatus ?>&ServiceHeaderType=<?php echo $ServiceHeaderType ?>&NextStatus='+this.form.NextStatus.value+'&Notes='+this.form.Notes.value,'content','loader','listpages','','applications','<?php echo $_SESSION['RoleCenter'] ?>')" value="Save">
			</td>
			</tr>			
		<!--</table>
		<hr>
		<legend>Current Owner(s)</legend> 		
		<table class="table striped hovered dataTable" id="dataTables-1">
			<thead>
				<tr>
					<th class="text-left"><a href="#" onclick="loadmypage('clients_list.php?save=1&ApplicationID=<?php echo $ApplicationID ?>&CustomerID=<?php echo $CustomerID ?>&CurrentStatus=<?php echo $CurrentStatus ?>&NextStatus='+this.form.NextStatus.value+'&Notes='+this.form.Notes.value,'content','loader','listpages','','applications','<?php echo $_SESSION['RoleCenter'] ?>')">Approve for <?php echo $CustomerName; ?></a></th>
					<th colspan="5" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
				</tr>
				<tr>
					<th width="20%" class="text-left">Permit No</th>
					<th width="60%" class="text-left">Business Name</th>
					<th width="20%" class="text-left">Permit Cost</th>
				</tr>
			</thead>

			<tbody>
			</tbody>
		</table> -->
		
	</div>
	</form>
</body>


