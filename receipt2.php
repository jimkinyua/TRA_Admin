<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];

if ($_REQUEST['search']=="1"){
		//print_r($_REQUEST);		
		$fromDate=$_REQUEST['fromDate'];
		$toDate=$_REQUEST['toDate'];
		$AgentID=$_REQUEST['AgentID'];
	}else{
		//echo "sio sawa";
	}

?>
<link href="css/metro-bootstrap.css" rel="stylesheet">
<link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
<link href="css/iconFont.css" rel="stylesheet">
<link href="css/docs.css" rel="stylesheet">
<link href="js/prettify/prettify.css" rel="stylesheet">
<body class="metro">
        <div class="example">
        <legend>POS Receipts (Summary)</legend>
			<form>        
            <table class="table striped hovered dataTable" id="posInvoice">
                <thead>
					<tr>
						<th colspan="4" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
					</tr>
					  
					<tr>
						<td><label >Agent/User</label>
							<div class="input-control select" data-role="input-control">								
								<select name="AgentID"  id="AgentID">
									<option value="0" selected="selected"></option>
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
								<div class="input-control text" data-role="input-control">						
									<input type="text" id="fromDate" name="fromDate" value="<?php echo $fromDate ?>"></input>				
								</div>
						</td>
						<td><label>To Date</label>
							<div class="input-control text" data-role="input-control">						
								<input type="text" id="toDate" name="toDate" value="<?php echo $toDate ?>"></input>				
							</div>
						</td>
						
						<td><label>&nbsp;</label>
						<input name="btnSearch" type="button" onclick="loadmypage('pos_invoices_summary.php?'+
									'&AgentID='+this.form.AgentID.value+	
									'&fromDate='+this.form.fromDate.value+								
									'&toDate='+this.form.toDate.value+									'&search=1','content','loader','listpages','','invoices-c','AgentID='+this.form.AgentID.value+':fromDate='+this.form.fromDate.value+':toDate='+this.form.toDate.value+'')" value="Search">
						</td>			  
					</tr>
					<tr>
						<th width="20%" class="text-left">Date</th> 
						<th width="20%" class="text-left">Agent</th>                                       
						<th width="25%" class="text-left">Market</th>
						<th width="15%" class="text-left">Invoice Number</th>
						<th width="10%" class="sum">Total</th>
						<th width="10%" class="sum">Paid</th>
					</tr>
                </thead>
				
                <tbody>
                </tbody>
            </table>
			</form>
</div>
</div>
