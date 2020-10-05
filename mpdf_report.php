<?php
	require 'DB_PARAMS/connect.php';
	require_once('utilities.php');
	require_once('GlobalFunctions.php');
	require_once('county_details.php');
	require_once('ReportingFunctions.php');
	
	$CreatedUserID='2024';
	$ServiceHeaderID='85754';
	//$feedBack=createPermit($db,$ServiceHeaderID,$cosmasRow);
	//$feedBack=reCreatePermit($db,$ServiceHeaderID,$cosmasRow,$CreatedUserID);

	//echo $feedBack[1];
	
	$report=$_REQUEST['report'];
	$reportFileName=time();
	$reportfile='pdfdocs/reports/'.$reportFileName.'.pdf';
	if ($report=='permits'){
		PermitsList($db,$cosmasRow,$reportFileName);
	}if ($report=='total_collections'){			
			mpesaTransactions($db,$cosmasRow,$rptName);
	}if ($report=='mpesa_today'){
			mpesaTransactions($db,$cosmasRow,$reportFileName);			
	}if ($report=='pos_today'){
			posTransactionsToday($db,$cosmasRow,$reportFileName);			
	}if ($report=='rent_balances'){
			rentBalances($db,$cosmasRow,$reportFileName);			
	}if ($report=='receipts_today'){			
			ReceiptsToday($db,$cosmasRow,$reportFileName);			
	}
	if ($report=='ReceiptsToday_Service'){			
			ReceiptsToday_Service($db,$cosmasRow,$reportFileName);		//RevenuePerStream	
	}
	if ($report=='revenue_stream'){			
			RevenuePerStream($db,$cosmasRow,$reportFileName);		//RevenuePerStream	
	}
	if ($report=='PosPerAgent'){			
			PosCollectionPerAgent($db,$cosmasRow,$reportFileName);		//RevenuePerStream	
	}
	if ($report=='tenancy'){			
			Tenancy($db,$cosmasRow,$reportFileName);		//RevenuePerStream	
	}
	
?>


	<div class="example">
		<table height="100%" width="100%">
			<tr>
				<td width="25%">
					<ul>
						<li><a onClick="loadmypage('mpdf_report.php?report=permits','content','loader','listpages')" style="width:80px">Permit</a></li>
						<li><a onClick="loadmypage('mpdf_report.php?report=total_collections','content','loader','listpages')" style="width:80px">Revenue Collections Today</a></li>
						<li><a onClick="loadmypage('mpdf_report.php?report=mpesa_today','content','loader','listpages')" style="width:80px">Mpesa Transactions Today</a></li>
						<li><a onClick="loadmypage('mpdf_report.php?report=pos_today','content','loader','listpages')" style="width:80px">POS Collections Today</a></li>
						<li><a onClick="loadmypage('mpdf_report.php?report=rent_balances','content','loader','listpages')" style="width:80px">Rent Balances</a></li>
						<li><a onClick="loadmypage('mpdf_report.php?report=receipts_today','content','loader','listpages')" style="width:80px">Receipts Today</a></li>
						<li><a onClick="loadmypage('mpdf_report.php?report=ReceiptsToday_Service','content','loader','listpages')" style="width:80px">Receipts Today (Per Service)</a></li>
						<li><a onClick="loadmypage('mpdf_report.php?report=revenue_stream','content','loader','listpages')" style="width:80px">Revenue Per Stream</a></li>
						<li><a onClick="loadmypage('mpdf_report.php?report=PosPerAgent','content','loader','listpages')" style="width:80px">Revenue Per Agent</a></li>
						<li><a onClick="loadmypage('mpdf_report.php?report=tenancy','content','loader','listpages')" style="width:80px">Tenancy Report</a></li>
					</ul>
				</td>
				<td>
					<embed src="<?php echo $reportfile; ?>" height="800" width="100%" ></embed>					
				</td>			
			</tr>
 
		</table>
	</div>

