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
$UserID = $_SESSION['UserID'];

if (isset($_REQUEST['msg']))
{
	$msg = $_REQUEST['msg'];	
}

$ApplicationID='';
$CustomerName='';
$CustomerID="";
$ServiceName ='';
$ServiceID='';
$Charges=0;
$Notes='';
$ServiceState="";
$CurrentStatus="";
$NextStatus="";
$Customer;
$SubCountyName;
$BusinessZoneID;
$WardName;
$CustomerType="";
$RegNo="";
$PostalAddress="";
$PostalCode="";
$Pin="";
$Vat="";
$Town="";
$Country="";
$Telephone1="";
$Mobile1="";
$Telephone2="";
$Mobile2="";
$Mobile1="";
$url="";
$Email="";
$ServiceHeaderType="";
$SubSystemID=1;
$WardID=0;
$NewSubCountyID=0;
$filter=false;

$InvoiceNo=0;

$ServiceCost=0;

if (isset($_REQUEST['ApplicationID'])) 
{ 
	$ApplicationID = $_REQUEST['ApplicationID']; 
}


if ($_REQUEST['filter']==1)
{
	$filter=true;
	if(isset($_REQUEST['SubCountyID']))
	{
		$SubCountyID=$_REQUEST['SubCountyID'];		
	}
}



if (isset($_REQUEST['change']))
{	
	$ApplicationID=$_REQUEST['ApplicationID'];
	$FromServiceID=$_REQUEST['FromServiceID'];
	$ToServiceID=$_REQUEST['ToServiceID'];
	$FromSubSystemID=$_REQUEST['FromSubSystemID'];
	$ToSubSystemID=$_REQUEST['ToSubSystemID'];
	$FromSubCountyID=$_REQUEST['FromSubCountyID'];
	$ToSubCountyID=$_REQUEST['ToSubCountyID'];
	$FromWardID=$_REQUEST['FromWardID'];
	$ToWardID=$_REQUEST['ToWardID'];
	$Printed=$_REQUEST['Printed'];
	$printed=$_REQUEST['printed'];
	$Notes=$_REQUEST['Notes'];
	
	if($FromServiceID==$ToServiceID 		 
		&& $FromSubCountyID==$ToSubCountyID 
		&& $FromWardID==$ToWardID
		&& $FromSubSystemID==$ToSubSystemID
		&& $Printed==$printed)
	{
		$msg="You have made not change in the application";
	}
	else
	{
		$sql="Update FormData Set value=$ToSubSystemID where ServiceHeaderID=$ApplicationID and FormColumnID=12237";
		$result2=sqlsrv_query($db,$sql);
		if($result2){
			$msg ="Application Changed Successfully";
		}else{
			DisplayErrors();
		}

		$sql="If exists(select 1 from formdata where ServiceHeaderID=$ApplicationID and FormColumnID=11204)
		Update FormData Set value=$ToWardID where ServiceHeaderID=$ApplicationID and FormColumnID=11204
		else 
		insert into FormData (ServiceHeaderID,FormColumnID,Value) values($ApplicationID,11204,$ToWardID)";

		//echo $sql;
		$result2=sqlsrv_query($db,$sql);
		if($result2){				
			$msg ="Application Changed Successfully";
		}else{
			DisplayErrors();
		}

		$sql="Update FormData Set value=$ToSubCountyID where ServiceHeaderID=$ApplicationID and FormColumnID=11203";
		$result2=sqlsrv_query($db,$sql);
		if($result2){				
			$msg ="Application Changed Successfully";
		}else{
			DisplayErrors();
		}

		$sql="Update ServiceHeader Set ServiceHeaderType=4, Printed='$Printed' where ServiceHeaderID=$ApplicationID ";
		$result2=sqlsrv_query($db,$sql);
		if($result2){				
			$msg ="Application Changed Successfully";
		}else{
			DisplayErrors();
		}

		$rst=SaveTransaction($db,$UserID," Changed application Details for application No: ".$ApplicationID);	
			
	}
}


