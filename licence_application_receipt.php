<?php 
require 'DB_PARAMS/connect.php';
require('GlobalFunctions.php');
require_once('utilities.php');

if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];

if (isset($_REQUEST['msg']))
{
	$msg = $_REQUEST['msg'];	
}

$InvoiceHeaderID0=0;
$InvoiceHeaderID=0;
$InvoiceHeaderID1=0;
$InvoiceHeaderID2=0;
$InvoiceHeaderID3=0;
$InvoiceHeaderID4=0;

$Customer='';
$Customer1='';
$Customer2='';
$Customer3='';
$Customer4='';

$InvoiceAmount='';
$InvoiceAmount1='';
$InvoiceAmount2='';
$InvoiceAmount3='';
$InvoiceAmount4='';

$Balance='';
$Balance1='';
$Balance2='';
$Balance3='';
$Balance4='';

$ReceiptAmount='';
$ReceiptAmount1='';
$ReceiptAmount2='';
$ReceiptAmount3='';
$ReceiptAmount4='';

$DateReceived=Date("d/m/Y");
$RefNumber="";
$BankID=0;
$PaymentMethod="";
$ReceiptAmount=0;

if (isset($_REQUEST['add'])){
	$InvoiceHeaderID0=isset($_REQUEST['InvoiceHeaderID0'])?$_REQUEST['InvoiceHeaderID0']:0;
	$InvoiceHeaderID=isset($_REQUEST['InvoiceHeaderID'])?$_REQUEST['InvoiceHeaderID']:0;
	$InvoiceHeaderID1=isset($_REQUEST['InvoiceHeaderID1'])?$_REQUEST['InvoiceHeaderID1']:0;
	$InvoiceHeaderID2=isset($_REQUEST['InvoiceHeaderID2'])?$_REQUEST['InvoiceHeaderID2']:0;
	$InvoiceHeaderID3=isset($_REQUEST['InvoiceHeaderID3'])?$_REQUEST['InvoiceHeaderID3']:0;
	$InvoiceHeaderID4=isset($_REQUEST['InvoiceHeaderID4'])?$_REQUEST['InvoiceHeaderID4']:0;
	

	

	//print_r($sql); 

	if($InvoiceHeaderID==0){
		$InvoiceHeaderID=$InvoiceHeaderID0;
		$InvoiceHeaderID0=0;

		$sql="select * from dbo.fnInvoiceDetails($InvoiceHeaderID)";
		$Details=sqlsrv_query($db,$sql);
		while($row=sqlsrv_fetch_array($Details,SQLSRV_FETCH_ASSOC)){
			$Customer=$row['CustomerName'];
			$InvoiceAmount=$row['Amount'];
			$Balance=$row['Balance'];
		}
	}else{
		$sql="select * from dbo.fnInvoiceDetails($InvoiceHeaderID)";
		$Details=sqlsrv_query($db,$sql);
		while($row=sqlsrv_fetch_array($Details,SQLSRV_FETCH_ASSOC)){
			$Customer=$row['CustomerName'];
			$InvoiceAmount=$row['Amount'];
			$Balance=$row['Balance'];
		}		
	}

	if($InvoiceHeaderID1==0){
		if($InvoiceHeaderID0!==0)
		{
			$InvoiceHeaderID1=$InvoiceHeaderID0;
			$sql="select * from dbo.fnInvoiceDetails($InvoiceHeaderID0)";
			$Details=sqlsrv_query($db,$sql);
			while($row=sqlsrv_fetch_array($Details,SQLSRV_FETCH_ASSOC)){
				$Customer1=$row['CustomerName'];
				$InvoiceAmount1=$row['Amount'];
				$Balance1=$row['Balance'];	
			}
			$InvoiceHeaderID0=0;
		}
	}else{
		$sql="select * from dbo.fnInvoiceDetails($InvoiceHeaderID1)";
		$Details=sqlsrv_query($db,$sql);
		while($row=sqlsrv_fetch_array($Details,SQLSRV_FETCH_ASSOC)){
			$Customer1=$row['CustomerName'];
			$InvoiceAmount1=$row['Amount'];
			$Balance1=$row['Balance'];
		}		
	}

	if($InvoiceHeaderID2==0){
		if($InvoiceHeaderID0!==0)
		{
			$InvoiceHeaderID2=$InvoiceHeaderID0;
			$sql="select * from dbo.fnInvoiceDetails($InvoiceHeaderID2)";
			$Details=sqlsrv_query($db,$sql);
			while($row=sqlsrv_fetch_array($Details,SQLSRV_FETCH_ASSOC)){
				$Customer2=$row['CustomerName'];
				$InvoiceAmount2=$row['Amount'];
				$Balance2=$row['Balance'];	
			}
			$InvoiceHeaderID0=0;
		}
	}else{
		$sql="select * from dbo.fnInvoiceDetails($InvoiceHeaderID2)";
		$Details=sqlsrv_query($db,$sql);
		while($row=sqlsrv_fetch_array($Details,SQLSRV_FETCH_ASSOC)){
			$Customer2=$row['CustomerName'];
			$InvoiceAmount2=$row['Amount'];
			$Balance2=$row['Balance'];
		}		
	}

	if($InvoiceHeaderID3==0){
		if($InvoiceHeaderID0!==0)
		{
			$InvoiceHeaderID3=$InvoiceHeaderID0;
			$sql="select * from dbo.fnInvoiceDetails($InvoiceHeaderID3)";
			$Details=sqlsrv_query($db,$sql);
			while($row=sqlsrv_fetch_array($Details,SQLSRV_FETCH_ASSOC)){
				$Customer3=$row['CustomerName'];
				$InvoiceAmount3=$row['Amount'];
				$Balance3=$row['Balance'];	
			}
			$InvoiceHeaderID0=0;
		}
	}else{
		$sql="select * from dbo.fnInvoiceDetails($InvoiceHeaderID3)";
		$Details=sqlsrv_query($db,$sql);
		while($row=sqlsrv_fetch_array($Details,SQLSRV_FETCH_ASSOC))
		{
			$Customer3=$row['CustomerName'];
			$InvoiceAmount3=$row['Amount'];
			$Balance3=$row['Balance'];
		}		
	}

	if($InvoiceHeaderID4==0){
		if($InvoiceHeaderID0!==0)
		{
			$InvoiceHeaderID4=$InvoiceHeaderID0;
			$sql="select * from dbo.fnInvoiceDetails($InvoiceHeaderID4)";

			$Details=sqlsrv_query($db,$sql);
			while($row=sqlsrv_fetch_array($Details,SQLSRV_FETCH_ASSOC)){
				$Customer4=$row['CustomerName'];
				$InvoiceAmount4=$row['Amount'];
				$Balance4=$row['Balance'];	
			}
			$InvoiceHeaderID0=0;
		}
	}else{
		$sql="select * from dbo.fnInvoiceDetails($InvoiceHeaderID4)";
		$Details=sqlsrv_query($db,$sql);
		//echo $sql;
		while($row=sqlsrv_fetch_array($Details,SQLSRV_FETCH_ASSOC))
		{
			$Customer4=$row['CustomerName'];
			$InvoiceAmount4=$row['Amount'];
			$Balance4=$row['Balance'];
		}		
	}
	
	
}

