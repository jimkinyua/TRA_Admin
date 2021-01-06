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
		// print_r($_REQUEST);
		$report=$_REQUEST['report'];
		$fromDate=$_REQUEST['fromDate'];
		$toDate=$_REQUEST['toDate'];
		$cName = $_REQUEST['cName'];
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
	}else if ($report=='facilitation_applications'){			
		facilitation_applications($db,$cosmasRow,$reportFileName,$fromDate,$toDate);
	}else if ($report=='classification_applications'){			
		classification_applications($db,$cosmasRow,$reportFileName,$fromDate,$toDate);
	}else if ($report=='facilitation_establishment'){			
			facilitation_establishment($db,$cosmasRow,$reportFileName,$fromDate,$toDate,$cName);
	}else if ($report=='classification_applicant'){			
			classification_applicant($db,$cosmasRow,$reportFileName,$fromDate,$toDate,$cName);
	}else if ($report=='facilitation_approved'){			
		facilitation_approved($db,$cosmasRow,$reportFileName,$fromDate,$toDate);
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
	}else if ($report=='all_graded'){
		all_graded($db,$cosmasRow,$reportFileName,$fromDate,$toDate);
	}else if ($report=='licence_applications'){
		licence_applications($db,$cosmasRow,$reportFileName,$fromDate,$toDate);
	}else if ($report=='licenced'){
		licenced($db,$cosmasRow,$reportFileName,$fromDate,$toDate);
	}else if ($report=='all_licenced'){
		all_licenced($db,$cosmasRow,$reportFileName,$fromDate,$toDate);
	}else if ($report=='licence_applicant'){
		licence_applicant($db,$cosmasRow,$reportFileName,$fromDate,$toDate,$cName);



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

								<?php
									$sql = "select RoleCenterID from UserRoles where UserID = $AgentID";

									$result = sqlsrv_query($db, $sql);
									while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
										$RoleCenterID = $row['RoleCenterID'];
										
									}
								if($RoleCenterID == 2026 || $RoleCenterID == 2025){
										
								?>
								<option value="0" ></option>
								<option value="facilitation_applications" <?php if($report=='facilitation_applications'){?> selected="selected" <?php } ?>>Establishment Applications</option>
								<option value="facilitation_approved" <?php if($report=='facilitation_approved'){?> selected="selected" <?php } ?>>Approved Applications</option>
								<option value="facilitation_establishment" <?php if($report=='facilitation_establishment'){?> selected="selected" <?php } ?>>Applicant Details</option>
								<?php
								}elseif($RoleCenterID == 3026 || $RoleCenterID == 4026 || $RoleCenterID == 4027){
										
										?>
										<option value="0" ></option>
										<option value="classification_applications" <?php if($report=='classification_applications'){?> selected="selected" <?php } ?>>Pending Classification Applications</option>
										<option value="graded" <?php if($report=='graded'){?> selected="selected" <?php } ?>>Graded Establishments Filtered</option>
										<option value="all_graded" <?php if($report=='all_graded'){?> selected="selected" <?php } ?>>All Graded Establishments</option>
										<option value="classification_applicant" <?php if($report=='classification_applicant'){?> selected="selected" <?php } ?>>Individual Applicants</option>?>
										<?php
								}elseif($RoleCenterID == 2014 || $RoleCenterID == 2020 || $RoleCenterID == 2023){
										
										?>
										<option value="0" ></option>
										<option value="licence_applications" <?php if($report=='licence_applications'){?> selected="selected" <?php } ?>>Pending Licence Applications</option>
										<option value="licenced" <?php if($report=='licenced'){?> selected="selected" <?php } ?>>Licenced Establishments Filtered</option>
										<option value="all_licenced" <?php if($report=='all_licenced'){?> selected="selected" <?php } ?>>All Licenced Establishments</option>
										<option value="licence_applicant" <?php if($report=='licence_applicant'){?> selected="selected" <?php } ?>>Individual Applicants</option>?>
								<?php }else{ ?>
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
								<option value="house_receipts" <?php if($report=='house_receipts'){?> selected="selected" <?php } ?>>Housing Receipts</option> -->								<?php } ?>	
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
					}elseif($report == 'facilitation_establishment'){
						?>
						<td><label width="20%">Applicant Name</label>
						<div class="input-control select" data-role="input-control">	
						
						<select id="cName" name="cName" required width="48">
				        <option value="" selected="selected" >select applicant</option>
				        <?php 
						$es_sql = "select sh.ServiceHeaderID,c.CustomerName,c.CustomerID,convert(date,sh.SubmissionDate) as DateApplied,st.ServiceStatusName
						from ServiceHeader sh
						join Customer c on sh.CustomerID = c.CustomerID
						join Services s on sh.ServiceID = s.ServiceID
						join ServiceStatus st on sh.ServiceStatusID = st.ServiceStatusID
						join ServiceCategory sc on sh.ServiceCategoryId = sc.ServiceCategoryID
						join ServiceGroup sg on sc.ServiceGroupID = sg.ServiceGroupID
						where sc.ServiceGroupID = 12 and sh.ServiceStatusID != 4";
						$es_result = sqlsrv_query($db, $es_sql) or die ("failed to load Status");

						$selected = '';
					    while ($myrow = sqlsrv_fetch_array( $es_result, SQLSRV_FETCH_ASSOC)) 
					    {
							$c_id = $myrow ['CustomerID'];
							$c_name = $myrow['CustomerName'];
							if ($c_name==$c_id) 
							{
							   	$selected = 'SELECTED';
							} else
							{
								$selected = '';
							}	 
						 	?>
				       <option value="<?php echo $c_id;?>"><?php echo $c_name;?></option> 

						
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
					}elseif($report == 'classification_applicant'){
						?>
						<td><label width="20%">Applicant Name</label>
						<div class="input-control select" data-role="input-control">	
						
						<select id="cName" name="cName" required width="48">
				        <option value="" selected="selected" >select applicant</option>
				        <?php 
						$es_sql = "select sh.ServiceHeaderID,c.CustomerName,c.CustomerID,convert(date,sh.SubmissionDate) as DateApplied,st.ServiceStatusName
						from ServiceHeader sh
						join Customer c on sh.CustomerID = c.CustomerID
						join Services s on sh.ServiceID = s.ServiceID
						join ServiceStatus st on sh.ServiceStatusID = st.ServiceStatusID
						join ServiceCategory sc on sh.ServiceCategoryId = sc.ServiceCategoryID
						join ServiceGroup sg on sc.ServiceGroupID = sg.ServiceGroupID
						where sc.ServiceGroupID = 11 and sh.ServiceStatusID != 4";
						// exit($es_sql);
						$es_result = sqlsrv_query($db, $es_sql) or die ("failed to load Status");

						$selected = '';
					    while ($myrow = sqlsrv_fetch_array( $es_result, SQLSRV_FETCH_ASSOC)) 
					    {
							$c_id = $myrow ['CustomerID'];
							$c_name = $myrow['CustomerName'];
							if ($c_name==$c_id) 
							{
							   	$selected = 'SELECTED';
							} else
							{
								$selected = '';
							}	 
						 	?>
				       <option value="<?php echo $c_id;?>"><?php echo $c_name;?></option> 

						
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
					}elseif($report == 'licence_applicant'){
						?>
						<td><label width="20%">Applicant Name</label>
						<div class="input-control select" data-role="input-control">	
						
						<select id="cName" name="cName" required width="48">
				        <option value="" selected="selected" >select applicant</option>
				        <?php 
						$es_sql = "select sh.ServiceHeaderID,c.CustomerName,c.CustomerID,convert(date,sh.SubmissionDate) as DateApplied,st.ServiceStatusName
						from ServiceHeader sh
						join Customer c on sh.CustomerID = c.CustomerID
						join Services s on sh.ServiceID = s.ServiceID
						join ServiceStatus st on sh.ServiceStatusID = st.ServiceStatusID
						join ServiceCategory sc on sh.ServiceCategoryId = sc.ServiceCategoryID
						join ServiceGroup sg on sc.ServiceGroupID = sg.ServiceGroupID
						where sh.ServiceStatusID != 4 and (sc.ServiceGroupID != 11 and sc.ServiceGroupID != 12)";
						// exit($es_sql);
						$es_result = sqlsrv_query($db, $es_sql) or die ("failed to load Status");

						$selected = '';
					    while ($myrow = sqlsrv_fetch_array( $es_result, SQLSRV_FETCH_ASSOC)) 
					    {
							$c_id = $myrow ['CustomerID'];
							$c_name = $myrow['CustomerName'];
							if ($c_name==$c_id) 
							{
							   	$selected = 'SELECTED';
							} else
							{
								$selected = '';
							}	 
						 	?>
				       <option value="<?php echo $c_id;?>"><?php echo $c_name;?></option> 

						
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


