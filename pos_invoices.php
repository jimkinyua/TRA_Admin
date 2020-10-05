<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];

if ($_REQUEST['search']=="1")
	{
		//print_r($_REQUEST);		
		$fromDate=$_REQUEST['fromDate'];
		$toDate=$_REQUEST['toDate'];
		$AgentID=$_REQUEST['AgentID'];
		$ServiceID=$_REQUEST['ServiceID'];		
		//echo $ServiceID;
	}
	else
	{
		//echo "sio sawa";
	}

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
        <legend>POS Receipts</legend>
			<form>        
            <table class="table striped hovered dataTable" id="posInvoice">
                <thead>
					<tr>
						<th colspan="6" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
					</tr>
					  
					<tr>
						<td colspan="6">
							<Table width='100%'>
								<tr>
									<td><label >Stream</label>
										<div class="input-control select" data-role="input-control">								
											<select name="ServiceID"  id="ServiceID">
												<option value="" selected="selected"></option>
												<?php 
												$s_sql = "SELECT ServiceID,ServiceName FROM SERVICES 
															WHERE ServiceID IN (
															SELECT DISTINCT ServiceID FROM ServiceTrees)";									
												$s_result = sqlsrv_query($db, $s_sql);
												if ($s_result) 
												{ //connection succesful 
													while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
													{
														$s_id = $row["ServiceID"];
														$s_name = $row["ServiceName"];
														if ($ServiceID==$s_id) 
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
									<td colspan="2"><label >Agent/User</label>
										<div class="input-control select" data-role="input-control">								
											<select name="AgentID"  id="AgentID">
												<option value="" selected="selected"></option>
												<?php 
												$s_sql = "select distinct ag.AgentID,ag.FirstName+' '+ag.MiddleName+' '+ag.LastName Names from UserDevices ud 
															join Agents ag on ud.DeviceUserID=ag.AgentID order by ag.FirstName+' '+ag.MiddleName+' '+ag.LastName";									
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
									</td>
									<td><label>From Date</label>
											<div class="input-control text datepicker" data-role="input-control" data-format="dd/mm/yyyy">						
												<input type="text" id="fromDate" name="fromDate" value="<?php echo $fromDate ?>"></input>	
												<button class="btn-date" type="button"></button>			
											</div>
									</td>
									<td><label>To Date</label>
										<div class="input-control text datepicker" data-role="input-control" data-format="dd/mm/yyyy">						
											<input type="text" id="toDate" name="toDate" value="<?php echo $toDate ?>"></input>		<button class="btn-date" type="button"></button>		
										</div>
									</td>
									
									<td><label>&nbsp;</label>
									<input name="btnSearch" type="button" onclick="loadmypage('pos_invoices.php?'+
												'&AgentID='+this.form.AgentID.value+	
												'&fromDate='+this.form.fromDate.value+								
												'&toDate='+this.form.toDate.value+
												'&ServiceID='+this.form.ServiceID.value+												'&search=1','content','loader','listpages','','invoices-b','AgentID='+this.form.AgentID.value+':fromDate='+this.form.fromDate.value+':toDate='+this.form.toDate.value+':ServiceID='+this.form.ServiceID.value+'')" value="Search">
									</td>
								</tr>
							</table>
						</td>									  
					</tr>
					<tr>
						<th width="15%" class="text-left">ReceiptDate</th> 
						<th width="15%" class="text-left">ReceiptNo</th>                                       
						<th width="10%" class="sum">Amount</th>
						<th width="20%" class="text-left">Service</th>
						<th width="20%" class="text-left">Agent</th>
						<th width="20%" class="text-left">Market</th>
					</tr>
                </thead>
				<tfoot>
					<tr>
						<th></th>
						<th></th>
						<th class="text-left"></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
				</tfoot>
                <tbody>
                </tbody>
            </table>
			</form>
</div>
</div>
