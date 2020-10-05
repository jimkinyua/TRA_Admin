<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];

$OwnerName='';
$MotherPlotNo='';
$RatesPayable='';
$PostalCode='';
$Town='';
$Mobile='';
$Email='';
$Url='';
$QrString='';

$upn='';
$plotno='';
$lrn='';
$titleno='';
$names='';
$ownernames='';
$upn='0';
$FirmID='';



if (isset($_REQUEST['plotno'])) { $plotno = $_REQUEST['plotno']; }
if (isset($_REQUEST['lrno'])) { $lrno = $_REQUEST['lrno']; }
if (isset($_REQUEST['upn'])) { $upn = $_REQUEST['upn']; }
if (isset($_REQUEST['owner'])) { $ownernames = $_REQUEST['owner']; }
if (isset($_REQUEST['FirmID'])) { $FirmID = $_REQUEST['FirmID']; }

//print_r ($_REQUEST);
if (isset($_REQUEST['delete']))
{
	$upn=$_REQUEST['upn'];
	$sql="Delete from land where UPN=$upn";
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Plot Deleted Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Action failed";			
	}	
}


if (isset($_REQUEST['Search']))
{
	$upn=$_REQUEST['upn'];
	$plotno=$_REQUEST['plotno'];
	$lrno=$_REQUEST['lrno'];
	$FirmID=$_REQUEST['FirmID'];
	
	print_r ($_REQUEST);
	exit;
	
	if ($upn!="")
	{
		$sql="select * from LAND where laifomsUPN='".$upn."'";
	}else
	{
		$sql="select * from LAND where lrn='".$lrn."' and PlotNo='".$plotno."'";
	}
	
	$result=sqlsrv_query($db,$sql);
	while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
	{
		$mdata.='<tr>
						<td>'.$row['lrn'].'</td>
						<td>'.$row['upn'].'</td>
						<td>'.$row['RatesPayable'].'</td>
						<td>'.$row['PrincipalBalance'].'</td>
						</tr>';
	}
}

?>
    <link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/prettify/prettify.css" rel="stylesheet">

<body class="metro">
	<div class="example">        
		<legend>Current Owner(s)</legend> 
		<form>
		<table class="table striped hovered dataTable" id="dataTables-1">
			<thead>
				<tr>
				<th class="text-left"><a href="#" onClick="loadpage('plots.php?add=1','content')">New Plot</a></th>
					<th colspan="5" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
				</tr>
				<tr>
					<td><label colspan="2">Firm Name</label>
						<div class="input-control select" data-role="input-control">						
						<select name="FirmID"  id="FirmID">
							<option value="0" selected="selected"></option>
							<?php 
							$s_sql = "SELECT FirmID,FirmName FROM LandFirms ORDER BY FirmName";									
							$s_result = sqlsrv_query($db, $s_sql);
							if ($s_result) 
							{ //connection succesful 
								while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
								{
									$s_id = $row["FirmID"];
									$s_name = $row["FirmName"];
									if ($FirmID==$s_id) 
									{
										$selected = 'selected="selected"';
									} else
									{
										$selected = '';
									}												
								 ?>
							<option value="<?php echo $s_id; ?>" <?php echo $selected; ?>><?php echo $s_name; ?></option>
							<?php 
								}
								
							}
							?>
					  </select>					
					</div>
					</td>
					<td>
						<label>UPN</label>
						<div class="input-control text" data-role="input-control">
							<input type="text" id="upn" name="upn" value="<?php echo $upn; ?>"></input>
							<button class="btn-clear" tabindex="-1"></button>
						</div>						
					</td>
					<td>
						<label>Block No</label>
						<div class="input-control text" data-role="input-control">
							<input type="text" id="lrno" name="lrno" value="<?php echo $lrno; ?>"></input>
							<button class="btn-clear" tabindex="-1"></button>
						</div>						
					</td>					
					<td>
						<label>Plot No</label>
						<div class="input-control text" data-role="input-control">
							<input type="text" id="plotno" name="plotno" value="<?php echo $plotno; ?>"></input>
							<button class="btn-clear" tabindex="-1"></button>
						</div>						
					</td>
					<td colspan="2">
						<label>Names</label>
						<div class="input-control text" data-role="input-control">
							<input type="text" id="ownernames" name="ownernames" value="<?php echo $ownernames; ?>"></input>
							<button class="btn-clear" tabindex="-1"></button>
						</div>						
					</td>					
					<td>
						<br><br>
											
						 <input name="btnSearch" type="button" onclick="loadmypage('land_from_revenue.php?'+
						'&upn='+this.form.upn.value+
						'&plotno='+this.form.plotno.value+
						'&lrno='+this.form.lrno.value+
						'&ownernames='+this.form.ownernames.value+
						'&search=1','content','loader','listpages','','IMPORTED_LAND_LIST','upn='+this.form.upn.value+':plotno='+this.form.plotno.value+':lrn='+this.form.lrno.value+':lrn='+this.form.ownernames.value+':firmid='+this.form.FirmID.value+'')" value="Search"> 
					
					</td>
				</tr>				
				<tr>
					<th  class="text-left">UPN</th>
					<th  class="text-left">lrno</th>
					<th  class="text-left">Plot Number</th>
					<th  class="text-left">Area (Ha)</th>
					<th  class="text-left">Farm Name</th>
					<th  class="text-left">Rates Payable</th>
					<th  class="text-left">Balance</th>
					<th  class="text-left">OwnerName</th>
					<th  class="text-left"></th>
				</tr>
			</thead>

			<tbody>
				<tbody>
					<?php
						echo $mdata;
					?>
                <tbody>			
			</tbody>
		</table> 
		<form>
	</div>
</body>


