<?php 
require 'DB_PARAMS/connect.php';
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

$RevenueStreamName='';
$Description='';
$RevenueStreamID='0';
$RevenueStreamCode='';
$RevenueCategoryID='';
$FormID='';
$CreatedDate="";
$PrimaryService="";


if (isset($_REQUEST['receipt']))
{
    if(isset($_REQUEST['DocumentNo']))
    {
        if ($_REQUEST['DocumentNo']!==''){
            $DocumentNo=$_REQUEST['DocumentNo'];

            $sql=" exec spReceiptDocument '$DocumentNo'";
            $result=sqlsrv_query($db,$sql);
            if($result){
                $msg="Receipt Re-Receipted successfully";
            }
        }
        
    }else
    {
        $DocumentNo='';
    }
}

if (isset($_REQUEST['import']))
{

    if(isset($_REQUEST['upn']))
    {
        if ($_REQUEST['upn']!==''){
            $upn=$_REQUEST['upn'];

            $sql=" exec spReImportPlots '$upn'";
            $result=sqlsrv_query($db,$sql);
            if($result){
                $msg="Plot Re-Imported successfully";
            }
        }
        
    }else
    {
        $upn='';
    }
}

if (isset($_REQUEST['match']))
{
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

    $newUPN='';
    $InvoiceHeaderID='';
    $Description='';

    if(isset($_REQUEST['InvoiceNo'])){$InvoiceNo=$_REQUEST['InvoiceNo'];}else{$InvoiceNo='';}
    if(isset($_REQUEST['newUPN'])){$newUPN=$_REQUEST['newUPN'];}else{$newUPN='';}

    if($InvoiceNo=='')
    {
        $msg="Please enter the invoicenumber";        
    }

    if($newUPN=='')
    {
        $msg="Please Enter the correct upn";
    }else{
        $sql="select lrn,plotno from land where upn=$newUPN";
       

        $result=sqlsrv_query($db,$sql,$params,$options);

        $recs=sqlsrv_num_rows($result);
        
        if($recs==0){
            $msg="the new upn is not existing in the system";
        }else{
            
            while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
            {
                $lrn=$row['lrn'];
                $plotno=$row['plotno'];

                $Description='(Block '.$lrn.'Plot No: '.$plotno.'UPN: '.$newUPN.')';    
            }

            if($Description!=='')
            {
                $sql="update InvoiceLines set description='$Description' where InvoiceHeaderID=$InvoiceNo";
                $result=sqlsrv_query($db,$sql);
                if($result){
                    $msg="Record updated successfully";
                }else{
                    $msg="Error in Updating";
                }
            }
        }

    }
}


if (isset($_REQUEST['search']))
{
    //print_r($_REQUEST); exit;
    $filter=" where 1=1 ";	
	if(isset($_REQUEST['InvoiceHeaderID']))
    {
        $InvoiceHeaderID=$_REQUEST['InvoiceHeaderID'];
        if ($_REQUEST['InvoiceHeaderID']!=='')
        {
            $InvoiceHeaderID=$_REQUEST['InvoiceHeaderID'];
            $filter.=" and rl.InvoiceHeaderID='$InvoiceHeaderID'";
        }
    }else
    {
        $InvoiceHeaderID='';
    }

    if(isset($_REQUEST['DocumentNo']))
    {
        if ($_REQUEST['DocumentNo']!==''){
            $DocumentNo=$_REQUEST['DocumentNo'];
            $filter.=" and r.ReferenceNumber='$DocumentNo'";
        }
        
    }else
    {
        $DocumentNo='';
    }

    //echo $filter;

	$sql = "select r.ReferenceNumber,r.Amount ReceiptAmount,r.CreatedDate,rl.InvoiceHeaderID,
            rl.Amount AllocatedAmount,il.[Description] InvoiceDescription,c.CustomerName
            from receipts r
            join ReceiptLines rl on rl.ReceiptID=r.ReceiptID
            join InvoiceLines il on rl.InvoiceHeaderID=il.InvoiceHeaderID
            join ServiceHeader sh on il.ServiceHeaderID=sh.ServiceHeaderID
            join Customer c on sh.CustomerID=c.CustomerID $filter";

    //echo $sql;
	$result = sqlsrv_query($db, $sql);
   	if($result)
    {
        while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
        {
            $mdata.='<tr>
            <td>'.date("d/m/Y",strtotime($row['CreatedDate'])).'</td>
            <td>'.$row['ReferenceNumber'].'</td>
            <td>'.$row['InvoiceDescription'].'</td>
            <td>'.number_format($row['ReceiptAmount'],2).'</td>
            <td>'.$row['InvoiceHeaderID'].'</td>
            <td>'.number_format($row['AllocatedAmount'],2).'</td>
            <td>'.$row['CustomerName'].'</td>
            </tr>';
        }
    }
}


 //echo $sql;


