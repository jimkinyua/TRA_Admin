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

$ReferenceNumber=$_REQUEST['DocumentNo'];
$amount=$_REQUEST['Amount'];
$lrn=$_REQUEST['lrn'];
$plotno=$_REQUEST['plotno'];
$receiptDate=$_REQUEST['receiptDate'];
$InvoiceHeaderID=$_REQUEST['InvoiceHeaderID'];



$msg = $_REQUEST['msg'];    

$params = array();
$options = array("Scrollable" => SQLSRV_CURSOR_KEYSET );

if($_REQUEST['match']==1){
    //print_r($_REQUEST);
    $ReferenceNumber=$_REQUEST['ReferenceNumber'];
    $Amount=$_REQUEST['Amount'];
    $lrn=$_REQUEST['lrn'];
    $plotno=$_REQUEST['plotno'];
    $upn=$_REQUEST['upn'];
    $receiptDate=$_REQUEST['receiptDate'];
    $InvoiceHeaderID=$_REQUEST['InvoiceHeaderID'];

    $Description='Payment';
    $LocalAuthorityID=0;
    $LaifomsUPN='';


    $sql="select laifomsUPN,LocalAuthorityID from land where upn='$upn'";
    $result=sqlsrv_query($db,$sql);
    while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
        $LocalAuthorityID=$row['LocalAuthorityID'];
        $LaifomsUPN=$row['laifomsUPN'];
    }


    $sql="if not exists(select 1 from LANDRECEIPTS where DocumentNo='$ReferenceNumber' and UPN=$upn)begin
                        insert into LandReceipts (DateReceived, LocalAuthorityID,UPN,LaifomsUPN,[Description],DocumentNo,Amount,Principal,Penalty,Balance) 
                        Values('$receiptDate',$LocalAuthorityID,$upn,'$LaifomsUPN','$Description','$ReferenceNumber','$Amount','$Amount','0',0)end
                        else
                        begin
                            Declare @LandRecID bigint
                            select @LandRecID=landreceiptsid from LANDRECEIPTS where DocumentNo='$ReferenceNumber' and abs(Amount)='$Amount'
                            update LANDRECEIPTS set upn='$upn',LaifomsUPN='$LaifomsUPN',LocalAuthorityID='$LocalAuthorityID' where DocumentNo='$ReferenceNumber' and LandReceiptsID=@LandRecID
                        end";

    $result=sqlsrv_query($db,$sql);

    if($result){
        $sql="exec spRefreshLand $upn";
        $result=sqlsrv_query($db,$sql);

        $sql="exec spRefreshLandStatement $upn";
        $result=sqlsrv_query($db,$sql);

        $sql="update MPlotPayments set Matched=1 where InvoiceHeaderID=$InvoiceHeaderID and DocumentNo='$ReferenceNumber'";
        echo $sql;
        $result=sqlsrv_query($db,$sql);

        $rst=SaveTransaction($db,$CreatedUserID,"Matched receipt no ".$ReferenceNumber.' to plotno '.$upn.' (UPN)');
        $msg="Payment Matched Successfully";
    }else{
        $msg="Action Failed";
    }

}




?>
<div class="example">
<form>
	<fieldset>
	  <legend>Match Payment to Plot</legend>
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
                    <label>Block Number</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="lrn" name="lrn" value="<?php echo $lrn; ?>" disabled="disabled"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
                    <label>Plot Number</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="plotno" name="plotno" value="<?php echo $plotno; ?>" disabled="disabled"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
            </tr>
            <tr>
                <td width="50%">
                    <label>New UPN</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="upn" name="upn"  value="<?php echo $upn; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>                       
                    </div>
                </td>
                <td width="50%">
            
                </td>
            </tr>		
                     
        </table>

		<input name="Button" type="button" onclick="loadmypage('match_receipt.php?match=1'+
        											'&upn='+this.form.upn.value+								
                                                    '&ReferenceNumber='+this.form.refno.value+                                                 
                                                    '&Amount='+this.form.amount.value+ 
                                                    '&upn='+this.form.upn.value+ 
                                                    '&lrn='+this.form.lrn.value+ 
                                                    '&receiptDate=+<?php echo $receiptDate ?>'+ 
                                                    '&InvoiceHeaderID=+<?php echo $InvoiceHeaderID ?>'+                                           
                                                    '&plotno='+this.form.plotno.value+
                                                    '','content','loader','listpages','','missingReceipts','')" value="Match">

	</fieldset>
</form>
</div>