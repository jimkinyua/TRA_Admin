<?php
require 'DB_PARAMS/connect.php';

require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';

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
        <legend>Mismatched Receipts</legend>
<form>        
            <table class="table striped hovered dataTable" id="dataTables-1" width="100%">
                <thead>
                  <tr>
                    <th colspan="7" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>
                <tr>
                    <th  class="text-left">Customer Name</th>
                    <th  class="text-left">Lrn</th>
                    <th  class="text-left">Plot No</th>
                    <th  class="text-left">Invoice No</th>
                    <th  class="text-left">Reference Number</th>
                    <th  class="text-left">Amount</th>					
					<th  class="text-left"></th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>
</form>

</div>
</div>