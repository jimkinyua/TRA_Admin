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
$amount=$_REQUEST['amount'];
$ReceiptID=$_REQUEST['ReceiptID'];
$Action=$_REQUEST['Action'];
$PageID=$_REQUEST['PageID'];

$msg = $_REQUEST['msg'];    

$params = array();
$options = array("Scrollable" => SQLSRV_CURSOR_KEYSET );


if ($_REQUEST['delete']=='1')
{
    $ReceiptID=$_REQUEST['ReceiptID'];
    $ReferenceNumber=$_REQUEST['refno'];
    $Comments=$_REQUEST['Comments'];
    $amount=$_REQUEST['amount'];
    $Action=$_REQUEST['Action'];
    $PageID=$_REQUEST['PageID'];
    $RefNumber=$_REQUEST['InvoiceHeaderID'];

    $sql="select 1 from ApprovalEntry where DocumentNo='$ReferenceNumber' and ApprovalStatus=0";   

    $qry=sqlsrv_query($db,$sql,$params,$options);
    $num_rows=sqlsrv_num_rows($qry);
    if ($num_rows>0)
    {
        $msg="The document had earlier been sent for reversal";
    }else
    {
        $sql="Insert into ApprovalEntry(SenderID,PageID,DocumentNo,RefNumber, Comments, Action,ApprovalStatus)
          values($CreatedUserID,'$PageID','$ReferenceNumber','$RefNumber','$Comments','$Action',0)";
        $qry=sqlsrv_query($db,$sql);

        if ($qry)
        {
            $rst=SaveTransaction($db,$CreatedUserID,"Created Appreval Request for Receipt number  ".$ReferenceNumber);

            $msg="Receipt Reversal sent for Approval";
        }else{
            DisplayErrors();
        }
    }           
}

?>
<div class="example">
<form>
	<fieldset>
	  <legend>Request to Reverse a Receipt</legend>
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
                    <label>Comment</label>
                    <div class="input-control textarea" data-role="input-control">
                        <textarea id="comments" name="comments" value="<?php echo $comments; ?>"></textarea>                        
                    </div>
                </td>
                <td width="50%">
            
                </td>
            </tr>		
                     
        </table>

		<input name="Button" type="button" onclick="loadmypage('receipt_to_invoice.php?delete=1'+
        											'&refno='+this.form.refno.value+								
                                                    '&ReceiptID='+<?php echo $ReceiptID; ?>+
                                                    '&PageID='+<?php echo $PageID; ?>+
                                                    '&Action='+<?php echo $Action; ?>+
                                                    '&Comments='+this.form.comments.value+ 
                                                    '&amount='+this.form.amount.value+ 
                                                    '&InvoiceHeaderID='+<?php echo $InvoiceHeaderID; ?>+
                                                    '','content','loader','listpages','','Receipts','')" value="Request">

	</fieldset>
</form>
</div>