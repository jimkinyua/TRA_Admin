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
$ApplicationID = $_REQUEST['ApplicationID'];
$UserID = $_SESSION['UserID'];

if (isset($_REQUEST['msg']))
{
	$msg = $_REQUEST['msg'];	
}




$s_sql="select c.*,f.ServiceHeaderType,bt.CustomerTypeName,
sh.ServiceStatusID,sh.ServiceHeaderID,bz.ZoneName,w.WardName,
s.ServiceName,sh.ServiceID,sh.CreatedDate,sh.SubSystemID, sub.SubSystemName,
S.ServiceCategoryID,sc.ServiceGroupID from Customer c join ServiceHeader sh on sh.CustomerID=c.CustomerID 
join services s on sh.ServiceID=s.ServiceID 
join Forms f on sh.FormID=f.FormID 
 inner join ServiceCategory sc on sh.ServiceCategoryID = sc.ServiceCategoryID
 inner join ServiceGroup sg on sc.ServiceGroupID=sg.ServiceGroupID
left join CustomerType bt on bt.CustomerTypeID=c.BusinessTypeID
left join BusinessZones bz on sh.BusinessZoneID=bz.ZoneID 
LEFT JOIN SubSystems sub on sh.BusinessZoneID = sub.SubSystemID
left join Wards w on bz.wardid=w.wardid  where sh.ServiceHeaderID=$ApplicationID";

$s_result=sqlsrv_query($db,$s_sql);

// echo $s_sql;exit;

if ($s_result)
{
	
	while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC)){	
		
		$CustomerType=$row['CustomerTypeName'];
		$CustomerID=$row['CustomerID'];
		$CustomerName=$row['CustomerName'];
		$ServiceID=$row['ServiceID'];
		$ServiceStatusID = $row['ServiceStatusID'];
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
		$url=$row['Website'];
		$Email=$row['Email'];
		$ApplicantEmail = $row['Email'];
		$SubCountyName=$row['SubCountyName'];
		$ServiceGroupID = $row['ServiceGroupID'];
		// $WardName=$row['WardName'];
		// $BusinessZone=$row['ZoneName'];
		$SubSystemID=$row['SubSystemID'];
		$SubSystemName=$row['SubSystemName'];
		$ApplicationDate=$row['CreatedDate'];//date('d/m/Y',strtotime($date));
		$ApplicationDate=date('d/m/Y',strtotime($ApplicationDate));
		
	}
}


//get the serviceCost

