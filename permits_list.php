<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
require_once('GlobalFunctions.php');
require_once('county_details.php');
//require('class_test.php');

if (!isset($_SESSION))
{
    session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];

//$salaam=new Salamu;

//echo $salaam->Hi("kikuyu");


if ($_REQUEST['resend']=='1')
{   
    $PermitNo=$_REQUEST['PermitNo'];    
    return resendPermit($db,$PermitNo,$cosmasRow);
    $msg=$feedBack[1];
}

if ($_REQUEST['revoke']=='1')
{   
    $PermitNo=$_REQUEST['permitno'];
    //print_r ($_REQUEST);
    $sql="update permits set status=2 where PermitNo='$PermitNo'";
    $result=sqlsrv_query($db,$sql);
    if($result){
        $msg="Permit Revoked Successfully";
    }else
    {
        DisplayErrors();
        $msg="Action Failed";
    }
}

?>
    <link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/prettify/prettify.css" rel="stylesheet">
    <script type="text/javascript">
        $(".datepicker").datepicker();
    </script>
<body class="metro">
    <div class="example">
        <legend>Permits</legend>
        <form>
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th colspan="9" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>
                  <tr>
                    <td colspan="9">
                        <table width="100%">
                            <tr>
                                <td><label>From Date </label>
                                        <div class="input-control text datepicker" data-role="input-control" data-format="dd/mm/yyyy">                       
                                            <input type="text" id="fromDate" name="fromDate" value="<?php echo $fromDate ?>"></input>
                                            <button class="btn-date" type="button"></button>                
                                        </div>
                                </td>
                                <td ><label>To Date </label>
                                    <div class="input-control text datepicker" data-role="input-control" data-format="dd/mm/yyyy">                       
                                        <input type="text" id="toDate" name="toDate" value="<?php echo $toDate ?>"></input>         <button class="btn-date" type="button"></button>    
                                    </div>
                                </td>
                                <td><label>Permit Number</label>
                                        <div class="input-control text" data-role="input-control">                      
                                            <input type="text" width="100" id="PermitNo" name="PermitNo" value="<?php echo $PermitNo ?>"></input>                
                                        </div>
                                </td>
                                <td><label>Application Number</label>
                                    <div class="input-control text" data-role="input-control">                      
                                        <input type="text" width="15" id="ServiceHeaderID" name="ServiceHeaderID" value="<?php echo $ServiceHeaderID ?>"></input>             
                                    </div>
                                </td>
                                <td><label>Invoice Number</label>
                                    <div class="input-control text" data-role="input-control">                      
                                        <input type="text" width="15" id="InvoiceHeaderID" name="InvoiceHeaderID" value="<?php echo $ServiceHeaderID ?>"></input>             
                                    </div>
                                </td>
                                <td><label>Customer Name</label>
                                    <div class="input-control text" data-role="input-control">                      
                                        <input type="text" width="15" id="CustomerName" name="CustomerName" value="<?php echo $CustomerName ?>"></input>             
                                    </div>
                                </td>                                
                                <td><label>&nbsp;</label>
                                <input name="btnSearch" type="button" onclick="loadmypage('permits_list.php?'+
                                            '&fromDate='+this.form.fromDate.value+                              
                                            '&toDate='+this.form.toDate.value+
                                            '&PermitNo='+this.form.PermitNo.value+                                
                                            '&ServiceHeaderID='+this.form.ServiceHeaderID.value+                                '&search=1','content','loader','listpages','','Permits','fromDate='+this.form.fromDate.value+':toDate='+this.form.toDate.value+':PermitNo='+this.form.PermitNo.value+':ServiceHeaderID='+this.form.ServiceHeaderID.value+':CustomerName='+this.form.CustomerName.value+':InvoiceHeaderID='+this.form.InvoiceHeaderID.value+'')" value="Search">
                                </td>
                            <tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <th  class="text-left">Permit<br>Number</th>
                    <th  class="text-left">Application<br>Number</th>
                    <th  class="text-left">Business<br> Name</th>
                    <th  class="text-left">Ward</th>
                    <th  class="text-left">Business<br>Activity</th>
                    <th  class="text-left">Permit<br> Cost</th>
                    <th  class="text-left">Issue<br> Date</th>                    
                    <th  class="text-left">Expiry<br> Date</th>
                    <th  class="text-left">Actions</th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>
        </form>
    </div>
</body>