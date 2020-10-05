<?php
//session_start();
require 'DB_PARAMS/connect.php';
    $MonthlyRent=0;
	$EstateID=$_REQUEST['EstateID'];
	$HouseNumber=$_REQUEST['HouseNumber'];;
	$EstateName='';
	
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
	

	$sql="select lr.DateReceived,lr.DocumentNo,lr.Description,lr.Amount,lr.OtherCharges,lr.Balance,lr.InvoiceNo
		from Houses l join HouseReceipts lr on lr.HouseNumber=l.HouseNumber 
		where l.HouseNumber='$HouseNumber' order by lr.DateReceived";
	$result=sqlsrv_query($db,$sql);
	while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
	{
		$mdata.='<tr>
						<td>'.$row['DateReceived'].'</td>
						<td>'.$row['DocumentNo'].'</td>
						<td>'.$row['Description'].'</td>
						<td align="right">'.number_format($row['Amount'],0).'</td>
						<td align="right">'.$row['OtherCharges'].'</td>
						<td align="right">'.number_format($row['Balance'],0).'</td>
						<td>'.$row['InvoiceNo'].'</td>
						</tr>';
	}

	$s_sql = "SELECT es.EstateName,isnull(tn.CurrentTenant,c.CustomerName) CurrentTenant,tn.MonthlyRent 
		FROM Estates es 
		join Tenancy tn on tn.EstateID=es.EstateID
		left join Customer c on tn.CustomerID=c.CustomerID where tn.HouseNumber='$HouseNumber'";									
	$s_result = sqlsrv_query($db, $s_sql);
	if ($s_result) 
	{ //connection succesful 
		while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
		{
			$EstateName = $row["EstateName"];
			$CurrentTenant=$row['CurrentTenant'];
			$MonthlyRent=$row['MonthlyRent'];
		}
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>House Statement</title>
	
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
					<td>
						<label>House No</label>
						<div class="input-control text" data-role="input-control">
							<input type="text" id="HouseNumber" name="HouseNumber" value="<?php echo $HouseNumber; ?>" disabled="disabled"></input>
							<button class="btn-clear" tabindex="-1"></button>
						</div>						
					</td>
					<td colspan="2">
						<label>Estate</label>
						<div class="input-control text" data-role="input-control">
							<input type="text" id="Estate" name="Estate" value="<?php echo $EstateName; ?>" disabled="disabled"></input>
							<button class="btn-clear" tabindex="-1"></button>
						</div>						
					</td>
					<td colspan="2">
						<label>Current Tenant</label>
						<div class="input-control text" data-role="input-control">
							<input type="text" id="CurrentTenant" name="CurrentTenant" value="<?php echo $CurrentTenant; ?>" disabled="disabled"></input>
							<button class="btn-clear" tabindex="-1"></button>
						</div>						
					</td>
					<td colspan="2">
						<label>Monthly Rent</label>
						<div class="input-control text" data-role="input-control">
							<input type="text" id="MonthlyRent" name="MonthlyRent" value="<?php echo number_format($MonthlyRent,2); ?>" disabled="disabled"></input>
							<button class="btn-clear" tabindex="-1"></button>
						</div>						
					</td>					
				</tr>
                <tr>
                    <th class="text-left">DateReceived</th>
                    <th class="text-left">Bill No</th>
					<th class="text-left">Description</th>
                    <th class="text-left">Amount</th>
					<th class="text-left">Other Charges</th>
                    <th class="text-left">Balance</th>
					<th class="text-left">Invoice No</th>
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
