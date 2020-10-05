<?php 
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];

 //print_r($_REQUEST);

if (isset($_REQUEST['msg']))
{
	$msg = $_REQUEST['msg'];	
}

//print_r($_REQUEST);


    $ServiceID='';
    $SubSystemID='';
    $LinkServiceID='0';
    $FinancialYearID='';
    $ChargeTypeID ='';
    $$ServiceCharge='';
    $ServiceName='';


    $InvoiceHeaderID=$_REQUEST['InvoiceHeaderID'];

    $RatesPayable=0;
    $Arrears=0;
    $Penalty=0; 
    $GroundRent=0;
    $OtherChareges=0;

 
    
    //Application Charges
    $sql="select LI.InvoiceHeaderID,LI.LandPropertyID,LRP.Description,LI.Amount from LandRatesProperties lrp
    join LandInvoices li on li.LandPropertyID=lrp.LandPropertyID
    WHERE LI.InvoiceHeaderID=$InvoiceHeaderID";

    $result=sqlsrv_query($db,$sql);
    while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
        $PropertyID=$row['LandPropertyID'];
        $Description=$row['Description'];
        $Amount=$row['Amount'];
        if($PropertyID==1){
            $RatesPayable=$Amount;
        }else if($PropertyID==2){
            $Arrears=$Amount;
        }else if($PropertyID==3){
           $Penalty=$Amount; 
        }else if($PropertyID==4){
           $GroundRent=$Amount; 
        }else if($PropertyID==5){
           $OtherCharges=$Amount;     
        }       
    }

    if($_REQUEST['update']==1)
    {
        //print_r($_REQUEST);
        $InvoiceHeaderID=$_REQUEST['InvoiceHeaderID'];
        $RatesPayable=isset($_REQUEST['RatesPayable'])?$_REQUEST['RatesPayable']:0;
        $Arrears=isset($_REQUEST['Arrears'])?$_REQUEST['Arrears']:0;
        $Penalty=isset($_REQUEST['Penalty'])?$_REQUEST['Penalty']:0;
        $GroundRent=isset($_REQUEST['GroundRent'])?$_REQUEST['GroundRent']:0;
        $OtherCharges=isset($_REQUEST['OtherCharges'])?$_REQUEST['OtherCharges']:0;

        $sql="select distinct upn from LandInvoices where InvoiceHeaderID=$InvoiceHeaderID";
        $result=sqlsrv_query($db,$sql);
        while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
            $upn=$row['upn'];
        }

        if($RatesPayable>0){
            $sql="Update LandInvoices set Amount=$RatesPayable 
            where InvoiceHeaderID=$InvoiceHeaderID and LandPropertyID=1";
            $result=sqlsrv_query($db,$sql);
        }
        if($Arrears>0){
            $sql="Update LandInvoices set Amount=$Arrears 
            where InvoiceHeaderID=$InvoiceHeaderID and LandPropertyID=2";
            $result=sqlsrv_query($db,$sql);
        }

        if($Penalty>0){
            $sql="Update LandInvoices set Amount=$Penalty 
            where InvoiceHeaderID=$InvoiceHeaderID and LandPropertyID=3";
            $result=sqlsrv_query($db,$sql);
        }

        if($GroundRent>0){
            $sql="Update LandInvoices set Amount=$GroundRent 
            where InvoiceHeaderID=$InvoiceHeaderID and LandPropertyID=4";
            $result=sqlsrv_query($db,$sql);

            $sql="Update Land set GroundRent=$GroundRent where upn=$upn";
            $result=sqlsrv_query($db,$sql);
        }

        if($OtherCharges>0){
            $sql="Update LandInvoices set Amount=$OtherCharges 
            where InvoiceHeaderID=$InvoiceHeaderID and LandPropertyID=5";
            $result=sqlsrv_query($db,$sql);

            $sql="Update Land set OtherCharges=$OtherCharges where upn=$upn";
            $result=sqlsrv_query($db,$sql);
        }

        $InvoiceTotal=$RatesPayable+$Arrears+$Penalty+$GroundRent+$OtherCharges;

        $sql="Update InvoiceLines set Amount=$InvoiceTotal where InvoiceHeaderID=$InvoiceHeaderID";
        $result=sqlsrv_query($db,$sql);

        if($result){
            $msg="update successful";            
        }else if($result){
            $msg="update failed";            
        }



    }    
    


