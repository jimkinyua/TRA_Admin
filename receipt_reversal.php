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

$ReferenceNumber=$_REQUEST['refno'];
$InvoiceHeaderID=$_REQUEST['InvoiceHeaderID'];
$amount=$_REQUEST['Amount'];
$ReceiptID=$_REQUEST['ReceiptID'];
$Action=$_REQUEST['Action'];
$PageID=$_REQUEST['PageID'];
$Reason=$_REQUEST['Reason'];



$PageID=$_REQUEST['PageID'];
$msg = $_REQUEST['msg'];    

$params = array();
$options = array("Scrollable" => SQLSRV_CURSOR_KEYSET );


$ReferenceNumber=$_REQUEST['refno'];
$InvoiceHeaderID=$_REQUEST['InvoiceHeaderID'];
$amount=$_REQUEST['Amount'];
$ReceiptID=$_REQUEST['ReceiptID'];
$Action=$_REQUEST['Action'];
$PageID=$_REQUEST['PageID'];

?>
<div class="example">
<form>
	<fieldset>
	  <legend>Receipt Reversal</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
             
		  </tr>
            <tr>
                <td width="50%">
                    <label>Reference No</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="refno" name="refno" value="<?php echo $ReferenceNumber; ?>" disabled="disabled"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                </td>
            </tr> 
		  </tr>
          <tr>
                <td width="50%">
                    <label>Invoice Number</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="InvoiceHeaderID" name="InvoiceHeaderID" value="<?php echo $InvoiceHeaderID; ?>" disabled="disabled"></input>
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
                        <input type="text" id="amount" name="amount" value="<?php echo number_format($amount,2); ?>" disabled="disabled"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                </td>
            </tr>

            <tr>
                <td width="50%">
                    <label>Reason for Request</label>
                    <div class="input-control textarea" data-role="input-control">
                        <textarea id="reason" name="reason"  disabled="disabled"><?php echo $Reason; ?></textarea>                        
                    </div>
                </td>
                <td width="50%">
            
                </td>
            </tr>
			
            <tr>
                <td width="50%">
                    <label>Comment</label>
                    <div class="input-control textarea" data-role="input-control">
                        <textarea id="comments" name="comments" value="<?php echo $comments; ?>"></textarea>                        
                    </div>
                </td>
                <td width="50%">
            
                </td>
            </tr>		
                     
        </table>

        

		<input name="Button" type="button" onclick="loadmypage('approval_requests.php?approve=1'+
					'&DocumentNo='+this.form.refno.value+								
                    '&ReceiptID='+<?php echo $ReceiptID; ?>+
                    '&PageID='+<?php echo $PageID; ?>+
                    '&Action='+<?php echo $Action; ?>+
                    '&Comments='+this.form.comments.value+ 
                    '&amount='+this.form.amount.value+ 
                    '&InvoiceHeaderID='+<?php echo $InvoiceHeaderID; ?>+
                    '','content','loader','listpages','','ApprovalRequests','<?php echo $_SESSION['RoleCenter']; ?>')" value="Approve">

        <input name="Button" type="button" onclick="loadmypage('approval_requests.php?approve=0'+
                    '&DocumentNo='+this.form.refno.value+                               
                    '&ReceiptID='+<?php echo $ReceiptID; ?>+
                    '&PageID='+<?php echo $PageID; ?>+
                    '&Action='+<?php echo $Action; ?>+
                    '&Comments='+this.form.comments.value+ 
                    '&amount='+this.form.amount.value+ 
                    '&InvoiceHeaderID='+<?php echo $InvoiceHeaderID; ?>+
                    '','content','loader','listpages','','ApprovalRequests','<?php echo $_SESSION['RoleCenter']; ?>')" value="Decline">


	</fieldset>
</form>
</div>