if (isset($_REQUEST['remove'])){
	$InvoiceHeaderID0=isset($_REQUEST['InvoiceHeaderID0'])?$_REQUEST['InvoiceHeaderID0']:0;
	$InvoiceHeaderID=isset($_REQUEST['InvoiceHeaderID'])?$_REQUEST['InvoiceHeaderID']:0;
	$InvoiceHeaderID1=isset($_REQUEST['InvoiceHeaderID1'])?$_REQUEST['InvoiceHeaderID1']:0;
	$InvoiceHeaderID2=isset($_REQUEST['InvoiceHeaderID2'])?$_REQUEST['InvoiceHeaderID2']:0;
	$InvoiceHeaderID3=isset($_REQUEST['InvoiceHeaderID3'])?$_REQUEST['InvoiceHeaderID3']:0;
	$InvoiceHeaderID4=isset($_REQUEST['InvoiceHeaderID4'])?$_REQUEST['InvoiceHeaderID4']:0;
	

	

	//print_r($sql); 

	if($InvoiceHeaderID==$InvoiceHeaderID0 && !$InvoiceHeaderID0==0){
		$InvoiceHeaderID=0;
		$InvoiceHeaderID0=0;		
		$Customer='';
		$InvoiceAmount=0;
		$Balance=0;
	}else{
		$sql="select * from dbo.fnInvoiceDetails($InvoiceHeaderID)";
		$Details=sqlsrv_query($db,$sql);
		while($row=sqlsrv_fetch_array($Details,SQLSRV_FETCH_ASSOC)){
			$Customer=$row['CustomerName'];
			$InvoiceAmount=$row['Amount'];
			$Balance=$row['Balance'];
		}		
	}

	if($InvoiceHeaderID1==$InvoiceHeaderID0 && !$InvoiceHeaderID0==0){
		$InvoiceHeaderID1=0;
		$InvoiceHeaderID0=0;		
		$Customer1='';
		$InvoiceAmount1=0;
		$Balance1=0;
	}else{
		$sql="select * from dbo.fnInvoiceDetails($InvoiceHeaderID1)";
		$Details=sqlsrv_query($db,$sql);
		while($row=sqlsrv_fetch_array($Details,SQLSRV_FETCH_ASSOC)){
			$Customer1=$row['CustomerName'];
			$InvoiceAmount1=$row['Amount'];
			$Balance1=$row['Balance'];
		}		
	}

	if($InvoiceHeaderID2==$InvoiceHeaderID0 && !$InvoiceHeaderID0==0){
		$InvoiceHeaderID2=0;
		$InvoiceHeaderID0=0;		
		$Customer2='';
		$InvoiceAmount2=0;
		$Balance2=0;
	}else{
		$sql="select * from dbo.fnInvoiceDetails($InvoiceHeaderID2)";
		$Details=sqlsrv_query($db,$sql);
		while($row=sqlsrv_fetch_array($Details,SQLSRV_FETCH_ASSOC)){
			$Customer2=$row['CustomerName'];
			$InvoiceAmount2=$row['Amount'];
			$Balance2=$row['Balance'];
		}		
	}

	if($InvoiceHeaderID3==$InvoiceHeaderID0 && !$InvoiceHeaderID0==0){
		$InvoiceHeaderID3=0;
		$InvoiceHeaderID0=0;		
		$Customer3='';
		$InvoiceAmount3=0;
		$Balance3=0;
	}else{
		$sql="select * from dbo.fnInvoiceDetails($InvoiceHeaderID3)";
		$Details=sqlsrv_query($db,$sql);
		while($row=sqlsrv_fetch_array($Details,SQLSRV_FETCH_ASSOC))
		{
			$Customer3=$row['CustomerName'];
			$InvoiceAmount3=$row['Amount'];
			$Balance3=$row['Balance'];
		}		
	}

	if($InvoiceHeaderID4==$InvoiceHeaderID0 && !$InvoiceHeaderID0==0){
		$InvoiceHeaderID4=0;
		$InvoiceHeaderID0=0;		
		$Customer4='';
		$InvoiceAmount4=0;
		$Balance4=0;
	}else{
		$sql="select * from dbo.fnInvoiceDetails($InvoiceHeaderID4)";
		$Details=sqlsrv_query($db,$sql);
		while($row=sqlsrv_fetch_array($Details,SQLSRV_FETCH_ASSOC))
		{
			$Customer4=$row['CustomerName'];
			$InvoiceAmount4=$row['Amount'];
			$Balance4=$row['Balance'];
		}		
	}
}

