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
		$MarketID=$_REQUEST['MarketID'];
	}else{
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
        <legend>POS Receipts (Summary)</legend>
			<form>        
            <table class="table striped hovered dataTable" id="posInvoice">
                <thead>
					<tr>
						<th colspan="4" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
					</tr>
					  
					<tr>
						<td><label >Market</label>
							<div class="input-control select" data-role="input-control">								
								<select name="MarketID"  id="MarketID">
									<option value="" selected="selected"></option>
									<?php 
									$s_sql = "select distinct ud.MarketID,mk.MarketName 
											from UserDevices ud join markets mk on ud.MarketID=mk.MarketID";									
									$s_result = sqlsrv_query($db, $s_sql);
									if ($s_result) 
									{ //connection succesful 
										while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
										{
											$s_id = $row["MarketID"];
											$s_name = $row["MarketName"];
											if ($MarketID==$s_id) 
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
									<input type="text" id="fromDate" name="fromDate" value="<?php echo $fromDate ?>"></input>		<button class="btn-date" type="button"></button>		
								</div>
						</td>
						<td><label>To Date</label>
							<div class="input-control text datepicker" data-role="input-control" data-format="dd/mm/yyyy">						
								<input type="text" id="toDate" name="toDate" value="<?php echo $toDate ?>"></input>	
								<button class="btn-date" type="button"></button>			
							</div>
						</td>
						
						<td><label>&nbsp;</label>
						<input name="btnSearch" type="button" onclick="loadmypage('pos_invoices_parking.php?'+
									'&MarketID='+this.form.MarketID.value+	
									'&fromDate='+this.form.fromDate.value+								
									'&toDate='+this.form.toDate.value+									'&search=1','content','loader','listpages','','invoices-d','MarketID='+this.form.MarketID.value+':fromDate='+this.form.fromDate.value+':toDate='+this.form.toDate.value+'')" value="Search">
						</td>			  
					</tr>
					<tr>
						<th  class="text-left">Date</th> 
						<th  class="text-left">Reg No</th>                                       
						<th  class="text-left">Receipt No</th>
						<th  class="text-left">Market Name</th>
						<th  class="sum">Amount</th>
					</tr>
                </thead>
				
                <tbody>
                </tbody>
            </table>
			</form>
</div>
</div>