// function getDetails($ApplicationID)
// {
	
	$s_sql="select c.*,f.ServiceHeaderType,bt.CustomerTypeName,sh.ServiceStatusID,sh.ServiceHeaderID,bz.ZoneName,w.WardName,sc.SubCountyName,s.ServiceName,sh.ServiceID,sh.SubSystemID,S.ServiceCategoryID,sh.Printed
	from Customer c 
	join ServiceHeader sh on sh.CustomerID=c.CustomerID
	join services s on sh.ServiceID=s.ServiceID
	join Forms f on sh.FormID=f.FormID
	left join CustomerType bt on bt.CustomerTypeID=c.BusinessTypeID 
	left join BusinessZones bz on sh.BusinessZoneID=bz.ZoneID
	left join Wards w on bz.wardid=w.wardid
	left join subcounty sc on w.subcountyid=sc.subcountyid
	
	where sh.ServiceHeaderID=$ApplicationID";

	$s_result=sqlsrv_query($db,$s_sql);

	if ($s_result)
	{
		
		while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC)){	
			
			$CustomerType=$row['CustomerTypeName'];
			$CustomerID=$row['CustomerID'];
			$CustomerName=$row['CustomerName'];
			$ServiceID=$row['ServiceID'];
			$ServiceName=$row['ServiceName'];
			$CurrentStatus=$row['ServiceStatusID'];
			$ServiceCategoryID=$row['ServiceCategoryID'];
			$RegNo=$row['RegistrationNumber'];
			$PostalAddress=$row['PostalAddress'];
			$PostalCode=$row['PostalCode'];
			$ServiceHeaderTypeID=$row['ServiceHeaderType'];
			$ServiceHeaderID=$row['ServiceHeaderID'];
			$Pin=$row['PIN'];
			$Vat=$row['VATNumber'];
			$Town=$row['Town'];
			$Country=$row['CountyID'];
			$Telephone1=$row['Telephone1'];
			$Mobile1=$row['Mobile1'];
			$Telephone2=$row['Telephone2'];
			$Mobile2=$row['Mobile2'];
			$Mobile1=$row['Mobile1'];
			$url=$row['Url'];
			$Email=$row['Email'];
			$SubCountyName=$row['SubCountyName'];
			$WardName=$row['WardName'];
			$BusinessZone=$row['ZoneName'];
			$SubSystemID=$row['SubSystemID'];
			$Printed=$row['Printed'];
		}
	}


	$sql="select fn.Value, w.WardName from fnFormData($ApplicationID) fn 
	join Wards w on fn.Value=w.WardID
	where fn.formcolumnid=11204
	";
	$res=sqlsrv_query($db,$sql);
	while($row=sqlsrv_fetch_array($res,SQLSRV_FETCH_ASSOC))
	{
		$WardID=$row['Value'];
	}

	if ($filter==false){
		$sql="select fn.Value, w.SubCountyName from fnFormData($ApplicationID) fn 
		join subcounty w on fn.Value=w.SubCountyID
		where fn.formcolumnid=11203";

		$res=sqlsrv_query($db,$sql);
		while($row=sqlsrv_fetch_array($res,SQLSRV_FETCH_ASSOC))
		{
			$SubCountyID=$row['Value'];
		}	
	}
	

	//get the subsystem

	$sql="select * from fnFormData($ServiceHeaderID) where formcolumnid=12237";
	$res=sqlsrv_query($db,$sql);
	while($row=sqlsrv_fetch_array($res,SQLSRV_FETCH_ASSOC))
	{
		$SubSystemID=$row['Value'];
	}

//}




  

?>
<script type="text/javascript">
$(document).ready(function(){
   		$(".popupwindow").popupwindow(profiles);
		console.log("from he>>>>re", $(".popupwindow").popupwindow);
   	});
