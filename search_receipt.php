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


if (isset($_REQUEST['search']))
{
    // print_r($_REQUEST);
    // exit;	
	$InvoiceHeaderID=isset($_REQUEST['InvoiceHeaderID'])?$_REQUEST['InvoiceHeaderID']:'';	
    $ReferenceNumber= isset($_REQUEST['ReferenceNumber'])?$_REQUEST['ReferenceNumber']:''; 

    if ($InvoiceHeaderID!==''){
        $filter=" where rl.InvoiceHeaderID=$InvoiceHeaderID";
    } else if($ReferenceNumber!==''){
        $filter=" where r.ReferenceNumber='$ReferenceNumber'";
    } 
	$sql = "select lr.landreceiptsID,lr.upn,lr.LocalAuthorityID,lr.LaifomsUPN,lr.DocumentNo,r.Amount ReceiptAmount,rl.InvoiceHeaderID,rl.Amount,il.[Description] from landreceipts lr
        join receipts r on lr.DocumentNo=r.ReferenceNumber
        join ReceiptLines rl on rl.ReceiptID=r.ReceiptID
        join InvoiceLines il on rl.InvoiceHeaderID=il.InvoiceHeaderID
         $filter
        order by lr.DateReceived";
    //echo $filter;
	$result=sqlsrv_query($db,$sql);
    while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
    {
        $mdata.='<tr>  
            <td>'.$row['landreceiptsID'].'</td>          
            <td>'.$row['DocumentNo'].'</td>
            <td>'.number_format($row['ReceiptAmount'],2).'</td>
            <td>'.$row['InvoiceHeaderID'].'</td>
            <td>'.number_format($row['Amount'],2).'</td>
            <td>'.$row['Description'].'</td>
            <td>'.number_format($row['upn'],2).'</td>
            <td>'.number_format($row['LoacalAuthorityID'],2).'</td>
            <td>'.number_format($row['LaifomsUPN'],2).'</td>
            </tr>';
    }

    

    // echo $sql;
}
if($_REQUEST['update']==1)
{
    $upn=$_REQUEST['upn'];
    $laifomsupn=$_REQUEST['laifomsupn'];
    $LocalAuthorityID=$_REQUEST['LocalAuthorityID'];
    $landreceiptsID=$_REQUEST['landreceiptsID'];

    $sql="update landreceipts set upn=$upn, LaifomsUPN=$laifomsupn, LocalAuthorityID=$LocalAuthorityID where landreceiptsID=$landreceiptsID";

    //echo $sql;

    $result=sqlsrv_query($db,$sql);
    if($result){
        $sql="exec spRefreshLand $upn";
        $result=sqlsrv_query($db,$sql);

        $msg="Update successful";
    }else{
        DisplayErrors();
    }


}


?>

<div class="example">
    <form>
    	<fieldset>
    	  <legend>Search Receipt</legend>
    		<table width="100%" border="0" cellspacing="0" cellpadding="3">
    			<tr>
    			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
                </tr>

    			<tr>
                    <td >
                    	<label>Enter Invoice Number</label>
    					
                    	<div class="input-control text" data-role="input-control">
    						<input name="InvoiceHeaderID" id="InvoiceHeaderID" type="text" value=""></input>                    	
                            <button class="btn-clear" tabindex="-1"></button>
                        </div>
                    </td>
                    <td>
                        <label>ReferenceNumber</label>
                        
                        <div class="input-control text" data-role="input-control">
                            <input name="ReferenceNumber" id="ReferenceNumber" type="text" value=""></input>                        
                            <button class="btn-clear" tabindex="-1"></button>
                        </div>
                    </td>

                    <td>

                        <input name="Button" type="button" onclick="loadmypage('search_receipt.php?'+
                                                '&InvoiceHeaderID='+this.form.InvoiceHeaderID.value+ 
                                                '&ReferenceNumber='+this.form.ReferenceNumber.value+                        
                                                '&search=1','content')" value="Search">
    				</td>
    			</tr>			
                         
            </table>
            <table class="table striped bordered hovered">
                    <thead>
                        <tr>
                            <th class="text-left">Record ID</th>
                            <th class="text-left">Document No</th>
                            <th class="text-left">Receipt Amount</th>
                            <th class="text-left">Invoice No</th>
                            <th class="text-left">Invoice Amount</th>
                            <th class="text-left">Description</th>
                            <th class="text-left">UPN</th>
                            <th class="text-left">Loacal Authority</th>
                            <th class="text-left">LaifomsUPN</th>                  
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            echo $mdata;
                        ?>
                    <tbody>
                    
                    </tbody>
            </table>

            <table width="100%" border="0" cellspacing="2" cellpadding="3">
                <tr>
                    <td>
                        <label>LandRecordID</label>
                        
                        <div class="input-control text" data-role="input-control">
                            <input name="landreceiptsID" id="landreceiptsID" type="text" value=""></input>                        
                            <button class="btn-clear" tabindex="-1"></button>
                        </div>
                    </td>
                    <td>
                        <label>upn</label>                        
                        <div class="input-control text" data-role="input-control">
                            <input name="upn" id="upn" type="text" value=""></input>                        
                            <button class="btn-clear" tabindex="-1"></button>
                        </div>
                    </td>
                    <td>
                        <label>Laifoms Upn</label>
                        
                        <div class="input-control text" data-role="input-control">
                            <input name="laifomsupn" id="laifomsupn" type="text" value=""></input>                        
                            <button class="btn-clear" tabindex="-1"></button>
                        </div>
                    </td>
                    <td>
                        <label>LocalAuthorityID</label>
                        
                        <div class="input-control text" data-role="input-control">
                            <input name="LocalAuthorityID" id="LocalAuthorityID" type="text" value=""></input>                        
                            <button class="btn-clear" tabindex="-1"></button>
                        </div>
                    </td>

                    <td>

                    <input name="BtnSearch" id="BtnSearch" type="button" onclick="loadmypage('search_receipt.php?'+
                            '&landreceiptsID='+this.form.landreceiptsID.value+ 
                            '&upn='+this.form.upn.value+
                            '&laifomsupn='+this.form.laifomsupn.value+  
                            '&LocalAuthorityID='+this.form.LocalAuthorityID.value+                        
                            '&update=1','content')" value="Update">
                    </td>
                </tr>                        
            </table>               
            <div style="margin-top: 20px">
            </div>
    	</fieldset>
    </form>
</div>
