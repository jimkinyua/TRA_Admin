<?php 
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
require_once('printpermits.php');
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

//print_r($_REQUEST); 

if (isset($_REQUEST['invoice']))
{

    if(isset($_REQUEST['NewInvoiceHeadeID'])){$NewInvoiceHeadeID=$_REQUEST['NewInvoiceHeadeID'];}else{$NewInvoiceHeadeID='';}
    if(isset($_REQUEST['ServiceHeaderID'])){$ServiceHeaderID=$_REQUEST['ServiceHeaderID'];}else{$ServiceHeaderID='';}
    if(isset($_REQUEST['OldinvoiceHeaderID'])){$OldinvoiceHeaderID=$_REQUEST['OldinvoiceHeaderID'];}else{$OldinvoiceHeaderID='';}


    if($NewInvoiceHeadeID=='')
    {
        $msg="Please enter the new invoice number";        
    }
    else{
        $sql="update permits set InvoiceHeaderID=$NewInvoiceHeadeID where ServiceHeaderID=$ServiceHeaderID and InvoiceHeaderID=$OldinvoiceHeaderID";       

        $result=sqlsrv_query($db,$sql);

        if($result){
            $msg="Update done successfully";
            printpermits($db,$ServiceHeaderID);
            $filter=" where sh.ServiceHeaderID='$ServiceHeaderID'";
            $mdata=search($db,$filter);

        }else{
            $msg="Update failed";
            DisplayErrors();
        }
    }
}
if (isset($_REQUEST['AppType']))
{
       
    if(isset($_REQUEST['ServiceHeaderID'])){$ServiceHeaderID=$_REQUEST['ServiceHeaderID'];}else{$ServiceHeaderID='';}
    
    $sql="update ServiceHeader set ServiceHeaderType=4 where ServiceHeaderID=$ServiceHeaderID";   

    $result=sqlsrv_query($db,$sql);

    if($result){
        $msg="Update done successfully";  
        printpermits($db,$ServiceHeaderID); 
        $filter=" where sh.ServiceHeaderID='$ServiceHeaderID'";     
    }else{
        $msg="Update failed";
        DisplayErrors();
    }

     $filter=" where sh.ServiceHeaderID='$ServiceHeaderID'";
     $mdata=search($db,$filter);    
}

if (isset($_REQUEST['schange']))
{
    
    if(isset($_REQUEST['InvoiceLineID'])){$InvoiceLineID=$_REQUEST['InvoiceLineID'];}else{$InvoiceLineID='';}
    if(isset($_REQUEST['serviceheaderid'])){$serviceheaderid=$_REQUEST['serviceheaderid'];}else{$serviceheaderid='';}
    if(isset($_REQUEST['ihserviceid'])){$ihserviceid=$_REQUEST['ihserviceid'];}else{$ihserviceid='';}
    if(isset($_REQUEST['ilserviceid'])){$ilserviceid=$_REQUEST['ilserviceid'];}else{$ilserviceid='';}
    
    $sql="update ServiceHeader set ServiceID=$ilserviceid where InvoiceLineID=$InvoiceLineID";   

    $result=sqlsrv_query($db,$sql);

    if($result){
        $msg="Update done successfully";  
        printpermits($db,$ServiceHeaderID); 
        $filter=" where sh.ServiceHeaderID='$ServiceHeaderID'";     
    }else{
        $msg="Update failed";
        DisplayErrors();
    }

     $filter=" where sh.ServiceHeaderID='$ServiceHeaderID'";
     $mdata=search($db,$filter);    
}



