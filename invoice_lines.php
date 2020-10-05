<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
require_once('GlobalFunctions.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];


if (isset($_REQUEST['ServiceName'])) { $ServiceName = $_REQUEST['ServiceName']; }
if (isset($_REQUEST['InvoiceHeaderID'])) { $InvoiceHeaderID = $_REQUEST['InvoiceHeaderID']; }
if (isset($_REQUEST['ServiceHeaderID'])) { $ServiceHeaderID = $_REQUEST['ServiceHeaderID']; }


//print_r($_REQUEST);


if($_REQUEST['add']==1){
    $InvoiceHeaderID=$_REQUEST['InvoiceHeaderID'];
    $ServiceID=$_REQUEST['ServiceID'];
    $ServiceHeaderID=$_REQUEST['ServiceHeaderID'];
    $Amount=$_REQUEST['Amount'];

    //print_r($_REQUEST);

    $s_sql="set dateformat dmy if not exists 
        (select 1 from InvoiceLines where InvoiceHeaderID=$InvoiceHeaderID and ServiceID=$ServiceID)  
        insert into InvoiceLines (ServiceHeaderID,InvoiceHeaderID,ServiceID,Amount,CreatedBy) 
        Values($ServiceHeaderID,$InvoiceHeaderID,$ServiceID,$Amount,$CreatedUserID)";
        $result = sqlsrv_query($db, $s_sql);
        if (!$result)
        {
            DisplayErrors();
            //echo $s_sql;
            $msg="Action failed";
        }else{
            //echo $s_sql;
            $msg="Charge Applied Successfully";
        }
}

if($_REQUEST['remove']==1){
    //print_r($_REQUEST); 
    $InvoicelineID=$_REQUEST['InvoiceLineID'];
    $InvoiceHeaderID=$_REQUEST['InvoiceHeaderID'];
    
    $s_sql="delete from invoicelines where InvoicelineID='$InvoicelineID'";
        $result = sqlsrv_query($db, $s_sql);
        if (!$result)
        {
            DisplayErrors();
            echo $s_sql;
            $msg="Action failed";
        }else{
            $rst=SaveTransaction($db,$CreatedUserID," Removed Charge from Invoice Number $InvoiceHeaderID");
            $msg="Charge Removed Successfully";
        }
}

?>
    <link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/prettify/prettify.css" rel="stylesheet">
<body class="metro">
        <div class="example">
        <legend>Add Charges to Invoice Number <?php echo $InvoiceHeaderID; ?></legend>
<form>        
            <table class="table striped hovered dataTable" id="dataTables-1" width="100%">
                <thead>
                  <tr>
                    <th colspan="3" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>
                  
                  <tr>
                    <td colspan="3">
                        <table width="100%">
                            <tr>
                                
                                <td><label>Reference Number</label>
                                        <div class="input-control select" data-role="input-control">
                                            <select name="ServiceID"  id="ServiceID">
                                            <option value="0" selected="selected"></option>
                                            <?php 
                                            $s_sql = "SELECT * FROM Services ORDER BY ServiceName";
                                            
                                            $s_result = sqlsrv_query($db, $s_sql);
                                            if ($s_result) 
                                            { 
                                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                                {
                                                    $s_id = $row["ServiceID"];
                                                    $s_name = $row["ServiceName"];                                                                                                   
                                                 ?>
                                            <option value="<?php echo $s_id; ?>" <?php echo $selected; ?>><?php echo $s_name; ?></option>
                                            <?php 
                                                }
                                            }
                                            ?>
                                          </select>
                                          </div>
                                </td>
                                <td><label>Amount</label>
                                    <div class="input-control text" data-role="input-control">                      
                                        <input type="text" width="15" id="Amount" name="Amount" value="<?php echo $Amount ?>"></input>             
                                    </div>
                                </td>
                                
                                <td><label>&nbsp;</label>
                                <input name="btnSearch" type="button" onclick="loadmypage('invoice_lines.php?'+
                                            '&ServiceID='+this.form.ServiceID.value+
                                            '&InvoiceHeaderID='+<?php echo $InvoiceHeaderID ?>+
                                            '&ServiceHeaderID='+<?php echo $ServiceHeaderID ?>+ 
                                            '&Amount='+this.form.Amount.value+                                
                                            '&InvoiceNo='+this.form.Amount.value+'&add=1','content','loader','listpages','','invoices_lines','<?php echo $InvoiceHeaderID; ?>')" value="Add">
                                </td>
                            <tr>
                        </table>
                    <td>
                </tr>
                <tr>
                    <th >Receipt No</th>
                    <th >Receipt Date</th>
                    <th >Amount</th>                 
                    <th ></th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>
</form>

</div>
</div>