?>
<div class="example">
<form>
	<fieldset>
	  <legend>Search Land Receipt(s)</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
			<tr>
                <td width="25%">
                	<label>Invoice Number</label>					
                	<div class="input-control text" data-role="input-control">
						<input name="InvoiceHeaderID" id="InvoiceHeaderID" type="text" value="<?php echo $InvoiceHeaderID; ?>"></input>          	
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="25%">
                    <label>Receipt Ref. Number</label>                   
                    <div class="input-control text" data-role="input-control">
                        <input name="DocumentNo" id="DocumentNo" type="text" value="<?php echo $DocumentNo; ?>"></input>            
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="25%">
                    <label>&nbsp &nbsp</label>
                    <input name="Button" type="button" onclick="loadmypage('effect_receipt.php?'+
                                            '&InvoiceHeaderID='+this.form.InvoiceHeaderID.value+
                                            '&DocumentNo='+this.form.DocumentNo.value+                        
                                            '&search=1','content')" value="Search">

                    <input name="Button" type="button" onclick="loadmypage('effect_receipt.php?'+
                                            '&InvoiceHeaderID='+this.form.InvoiceHeaderID.value+
                                            '&DocumentNo='+this.form.DocumentNo.value+                        
                                            '&receipt=1','content')" value="Re-Receipt">
				</td>
                <td width="25%">
                    
                </td>
			</tr>
            <tr>
                <td>
                    <label>Upn To Re-Import Statement <br> (From LAIFOMS)</label>
                    <div class="input-control text" data-role="input-control">
                            <input name="upn" id="upn" type="text" value="<?php echo $upn; ?>"></input>            
                            <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td>
                    <label>&nbsp &nbsp</label>
                    <input name="Button" type="button" onclick="loadmypage('effect_receipt.php?'+
                                            '&upn='+this.form.upn.value+                     
                                            '&import=1','content')" value="Re-Import">
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>
                    <label>Invoice No</label>
                    <div class="input-control text" data-role="input-control">
                            <input name="InvoiceNo" id="InvoiceNo" type="text" value="<?php echo $InvoiceNo; ?>"></input>            
                            <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td>
                    <label>Match to UPN.. </label>
                    <div class="input-control text" data-role="input-control">
                            <input name="newUPN" id="newUPN" type="text" value="<?php echo $newUPN; ?>"></input>            
                            <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td>
                    <label>&nbsp &nbsp</label>
                    <input name="Button" type="button" onclick="loadmypage('effect_receipt.php?'+
                                            '&newUPN='+this.form.newUPN.value+   
                                            '&InvoiceNo='+this.form.InvoiceNo.value+                     
                                            '&match=1','content')" 
                                            value="Match">
                </td>
                <td></td>
                <td></td>
            </tr>                     
        </table>
        <table class="table striped bordered hovered">
                <thead>                
                <tr>
                    <th class="text-left">Receipt Date</th>
                    <th class="text-left">Reference Number</th>
                    <th class="text-left">Invoice Description</th>
                    <th class="text-left">ReceiptAmount</th>
                    <th class="text-left">Invoice Number</th>
                    <th class="text-left">AllocatedAmount</th>
                     <th class="text-left">Customer</th>                
                </tr>
                </thead>
                <tbody>
                    <?php
                        echo $mdata;
                    ?>
                <tbody>
                
                </tbody>
            </table>
		
           
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>