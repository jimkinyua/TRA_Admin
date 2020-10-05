<?php 
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];
$DateLine=date('d/m/Y',strtotime('2017-04-30'));
$ApplicationDate=date('d/m/Y');

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

$ApplicationID=$_REQUEST['ApplicationID'];
$ServiceID=$_REQUEST['ServiceID'];
$SubSystemID=$_REQUEST['SubSystemID'];
$Renew=$_REQUEST['Renew'];
$PermitCost=0;

//print_r($_REQUEST);

$MonthsLate=0;
$sql="set dateformat dmy select datediff(month,'$DateLine',getdate()) Mnths";
$result=sqlsrv_query($db,$sql);

while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
    $MonthsLate=$rw['Mnths'];
}

	
	$sql = "select s.ServiceName,c.CustomerName 
            from  customer c
            join ServiceHeader sh on sh.CustomerID=c.CustomerID
            join  services s on sh.serviceid=s.serviceid
            where sh.ServiceHeaderID=$ApplicationID 
            order by s.ServiceID ";

	//print_r($sql);		
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$ServiceName=$myrow['ServiceName'];
        $CustomerName=$myrow['CustomerName'];
	}

    $sql="select Distinct s.ServiceID,s.ServiceName, sc.Amount 
            from ServiceCharges sc
            join Services s on sc.ServiceID=s.ServiceID
            join FinancialYear fy on fy.FinancialYearID=sc.FinancialYearId
            where  SubSystemId=$SubSystemID and sc.ServiceID=$ServiceID and fy.isCurrentYear=1";

    $result=sqlsrv_query($db,$sql);
    //echo $sql;
    while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
    {
        $PermitCost=$row['Amount'];
        $mdata.='<tr>
            <td>'.number_format($row['ServiceID'],2).'</td>
            <td>'.$row['ServiceName'].'</td>
            <td>'.number_format($row['Amount'],2).'</td>
            <td></td>        
            </tr>';
    }

    //Application Fees
    $sql="select s.ServiceID,s.ServiceName, Amount 
            from ServiceCharges sc
            join services s on sc.ServiceID=s.serviceid                                 
            join FinancialYear fy on sc.FinancialYearId=fy.FinancialYearID                                      
            and fy.isCurrentYear=1
            and sc.SubSystemId=$SubSystemID
            and sc.serviceid=281";

    //echo $sql;

    $result=sqlsrv_query($db,$sql);
    while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
    {
        $mdata.='<tr>
            <td>'.number_format($row['ServiceID'],2).'</td>
            <td>'.$row['ServiceName'].'</td>
            <td>'.number_format($row['Amount'],2).'</td>
            <td></td>        
            </tr>';
    }

     //Conservancy Charges
    $sql="select * from fnConservancyCost($PermitCost,$SubSystemID)";
    //echo $sql;
    $result=sqlsrv_query($db,$sql);
    while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
    {
        $ServiceID=1696;
        $ServiceName="Conservance Fees";
        $mdata.='<tr>
            <td>'.$ServiceID.'</td>
            <td>'.$ServiceName.'</td>
            <td>'.number_format($row['Amount'],2).'</td>
            <td></td>        
            </tr>';
    }

    //Application Charges
    $sql="select distinct s1.ServiceID,s1.ServiceName ,sc.Amount 
            from ApplicationCharges sc 
            join ServiceHeader sh on sh.serviceheaderid=sc.serviceheaderid 
            join Services s1 on sc.ServiceID=s1.ServiceID 
            where sh.ServiceHeaderID=$ApplicationID";



    $result=sqlsrv_query($db,$sql);
    while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
    {
        $mdata.='<tr>
            <td>'.number_format($row['ServiceID'],2).'</td>
            <td>'.$row['ServiceName'].'</td>
            <td>'.number_format($row['Amount'],2).'</td>
            <td>

            <input name="Button" type="button"  value="Remove" 
                onClick="loadmypage(\'add_charge.php?delete=1&ServiceID='.$row['ServiceID'].'&ApplicationID='.$ApplicationID.'\',\'charge\')">

            </td>        
            </tr>';
    }

    //penalty
    //echo 'The Business is '.$PermitCost;
    //if(strtotime($DateLine)<strtotime($ApplicationDate) and $Renew==1){ 
    if($MonthsLate>0 and $Renew==1){           
        $penalty=0.50*(double)$PermitCost; //Activate this later...
       
         // echo 'ndani';                      
        $CPenalty=0;
        $ServiceID='283';
        $penalty=0.50*(double)$PermitCost;

        //how late?

        $sql="set dateformat dmy select datediff(MONTH,'$DateLine',getdate()) Months";
        $dt=sqlsrv_query($db,$sql);
        //echo $sql;
        $rw=sqlsrv_fetch_array($dt,SQLSRV_FETCH_ASSOC);

        $months=$rw['Months'];
        while($months>0){
            //echo (double)$PermitCost+(double)$CPenalty;
            $CPenalty+=(.03*((double)$PermitCost+(double)$CPenalty));
            $months-=1;
        }

        $CPenalty=roundUpToAny($CPenalty,$x=5);

        $penalty+=(float)$CPenalty;


        //echo $penalty;

        $ServiceName='Penalty On Late Payment';
        $InvoiceAmount+=$ServiceAmount;

        $mdata.='<tr>
            <td>'.$ServiceID.'</td>
            <td>'.$ServiceName.'</td>
            <td>'.$penalty.'</td>
            <td>   

            </td>        
            </tr>';
    }
    else{
        //echo 'Nje';
        $penalty=0;
    }	

    if ($_REQUEST['add']==1){

       //print_r($_REQUEST);

        $ServiceID=$_REQUEST['ServiceID'];
        $ApplicationID=$_REQUEST['ApplicationID'];
        $Amount=$_REQUEST['Amount'];;
        
        $s_sql="set dateformat dmy if not exists 
        (select 1 from ApplicationCharges where ServiceID=$ServiceID and ServiceHeaderID=$ApplicationID)  
        insert into ApplicationCharges (ServiceHeaderID,ServiceID,Amount) 
        Values($ApplicationID,$ServiceID,$Amount)";
        $result = sqlsrv_query($db, $s_sql);
        if (!$result)
        {
            DisplayErrors();
            echo $s_sql;
            $msg="Action failed";
        }else{
            //echo $s_sql;
            $msg="Charge Applied Successfully";
        }
    }
    if ($_REQUEST['delete']==1){

        $ServiceID=$_REQUEST['ServiceID'];
        $ApplicationID=$_REQUEST['ApplicationID'];
        
        $s_sql="Delete from ApplicationCharges where ServiceHeaderID=$ApplicationID and ServiceID=$ServiceID";
        $result = sqlsrv_query($db, $s_sql);
        if (!$result)
        {
            DisplayErrors();
            //echo $s_sql;
            $msg="Action failed";
        }else{            
            $msg="Charge Removed Successfully";
        }
    }


