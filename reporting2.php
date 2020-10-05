<?php
	require 'DB_PARAMS/connect.php';
	require_once('utilities.php');
	require_once('GlobalFunctions.php');
	require_once('county_details.php');
	require_once('ReportingFunctions.php');

	$UserID = $_SESSION['UserID'];
	$AgentID=$UserID;//$_REQUEST['user'];

	$fromDate=date("d/m/Y");
	$toDate=date("d/m/Y");
	
	if ($_REQUEST['search']=="1"){
		//print_r($_REQUEST);
		$report=$_REQUEST['report'];
		$fromDate=$_REQUEST['fromDate'];
		$toDate=$_REQUEST['toDate'];
		
	}else{
		//echo "sio sawa";
	}
	
	//$report='permits';//$_REQUEST['report'];
	$reportFileName=time();	
	
	if ($report=='permits'){
		PermitsList($db,$cosmasRow,$reportFileName,$fromDate,$toDate);
	}else if ($report=='mpesa'){
			mpesaTransactions($db,$cosmasRow,$reportFileName,$fromDate,$toDate);			
	}else if ($report=='pos'){
			posTransactions($db,$cosmasRow,$reportFileName,$fromDate,$toDate,$AgentID);			
	}else if ($report=='rent_balances'){
			rentBalances($db,$cosmasRow,$reportFileName);			
	}else if ($report=='receipts_today'){			
			ReceiptsToday($db,$cosmasRow,$reportFileName);			
	}
	else if ($report=='ReceiptsToday_Service'){			
			ReceiptsToday_Service($db,$cosmasRow,$reportFileName);		//RevenuePerStream	
	}else if ($report=='revenue_stream'){			
			RevenuePerStream($db,$cosmasRow,$reportFileName);		//RevenuePerStream	
	}else if ($report=='PosPerAgent'){			
			PosCollectionPerAgent($db,$cosmasRow,$reportFileName);		//RevenuePerStream	
	}else if ($report=='receipts'){			
			Receipts($db,$cosmasRow,$reportFileName,$fromDate,$toDate,$AgentID);			
	}
	else if ($report=='receipts_summry'){			
			Receipts_Summary($db,$cosmasRow,$reportFileName,$fromDate,$toDate);		
	}
	else if ($report=='tenancy'){			
			Tenancy($db,$cosmasRow,$reportFileName);		
	}
	else if ($report=='receipts_per_stream'){			
			ReceiptsPerStream($db,$cosmasRow,$reportFileName,$fromDate,$toDate);		
	}
	else if ($report=='house_receipts'){			
			HousingReceipts($db,$cosmasRow,$reportFileName,$fromDate,$toDate);		
	}
	else if ($report=='house_invoices'){			
			HousingInvoices($db,$cosmasRow,$reportFileName,$fromDate,$toDate);		
	}
	else if ($report=='receipts_receipted'){			
			Receipts_Receipted($db,$cosmasRow,$reportFileName,$fromDate,$toDate,$UserID);		
	}
	else if ($report=='receipts_deposited'){			
			Receipts_Deposited($db,$cosmasRow,$reportFileName,$fromDate,$toDate);		
	}
	else if ($report=='permits_summary'){			
			Permits_Summary($db,$cosmasRow,$reportFileName,$fromDate,$toDate);		
	}
	$reportfile='pdfdocs/reports/'.$reportFileName.'.pdf';
?>
    <link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/prettify/prettify.css" rel="stylesheet">
<body class="metro">
	<div class="example">
		<legend>REPORTS</legend>   
		<form>
			<table class="example" width="100%">
				<thead>
				<tr>
					<td><label width="20%">Report</label>
						<div class="input-control select" data-role="input-control">						
							<select name="ReportName"  id="ReportName">
								<option value="0" ></option>
								<option value="Services" selected="selected">Services</option>
								<option value="permits">Permits Issued</option>
								<option value="permits_summary">Permits (Summary)</option>
								<option value="mpesa">Mpesa Transactions</option>
								<option value="pos">POS Collection</option>								
								<option value="receipts">Receipts</option>
								<option value="receipts_deposited">Receipts (Deposited)</option>
								<option value="receipts_receipted" >Receipts (Receipted)</option>
								<option value="revenue_stream">Revenue Per Stream</option>	
								<option value="receipts_summry">Receipts(Summary)</option>
								<option value="tenancy">Tenancy</option>
								<option value="receipts_per_stream">Receipts Per Stream</option>
								<option value="house_invoices">Housing Invoices</option>
								<option value="house_receipts">Housing Receipts</option>								
						  </select>					
						</div>
					</td>
					
					
					<td><label>&nbsp;</label>
					<input name="btnSearch" type="button" onclick="loadmypage('GetReports.php?'+
								'&report='+this.form.ReportName.value+	
								'&search=1','content','loader','listpages','')" value="Search">
					</td>
				</tr>
				</thead>
				<tbody>
				<tr>
					<th colspan="5" class="text-left">					
						<embed src="<?php echo $reportfile; ?>" height="800" width="100%" ></embed>
					</th>				
				</tr>				
				</tbody>
			</table> 
		</form>
	</div>
</body>


