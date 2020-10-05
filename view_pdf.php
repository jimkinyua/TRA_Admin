<?php
	require 'DB_PARAMS/connect.php';
	require_once('utilities.php');
	require_once('GlobalFunctions.php');
	require_once('county_details.php');

	//echo $feedBack[1];
	
	$report=$_REQUEST['report'];
	$type=$_REQUEST['type'];
	
	if($type=='invoice'){
        //echo 'sawa';		
		$Remark='';
		$ApplicationID=$_REQUEST['ServiceHeaderID'];
		$InvoiceHeaderID=$_REQUEST['InvoiceHeaderID'];
		$feedBack=createInvoice($db,$ApplicationID,$cosmasRow,$Remark,'',$InvoiceHeaderID);
				  //createInvoice($db,$ApplicationID,$cosmasRow,$Remark,$CustomerName,$InvoiceHeaderID	)
		$reportfile='pdfdocs/invoices/'.$InvoiceHeaderID.'.pdf';
	}else{
		
		$reportfile='pdfdocs/sbps/'.$report.'.pdf';
		//exit($reportfile);

	}

?>
<!DOCTYPE html>
<html>
<head>
	
</head>
<body>
<div class="example">
	<embed src="<?php echo $reportfile; ?>" height="800" width="100%" ></embed>	
</div>
</body>
</html>