if ($ServiceHeaderTypeID==1)
{

	$BSql="select l.RatesPayable from LandApplication la join land l on la.PlotNo=l.PlotNo and la.LRN=l.LRN where la.ServiceHeaderID=$ApplicationID";
	$rsult=sqlsrv_query($db,$BSql);
	//echo $BSql;
	if ($rsu=sqlsrv_fetch_array($rsult,SQLSRV_FETCH_ASSOC))
	{
		$ServiceCost=$rsu['RatesPayable'];							
	}else
	{
		$ServiceCost=0;
	}	
}else
{
	$ApplicationChargesSQL="select sum(sc.Amount) Amount 
	from ApplicationCharges sc 
	join ServiceHeader sh on sh.serviceheaderid=sc.serviceheaderid 
	join Services s1 on sc.ServiceID=s1.ServiceID 
	where sh.ServiceHeaderID=$ServiceHeaderID";

	$result=sqlsrv_query($db,$ApplicationChargesSQL);
	while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
	{
		$ApplicationCharge=$row['Amount'];
	}
	// echo $ApplicationChargesSQL; exit;

	$ServiceCost = $ApplicationCharge;
		// //get the subsystem
		// $sql="select fn.Value, ss.SubSystemName from fnFormData($ServiceHeaderID) fn 
		// 		join SubSystems ss on fn.Value=ss.SubSystemID
		// 		where formcolumnid=12237";
		// $res=sqlsrv_query($db,$sql);
		// while($row=sqlsrv_fetch_array($res,SQLSRV_FETCH_ASSOC))
		// {
		// 	$SubSystemID=$row['Value'];
		// 	$SubSystemName=$row['SubSystemName'];
		// }

		// //get the ward

		// $sql="select fn.Value, w.WardName from fnFormData($ServiceHeaderID) fn 
		// 	join Wards w on fn.Value=w.WardID
		// 	where fn.formcolumnid=11204
		// 	";
		// $res=sqlsrv_query($db,$sql);
		// while($row=sqlsrv_fetch_array($res,SQLSRV_FETCH_ASSOC))
		// {
		// 	$WardName=$row['WardName'];
		// }	

		// $ServiceCost=getServiceCost($db,$ServiceID,$SubSystemID,$ServiceHeaderID);
	
}

		function getServiceCost($db,$ServiceID,$SubSystemID,$ServiceHeaderID){
			//echo $SubSystemID.'<BR>';
			$sql="select * from fnServiceCost($ServiceID,$SubSystemID)";
			$result=sqlsrv_query($db,$sql);
			if ($result)
			{
				while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{									
					$ServiceCost=$row['Amount'];
				}

				//Conservancy
				$sql1="select * from fnConservancyCost($ServiceCost,$SubSystemID)";
				//echo $sql1;
				$rs=sqlsrv_query($db,$sql1);
				if ($rs)
				{
					while($row=sqlsrv_fetch_array($rs,SQLSRV_FETCH_ASSOC))
					{									
						$ConservancyCost=$row["Amount"];										
					}	
				}	

				//penalty
				//echo 'The Business is '.$BusinessIsOld;
				if(strtotime($ApplicationDate)>strtotime($DateLine) and $BusinessIsOld==1)
					$penalty=.50*(double)$ServiceCost;
				else{
					$penalty=0;
				}
				//echo $ServiceCost;
				/* echo $ServiceCost.'<BR>';
				echo $penalty;  */
				/*echo '<br>'.$ApplicationDate;*/
				$OtherCharge=0;
				//With other Charges?
				$sql="select Amount 
						from ServiceCharges sc
						join services s on sc.ServiceID=s.serviceid                                 
						join FinancialYear fy on sc.FinancialYearId=fy.FinancialYearID                                      
						and fy.isCurrentYear=1
						and sc.SubSystemId=$SubSystemID
						and sc.serviceid=281";

											// echo $sql;

											// echo '<br><br>'
				
				$s_result = sqlsrv_query($db, $sql);
				while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
				{							
					$OtherCharge=$row["Amount"];												
				}

				//Application Charges
				$ApplicationCharge=0;
				$sql="select sum(sc.Amount) Amount 
						from ApplicationCharges sc 
						join ServiceHeader sh on sh.serviceheaderid=sc.serviceheaderid 
						join Services s1 on sc.ServiceID=s1.ServiceID 
						where sh.ServiceHeaderID=$ServiceHeaderID";

				//echo $sql;

				$result=sqlsrv_query($db,$sql);
				while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{
					$ApplicationCharge=$row['Amount'];
				}
			//echo $sql;
			//echo '<BR>'.$OtherCharge.'<BR>';
				$ServiceCost=$ServiceCost+$OtherCharge+$penalty+$ApplicationCharge+$ConservancyCost;
				return $ServiceCost;
			}
		}

//get the Arrears