if (isset($_REQUEST['search']))
{


    if(isset($_REQUEST['ServiceHeaderID'])){$ServiceHeaderID=$_REQUEST['ServiceHeaderID'];}else{$ServiceHeaderID='';}
    if(isset($_REQUEST['InvoiceHeaderID'])){$InvoiceHeaderID=$_REQUEST['InvoiceHeaderID'];}else{$InvoiceHeaderID='';}
    
    $filter=" where 1=1 ";  
    if(!$InvoiceHeaderID=='')
    {       
        $sql="select distinct ServiceHeaderID from InvoiceLines where InvoiceHeaderID=$InvoiceHeaderID";
        $result=sqlsrv_query($db,$sql);
        while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
        {
            $ServiceHeaderID=$row['ServiceHeaderID'];
        }    
        
        $filter.=" and sh.ServiceHeaderID='$ServiceHeaderID'";
        
    }

    if(isset($_REQUEST['ServiceHeaderID']))
    {
        if ($_REQUEST['ServiceHeaderID']!=='')
        {
            $ServiceHeaderID=$_REQUEST['ServiceHeaderID'];
            $filter.=" and sh.ServiceHeaderID='$ServiceHeaderID'";
        }
        
    }else
    {
        $ServiceHeaderID='';
    }

    $mdata=search($db,$filter);
    
}

function search($db,$filter)
{
    $params = array();
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

    $sql = "
        select distinct  sh.ServiceHeaderID,sh.ServiceHeaderType,il.InvoiceHeaderID,
        iif(rl.invoiceheaderid is null,'',rl.invoiceheaderid) PaidInvoice,iif(il.InvoiceHeaderID=p.InvoiceHeaderID,il.InvoiceHeaderID,'') PermitInvoice,c.CustomerName

        from ServiceHeader sh 
        left join InvoiceLines il on il.ServiceHeaderID=sh.ServiceHeaderID
        left join Permits p on p.ServiceHeaderID=sh.ServiceHeaderID
        left join InvoiceLines il2 on il2.InvoiceHeaderID=p.InvoiceHeaderID
        join Customer c on c.CustomerID=sh.CustomerID
        left join ReceiptLines rl on rl.InvoiceHeaderID=il.InvoiceHeaderID $filter";

    
    $result = sqlsrv_query($db, $sql,$params, $options);

    if($result)
    {
        $row_count = sqlsrv_num_rows( $result );
   
        if ($row_count === false){

        }

        while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
        {
            $SH_ServiceID=0;
            $IL_ServiceID=0;
            $InvoiceLineID=0;

            $sql="select sh.ServiceID SH_ServiceID,il.ServiceID IL_ServiceID,il.InvoiceLineID from ServiceHeader sh
                    join  InvoiceLines il on il.ServiceHeaderID=sh.serviceheaderid
                     where il.InvoiceHeaderID=".$row['InvoiceHeaderID']." and Description like '%Year%'";


            $result2=sqlsrv_query($db,$sql);
            while($row2=sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC))
            {
                $SH_ServiceID=$row2['SH_ServiceID'];
                $IL_ServiceID=$row2['IL_ServiceID'];
                $InvoiceLineID=$row2['InvoiceLineID'];
            }

            $mdata.='<tr>
            <td>'.$row['ServiceHeaderID'].'</td>
            <td>'.$row['InvoiceHeaderID'].'</td>
            <td>'.$row['ServiceHeaderType'].'</td>
            <td>'.$row['CustomerName'].'</td>  
            <td>'.$row['PaidInvoice'].'</td>
            <td>'.$row['PermitInvoice'].'</td> 
            <td>'.$SH_ServiceID.'</td>
            <td>'.$IL_ServiceID.'</td>         
            </tr>';
        }
    }

    $MData=new array();
    $MData[0]=$MData;
    $MData[1]=$SH_ServiceID;
    $MData[2]=$IL_ServiceID;

    return $mdata;
 }




//echo $sql;