if ($_REQUEST['Receipt']==1)
{
	$DateReceived=$_REQUEST['DateReceived'];
	$SlipAmount=$_REQUEST['SlipAmount'];
	$PaymentMethod=$_REQUEST['PaymentMethod'];
	$RefNumber=$_REQUEST['RefNumber'];
	$BankID=$_REQUEST['BankID'];

	$InvoiceHeaderID=(Double)isset($_REQUEST['InvoiceHeaderID'])?$_REQUEST['InvoiceHeaderID']:0;
	$InvoiceHeaderID1=(Double)isset($_REQUEST['InvoiceHeaderID1'])?$_REQUEST['InvoiceHeaderID1']:0;
	$InvoiceHeaderID2=(Double)isset($_REQUEST['InvoiceHeaderID2'])?$_REQUEST['InvoiceHeaderID2']:0;
	$InvoiceHeaderID3=(Double)isset($_REQUEST['InvoiceHeaderID3'])?$_REQUEST['InvoiceHeaderID3']:0;
	$InvoiceHeaderID4=(Double)isset($_REQUEST['InvoiceHeaderID4'])?$_REQUEST['InvoiceHeaderID4']:0;

	$ReceiptAmount=(Double)isset($_REQUEST['ReceiptAmount'])?$_REQUEST['ReceiptAmount']:0;
	$ReceiptAmount1=(Double)isset($_REQUEST['ReceiptAmount1'])?$_REQUEST['ReceiptAmount1']:0;
	$ReceiptAmount2=(Double)isset($_REQUEST['ReceiptAmount2'])?$_REQUEST['ReceiptAmount2']:0;
	$ReceiptAmount3=(Double)isset($_REQUEST['ReceiptAmount3'])?$_REQUEST['ReceiptAmount3']:0;
	$ReceiptAmount4=(Double)isset($_REQUEST['ReceiptAmount4'])?$_REQUEST['ReceiptAmount4']:0;

	$TotalReceipt=(Double)$ReceiptAmount+(Double)$ReceiptAmount1+(Double)$ReceiptAmount2+(Double)$ReceiptAmount3+(Double)$ReceiptAmount4;
	
	if ((Double)$TotalReceipt<=0){
		$msg="The receipt Amount is not set";
	}else if((Double)$TotalReceipt!==(Double)$SlipAmount){
		$msg="The Slip amount MUST be EQUAL to the Distributed Amount";
	}else if($RefNumber==""){
		$msg="The Reference Number is not set";
	}else if ($PaymentMethod=="0"){
		$msg="The Payment Method is not set";
	}else if($BankID=="0"){
		$msg="The Receiving Bank is not set";
	}else
	{
		if($InvoiceHeaderID!==0 and $ReceiptAmount!==0){

			if((double)$ReceiptAmount>0)
			{	
				$result=ReceiptMoney($db,$DateReceived,$BankID,$RefNumber,$PaymentMethod,$InvoiceHeaderID,$SlipAmount,$ReceiptAmount,$CreatedUserID);
			}
		}
		if($InvoiceHeaderID1!==0 and $ReceiptAmount1!==0){
			if((double)$ReceiptAmount1>0)
			{	
				$result=ReceiptMoney($db,$DateReceived,$BankID,$RefNumber,$PaymentMethod,$InvoiceHeaderID1,$SlipAmount,$ReceiptAmount1,$CreatedUserID);
			}
		}
		if($InvoiceHeaderID2!==0 and $ReceiptAmount2!==0){
			if((double)$ReceiptAmount2>0)
			{	
				$result=ReceiptMoney($db,$DateReceived,$BankID,$RefNumber,$PaymentMethod,$InvoiceHeaderID2,$SlipAmount,$ReceiptAmount2,$CreatedUserID);
			}
		}
		if($InvoiceHeaderID3!==0 and $ReceiptAmount3!==0){	
			if((double)$ReceiptAmount3>0)
			{
				$result=ReceiptMoney($db,$DateReceived,$BankID,$RefNumber,$PaymentMethod,$InvoiceHeaderID3,$SlipAmount,$ReceiptAmount3,$CreatedUserID);
			}
		}
		if($InvoiceHeaderID4!==0 and $ReceiptAmount4!==0){	
			if((double)$ReceiptAmount4>0)
			{
				$result=ReceiptMoney($db,$DateReceived,$BankID,$RefNumber,$PaymentMethod,$InvoiceHeaderID4,$SlipAmount,$ReceiptAmount4,$CreatedUserID);
			}
		}
		
		$msg=$result[1];	
	}

	if($InvoiceHeaderID==0){
		$InvoiceHeaderID=$InvoiceHeaderID0;
		$InvoiceHeaderID0=0;

		$sql="select * from dbo.fnInvoiceDetails($InvoiceHeaderID)";
		$Details=sqlsrv_query($db,$sql);
		while($row=sqlsrv_fetch_array($Details,SQLSRV_FETCH_ASSOC)){
			$Customer=$row['CustomerName'];
			$InvoiceAmount=$row['Amount'];
			$Balance=$row['Balance'];
		}
	}else{
		$sql="select * from dbo.fnInvoiceDetails($InvoiceHeaderID)";
		$Details=sqlsrv_query($db,$sql);
		while($row=sqlsrv_fetch_array($Details,SQLSRV_FETCH_ASSOC)){
			$Customer=$row['CustomerName'];
			$InvoiceAmount=$row['Amount'];
			$Balance=$row['Balance'];
		}		
	}

	if($InvoiceHeaderID1==0){
		if($InvoiceHeaderID0!==0)
		{
			$InvoiceHeaderID1=$InvoiceHeaderID0;
			$sql="select * from dbo.fnInvoiceDetails($InvoiceHeaderID0)";
			$Details=sqlsrv_query($db,$sql);
			while($row=sqlsrv_fetch_array($Details,SQLSRV_FETCH_ASSOC)){
				$Customer1=$row['CustomerName'];
				$InvoiceAmount1=$row['Amount'];
				$Balance1=$row['Balance'];	
			}
			$InvoiceHeaderID0=0;
		}
	}else{
		$sql="select * from dbo.fnInvoiceDetails($InvoiceHeaderID1)";
		$Details=sqlsrv_query($db,$sql);
		while($row=sqlsrv_fetch_array($Details,SQLSRV_FETCH_ASSOC)){
			$Customer1=$row['CustomerName'];
			$InvoiceAmount1=$row['Amount'];
			$Balance1=$row['Balance'];
		}		
	}

	if($InvoiceHeaderID2==0){
		if($InvoiceHeaderID0!==0)
		{
			$InvoiceHeaderID2=$InvoiceHeaderID0;
			$sql="select * from dbo.fnInvoiceDetails($InvoiceHeaderID2)";
			$Details=sqlsrv_query($db,$sql);
			while($row=sqlsrv_fetch_array($Details,SQLSRV_FETCH_ASSOC)){
				$Customer2=$row['CustomerName'];
				$InvoiceAmount2=$row['Amount'];
				$Balance2=$row['Balance'];	
			}
			$InvoiceHeaderID0=0;
		}
	}else{
		$sql="select * from dbo.fnInvoiceDetails($InvoiceHeaderID2)";
		$Details=sqlsrv_query($db,$sql);
		while($row=sqlsrv_fetch_array($Details,SQLSRV_FETCH_ASSOC)){
			$Customer2=$row['CustomerName'];
			$InvoiceAmount2=$row['Amount'];
			$Balance2=$row['Balance'];
		}		
	}

	if($InvoiceHeaderID3==0){
		if($InvoiceHeaderID0!==0)
		{
			$InvoiceHeaderID3=$InvoiceHeaderID0;
			$sql="select * from dbo.fnInvoiceDetails($InvoiceHeaderID3)";
			$Details=sqlsrv_query($db,$sql);
			while($row=sqlsrv_fetch_array($Details,SQLSRV_FETCH_ASSOC)){
				$Customer3=$row['CustomerName'];
				$InvoiceAmount3=$row['Amount'];
				$Balance3=$row['Balance'];	
			}
			$InvoiceHeaderID0=0;
		}
	}else{
		$sql="select * from dbo.fnInvoiceDetails($InvoiceHeaderID3)";
		$Details=sqlsrv_query($db,$sql);
		while($row=sqlsrv_fetch_array($Details,SQLSRV_FETCH_ASSOC))
		{
			$Customer3=$row['CustomerName'];
			$InvoiceAmount3=$row['Amount'];
			$Balance3=$row['Balance'];
		}		
	}

	if($InvoiceHeaderID4==0){
		if($InvoiceHeaderID0!==0)
		{
			$InvoiceHeaderID4=$InvoiceHeaderID0;
			$sql="select * from dbo.fnInvoiceDetails($InvoiceHeaderID4)";
			$Details=sqlsrv_query($db,$sql);
			while($row=sqlsrv_fetch_array($Details,SQLSRV_FETCH_ASSOC)){
				$Customer4=$row['CustomerName'];
				$InvoiceAmount4=$row['Amount'];
				$Balance4=$row['Balance'];	
			}
			$InvoiceHeaderID0=0;
		}
	}else{
		$sql="select * from dbo.fnInvoiceDetails($InvoiceHeaderID4)";
		$Details=sqlsrv_query($db,$sql);
		while($row=sqlsrv_fetch_array($Details,SQLSRV_FETCH_ASSOC))
		{
			$Customer4=$row['CustomerName'];
			$InvoiceAmount4=$row['Amount'];
			$Balance4=$row['Balance'];
		}		
	}
}

