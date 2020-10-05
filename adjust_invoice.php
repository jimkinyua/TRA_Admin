<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];

$Amount=0;

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
        <legend>Invoice Adjustment</legend>
			<form id="fnForm1" name="fnForm1" action="pos_invoices_summary.php">        
            <table class="table striped hovered dataTable" id="posInvoice">
                <thead>
					<tr>
						<th colspan="4" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
					</tr>
					  
					<tr>
						<td colspan="2"><label >Service To Add</label>
							<div class="input-control select" data-role="input-control">								
								<select name="ServiceID"  id="ServiceID">
									<option value="0" selected="selected"></option>
									<?php 
									$s_sql = "select ServiceID,ServiceName from Services order by ServiceName";									
									$s_result = sqlsrv_query($db, $s_sql);
									if ($s_result) 
									{ //connection succesful 
										while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
										{
											$s_id = $row["ServiceID"];
											$s_name = $row["ServiceName"];												
										 ?>
									<option value="<?php echo $s_id; ?>" <?php echo $selected; ?>><?php echo $s_name; ?></option>
									<?php 
										}
										
									}
									?>
							  </select>							
							</div>
						</td>
						<td><label>Amount</label>
								<div class="input-control text" data-role="input-control">						
									<input type="text" id="Amount" name="Amount" value="<?php echo $Amount ?>"></input>		<button class="btn-date" type="button"></button>					
								</div>
						</td>
						<td><label>&nbsp;</label>
						<input name="btnSearch" type="button" onclick="loadmypage('adjust_invoice.php?'+
																		'&search=1','content','loader','listpages','','invoices-items','2345'')" value="Search">
						</td>				
									  
					</tr>

					<tr>
						<th  class="text-left">ServiceID</th> 
						<th  class="text-left">Service Name</th>                                       
						<th  class="text-left">Amount</th>											
						<th  >&nbsp</th>
					</tr>
                </thead>
				
                <tbody>
                </tbody>
            </table>
			</form>
</div>
</div>
