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
		$RevenueStreamID=$_REQUEST['RevenueStreamID'];
	}else{
		//echo "sio sawa";
	}
	
	//$report='permits';//$_REQUEST['report'];
	$reportFileName=time();	
	
	if ($report=='permits'){
		PermitsList($db,$cosmasRow,$reportFileName,$fromDate,$toDate);
	}else if ($report=='permits_summary'){			
			Permits_Summary($db,$cosmasRow,$reportFileName,$fromDate,$toDate);		
	}else if ($report=='mpesa'){
			mpesaTransactions($db,$cosmasRow,$reportFileName,$fromDate,$toDate);			
	}else if ($report=='revenue_stream'){		     	
			RevenuePerStream($db,$cosmasRow,$reportFileName,$fromDate,$toDate);			
	}else if ($report=='revenue_stream_deposited'){		     	
			RevenuePerStream_deposits($db,$cosmasRow,$reportFileName,$fromDate,$toDate,'');			
	}else if ($report=='revenue_stream_mpesa'){		     	
			RevenuePerStream_deposits($db,$cosmasRow,$reportFileName,$fromDate,$toDate,'1014');			
	}else if ($report=='revenue_perfomance'){		     	
			RevenuePerformance($db,$cosmasRow,$reportFileName,$fromDate,$toDate);			
	}else if ($report=='land_rates_status_asAt'){		     	
			LandRatesStatusAsAt($db,$cosmasRow,$reportFileName,$fromDate,$toDate);			
	}else if ($report=='mpesa_parking'){
			mpesa_parking($db,$cosmasRow,$reportFileName,$fromDate,$toDate);			
	}else if ($report=='pos'){
			posTransactions($db,$cosmasRow,$reportFileName,$fromDate,$toDate,$AgentID);			
	}else if ($report=='rent_balances'){
			rentBalances($db,$cosmasRow,$reportFileName);			
	}else if ($report=='receipts_today'){			
			ReceiptsToday($db,$cosmasRow,$reportFileName);			
	}
	else if ($report=='ReceiptsToday_Service'){			
			ReceiptsToday_Service($db,$cosmasRow,$reportFileName);		//RevenuePerStream	
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
	else if ($report=='general_invoices'){	

			GeneralInvoices($db,$cosmasRow,$reportFileName,$RevenueStreamID,$fromDate,$toDate);		
	}
	else if ($report=='house_invoices'){			
			HousingInvoices($db,$cosmasRow,$reportFileName,$fromDate,$toDate);		
	}
	else if ($report=='receipts_receipted'){
			$rolecenterid=$_SESSION['RoleCenter'];			
			if($rolecenterid==2013 || $rolecenterid==2007|| $rolecenterid==1){
				$UserID='All';
			}				
			Receipts_Receipted($db,$cosmasRow,$reportFileName,$fromDate,$toDate,$UserID);		
	}
	else if ($report=='receipts_deposited'){
			$rolecenterid=$_SESSION['RoleCenter'];			
			if($rolecenterid==2013 || $rolecenterid==2007){
				$UserID='All';
			}

			Receipts_Deposited($db,$cosmasRow,$reportFileName,$fromDate,$toDate,$UserID);

	}
	
	$reportfile='pdfdocs/reports/'.$reportFileName.'.pdf';
?>
    <link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/prettify/prettify.css" rel="stylesheet">
    <script type="text/javascript">
    	$(".datepicker").datepicker();
    </script>

<body class="metro">
	<div class="example">
		<legend>REPORTS</legend>   
		<form>
			<table class="example" width="100%">
				<thead>
				<tr>
					<td><label width="20%">Revenue Stream</label>
						<div class="input-control select" data-role="input-control">						
							<select name="RevenueStreamID"  id="RevenueStreamID"">
								<option value="0" selected="selected"></option>
								<?php 
								$s_sql = "SELECT * FROM RevenueStreams ORDER BY RevenueStreamName";									
								$s_result = sqlsrv_query($db, $s_sql);
								if ($s_result) 
								{ //connection succesful 
									while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
									{
										$s_id = $row["RevenueStreamID"];
										$s_name = $row["RevenueStreamName"];
										if ($RevenueStreamID==$s_id) 
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
					<td><label width="20%">From Date</label>
						<div class="input-control text datepicker" data-role="input-control" data-format="dd/mm/yyyy">	
							<input type="text" id="fromDate" name="fromDate" value="<?php echo $fromDate ?>"></input>				
						</div>
					</td>
					<td><label width="20%">To Date</label>
						<div class="input-control text datepicker" data-role="input-control" data-format="dd/mm/yyyy">	
							<input type="text" id="toDate" name="toDate" value="<?php echo $toDate ?>"></input>				
						</div>
					</td>
					<!-- <td><label width="20%">Agent/User</label>
						<div class="input-control select" data-role="input-control">								
							<select name="AgentID"  id="AgentID">
								<option value="0" selected="selected"></option>
								<?php 
								$s_sql = "SELECT u.AgentID,ag.FirstName+' '+ag.Middlename+' '+ag.LastName Names FROM Users u join Agents ag on u.AgentID=ag.AgentID ORDER BY 1";									
								$s_result = sqlsrv_query($db, $s_sql);
								if ($s_result) 
								{ //connection succesful 
									while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
									{
										$s_id = $row["AgentID"];
										$s_name = $row["Names"];
										if ($AgentID==$s_id) 
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
					</td> -->
					<td><label>&nbsp;</label>
					<input name="btnSearch" type="button" onclick="loadmypage('reporting-invoices.php?'+
								'&report=general_invoices'+	
								'&RevenueStreamID='+this.form.RevenueStreamID.value+
								'&fromDate='+this.form.fromDate.value+
								'&toDate='+this.form.toDate.value+'&search=1','content','loader','listpages','')" value="Search">
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