?>
<!DOCTYPE html>
<html>
<head>
    <title>Aplication Charges</title>   
</head>
<body class="metro" >
    <div class="example" id="charge">
        <form action="add_charge.php" method="POST">
            <fieldset>
              <legend>Add Accompanying Services</legend>
                <table border="0" cellspacing="0" cellpadding="3" width="100%">
                    <tr>
                      <td align="center" colspan="2" style="color:#F00"><?php echo $msg; ?></td>
                  </tr>
                    <tr>
                        <td colspan="2">
                            <label>Business Name</label>
                            <div class="input-control text" data-role="input-control">
                                <input type="text" id="CustomerName" name="CustomerName" value="<?php echo $CustomerName; ?>" disabled></input>
                                <button class="btn-clear" tabindex="-1"></button>
                            </div>
                        </td>                        
                    </tr>
                    <tr>
                        <td colspan="2">
                            <label>Applied Service</label>
                            <div class="input-control textarea" data-role="input-control">
                               
                                <TEXTAREA id="A_serviceName" name="A_serviceName" disabled><?php echo $ServiceName; ?></TEXTAREA>
                               
                            </div>
                        </td>                        
                    </tr>
                     <tr>
                        <td colspan="2">
                            <label>Accompanying Service (Fees)</label>
                            <table>
                                <tr>
                                    <td> <label>&nbsp</label>
                                    <div class="input-control select" data-role="input-control">
                                        
                                        <select name="ServiceID"  id="ServiceID">
                                            <option value="0" selected="selected"></option>
                                            <?php 
                                            $s_sql = "SELECT * FROM Services where servicename like '%signage%' or ServiceID=283 ORDER BY ServiceID ";
                                            
                                            $s_result = sqlsrv_query($db, $s_sql);
                                            if ($s_result) 
                                            { //connection succesful 
                                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                                {
                                                    $s_id = $row["ServiceID"];
                                                    $s_name = $row["ServiceName"];
                                                    if ($ServiceID==$s_id) 
                                                    {
                                                        $selected = 'selected="selected"';
                                                    } else
                                                    {
                                                        $selected = '';
                                                    }                                               
                                                 ?>
                                            <option value="<?php echo $s_id; ?>" <?php echo $selected; ?>><?php echo $s_name; ?></option>
                                            <?php 
                                                }
                                            }
                                            ?>
                                      </select>
                                    
                                  </div>
                                  </td>
                                  <td width="20%">
                                    <label>Amount</label>
                                    <div class="input-control text" data-role="input-control">
                                        <input type="text" id="Amount" name="Amount" value="<?php echo $Amount; ?>" ></input>
                                        <button class="btn-clear" tabindex="-1"></button>
                                    </div>
                                </td>  
                                  <td width="20%">
                                        <label>&nbsp</label>
                                      <input name="Button" type="button"  value="Save" onClick="loadmypage('add_charge.php?ServiceID='+ this.form.ServiceID.value +'&Amount='+ this.form.Amount.value +'&ApplicationID=<?php echo $ApplicationID ?>&SubSystemID=<?php echo $SubSystemID ?>&MainServiceID=<?php echo $ServiceID ?>&add=1','charge')">
                                  </td>
                                </tr>
                            </table>
                        </td>
                                         
                  </tr>                          
                             
                </table>                                      
             
                <div style="margin-top: 20px">
        </div>

            </fieldset>
        </form>

        <table class="table striped bordered hovered">
            <thead>             
            <tr>
                <th class="text-left">Service ID</th>
                <th class="text-left">Service Name</th>
                <th class="text-left">Amount</th>                   
            </tr>
            </thead>
            <tbody>
                <?php
                    echo $mdata;
                ?>
            <tbody>
            
            </tbody>
        </table>   
</BODY>
</html>