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
$CreatedBy = $_SESSION['UserID'];
$ChargeID='';
$ServiceHeaderID='';
$Description='';
$ParkName='';
$Os_Amount=0;

$fromDate=date('d/m/Y');
$toDate=date('d/m/Y');
$Customer='';

if(isset($_REQUEST['CustomerID'])){$CustomerID=$_REQUEST['CustomerID'];}
if(isset($_REQUEST['noofvehicles'])){$noofvehicles=$_REQUEST['noofvehicles'];}
if(isset($_REQUEST['CustomerName'])){$CustomerName=$_REQUEST['CustomerName'];}


//the sacco total cost
$sql="select mr.RouteID,mr.RouteName,sc.SittingCapacity,bp.ParkName 
	from CustomerVehicles cv
	left join BusParks bp on cv.BusParkID=bp.ParkID 
	left join MatatuRoutes mr on cv.[Route]=mr.RouteID
	left join SittingCapacity sc on cv.SittingCapacity=sc.ID
	where cv.CustomerID='$CustomerID'";

$result=sqlsrv_query($db,$sql);

$TotalCost=0;


while ($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {
	$RegNo=$rw['RegNo'];
	$RouteID=$rw['RouteID'];
	$Capacity=$rw['SittingCapacity'];
	$ParkName=$rw['ParkName'];



	$Os_Amount+=getCharges($db,$RouteID,$Capacity);



}



function getCharges($db,$RouteID,$Capacity){
	$Amount=0;
	$sql="select isnull(Amount,0)Amount from RouteCharges 
			where $Capacity>=[FromCapacity] 
			and $Capacity<=[ToCapacity] 
			and RouteID=$RouteID";

	$rst=sqlsrv_query($db,$sql);
 

	if($rst){
		while($row=sqlsrv_fetch_array($rst,SQLSRV_FETCH_ASSOC))
		{
			$Amount=$row['Amount'];
		}
	}else{
		DisplayErrors();

	}
	if ($Amount=='')
	{
		$Amount=0;
	} 

	return $Amount;

}

$ServiceID='2785';


if (isset($_REQUEST['delete']))
{
	$ApplicationID=$_REQUEST['ApplicationID'];
	//print_r($_REQUEST);
	
	$sql="exec spDeleteMiscelaneous $ApplicationID";
	$result=sqlsrv_query($db,$sql);
	if($result){
		
		while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
			$msg=$row['Message'];
		}		
	}else{
		DisplayErrors();
	}
	
}

if (isset($_REQUEST['save']))
{	
	


	
	$result4a=null;
	$result4b=null;
	$result4c=null;
	$result4d=null;
	$result4e=null;
	$result4f=null;
	$result4g=null;
	$result4h=null;
	$result4i=null;
	
	$ServiceStatusID='5';
	$InvoiceDate=date('d/m/Y');
	$InvoiceNo=time();
	
	
		$CustomerID=$_REQUEST['CustomerID'];
		$Description=$_REQUEST['Description'];
		$os_ServiceID=$_REQUEST	['os_ServiceID'];
		$Os_Amount=$_REQUEST['Os_Amount'];
		$noofvehicles=$_REQUEST['noofvehicles'];
		


		$Description= " (".$ParkName.": Invoice for ".$noofvehicles	." Vehicles)";

		// print_r($Description); exit;
				
		
		if(sqlsrv_begin_transaction($db)===false)
		{
			$msg=sqlsrv_errors();
			$Sawa=false;
		}
		
		$sql="Insert into ServiceHeader (CustomerID,ServiceID,ServiceStatusID,CreatedBy)
		Values('$CustomerID',$os_ServiceID,'$ServiceStatusID',$CreatedBy) SELECT SCOPE_IDENTITY() AS ID";
		
		$result=sqlsrv_query($db,$sql);
		if($result)
		{
			$ServiceHeaderID=lastid($result);
			
			$sql="set dateformat dmy insert into InvoiceHeader (InvoiceDate,InvoiceNo,CustomerID,CustomerName,Description,CreatedBy) Values('$InvoiceDate','$InvoiceNo',$CustomerID,'$CustomerName','$Description','$CreatedBy') SELECT SCOPE_IDENTITY() AS ID";
			$result3 = sqlsrv_query($db, $sql);	
			if ($result3)
			{
				$InvoiceHeaderID=lastid($result3);
					
				$sql="set dateformat dmy insert into InvoiceLines (InvoiceHeaderID,ServiceHeaderID,Description,ServiceID,Amount,CreatedBy) 
					Values($InvoiceHeaderID,$ServiceHeaderID,'$Description',$os_ServiceID,$Os_Amount,'$CreatedBy')";
				$result4a = sqlsrv_query($db, $sql);						
											
							
			}else
			{
				echo 'Result 3 failed'.'<br>'.$sql;
			}
			
		}else
		{
			echo 'Result 1 failed'.'<br>'.$sql;
		}

		if($result && $result3 && $result4a)
		{
			$rst=SaveTransaction($db,$UserID," Created a Matatu Sacco Invoice Number ".$InvoiceHeaderID);	

			sqlsrv_commit($db);	
			$msg="Invoice Created Successfully";			
			$Sawa=true;
		}else
		{
			echo $sql;
			sqlsrv_rollback($db);
			$msg="Transaction Failed";
			$Sawa=false;
		}		

	
}
?>
<div class="example">
<form>
	<fieldset>
	  <legend>Bus Park Invoicing</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
			  <br>
		  </tr>
            <tr>
                <td width="50%">
                    <label>Customer Name</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="CustomerName" name="CustomerName" value="<?php echo $CustomerName; ?>" readonly></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                </td>
            </tr>
            <tr>
                <td width="50%">
                    <label>Number Of Vehicles</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="noofvehicles" name="noofvehicles" value="<?php echo $noofvehicles; ?>" readonly></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                </td>
            </tr>			
			<tr>
                <td width="50%">
                	<label>Charge Description</label>
                	<div class="input-control textarea" data-role="input-control">
                    	<textarea name="Description" id="Description"><?php echo $Description; ?></textarea>
                    </div>
                </td>
                <td width="50%">

                	</td>
          	</tr>         
          <tr>
			  <td><label>Service</label>
				<div class="input-control select" data-role="input-control">
					<select name="os_ServiceID"  id="os_ServiceID">
					<option value="0" selected="selected"></option>
					<?php 
					$s_sql = "SELECT * FROM Services where ServiceID='2785' ORDER BY ServiceName";
					
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
                  </div></td>
				<td></td>                  
          </tr>
            <tr>
                <td width="50%">
                    <label>Amount</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="Os_Amount" name="Os_Amount" value="<?php echo $Os_Amount; ?>" disabled="disabled"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                </td>
            </tr>
                     
        </table>
		<input name="Button" type="button" onclick="loadmypage('buspark_invoicing.php?'+
                                            '&CustomerName='+this.form.CustomerName.value+
                                            '&Os_Amount='+this.form.Os_Amount.value+ 
                                            '&Description='+this.form.Description.value+ 
                                            '&os_ServiceID='+this.form.os_ServiceID.value+
											'&CustomerID='+<?php echo $CustomerID ?>+
											'&noofvehicles='+<?php echo $noofvehicles ?>+									
        									'&save=1','content','loader','listpages','','')" value="Save">
      <input type="reset" value="Cancel" onClick="loadmypage('miscellaneous_list.php?i=1','content','loader','listpages','','Miscellaneous')">
	 <!-- <input name="createFlatWindow" id="createFlatWindow" type="button" class="button"  value="Create Flat Window" onclick="flatWindow()"> -->
        
		<div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>