if (isset($_REQUEST['approve']))
{	
	$input=array_slice($_REQUEST,2,count($input)-1);	
	foreach ($input AS $id => $value)
	{	
		$newID=substr($id,3,strlen($id)-3);	
			
		$sql="if exists(select * from FormData where FormColumnID=$newID)
				Update FormData set Value='$value' where FormColumnID=$newID and ServiceHeaderID=$ApplicationID
			  else
				insert into FormData (FormColumnID,ServiceHeaderID,Value)
			    values($newID,$ApplicationID,'$value')";
				
		$result=sqlsrv_query($db,$sql);
		
		if(!$result)
		{
			DisplayErrors();
			continue;
		}		

	}	
}
if (isset($_REQUEST['change']))
{	
	$ApplicationID=$_REQUEST['ApplicationID'];
	$FromServiceID=$_REQUEST['FromServiceID'];
	$ToServiceID=$_REQUEST['ToServiceID'];
	$FromSubSystemID=$_REQUEST['FromSubSystemID'];
	$ToSubSystemID=$_REQUEST['ToSubSystemID'];
	$FromWardID=$_REQUEST['FromWardID'];
	$ToWardID=$_REQUEST['ToWardID'];
	$CurrentStatus=$_REQUEST['CurrentStatus'];
	$Notes=$_REQUEST['Notes'];

	
	
	if($FromServiceID==$ToServiceID && $FromSubSystemID==$ToSubSystemID && $FromWardID==$ToWardID){
		$msg="You have made not change in the application";
	}else if($CurrentStatus>4){
		$msg="The Application Cannot Be modified at this stage";
	}
	else
	{

		$sql="Update ServiceHeader Set ServiceID=$ToServiceID where ServiceHeaderID=$ApplicationID";
		$result=sqlsrv_query($db,$sql);
		if($result){

			$rst=SaveTransaction($db,$UserID," Changed Application number $InvoiceHeader from service $FromServiceID to $ToServiceID");

			$sql="Insert into ServiceHeaderChange(ServiceHeaderID,FromServiceID,ToServiceID,CreatedBy,Notes)
			Values ($ServiceHeaderID,$FromServiceID,$ToServiceID,$UserID,'$Notes')";
			
			$result1=sqlsrv_query($db,$sql);
			if($result1)
			{
				$msg ="Application Changed Successfully";
			}else{
				DisplayErrors();
			}
			
			if($FromServiceID!==$ToServiceID)
			{
				$sql="Update InvoiceLines Set ServiceID=$ToServiceID where ServiceHeaderID=$ApplicationID and ServiceHeaderID=$FromServiceID";
				
				$result2=sqlsrv_query($db,$sql);
				if($result2){
					$msg ="Application Changed Successfully";
				}else{
					DisplayErrors();
				}
			}

			if($FromSubSystemID!==$ToSubSystemID)
			{
				$sql="Update FormData Set value=$ToSubSystemID where ServiceHeaderID=$ApplicationID and FormColumnID=12237";
				$result2=sqlsrv_query($db,$sql);
				echo $sql;
				if($result2){
					$rst=SaveTransaction($db,$UserID," Changed Application number $InvoiceHeader from SubSystem $FromSubSystemID to $ToSubSystemID");
					$msg ="Application Changed Successfully";
				}else{
					DisplayErrors();
				}
			}
			
			if($FromWardID!==$ToWardID)
			{
				$sql="Update FormData Set value=$ToWardID where ServiceHeaderID=$ApplicationID and FormColumnID=11204";
				$result2=sqlsrv_query($db,$sql);
				if($result2){
					$rst=SaveTransaction($db,$UserID," Changed Application number $InvoiceHeader from Ward $FromWardID to $ToWardID");				
					$msg ="Application Changed Successfully";

				}else{
					DisplayErrors();
				}
			}
			
			$ServiceCost=getServiceCost($db,$ToServiceID,$ToSubSystemID);
		}	
	}
}





if (isset($_REQUEST['addofficer']))
{	
	$ApplicationID=$_REQUEST['ApplicationID'];
	$User_ID=$_REQUEST['User_ID'];
	
	if($CurrentStatus>4){
		$msg="The Application Cannot Be modified at this stage";
	}
	else
	{

			// $sql="Insert into InspectionOfficers(UserID, ServiceHeaderID)
			// Values ($User_ID, $ServiceHeaderID)";
			$sql="insert into Inspections(ServiceHeaderID,UserID,InspectionStatusID) values($ApplicationID,$User_ID,0)";

			// echo $sql;exit;
			
			$result1=sqlsrv_query($db,$sql);
			if($result1)
			{
				$msg ="The Inspection Officer Has Been Successfully Added";
			}else{
				DisplayErrors();
			}
	}
}

if (isset($_REQUEST['deleteofficer']))
{	
	$ApplicationID=$_REQUEST['ApplicationID'];
	$User_ID=$_REQUEST['User_ID'];
	
	if($CurrentStatus>4){
		$msg="The Application Cannot Be modified at this stage";
	}
	else
	{

			
			$sql="delete from Inspections where ServiceHeaderID=$ApplicationID and UserID = $User_ID";

			// echo $sql;exit;
			
			$result1=sqlsrv_query($db,$sql);
			if($result1)
			{
				$msg ="The Inspection Officer Has Been Successfully Deleted";
			}else{
				DisplayErrors();
			}
	}
}
if (isset($_REQUEST['InspectionDate']))
{	
	$ApplicationID=$_REQUEST['ApplicationID'];
	$SetDate=$_REQUEST['SetDate'];
	
	if($CurrentStatus>4){
		$msg="The Application Cannot Be modified at this stage";
	}
	else
	{

			$sql="update ServiceHeader set SetDate = '$SetDate' where ServiceHeaderID =$ApplicationID";

			// echo $sql;
			
			$result1=sqlsrv_query($db,$sql);
			if($result1)
			{
				$msg ="The Inspection Date Has Been Set";
			}else{
				DisplayErrors();

			}
	}
}



	 //$ServiceCost=$ServiceCost-OtherCharge;
  //echo $ServiceID.'<br>';
  // echo 'Subsustem '.$SubSystemID.'<BR>';
  // echo 'ServiceCost '.$ServiceCost.'<BR>';
  //  echo 'Conservancy '.$ConservancyCost.'<BR>';
  // echo 'Other Charges '.$OtherCharge.'<BR>'; 
  

