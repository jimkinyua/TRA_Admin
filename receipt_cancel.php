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

$refno=$_REQUEST['ReferenceNumber'];
$amount=$_REQUEST['amount'];
$ReceiptID=$_REQUEST['ReceiptID'];

//print_r($_REQUEST);

if (isset($_REQUEST['msg']))
{
	$msg = $_REQUEST['msg'];	
}

$SubCountyID="0";
$SenderName="";


?>
<div class="example">
<form>
	<fieldset>
	  <legend>Cancel A Receipt</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
             
		  </tr>
            <tr>
                <td width="50%">
                    <label>Reference No</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="refno" name="refno" value="<?php echo $refno; ?>" disabled="disabled"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                </td>
            </tr> 
		  </tr>
            <tr>
                <td width="50%">
                    <label>Amount</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="amount" name="amount" value="<?php echo $amount; ?>" disabled="disabled"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr>
			
            <tr>
                <td width="50%">
                    <label>Reason for Cancellation</label>
                    <div class="input-control textarea" data-role="input-control">
                        <textarea name="reason" id="reason"></textarea>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr>
                     
        </table>
		<input name="Button" type="button" 
        onclick="loadmypage('receipts_list.php?cancel=1'+
		'&ReferenceNumber='+this.form.refno.value+
        '&reason='+this.form.reason.value+
        '&ReceiptID='+<?php echo $ReceiptID; ?>+
        '','content','loader','listpages','','Receipts','')" value="Cancel">

	</fieldset>
</form>
</div>