?>
<!DOCTYPE html>
<html>
<head>
    <title>Invoice Breakdown</title>   
</head>
<body class="metro" >
    <div class="example" id="charge">
        <form action="add_charge.php" method="POST">
            <fieldset>
              <legend>Invoice Break Down</legend>
                <table border="0" cellspacing="0" cellpadding="3" width="100%">
                    <tr>
                      <td align="center" colspan="2" style="color:#F00"><?php echo $msg; ?></td>
                  </tr>
                  <tr>
                        <td width="50%">
                            <label>Invoice Number</label>
                            <div class="input-control text" data-role="input-control">
                                <input type="text" id="InvoiceHeaderID" name="InvoiceHeaderID" value="<?php echo $InvoiceHeaderID; ?>" disabled></input>
                                <button class="btn-clear" tabindex="-1"></button>
                            </div>
                        </td> 
                        <td></td> 
                    </tr>
                    <tr>
                        <td width="50%">
                            <label>Rates (Current Year)</label>
                            <div class="input-control text" data-role="input-control">
                                <input type="text" id="RatesPayable" name="RatesPayable" value="<?php echo $RatesPayable; ?>" ></input>
                                <button class="btn-clear" tabindex="-1"></button>
                            </div>
                        </td>
                        <td></td>                        
                    </tr>
                    <tr>
                        <td width="50%">
                            <label>Rates (Arrears)</label>
                            <div class="input-control text" data-role="input-control">
                                <input type="text" id="Arrears" name="Arrears" value="<?php echo $Arrears; ?>" ></input>
                                <button class="btn-clear" tabindex="-1"></button>
                            </div>
                        </td>
                        <td></td>                         
                    </tr>
                    <tr>
                        <td width="50%">
                            <label>Accumulated Penalty</label>
                            <div class="input-control text" data-role="input-control">
                                <input type="text" id="Penalty" name="Penalty" value="<?php echo $Penalty; ?>" ></input>
                                <button class="btn-clear" tabindex="-1"></button>
                            </div>
                        </td> 
                        <td></td>                        
                    </tr>
                    <tr>
                        <td width="50%">
                            <label>Ground Rent</label>
                            <div class="input-control text" data-role="input-control">
                                <input type="text" id="GroundRent" name="GroundRent" value="<?php echo $GroundRent; ?>" ></input>
                                <button class="btn-clear" tabindex="-1"></button>
                            </div>
                        </td>                        
                    </tr> 
                    <tr>
                        <td width="50%">
                            <label>Other Charges</label>
                            <div class="input-control text" data-role="input-control">
                                <input type="text" id="OtherCharges" name="OtherCharges" value="<?php echo $OtherCharges; ?>" ></input>
                                <button class="btn-clear" tabindex="-1"></button>
                            </div>
                        </td>                        
                    </tr>


                    <td colspan="2"><label>&nbsp;</label>
                    <input name="btnUpdate" type="button" onclick="loadmypage('land_invoice_edit.php?'+  
                                '&InvoiceHeaderID='+<?php echo $InvoiceHeaderID; ?>+                         
                                '&RatesPayable='+this.form.RatesPayable.value+                              
                                '&Arrears='+this.form.Arrears.value+
                                '&Penalty='+this.form.Penalty.value+
                                '&GroundRent='+this.form.GroundRent.value+
                                '&OtherCharges='+this.form.OtherCharges.value+
                                '&update=1','content')" value="Update">
                                
                                <!--,'fromDate='+this.form.fromDate.value+':toDate='+this.form.toDate.value+' -->
                    </td>                       
                             
                </table>                                      
             
                <div style="margin-top: 20px">
        </div>

            </fieldset>
        </form>      
</BODY>
</html>