</script>
<div class="example">
   <legend>Applied Service Change</legend>
   <form>
      <fieldset>
          <table width="100%" border="0" cellspacing="0" cellpadding="3">
              <tr>
                <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
            </tr>
			<tr>
				 <td width="50%">
					<label>Customer Name</label>
					  <div class="input-control text" data-role="input-control">
						  <input name="customer" type="text" id="customer" value="<?php echo $CustomerName; ?>" disabled="disabled" placeholder="">
						  
					  </div>                 	
				  </td>
				  <td></td>                  
			</tr>
			<tr>
			  <td width="50%">
			  <label>Application No</label>
				  <div class="input-control text" data-role="input-control">
					  <input name="ServiceHeaderID" type="text" id="ServiceHeaderID" value="<?php echo $ServiceHeaderID; ?>" disabled="disabled" placeholder="">						  
				  </div>				  
			  </td>
			  <td width="50%">				  
			  </td>   
			</tr>
			<tr>
                  <td width="50%">
                  <label>Service</label>
					  <div class="input-control text" data-role="input-control">
						  <input name="servicename" type="text" id="servicename" value="<?php echo $ServiceName; ?>" disabled="disabled" placeholder="">
						  
					  </div>				  
                  </td>
                  <td width="50%">	
					
                  </td>   
			</tr>
			<tr>
                  <td width="50%">
					  <label>Change Sub County To..</label>
						  <div class="input-control select" data-role="input-control">
							<select name="ToSubCountyID"  id="ToSubCountyID" onchange="loadmypage('application_change_all.php?filter=1'+
        											'&ApplicationID='+<?php echo $ApplicationID; ?>+
													'&SubCountyID='+this.form.ToSubCountyID.value+	        
                                                    '','content')">

							<option value="0" selected="selected"></option>
							<?php 
							$s_sql = "select SubCountyID,SubCountyName 
									from SubCounty";
							
							$s_result = sqlsrv_query($db, $s_sql);
							if ($s_result) 
							{ //connection succesful 
								while ($row = sqlsrv_fetch_array($s_result, SQLSRV_FETCH_ASSOC))
								{
									$s_id = $row["SubCountyID"];
									$s_name = $row["SubCountyName"];
									if ($SubCountyID==$s_id) 
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
                  <td width="50%">	
					
                  </td>   
			</tr>
			<tr>
                  <td width="50%">
					  <label>Change Ward To..</label>
						  <div class="input-control select" data-role="input-control">
							<select name="ToWardID"  id="ToWardID">
							<option value="0" selected="selected"></option>
							<?php 
							$s_sql = "select WardID,WardName 
									from Wards where SubCountyID=$SubCountyID";
							
							$s_result = sqlsrv_query($db, $s_sql);
							if ($s_result) 
							{ //connection succesful 
								while ($row = sqlsrv_fetch_array($s_result, SQLSRV_FETCH_ASSOC))
								{
									$s_id = $row["WardID"];
									$s_name = $row["WardName"];
									if ($WardID==$s_id) 
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
                  <td width="50%">	
					
                  </td>   
			</tr>
			<tr>
                  <td width="50%">
					  <label>Change Sub System To..</label>
						  <div class="input-control select" data-role="input-control">
							<select name="ToSubSystemID"  id="ToSubSystemID">
							<option value="0" selected="selected"></option>
							<?php 
							$s_sql = "select SubSystemID,SubSystemName 
									from SubSystems";
							
							$s_result = sqlsrv_query($db, $s_sql);
							if ($s_result) 
							{ //connection succesful 
								while ($row = sqlsrv_fetch_array($s_result, SQLSRV_FETCH_ASSOC))
								{
									$s_id = $row["SubSystemID"];
									$s_name = $row["SubSystemName"];
									if ($SubSystemID==$s_id) 
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
                  <td width="50%">	
					
                  </td>   
			</tr>
			<tr>
                  <td width="50%">
					  <label>Permit Printed?</label>
						  <div class="input-control select" data-role="input-control">
							<select name="Printed"  id="Printed">
							<option value="0" selected="selected"></option>							
								<option value="0" <?php if($Printed==0){ echo "selected";} ?>>Not Printed</option>
								<option value="1" <?php if($Printed==1){ echo "selected";} ?>>Printed</option>
						  </select>
						
					  </div>				  
                  </td>
                  <td width="50%">	
					
                  </td>   
			</tr>			
			<tr>
				<td width="50%"><label>Notes</label>
				  <div class="input-control textarea" data-role="input-control">
					<textarea name="Notes" type="textarea> id="Notes" placeholder=""><?php //echo $Notes; ?></textarea>  
				  </div>
				</td>                  
				<td width="50%"></td>   
			</tr>                 
            		
          </table> 
          
          
          <input type="reset" value="Cancel" onClick="loadmypage('application_change.php?ApplicationID=<?php echo $ApplicationID; ?>&CurrentStatus=<?php echo $CurrentStatus; ?>','content','loader','listpages','','applications','<?php echo $_SESSION['RoleCenter'] ?>')">
			
		<input type="button" value="Change" 
		onClick="deleteConfirm2('Are you sure you want to change the Application','application_change_all.php?change=1'+
		'&ApplicationID='+<?php echo $ApplicationID; ?>+
		'&FromSubCountyID='+<?php echo $SubCountyID; ?>+
		'&ToSubCountyID='+this.form.ToSubCountyID.value+
		'&FromSubSystemID='+<?php echo $SubSystemID; ?>+
		'&ToSubSystemID='+this.form.ToSubSystemID.value+
		'&FromWardID='+<?php echo $WardID; ?>+
		'&ToWardID='+this.form.ToWardID.value+
		'&Printed='+this.form.Printed.value+
		'&printed='+<?php echo $Printed; ?>+
		'&Notes='+this.form.Notes.value+'','content')"> 		  


      </fieldset>
  </form>                  
