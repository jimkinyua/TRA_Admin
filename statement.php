<?php
//session_start();
require 'DB_PARAMS/connect.php';

$plotno=$_REQUEST['plotno'];
$lrn=$_REQUEST['lrn'];
$upn=$_REQUEST['upn'];
$authority=$_REQUEST['authority'];
$Owner='';
$RatesPayable=0;
$GroundRent=0;
$OtherCharges=0;
$RatesBalance=0;
$GroundRentBalance=0;
$OtherChargesBalance=0;
$TotalBalance=0;
$Area=0;
$SiteValue=0;
$PhysicalLocation='';


$qry="exec spBillPlot_test5 '$upn'";
			
$s_result = sqlsrv_query($db, $qry);

$qry="exec spRefreshLandStatement5 '$upn'";
$s_result = sqlsrv_query($db, $qry);

	
	$sql="select lr.DateReceived,lr.DocumentNo,lr.Description,lr.Amount,lr.InvoiceNo,lr.Balance,lr.Penalty,lr.GroundRent,lr.OtherCharges,lr.Principal,lr.PenaltyBalance,isnull(lr.Waiver,0) Waiver
	from LAND l 
	join LANDRECEIPTS lr on lr.upn=l.upn	
	where l.PlotNo='$plotno' and l.LRN='$lrn' and lr.LocalAuthorityID='$authority' and l.upn='$upn' 
	order by lr.DateReceived,lr.LandReceiptsId";


$result=sqlsrv_query($db,$sql);
while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
{
	$mdata.='<tr>
		<td>'.date("d/m/Y",strtotime($row['DateReceived'])).'</td>
		<td>'.$row['DocumentNo'].'</td>
		<td>'.$row['Description'].'</td>
		<td>'.number_format($row['Amount'],2).'</td>
		<td>'.number_format($row['Principal'],2).'</td>
		<td>'.number_format($row['Penalty'],2).'</td>
		<td>'.number_format($row['PenaltyBalance'],2).'</td>
		<td>'.number_format($row['GroundRent'],2).'</td>
		<td>'.number_format($row['OtherCharges'],2).'</td>
		<td>'.number_format($row['Waiver'],2).'</td>
		<td>'.number_format($row['Balance'],2).'</td>
		</tr>';
}

$s_sql = "SELECT LaifomsOwner Owner,RatesPayable,GroundRent,OtherCharges,PrincipalBalance,PenaltyBalance,GroundRentBalance,OtherChargesBalance,(select Balance from dbo.fnlastplotrecord($upn)) Balance,Area,SiteValue,upn,laifomsUPN,zw.ZoneWardName  
	FROM Land 
	left join ZonesAndWards zw on Land.ZoneWardCode=zw.ZoneWardCode and Land.LocalAuthorityID=zw.LocalAuthorityID
	where PlotNo='$plotno' and LRN='$lrn' and upn='$upn'";	
//echo $s_sql;								
$s_result = sqlsrv_query($db, $s_sql);
if ($s_result) 
{ //connection succesful 
	while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
	{
		$Owner = $row["Owner"];
		$RatesPayable=$row['RatesPayable'];
		$PenaltyBalance=$row['PenaltyBalance'];
		$GroundRent=$row['GroundRent'];
		$OtherCharges=$row['OtherCharges'];
		$RatesBalance=$row['Balance'];
		$GroundRentBalance=$row['GroundRentBalance'];
		$OtherChargesBalance=$row['OtherChargesBalance'];
		$TotalBalance=$row['Balance'];
		$Area=$row['Area'];
		$SiteValue=$row['SiteValue'];
		$laifomsUPN=$row['laifomsUPN'];
		$PhysicalLocation=$row['ZoneWardName'];
	}
}