?>
<script type="text/javascript">
    	$(".datepicker").datepicker();
</script>
<body class="metro">
<div class="example">
	<form class="ui form" action="receipt.php" name="fnForm2">
    <h3> Receipt Payment </h3>
	<fieldset>
    <div>
      <table class="ui selectable attached basic table">
        <thead>
		  <tr>
			  <td colspan="6" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
          <tr>
            <th class="three wide">Invoice No</th>            
            <th class="three wide">Customer</th>
            <th class="four wide">Amount Invoiced</th>
			<th class="four wide">Balance</th>
            <th class="four wide">Amount Receipted</th>
            <td class="one Wide"></td>
          </tr>
        </thead>
        <tbody id="tbody"> 
        	<tr>
              <td>
                <div class="input-control text" data-role="input-control">
                    <input type="text" id="InvoiceHeaderID0" name="InvoiceHeaderID0"  ></input>
                    <button class="btn-clear" tabindex="-1"></button>
                </div>
              </td>
              <td>
              <input name="Button" type="button" onclick="loadmypage('receipt.php?'+
              										'&add=1'+
              										'&InvoiceHeaderID0='+this.form.InvoiceHeaderID0.value+
        											'&InvoiceHeaderID='+<?php echo $InvoiceHeaderID; ?>+
        											'&InvoiceHeaderID1='+<?php echo $InvoiceHeaderID1; ?>+
        											'&InvoiceHeaderID2='+<?php echo $InvoiceHeaderID2; ?>+
        											'&InvoiceHeaderID3='+<?php echo $InvoiceHeaderID3; ?>+
        											'&InvoiceHeaderID4='+<?php echo $InvoiceHeaderID4; ?>+                                   
                                                    '','content','loader','listpages','','')" value="ADD INVOICE">
              </td>
          	  <td></td>
          	  <td></td>
          	  <td></td>
          	  <td></td>
          </tr>
        	<tr>
              <td class='due' data-due=""> <?php echo $InvoiceHeaderID ?></td>
              <td><?php echo $Customer ?></td>              
              <td class='due' data-due=""> <?php echo number_format($InvoiceAmount,2) ?></td>
			  <td class='due' data-due=""> <?php echo number_format($Balance,2) ?></td>
              <td  class="required">
                <div class="ui left corner labeled mini input" style="border-radius: 0;">
                  <div class="ui left corner label"> <i class="asterisk icon"  style="color: #d95c5c; font-size: 70%;"></i> </div>
                  <input class='receipted' type="text" name="ReceiptAmount" placeholder="KSh. 0.00" style="padding-left: 25px;">
                </div>
              </td>
              <td>
              <input name="Button" type="button" onclick="loadmypage('receipt.php?'+
              										'&remove=1'+
              										'&InvoiceHeaderID0='+<?php echo $InvoiceHeaderID; ?>+
        											'&InvoiceHeaderID='+<?php echo $InvoiceHeaderID; ?>+
        											'&InvoiceHeaderID1='+<?php echo $InvoiceHeaderID1; ?>+
        											'&InvoiceHeaderID2='+<?php echo $InvoiceHeaderID2; ?>+
        											'&InvoiceHeaderID3='+<?php echo $InvoiceHeaderID3; ?>+
        											'&InvoiceHeaderID4='+<?php echo $InvoiceHeaderID4; ?>+                                   
                                                    '','content','loader','listpages','','')" value="Remove">
              </td>
            </tr>         
            <tr>
              <td class='due' data-due=""> <?php echo $InvoiceHeaderID1 ?></td>              
              <td class='due' data-due=""> <?php echo $Customer1 ?></td>              
              <td class='due' data-due=""> <?php echo number_format($InvoiceAmount1,2) ?></td>
			  <td class='due' data-due=""> <?php echo number_format($Balance1,2) ?></td>
              <td  class="required">
                <div class="ui left corner labeled mini input" style="border-radius: 0;">
                  <div class="ui left corner label"> <i class="asterisk icon"  style="color: #d95c5c; font-size: 70%;"></i> </div>
                  <input class='receipted' type="text" name="ReceiptAmount1" placeholder="KSh. 0.00" style="padding-left: 25px;">
                </div>
              </td>
              <td>
              <input name="Button" type="button" onclick="loadmypage('receipt.php?'+
              										'&remove=1'+
              										'&InvoiceHeaderID0='+<?php echo $InvoiceHeaderID1; ?>+
        											'&InvoiceHeaderID='+<?php echo $InvoiceHeaderID; ?>+
        											'&InvoiceHeaderID1='+<?php echo $InvoiceHeaderID1; ?>+
        											'&InvoiceHeaderID2='+<?php echo $InvoiceHeaderID2; ?>+
        											'&InvoiceHeaderID3='+<?php echo $InvoiceHeaderID3; ?>+
        											'&InvoiceHeaderID4='+<?php echo $InvoiceHeaderID4; ?>+                                   
                                                    '','content','loader','listpages','','')" value="Remove">
              </td>
            </tr> 
            <tr>
              <td class='due' data-due=""> <?php echo $InvoiceHeaderID2 ?></td>
              <td class='due' data-due=""> <?php echo $Customer2 ?></td>
			  <td class='due' data-due=""> <?php echo number_format($InvoiceAmount2,2) ?></td>
			  <td class='due' data-due=""> <?php echo number_format($Balance2,2) ?></td>
              <td  class="required">
                <div class="ui left corner labeled mini input" style="border-radius: 0;">
                  <div class="ui left corner label"> <i class="asterisk icon"  style="color: #d95c5c; font-size: 70%;"></i> </div>
                  <input class='receipted' type="text" name="ReceiptAmount2" placeholder="KSh. 0.00" style="padding-left: 25px;">
                </div>
              </td>
              <td>
              <input name="Button" type="button" onclick="loadmypage('receipt.php?'+
              										'&remove=1'+
              										'&InvoiceHeaderID0='+<?php echo $InvoiceHeaderID2; ?>+
        											'&InvoiceHeaderID='+<?php echo $InvoiceHeaderID; ?>+
        											'&InvoiceHeaderID1='+<?php echo $InvoiceHeaderID1; ?>+
        											'&InvoiceHeaderID2='+<?php echo $InvoiceHeaderID2; ?>+
        											'&InvoiceHeaderID3='+<?php echo $InvoiceHeaderID3; ?>+
        											'&InvoiceHeaderID4='+<?php echo $InvoiceHeaderID4; ?>+                                   
                                                    '','content','loader','listpages','','')" value="Remove">
              </td>
            </tr>
            <tr>
              <td class='due' data-due=""> <?php echo $InvoiceHeaderID3 ?></td>
              <td class='due' data-due=""> <?php echo $Customer3 ?></td>
			  <td class='due' data-due=""> <?php echo number_format($InvoiceAmount3,2) ?></td>
			  <td class='due' data-due=""> <?php echo number_format($Balance3,2) ?></td>
              <td  class="required">
                <div class="ui left corner labeled mini input" style="border-radius: 0;">
                  <div class="ui left corner label"> <i class="asterisk icon"  style="color: #d95c5c; font-size: 70%;"></i> </div>
                  <input class='receipted' type="text" name="ReceiptAmount3" placeholder="KSh. 0.00" style="padding-left: 25px;">
                </div>
              </td>
              <td>
              <input name="Button" type="button" onclick="loadmypage('receipt.php?'+
              										'&remove=1'+
              										'&InvoiceHeaderID0='+<?php echo $InvoiceHeaderID3; ?>+
        											'&InvoiceHeaderID='+<?php echo $InvoiceHeaderID; ?>+
        											'&InvoiceHeaderID1='+<?php echo $InvoiceHeaderID1; ?>+
        											'&InvoiceHeaderID2='+<?php echo $InvoiceHeaderID2; ?>+
        											'&InvoiceHeaderID3='+<?php echo $InvoiceHeaderID3; ?>+
        											'&InvoiceHeaderID4='+<?php echo $InvoiceHeaderID4; ?>+                                   
                                                    '','content','loader','listpages','','')" value="Remove">
              </td>
            </tr>
            <tr>
              <td class='due' data-due=""> <?php echo $InvoiceHeaderID4 ?></td>              
			  <td class='due' data-due=""> <?php echo $Customer4 ?></td>
			  <td class='due' data-due=""> <?php echo number_format($InvoiceAmount4,2) ?></td>
			  <td class='due' data-due=""> <?php echo number_format($Balance4,2) ?></td>
              <td  class="required">
                <div class="ui left corner labeled mini input" style="border-radius: 0;">
                  <div class="ui left corner label"> <i class="asterisk icon"  style="color: #d95c5c; font-size: 70%;"></i> </div>
                  <input class='receipted' type="text" name="ReceiptAmount4" placeholder="KSh. 0.00" style="padding-left: 25px;">
                </div>
              </td>
              <td>
              <input name="Button" type="button" onclick="loadmypage('receipt.php?'+
              										'&remove=1'+
              										'&InvoiceHeaderID0='+<?php echo $InvoiceHeaderID4; ?>+
        											'&InvoiceHeaderID='+<?php echo $InvoiceHeaderID; ?>+
        											'&InvoiceHeaderID1='+<?php echo $InvoiceHeaderID1; ?>+
        											'&InvoiceHeaderID2='+<?php echo $InvoiceHeaderID2; ?>+
        											'&InvoiceHeaderID3='+<?php echo $InvoiceHeaderID3; ?>+
        											'&InvoiceHeaderID4='+<?php echo $InvoiceHeaderID4; ?>+                                   
                                                    '','content','loader','listpages','','')" value="Remove">
              </td>
            </tr>                     
        </tbody>
        
      </table>
    </div>

    <div class="required field">
      	<label>Date</label>
		<div class="input-control text datepicker" data-role="input-control" data-format="dd/mm/yyyy">					
			<input type="text" id="DateReceived" name="DateReceived" value="<?php echo $DateReceived ?>"></input>
			<button class="btn-date" type="button"></button>				
		</div>
    </div>
    <div class="field">
      <label>Bank Slip Amount</label>
      <input type="text" name="SlipAmount" placeholder="Slip Amount">
    </div>
    <div class="required field" width="30%">
      <label>Payment Method</label>
      <select class="ui fluid search dropdown" name="PaymentMethod">
        <option value="0" selected="selected"></option>
		<?php 
		$s_sql = "SELECT ReceiptMethodID,ReceiptMethodName FROM ReceiptMethod ORDER BY 1";
		
		$s_result = sqlsrv_query($db, $s_sql);
		if ($s_result) 
		{ //connection succesful 
			while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
			{
				$s_id = $row["ReceiptMethodID"];
				$s_name = $row["ReceiptMethodName"];
				if ($SubCountyID==$s_id) 
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
    <div class="required field">
      <label>Receiving Bank</label>
      <select class="ui fluid search dropdown" name="BankID">
        <option value="0" selected="selected"></option>
		<?php 
		$s_sql = "SELECT BankID,BankName FROM bANKS ORDER BY 1";
		
		$s_result = sqlsrv_query($db, $s_sql);
		if ($s_result) 
		{ //connection succesful 
			while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
			{
				$s_id = $row["BankID"];
				$s_name = $row["BankName"];
				if ($SubCountyID==$s_id) 
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
    <div class="field">
      <label>Reference Number</label>
      <input type="text" name="RefNumber" value="<?php echo $RefNumber; ?>" placeholder="RefNumber">
    </div>
	<div class="field">
		<input name="btnReceipt" type="button" Value="Save" class="ui button" onclick="loadmypage('receipt.php?'+							
							'&SlipAmount='+this.form.SlipAmount.value+
							'&DateReceived='+this.form.DateReceived.value+
							'&PaymentMethod='+this.form.PaymentMethod.value+
							'&BankID='+this.form.BankID.value+
							'&RefNumber='+this.form.RefNumber.value+
							'&InvoiceHeaderID='+<?php echo $InvoiceHeaderID; ?>+
							'&InvoiceHeaderID1='+<?php echo $InvoiceHeaderID1; ?>+
							'&InvoiceHeaderID2='+<?php echo $InvoiceHeaderID2; ?>+
							'&InvoiceHeaderID3='+<?php echo $InvoiceHeaderID3; ?>+
							'&InvoiceHeaderID4='+<?php echo $InvoiceHeaderID4; ?>+
							'&ReceiptAmount='+this.form.ReceiptAmount.value+
							'&ReceiptAmount1='+this.form.ReceiptAmount1.value+
							'&ReceiptAmount2='+this.form.ReceiptAmount2.value+
							'&ReceiptAmount3='+this.form.ReceiptAmount3.value+
							'&ReceiptAmount4='+this.form.ReceiptAmount4.value+
							'&Receipt=1'+
							'','content')"/>
		<!--					
		<input name="btnReceipt" type="button" Value="Save" class="ui button" onclick="loadmypage('receipt.php?'+							
							'&ReceiptAmount='+this.form.ReceiptAmount.value+'&receipt=1','content')"/> -->
	</div>
	</fieldset>							
</form>
</div>
</body>


    