?>
<div class="example">
<form>
    <fieldset>
      <legend>Search Permit</legend>
        <table width="100%" border="0" cellspacing="0" cellpadding="3">
            <tr>
              <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
          </tr>
            <tr>
                <td width="25%">
                    <label>Invoice Number</label>                   
                    <div class="input-control text" data-role="input-control">
                        <input name="InvoiceHeaderID" id="InvoiceHeaderID" type="text"></input>             
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="25%">
                    <label>Application Number</label>                   
                    <div class="input-control text" data-role="input-control">
                        <input name="ServiceHeaderID" id="ServiceHeaderID" type="text"></input>            
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="25%">
                    <label>&nbsp &nbsp</label>
                    <input name="Button" type="button" onclick="loadmypage('checkpermit.php?'+
                                            '&InvoiceHeaderID='+this.form.InvoiceHeaderID.value+
                                            '&ServiceHeaderID='+this.form.ServiceHeaderID.value+                        
                                            '&search=1','content')" value="SEARCH">

                </td>
                <td width="25%">
                    
                </td>
            </tr>
            
            <tr>
                <td>
                    <label>Change Invoice No From</label>
                    <div class="input-control text" data-role="input-control">
                            <input name="OldinvoiceHeaderID" id="OldinvoiceHeaderID" type="text"></input>            
                            <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td>
                    <label>Change Invoice No To</label>
                    <div class="input-control text" data-role="input-control">
                            <input name="NewInvoiceHeadeID" id="NewInvoiceHeadeID" type="text"></input>            
                            <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td>
                    <label>&nbsp &nbsp</label>
                    <input name="Button" type="button" onclick="loadmypage('checkpermit.php?'+
                                            '&NewInvoiceHeadeID='+this.form.NewInvoiceHeadeID.value+   
                                            '&OldinvoiceHeaderID='+this.form.OldinvoiceHeaderID.value+   
                                            '&ServiceHeaderID='+<?php echo $ServiceHeaderID; ?>+                     
                                            '&invoice=1','content')" 
                                            value="Change">
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>
                    <label>Change ApplicationType</label>
                    <div class="input-control text" data-role="input-control">
                            <input name="ServiceHeaderTypeID" id="ServiceHeaderTypeID" type="text" value="4"></input>            
                            <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td>
                    
                </td>
                <td>
                    <label>&nbsp &nbsp</label>
                    <input name="Button" type="button" onclick="loadmypage('checkpermit.php?'+
                                            '&ServiceHeaderID='+<?php echo $ServiceHeaderID; ?>+
                                            '&AppType=1','content')" 
                                            value="Change">
                </td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>
                    <label>SH ServiceID</label>
                    <div class="input-control text" data-role="input-control">
                            <input name="SH_ServiceID" id="SH_ServiceID" type="text" value="<?php echo $SH_ServiceID  ?>" disabled="disabled"></input>            
                            <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td>
                   <label>IL ServiceID</label>
                    <div class="input-control text" data-role="input-control">
                            <input name="ilserviceid" id="ilserviceid" type="text"  value="<?php echo $IL_ServiceID  ?>" disabled="disabled"></input>            
                            <button class="btn-clear" tabindex="-1"></button>
                    </div> 
                </td>
                <td>
                    <label>&nbsp &nbsp</label>
                    <input name="Button" type="button" onclick="loadmypage('checkpermit.php?'+
                                            '&ServiceHeaderID='+<?php echo $ServiceHeaderID; ?>+
                                            '&InvoiceLineID='+<?php echo $InvoiceLineID; ?>+
                                            '&ihserviceid='+<?php echo $ihserviceid; ?>+
                                            '&ilserviceid='+<?php echo $ilserviceid; ?>+
                                            '&schange=1','content')" 
                                            value="Change">
                </td>
                <td></td>
                <td></td>
            </tr>                      
        </table>
        <table class="table striped bordered hovered">
                <thead>                
                <tr>
                    <th class="text-left">Application No</th>
                    <th class="text-left">InvoiceNumber</th>
                    <th class="text-left">Application Type</th>
                     <th class="text-left">Customer</th> 
                     <th class="text-left">Paid Invoice</th>
                     <th class="text-left">Permit Invoice</th>   
                     <th class="text-left">IH_ServiceID</th>
                     <th class="text-left">IL_ServiceID</th>   

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