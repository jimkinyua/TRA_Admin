<?php
require 'DB_PARAMS/connect.php';

require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';

$PageID=25;
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
$InvoiceNo='';

if(isset($_REQUEST['fromDate'])){$fromDate=$_REQUEST['fromDate'];}
if(isset($_REQUEST['toDate'])){$toDate=$_REQUEST['toDate'];}
if(isset($_REQUEST['RefNo'])){$RefNo=$_REQUEST['RefNo'];}
if(isset($_REQUEST['InvoiceNo'])){$InvoiceNo=$_REQUEST['InvoiceNo'];}



$CreatedUserID = $_SESSION['UserID'];

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
	$ReceiptID=$_REQUEST['ReceiptID'];
	$ReferenceNumber=$_REQUEST['refno'];
	$InvoiceHeaderID=$_REQUEST['invoiceno'];
	$Amount=$_REQUEST['amount'];
	$splitamount=$_REQUEST['splitamount'];
	
	//print_r($_REQUEST);exit;
	$msg=receiptToInvoice($db,$ReceiptID,$InvoiceHeaderID,$ReferenceNumber,$Amount,$splitamount);
}
if ($_REQUEST['effect']=='1')
{
	$upn='';
	$ReceiptID=$_REQUEST['ReceiptID'];
	$ReferenceNumber=$_REQUEST['ReferenceNumber'];

	$sql="exec spReceiptDocument '$ReferenceNumber'";

	$result=sqlsrv_query($db,$sql);
	if($result)
	{
		$msg="Receipt Receipted!";
	}else
	{
		$msg="Receipt verification failed!";
	}	
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
        <legend>Receipts</legend>
<form>        
            <table class="table striped hovered dataTable" id="dataTables-1" width="100%">
                <thead>
                  <tr>
                    <th colspan="7" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>
				  
				  <tr>
					<td colspan="7">
						<table width="100%">
							<tr>
								<td colspan="2"><label>From Date </label>
										<div class="input-control text datepicker" data-role="input-control">						
											<input type="text" id="fromDate" name="fromDate" value="<?php echo $fromDate ?>"></input>
											<button class="btn-date" type="button"></button>				
										</div>
								</td>
								<td colspan="2"><label>To Date </label>
									<div class="input-control text datepicker" data-role="input-control">						
										<input type="text" id="toDate" name="toDate" value="<?php echo $toDate ?>"></input>			<button class="btn-date" type="button"></button>	
									</div>
								</td>
								<td><label>Reference Number</label>
										<div class="input-control text" data-role="input-control">						
											<input type="text" width="100" id="RefNo" name="RefNo" value="<?php echo $RefNo ?>"></input>				
										</div>
								</td>
								<td><label>Invoice Number</label>
									<div class="input-control text" data-role="input-control">						
										<input type="text" width="15" id="InvoiceNo" name="InvoiceNo" value="<?php echo $InvoiceNo ?>"></input>				
									</div>
								</td>
								<td colspan="5"><label>Customer Name</label>
									<div class="input-control text" data-role="input-control">						
										<input type="text" width="15" id="CustomerName" name="CustomerName" value="<?php echo $CustomerName ?>"></input>				
									</div>
								</td>
								<td colspan="2"><label>Receipt No.</label>
									<div class="input-control text" data-role="input-control">						
										<input type="text" width="15" id="ReceiptID" name="ReceiptID" value="<?php echo $ReceiptID ?>"></input>				
									</div>
								</td>
								
								<td><label>&nbsp;</label>
								<input name="btnSearch" type="button" onclick="loadmypage('receipts_list.php?'+
											'&fromDate='+this.form.fromDate.value+								
											'&toDate='+this.form.toDate.value+
											'&RefNo='+this.form.RefNo.value+								
											'&InvoiceNo='+this.form.InvoiceNo.value+								'&search=1','content','loader','listpages','','receipts','fromDate='+this.form.fromDate.value+':toDate='+this.form.toDate.value+':RefNo='+this.form.RefNo.value+':InvoiceNo='+this.form.InvoiceNo.value+':CustomerName='+this.form.CustomerName.value+':ReceiptID='+this.form.ReceiptID.value+'')" value="Search">
								</td>
							<tr>
						</table>
					<td>
				</tr>
                <tr>
                    <th  class="text-left">Receipt No</th>
                    <th  class="text-left">Receipt Date</th>
                    <th  class="text-left">Invoice No</th>
                    <th  class="text-left">Amount</th>
                    <th  class="text-left">Customer Name</th>
                    <th  class="text-left">Status</th>
                    <th  class="text-left">Receipt Method</th>
					<th  class="text-left"></th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>
</form>

</div>
</div>