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
		$cName = $_REQUEST['cName'];
		$RevenueStreamID=$_REQUEST['RevenueStreamID'];	
		
		// echo $cName;
		// exit;
	}else{
		//echo "sio sawa";
	}
	
	//$report='permits';//$_REQUEST['report'];
	$reportFileName=time();	
	
	if ($report=='permits'){
		PermitsList($db,$cosmasRow,$reportFileName,$fromDate,$toDate);
	}else if ($report=='permits_summary'){			
			Permits_Summary($db,$cosmasRow,$reportFileName,$fromDate,$toDate);
	}else if ($report=='newestablishment'){			
			newestablishment($db,$cosmasRow,$reportFileName,$fromDate,$toDate);
	}else if ($report=='revenuegenerated'){			
			revenuegenerated($db,$cosmasRow,$reportFileName,$fromDate,$toDate);		
	}else if ($report=='licenceexpirynotification'){			
			licenceexpirynotification($db,$cosmasRow,$reportFileName,$fromDate,$toDate);
	}else if ($report=='establishmentbranches'){			
			establishmentbranches($db,$cosmasRow,$reportFileName,$fromDate,$toDate,$cName);
	}else if ($report=='graded'){
		graded($db,$cosmasRow,$reportFileName,$fromDate,$toDate);


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
	else if ($report=='house_invoices'){			
			HousingInvoices($db,$cosmasRow,$reportFileName,$fromDate,$toDate);		
	}
	else if ($report=='general_invoices'){	

			GeneralInvoices($db,$cosmasRow,$reportFileName,$RevenueStreamID,$fromDate,$toDate);		
	}
	else if ($report=='receipts_receipted')
			{
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
					<td><label width="20%">Report</label>
						<div class="input-control select" data-role="input-control">						
							<select name="ReportName"  id="ReportName">
								<option value="0" ></option>
								<!-- <option value="permits" <?php if($report=='permits'){?> selected="selected" <?php } ?>>Licences Issued</option> -->
								<option value="graded" <?php if($report=='graded'){?> selected="selected" <?php } ?>>Graded Establishments</option>
								<option value="newestablishment" <?php if($report=='newestablishment'){?> selected="selected" <?php } ?>>Licenced Establishments</option>
								<option value="revenuegenerated" <?php if($report=='revenuegenerated'){?> selected="selected" <?php } ?>>Revenue Generated</option>
								<option value="licenceexpirynotification" <?php if($report=='licenceexpirynotification'){?> selected="selected" <?php } ?>>Licences Expiry Notification</option>
								<option value="receipts" <?php if($report=='receipts'){?> selected="selected" <?php } ?>>Receipts</option>
								<option value="receipts_deposited" <?php if($report=='receipts_deposited'){?> selected="selected" <?php } ?>>Receipts (Deposited)</option>
								<option value="receipts_receipted" <?php if($report=='receipts_receipted'){?> selected="selected" <?php } ?>>Receipts (Receipted)</option>
								<option value="revenue_stream" <?php if($report=='revenue_stream'){?> selected="selected" <?php } ?>>Revenue Per Stream</option>
								<option value="establishmentbranches" <?php if($report=='establishmentbranches'){?> selected="selected" <?php } ?>>Establishment Branches</option>

								<!-- <option value="permits_summary" <?php if($report=='permits_summary'){?> selected="selected" <?php } ?>>Permits (Summary)</option>
								<option value="mpesa" <?php if($report=='mpesa'){?> selected="selected" <?php } ?>>Mpesa Transactions</option>
								<option value="mpesa_parking" <?php if($report=='mpesa_parking'){?> selected="selected" <?php } ?>>Mpesa Transactions (Parking)</option>
								<option value="pos" <?php if($report=='pos'){?> selected="selected" <?php } ?>>POS Collection</option>								
								
								<option value="revenue_perfomance" <?php if($report=='revenue_perfomance'){?> selected="selected" <?php } ?>>Revenue Performance</option>
								<option value="revenue_stream_deposited" <?php if($report=='revenue_stream_deposited'){?> selected="selected" <?php } ?>>Revenue Per Stream (Deposited)</option>
								<option value="revenue_stream_mpesa" <?php if($report=='revenue_stream_mpesa'){?> selected="selected" <?php } ?>>Revenue Per Stream (Mpesa)</option>	
								<option value="receipts_summry" <?php if($report=='receipts_summry'){?> selected="selected" <?php } ?>>Receipts(Summary)</option>
								<option value="tenancy" <?php if($report=='tenancy'){?> selected="selected" <?php } ?>>Tenancy</option>
								<option value="receipts_per_stream" <?php if($report=='receipts_per_stream'){?> selected="selected" <?php } ?>>Receipts Per Stream</option>
								<option value="land_rates_status_asAt" <?php if($report=='land_rates_status_asAt'){?> selected="selected" <?php } ?>>Land Rates Status As At</option>
								<option value="house_invoices" <?php if($report=='house_invoices'){?> selected="selected" <?php } ?>>Housing Invoices</option>
								<option value="house_receipts" <?php if($report=='house_receipts'){?> selected="selected" <?php } ?>>Housing Receipts</option>
								<option value="house_receipts" <?php if($report=='house_receipts'){?> selected="selected" <?php } ?>>Housing Receipts</option> -->									
						  </select>					
						</div>
					</td>
					<?php
					if($report == 'establishmentbranches'){
						?>
						<td><label width="20%">Establishment Name</label>
						<div class="input-control select" data-role="input-control">	
						
						<select name="cName" required width="48">
				        <option value="" selected="selected" >select establishment</option>
				        <?php 
						$status_sql = "select top 1 with ties ca.CustomerAgentID,ca.AgentID,c.CustomerName 
							from Customer c 
							join CustomerAgents ca on c.CustomerID = ca.CustomerID
							join Agents ag on ag.AgentID = ca.AgentID
							order by row_number() over (partition by ca.AgentID order by c.CustomerName desc)";
						$status_result = sqlsrv_query($db, $status_sql) or die ("failed to load Status");

						$selected = '';
					    while ($myrow = sqlsrv_fetch_array( $status_result, SQLSRV_FETCH_ASSOC)) 
					    {
							$a_id = $myrow ['AgentID'];
							$a_name = $myrow['CustomerName'];
							if ($a_name==$a_id) 
							{
							   	$selected = 'SELECTED';
							} else
							{
								$selected = '';
							}	 
						 	?>
				       <option value="<?php echo $a_id;?>"><?php echo $a_name;?></option> 

						
				        <?php
					 }
					 ?>
				    </select>


						</div>
						</td>
						<td></td>
						<td><label>&nbsp;</label>
					<input name="btnSearch" type="button" onclick="loadmypage('reporting.php?'+
								'&report='+this.form.ReportName.value+	
								'&cName='+this.form.cName.value+ '&search=1','content','loader','listpages','')" value="Search">
					</td>
						<?php
					}else{
					?>
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
					<input name="btnSearch" type="button" onclick="loadmypage('reporting.php?'+
								'&report='+this.form.ReportName.value+	
								'&fromDate='+this.form.fromDate.value+								'&toDate='+this.form.toDate.value+'&search=1','content','loader','listpages','')" value="Search">
					</td>
					<?php }
				?>
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


