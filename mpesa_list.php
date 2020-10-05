<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
require_once('GlobalFunctions.php');
require_once('county_details.php');
	
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];


$PageID=51;
$myRights=getrights($db,$UserID,$PageID);
if ($myRights)
{
	$View=$myRights['View'];
	$Edit=$myRights['Edit'];
	$Add=$myRights['Add'];
	$Delete=$myRights['Delete'];
}

$fromDate=date('d/m/Y');
$toDate=date('d/m/Y');
$RefNo='';
$PhoneNo='';
$Sender='';

if(isset($_REQUEST['fromDate'])){$fromDate=$_REQUEST['fromDate'];}
if(isset($_REQUEST['toDate'])){$toDate=$_REQUEST['toDate'];}
if(isset($_REQUEST['RefNo'])){$RefNo=$_REQUEST['RefNo'];}
if(isset($_REQUEST['PhoneNo'])){$PhoneNo=$_REQUEST['PhoneNo'];}
if(isset($_REQUEST['Sender'])){$Sender=$_REQUEST['Sender'];}

if ($_REQUEST['verify']=='1')
{
	$receiptno=$_REQUEST['receiptno'];
	
	$sql="update receipts set ReceiptStatusID=1 where ReferenceNumber='$receiptno'";
	
	$result=sqlsrv_query($db,$sql);
	if($result)
	{
		$msg="Receipt verified!";
	}else
	{
		$msg="Receipt verification failed!";
	} 
}

if($_REQUEST['link']==1)
{
	$mpesa_code=$_REQUEST['mpesa_code'];
	$mpesa_acc=$_REQUEST['InvoiceNo'];
	$mpesa_amt=$_REQUEST['mpesa_amt'];
	$splitamount=$_REQUEST['splitamount'];
	
	$msg=mpesaToInvoice($db,$mpesa_acc,$mpesa_code,$mpesa_amt,$CreatedUserID);

	$rst=SaveTransaction($db,$CreatedUserID,"Linked Mpesa transaction $mpesa_code to invoice number $InvoiceHeaderID");
	
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
        <legend>MPESA RECEIPTS</legend>
        <form>
            <table class="table striped hovered dataTable" id="tableToolsTable" width="100%">
                <thead>
                  <tr>
                    <th colspan="6" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>
				 <tr>
                    <th colspan="6" class="text-center">
					<table width="100%">
						<tr>
						  <td><label>From Date</label>
								<div class="input-control text datepicker" data-role="input-control">						
									<input type="text" id="fromDate" name="fromDate" value="<?php echo $fromDate ?>"></input>	<button class="btn-date" type="button"></button>	
								</div>
							</td>
							<td><label>To Date</label>
									<div class="input-control text datepicker" data-role="input-control">						
										<input type="text" id="toDate" name="toDate" value="<?php echo $toDate ?>"></input>		
										<button class="btn-date" type="button"></button>		
									</div>
								</td>
								<td><label>Reference Number</label>
										<div class="input-control text" data-role="input-control">						
											<input type="text" width="100" id="RefNo" name="RefNo" value="<?php echo $RefNo ?>"></input>
											<button class="btn-date" type="button"></button>				
										</div>
								</td>
								<td><label>Phone Number</label>
									<div class="input-control text" data-role="input-control">						
										<input type="text" width="15" id="PhoneNo" name="PhoneNo" value="<?php echo $PhoneNo ?>"></input>				
									</div>
								</td>
								<td><label>Sender</label>
									<div class="input-control text" data-role="input-control">						
										<input type="text" width="15" id="Sender" name="Sender" value="<?php echo $Sender ?>"></input>				
									</div>
								</td>
								
								<td><label>&nbsp;</label>
								<input name="btnSearch" type="button" onclick="loadmypage('mpesa_list.php?'+
											'&fromDate='+this.form.fromDate.value+								
											'&toDate='+this.form.toDate.value+
											'&RefNo='+this.form.RefNo.value+								
											'&PhoneNo='+this.form.PhoneNo.value+
											'&Sender='+this.form.Sender.value+								'&search=1','content','loader','listpages','','Mpesa','fromDate='+this.form.fromDate.value+':toDate='+this.form.toDate.value+':RefNo='+this.form.RefNo.value+':PhoneNo='+this.form.PhoneNo.value+':Sender='+this.form.Sender.value+'')" value="Search">
								</td>
						</tr>
					</table>
					</th>
                  </tr>
                <tr>
                    <th width="15%" class="text-left">Receipt Date</th>
					<th width="15%" class="text-left">Mpesa Code</th>                    
                    <th width="15%" class="text-left">Invoice No</th>
                    <th width="15%" class="text-left">Amount</th>
                    <th width="30%" class="text-left">Customer Name</th>
					<th width="10%" class="text-left"></th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>
		</form>

</div>
</div>