?>
<!DOCTYPE html>
<html>
<head>
	<title>Land Statement</title>
	
	<link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
	<link href="css/jquery.dataTables.min.css" rel="stylesheet">
	
	<script src="js/jquery/jquery.min.js"></script>
	<script src="js/jquery/jquery.widget.min.js"></script>
	<script src="js/metro.min.js"></script>   
    <script src="js/jquery/jquery.dataTables.js"></script>
	<script type="text/javascript">
	var profiles =
	{

		window800:
		{
			height:800,
			width:800,
			status:1
		},

		window200:
		{
			height:200,
			width:200,
			status:1,
			resizable:0
		},

		windowCenter:
		{
			height:300,
			width:400,
			center:1
		},

		windowNotNew:
		{
			height:300,
			width:400,
			center:1,
			createnew:0
		},

		windowCallUnload:
		{
			height:300,
			width:400,
			center:1,
			onUnload:unloadcallback
		},

	};

	function unloadcallback(){
		alert("unloaded");
	};


   	$(document).ready(function(){
		$('#example').DataTable();
		
   		$(".popupwindow").popupwindow(profiles);
		console.log("from here", $(".popupwindow").popupwindow);
   	});
	</script>
	
</head>
<body class="metro">
	<div class="example" class="display" cellspacing="0" width="100%">
            <table class="table striped bordered hovered">
                <thead>
				<tr>
					<td colspan="11">
						<table width="100%">
							<tr>
								<td width="20%">
									<label>UPN</label>
									<?php echo $upn; ?>						
								</td>
								<td width="20%">
									<label>Laifoms UPN</label>
									<?php echo $laifomsUPN; ?>						
								</td>
								<td width="20%">
									<label>Plot No</label>
									<?php echo $plotno; ?>						
								</td>
								<td width="20%">
									<label>Block</label>
									<?php echo $lrn; ?>						
								</td>										
								<td width="20%">
									<label>Owner</label>
									<?php echo $Owner; ?>						
								</td>
								
							</tr>
						</table>
					</td>
										
				</tr>
				<tr>
					<td colspan="11">
						<table width="100%">
							<tr>
								<td width="20%">
									<label>Acrages</label>
									<?php echo number_format($Area,2); ?>						
								</td>	
								<td width="20%">
									<label>Annual Land Rate</label>
									<?php echo number_format($RatesPayable,2); ?>						
								</td>
								<td width="20%">
									<label>Ground Rent</label>
									<?php echo number_format($GroundRent,2); ?>						
								</td>
								<td width="20%">
									<label>Other Charges</label>
									<?php echo number_format($OtherCharges,2); ?>						
								</td>
								<td width="20%">
									<label>Site Value</label>
									<?php echo number_format($SiteValue,2); ?>						
								</td>
									
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="11">
						<table width="100%">
							<tr>
								<td>
									<label>Physical Location</label>
									<?php echo $PhysicalLocation; ?>						
								</td>
								<td>
									<label>Rates Balance</label>
									<?php echo number_format($RatesBalance,2); ?>						
								</td>
								<td>
									<label>Penalty Balance</label>
									<?php echo number_format($PenaltyBalance,2); ?>						
								</td>
								<td>
									<label>Ground Rent Balance</label>
									<?php echo number_format($GroundRentBalance,2); ?>						
								</td>
								<td>
									<label>Other Charges Balance</label>
									<?php echo number_format($OtherChargesBalance,2); ?>						
								</td>
								
							</tr>
						</table>
					</td>
				</tr>
                <tr>
                    <th class="text-left">DateReceived</th>
                    <th class="text-left">Bill No</th>
					<th class="text-left">Description</th>
                    <th class="text-left">Amount</th>
                    <th class="text-left">Principal</th>
                    <th class="text-left">Penalty</th>
                     <th class="text-left">Pen. Balance</th>
                    <th class="text-left">Grount Rent</th>
                    <th class="text-left">Other Charges</th>
                    <th class="text-left">Waiver</th>
                    <th class="text-left">Balance</th>					
                </tr>
                </thead>
				<tbody>
					<?php
						echo $mdata;
					?>
                <tbody>
                
                </tbody>
            </table>
        </div>
</body>
</html>