?>
<script type="text/javascript">
	$("#addCharges").on('click', function(ev){
		var url = 'add_charge.php?ApplicationID=' 
			+ ev.target.dataset.appId + '&SubSystemID=' + ev.target.dataset.ssId+ '&ServiceID=' + ev.target.dataset.sId+ '&Renew=0'
		//console.log(url)
		$.get(url, function(res) {
			$.Dialog({
		        shadow: true,
		        overlay: false,
		        draggable: true,
		        icon: '<span class="icon-rocket"></span>',
		        title: 'Application Charges',
		        width: 500,
		        padding: 10,
		        content: res
		    });
		})
	    
	});
</script>
<style type="text/css">
	.tab-folder > .tab-content:target ~ .tab-content:last-child, .tab-folder > .tab-content {
    display: none;
}
.tab-folder > :last-child, .tab-folder > .tab-content:target {
    display: block;
}
</style>
<div class="example">
   <legend>Application Details</legend>
   <form>
      <fieldset>
          <table width="100%" border="0" cellspacing="0" cellpadding="3">
              <tr>
                <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
            </tr>
                              <?php 
                  if($ServiceStatusID == 5 && $ServiceCategoryID == 2033){
                  	?>
                  	<tr>
                  <td width="50%">
                  <label>Recommendation:</label>
					  <div class="input-control text" data-role="input-control">
						  <p style="color: red;">Re-Inspection from the inspection team</p>						  
					  </div>				  
                  </td>
                  <td width="50%">				  
                  </td>   
              </tr>
              <?php
                  }else{
                  ?>
                  
                  <?php
              }
              ?> 
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
              <!-- <tr>
                  <td width="50%">
                  <label>Ward</label>
					  <div class="input-control text" data-role="input-control">
						  <input name="Ward" type="text" id="Ward" value="<?php echo $WardName; ?>" disabled="disabled" placeholder="">						  
					  </div>				  
                  </td>
                  <td width="50%">				  
                  </td>   
              </tr> -->
              <tr>
                  <td width="50%">
                  <label>TRA Region</label>
					  <div class="input-control text" data-role="input-control">
						  <input name="SubSystem" type="text" id="SubSystem" value="<?php echo $SubSystemName; ?>" disabled="disabled" placeholder="">						  
					  </div>				  
                  </td>
                  <td width="50%">				  
                  </td>   
              </tr>
              <tr>
                  <td width="50%">
                  <label>Service Applied For</label>
					  <div class="input-control text" data-role="input-control">
						  <input name="servicename" type="text" id="servicename" value="<?php echo $ServiceName; ?>" disabled="disabled" placeholder="">
						  
					  </div>				  
                  </td>
                  <!-- <td width="50%"> -->
				<!-- <label>&nbsp;</label>				   -->
					<!--service_approval.php?ApplicationID='+app_id+'&app_type='+app_type+'&CurrentStatus='+current_status
					<input name="Button" type="button" onclick="loadmypage('service_form.php?save=1&ApplicationID=<?php echo $ApplicationID ?>','content','loader','','')" value="Change">-->
					<!-- <input name="Button" type="button" 
					onclick="loadmypage('application_change.php?ApplicationID=<?php echo $ApplicationID; ?>&CurrentStatus=<?php echo $CurrentStatus; ?>','content','loader','','')" value="Change"> -->
                  <!-- </td>    -->
              </tr>	



              <?php 
              if($ServiceGroupID == 12){

              }else{
              ?>
 			 <tr> 
                  <td width="50%">
                  <label>Add Inspection Officers</label>
					  <div class="input-control text" data-role="input-control">
						  <input name="servicename" type="text" id="servicename" value="Add Inspection Officer" disabled="disabled" placeholder="">
						  
					  </div>				  

                  </td>
                 <!--  <td width="50%">
				<label>&nbsp;</label>				   

                  </td> -->
                  <td width="50%">

				<label>&nbsp;</label>				  
					<!-- service_approval.php?ApplicationID='+app_id+'&app_type='+app_type+'&CurrentStatus='+current_status -->
					<!-- <input name="Button" type="button" onclick="loadmypage('service_form.php?save=1&ApplicationID=<?php echo $ApplicationID ?>','content','loader','','')" value="Change"> -->
					<input name="Button" type="button" 
					onclick="loadmypage('add_officer.php?ApplicationID=<?php echo $ApplicationID; ?>&CurrentStatus=<?php echo $CurrentStatus; ?>','content','loader','','')" value="Add Inspection Officer">
                  </td>   
              </tr>	

<tr>

	<?php
	$sql="select SetDate from ServiceHeader where ServiceHeaderID = $ApplicationID";
	$s_result=sqlsrv_query($db,$sql);
		if ($s_result){
			?>
			
			<?php
			while($row=sqlsrv_fetch_array($s_result,SQLSRV_FETCH_ASSOC))
				{									
					$SetDate = $row['SetDate'];
				}
			}
			?>
                  <td width="50%">
                  <label>Inspection Date</label>
					  <div class="input-control text" data-role="input-control">
						  <input name="servicename" type="text" id="servicename" value="<?php echo isset($SetDate)?$SetDate:'Not Set'; ?>" disabled="disabled" placeholder="">
						  
					  </div>				  
                  </td>
                  <td width="50%">
				<label>&nbsp;</label>				  
					<!--service_approval.php?ApplicationID='+app_id+'&app_type='+app_type+'&CurrentStatus='+current_status
					<input name="Button" type="button" onclick="loadmypage('service_form.php?save=1&ApplicationID=<?php echo $ApplicationID ?>','content','loader','','')" value="Change">-->
					<input name="Button" type="button" 
					onclick="loadmypage('inspection_date.php?ApplicationID=<?php echo $ApplicationID; ?>&CurrentStatus=<?php echo $CurrentStatus; ?>','content','loader','','')" value="Set Inspection Date">
                  </td>   
              </tr>


	

			  <tr>
				   <td width="50%">
						<label>Service Cost (Ksh.)</label>
						  <div class="input-control text" data-role="input-control">
							  <input name="servicecost" type="text" id="servicecost" value="<?php echo $ServiceCost; ?>" disabled="disabled" placeholder="">
							  
						  </div>                  	
                  </td>
                  <td>
                  			<label>&nbsp</label>
						  <div class="input-control text" data-role="input-control">
							  <input id="addCharges" name="Button" type="button" 
								data-app-id="<?php echo $ApplicationID; ?>"
								data-ss-id="<?php echo $SubSystemID; ?>"
								data-s-id="<?php echo $ServiceID; ?>"
							 value="Add Charges">
							  
						  </div>
                  </td>
			  </tr>
			  <?php } ?>
				<tr>
					<td colspan="2">
						<HR>  
						<ul id="menu" class="tabs">
						    <li><a href="#tab1">Applicant's Details</a></li>
						    <li><a href="#tab2">Application Notes</a></li>
						    <li><a href="#tab3">Application Attachments</a></li>
						    <!-- <li><a href="#tab4">Notes</a></li> -->
						</ul>  

						<div class="tab-control" data-role="tab-clontrol">
						<div class="tab-control" data-role="tab-control">
						<!-- 	<ul class="tabs">
								<li class=""><a href="#_page_4">Applicant's Details</a></li>	
								<li class="active"><a href="#_page_1">Aplication Notes</a></li>
								<li class=""><a href="#_page_3">Application Attachments</a></li>
								<li class=""><a href="#_page_2">Notes</a></li>
							<?php if($ServiceGroupID==12){
								}else{?>
								<li class=""><a href="#_page_5">Inspection Officers</a></li>
							<?php } ?>
							</ul> -->							
							<div class="frames">
								<div class="frame" id="_page_4" style="display: none;">
								
								  <div class="frame" id="_page_2" style="display: block;">
									               
								  </div>
							  <div class="frame" id="_page_3" style="display: none;">
									
								  </div>
								  <div class="frame" id="_page_1" style="display: none;">
									<table width="50%">
										<!-- <tr>
											<td width="30%">
												<label>SubCounty</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="SubCounty" type="text" id="SubCounty" value="<?php echo $SubCountyName; ?>" disabled="disabled">													  
												  </div>
											</td>
											<td width="30%">
												<label>WardName</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="WardName" type="text" id="WardName" value="<?php echo $WardName; ?>" disabled="disabled">													  
												  </div>
											</td>
											<td width="30%">
												<label>BusinessZone</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="ZoneName" type="text" id="ZoneName" value="<?php echo $BusinessZone; ?>" disabled="disabled">													  
												  </div>
											</td>											
										</tr> -->
										
										<?php  
											if ($ServiceHeaderTypeID==1)
											{
												$sql=" select lrn,plotno,mplotno,titleno from landapplication where ServiceHeaderID='$ApplicationID'";
												$s_result=sqlsrv_query($db,$sql);
												if ($s_result){
													while($row=sqlsrv_fetch_array($s_result,SQLSRV_FETCH_ASSOC))
													{									
														echo "<tr><td>Lrn No: </td><td> ".$row["lrn"]."</td></tr>";
														echo "<tr><td>Plot No: </td><td> ".$row["plotno"]."</td></tr>";
														echo "<tr><td>Mother PlotNo: </td><td> ".$row["mplotno"]."</td></tr>";
														echo "<tr><td>Title No:</td><td> ".$row["titleno"]."</td></tr>"; 
														
														$lrn=$row["lrn"];
														$plotno=$row["plotno"];
														
														$BSql="select RatesPayable,PrincipalBalance from LAND where lrn='".$row["lrn"]."' and PlotNo='".$row["plotno"]."'";
														//echo ($BSql);
														$rsult=sqlsrv_query($db,$BSql);
														if ($rsu=sqlsrv_fetch_array($rsult,SQLSRV_FETCH_ASSOC))
														{
															$Balance=$rsu['PrincipalBalance'];
															$Rates=$rsu['RatesPayable'];
														}else
														{
															$balance=0;
														}														
													}													
													echo "<tr><td>Rates Payable:</td><td> ".$Rates."</td></tr>";
													echo "<tr><td>Outstanding Balance:</td><td> ".$Balance."</td></tr>";
													echo "<a href='statement.php?popupwindow&lrn=$lrn&plotno=$plotno' class='popupwindow' target='_blank'>Rates Statement</a>";
												}else
												{
													echo $sql;
												}												
												
											}else if ($ServiceHeaderTypeID==2)
											{
												$sql="select h.HouseNo,e.EstateID,e.EstateName from HouseApplication h join Estates e on h.EstateID=e.EstateID where h.serviceheaderid=$ApplicationID";
												$s_result=sqlsrv_query($db,$sql);
												if ($s_result){
													while($row=sqlsrv_fetch_array($s_result,SQLSRV_FETCH_ASSOC)){									
														echo "<tr><td>Estate Name: </td><td> ".$row["EstateName"]."</td></tr>";
														echo "<tr><td>House No: </td><td> ".$row["HouseNo"]."</td></tr>";														
														}
												}else
												{
													// echo $sql;
												}												
											}else if ($ServiceHeaderTypeID==3)
											{
												$sql="select ha.FromDate,ha.ToDate,s.ServiceName from HireApplication ha 
														join ServiceHeader sh on ha.ServiceHeaderID=sh.ServiceHeaderID
														join Services s on sh.ServiceID=s.ServiceID where ha.ServiceHeaderID=$ApplicationID";
												$s_result=sqlsrv_query($db,$sql);
												if ($s_result){
													while($row=sqlsrv_fetch_array($s_result,SQLSRV_FETCH_ASSOC)){
														echo "<tr><td>Facilty: </td><td> ".$row["ServiceName"]."</td></tr>";
														echo "<tr><td>Start Date: </td><td> ".$row["FromDate"]."</td></tr>";
														echo "<tr><td>End Date: </td><td> ".$row["ToDate"]."</td></tr>";														
														}
												}else
												{
													echo $sql;
												}												
											}else
											{
												$sql="select FD.ServiceHeaderID,FC.FormColumnName,FD.Value,FD.FormDataID,fc.ColumnDataTypeID,fc.Notes from FormData fd 
											  inner join FormColumns fc on fd.FormColumnID=fc.FormColumnID
											  WHERE FD.ServiceHeaderID=$ApplicationID
											  ORDER BY FC.Priority";

													$s_result=sqlsrv_query($db,$sql);
													
													if ($s_result){
														while($row=sqlsrv_fetch_array($s_result,SQLSRV_FETCH_ASSOC)){
															$rValue=$row['Value'];
															$Notes=$row['Notes'];
															
															if($Notes!='')
															{
																$rsult='';
																$Notes=str_replace("'","''",$Notes);
																//echo $notes; exit;
																$sql="select * from [dbo].[fnColumnDetails] ('$Notes')";									
																$rsult=sqlsrv_query($db,$sql);
																if ($rsult)
																{
																	while($rw=sqlsrv_fetch_array($rsult,SQLSRV_FETCH_ASSOC))
																	{
																		$Table=$rw['table_name'];
																		$displayName=$rw['display_column'];
																		$ColumnName=$rw['column_name'];
																		
																		$myQry="select ".$displayName ." from ".$Table." where ".$ColumnName." =$rValue";
																		$DName=sqlsrv_query($db,$myQry);
																		if($DName)
																		{
																			
																			while($rww=sqlsrv_fetch_array($DName,SQLSRV_FETCH_ASSOC))
																			{
																				$rValue=$rww[$displayName];
																			}
																			
																		}else
																		{

																		}
																		// echo $rValue;
																		
																	}
																}else
																{

																}
															}
															
															
															$dataType=$row['ColumnDataTypeID'];									
																								
															echo "<tr><td>".$row["FormColumnName"]."</td><td>".$rValue."</td></tr>";
															}
													}
											}
										
										?>            	
									</table> 
								  </div>
								  <?php if($ServiceGroupID == 12){

								  }else{ ?>
								  <div class="frame" id="_page_5" style="display: none;">

								  	<?php 
								  	include 'inspection_officers_list.php'; 
								  	$UserID = '$UserID';
								  	$ServiceID = $ApplicationID;
								  	//include 'inspection_officers.php'; 
								  	?>

								  	
									<!-- <table class="hovered" cellpadding="3" cellspacing="1">

										

										<?php




											$sql="SELECT u.Email, FirstName, Middlename, LastName UserNames 
												FROM Users u 
												join agents ag on u.agentid=ag.agentID ";
												$s_result=sqlsrv_query($db,$sql);
												if ($s_result){
													while($row=sqlsrv_fetch_array($s_result,SQLSRV_FETCH_ASSOC)){
														echo "<tr><td>Facilty: </td><td> ".$row["Email"]."</td></tr>";
														echo "<tr><td>Start Date: </td><td> ".$row["FirstName"]."</td></tr>";
														echo "<tr><td>End Date: </td><td> ".$row["LastName"]."</td></tr>";														
														}
												}else
												{
													echo $sql;
												}						



										?>





									  </table>  -->
								  </div>

								</div>

						  </div>

						</div>					
					</td>
				</tr>
			<?php } ?>
				<!-- <tr>

					<td width="50%"><label>Notes</label>
					  <div class="input-control textarea" data-role="input-control">
						<textarea name="Notes" type="textarea> id="Notes" placeholder=""><?php //echo $Notes; ?></textarea>  
					  </div>
					</td>                  
					<td width="50%"></td>   
				</tr>	



            <tr>
              <td width="50%">
                <label>Action</label>
                <div class="input-control select" data-role="input-control">
                  <select name="NextStatus"  id="NextStatus">                    
                    <?php 
                         
						
						$s_sql="SELECT ServiceStatusID,ServiceStatusDisplay  from ServiceStatus where ServiceStatusID in (2,6)";						

						
						$s_result = sqlsrv_query($db, $s_sql);
						if ($s_result) 
						{ //connection succesful 
						  while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
						  {
							  $s_name = $row["ServiceStatusDisplay"];							  
							  $s_id = $row["ServiceStatusID"];
                                    
						   ?>
						  <option value="<?php echo $s_id; ?>" <?php echo $selected; ?>>
						  	<?php if($s_id==2 && $ServiceGroupID==12){
						  		echo 'Trade Facilitation Application Approved';
						  	}else{
						  	echo $s_name; 
						  }?>
						  		
						  	</option>
						<?php 
						  }
						}
                          ?>
                  </select> 
                  <?php  //echo $s_sql;  ?>
                </div></td>
                <td width="50%"></td>   
            </tr>     -->                   
            	
          </table> 
          
          
          <span class="table_text">
          <input name="ApplicationID" type="hidden" id="ApplicationID" value="<?php echo $ApplicationID;?>" />
  <input name="edit" type="hidden" id="edit" value="<?php echo $edit;?>" />
  <input name="edit" type="hidden" id="CurrentStatus" value="<?php echo $CurrentStatus;?>" />
                  </span>
          <div style="margin-top: 20px">
  </div>


      </fieldset>
  </form>                  

    <div class="tab-folder">
    <div id="tab2" class="tab-content">
    	<table class="hovered" cellpadding="3" cellspacing="1">
										<?php 
											$sql="SELECT SH.ServiceHeaderID, SS.ServiceStatusName, SAA.Notes, SAA.CreatedDate, U.FirstName+' '+U.MiddleName+' '+u.LastName UserFullNames
													FROM dbo.ServiceApprovalActions AS SAA INNER JOIN
													dbo.ServiceHeader AS SH ON SAA.ServiceHeaderID = SH.ServiceHeaderID INNER JOIN
													dbo.Agents AS U ON SAA.CreatedBy = U.AgentId INNER JOIN
													dbo.ServiceStatus AS SS ON SAA.ServiceStatusID = SS.ServiceStatusID
													where SH.ServiceHeaderID=$ApplicationID";

													$s_result=sqlsrv_query($db,$sql);
													
													if ($s_result)
													{
														while($row=sqlsrv_fetch_array($s_result,SQLSRV_FETCH_ASSOC))
														{									
															echo "<tr><td>".$row["ServiceStatusName"]."</td><td>".$row["Notes"]."</td><td>".$row["CreatedDate"]."</td><td>".$row["UserFullNames"]."</td></tr>";
														}
													}
										?>             	
									  </table> 
    </div>
    			<div id="tab3" class="tab-content"><table class="hovered" cellpadding="3" cellspacing="1">
										<?php 
											$sql="select d.DocumentName,att.ID
													from Attachments att
													join Documents d on d.DocumentID=att.DocumentID
													 where att.ApplicationNo=$ApplicationID";

													$s_result=sqlsrv_query($db,$sql);
													
													if ($s_result){
														while($row=sqlsrv_fetch_array($s_result,SQLSRV_FETCH_ASSOC)){									
															echo "<tr>
																<td>
																<a href='documentdownload.php?id=".$row["ID"]."' target='_blank' >".$row["DocumentName"]." </a>
																</td>
															</tr>";
															}
													}
										?>             	
									  </table> </div>
    <div id="tab1" class="tab-content">
    	<fieldset>
										<table width="50%" border="0" cellspacing="0" cellpadding="3">
											<tr>
												<td width="50%">
												   <label>Customer Name</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="CustomerName" type="text" id="CustomerName" value="<?php echo $CustomerName; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td>                 
												<td width="50%">
												   <label>Business Type</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="CustomerType" type="text" id="CustomerType" value="<?php echo $CustomerType; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td>                        
											<tr> 
											<tr>
												<td width="50%">
												   <label>Registration No</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="RegNo" type="text" id="RegNo" value="<?php echo $RegNo; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td> 
												<td width="50%">
												   <label>Pin</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="Pin" type="text" id="Pin" value="<?php echo $Pin; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td>                   
											</tr>
											<tr>
												<td width="50%">
												   <label>Postal Address</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="PostalAddress" type="text" id="PostalAddress" value="<?php echo $PostalAddress; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td> 
												<td width="50%">
												   <label>Postal Code</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="PostalCode" type="text" id="PostalCode" value="<?php echo $PostalCode; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td>                   
											</tr> 
											<tr>
												<td width="50%">
												   <label>Town</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="Town" type="text" id="Town" value="<?php echo $Town; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td> 
												<td width="50%">
												   <label>Country</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="Country" type="text" id="Country" value="<?php echo $Country; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td>                   
											</tr> 
											<tr>
												<td colspan="2">
												   <label>Physical Location</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="Location" type="text" id="Location" value="<?php echo $Town; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td>                    
											</tr>                                     
											 <tr>
												<td width="50%">
												   <label>Telephone 1</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="Telephone1" type="text" id="Telephone1" value="<?php echo $Telephone1; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td>
																		 
												<td width="50%">
												   <label>Telephone 2</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="Telephone2" type="text" id="Telephone2" value="<?php echo $Telephone2; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td>  
																 
											</tr>
											<tr>
												<td width="50%">
												   <label>Mobile 1</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="Mobile1" type="text" id="Mobile1" value="<?php echo $Mobile1; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td> 
												<td width="50%">
												   <label>Mobile 2</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="Mobile2" type="text" id="Mobile2" value="<?php echo $Mobile2; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td>                   
											</tr>
											<tr>
												<td width="50%">
												   <label>Email</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="Email" type="text" id="Email" value="<?php echo $Email; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td> 
												<td width="50%">
												   <label>Url</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="Url" type="text" id="Url" value="<?php echo $url; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td>                   
											</tr>                   
														   
										</table>            
									</fieldset>
    </div>
</div>