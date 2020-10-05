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

$mpesa_code=$_REQUEST['mpesa_code'];
$mpesa_amt=$_REQUEST['mpesa_amt'];
$mpesa_sender=$_REQUEST['mpesa_sender'];

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
	  <legend>Link MPESA Receipt to an Invoice</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
            <tr>
                <td width="50%">
                    <label>Sender Name</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="SenderName" name="SenderName" value="<?php echo $mpesa_sender; ?>" disabled="disabled"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr> 
		  </tr>
            <tr>
                <td width="50%">
                    <label>MPesa Transaction No</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="mpesa_code" name="mpesa_code" value="<?php echo $mpesa_code; ?>" disabled="disabled"></input>
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
                        <input type="text" id="amount" name="amount" value="<?php echo $mpesa_amt; ?>" disabled="disabled"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr>
			
            <tr>
                <td width="50%">
                    <label>Allocated Amount</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="splitamount" name="splitamount" value="<?php echo $mpesa_amt; ?>" ></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr>			

            <tr>
                <td width="50%">
                    <label>Invoice</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="InvoiceNo" name="InvoiceNo" value="" ></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr>			
                     
        </table>
		<input name="Button" type="button" onclick="loadmypage('mpesa_list.php?link=1'+
        											'&mpesa_code='+this.form.mpesa_code.value+
                                                    '&InvoiceNo='+this.form.InvoiceNo.value+
													'&mpesa_amt='+this.form.amount.value+
													'&splitamount='+this.form.splitamount.value+
                                                    '','content','loader','listpages','','Mpesa','')" value="Link">

	</fieldset>
</form>
</div>