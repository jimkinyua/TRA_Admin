<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
require_once('GlobalFunctions.php');
require_once('county_details.php');
require_once('smsgateway.php');


if (!isset($_SESSION))
{
	session_start();
}
$UserID=$_SESSION['UserID'];
$msg ='';

// print_r($_REQUEST); 

if($_REQUEST['submit']==1){
	//
	$InspectionID=$_REQUEST['InspectionID'];
	$Status=$_REQUEST['Status'];
	$Comment=$_REQUEST['Comment'];
	if($Comment==''){
		$Comment='Cleared for Payment and Licencing';
	}
	
	$sql="Select ServiceHeaderID from Inspections where InspectionID=$InspectionID";
	$result=sqlsrv_query($db,$sql);
	if($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
		$ApplicationID=$rw['ServiceHeaderID'];
	}
	// exit($ApplicationID);
	// date('Y-m-d H:i:s')
	$TodayDate = date("Y-m-d H:i:s");
	$date = 31; $month =12; $year = date("Y"); //Licences Expire on 31ST Dec EveryYear
	$ExpiryDate="$date.$month.$year";
    $local=new datetime($ExpiryDate);
	$sqlExpiryDate = $local->format('Y-m-d H:i:s');
	$LicenceNumber ='KTLL/2020/TEST'.rand(87, 600);
	$ChangeStatussql="Update ServiceHeader set ServiceStatusID=4, PermitNo='$LicenceNumber', IssuedDate='$TodayDate', ExpiryDate='$sqlExpiryDate' where ServiceHeaderID='$ApplicationID'";
	// echo '<pre>';
	// print_r($ChangeStatussql);
	// exit;
	$result=sqlsrv_query($db,$ChangeStatussql);

	

	if($result){
		GenerateInvoice($db,$ApplicationID,$UserID);
	}else{
		DisplayErrors();
		$msg="Failed to Issue Licence, contact the technical teamss";

	}

	

	$sql="Update Inspections Set InspectionStatusID=$Status,UserComment='$Comment' where InspectionID='$InspectionID'";
	$result=sqlsrv_query($db,$sql);
	if($result){
		$sql="Insert into InspectionComments (InspectionID,UserID,InspectionStatusID,UserComment) Values($InspectionID,$UserID,$Status,'$Comment')";
		$result=sqlsrv_query($db,$sql);
		if($result){
			$msg="Status Saved Successfully";
		}else
		{
			DisplayErrors();
			$msg="Failed to save Status, contact the technical teamss";
		}

	}else{
		DisplayErrors();
		$msg="Failed to save status, contact the technical team";
	}
}


checkSession($db,$UserID);



?>
    <link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/prettify/prettify.css" rel="stylesheet">
	<script src="js/metro/metro-datepicker.js"></script>   
	<script src="js/metro/metro-calendar.js"></script>	

	<script type="text/javascript">
	    	$(".datepicker").datepicker();
	    </script>   

        <div class="example">
        <legend>SBP Applications</legend>
       <!--  <input type="text" id="session" name="session" /> -->


       <input name="Button" type="button" 
					onclick="loadmypage('graded.php?ApplicationID=<?php echo $ApplicationID; ?>&CurrentStatus=<?php echo $CurrentStatus; ?>','content','loader','','')" value="View Grades">
	   <input name="Button" type="button" 
					onclick="loadmypage('add_officer.php?ApplicationID=<?php echo $ApplicationID; ?>&CurrentStatus=<?php echo $CurrentStatus; ?>','content','loader','','')" value="Completed Inspections">


		<form>
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>                    
                    <th colspan="8" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>
				<tr>
					<td colspan="8">
						<table width="100%">
							<tr>
								<td width="20%"><label>From Date </label>
										<div class="input-control text datepicker" data-role="input-control">						
											<input type="text" id="fromDate" name="fromDate" value="<?php echo $fromDate ?>"></input>	
											<button class="btn-date" type="button"></button>			
										</div>
								</td>
								<td width="20%"><label>To Date </label>
									<div class="input-control text datepicker" data-role="input-control">						
										<input type="text" id="toDate" name="toDate" value="<?php echo $toDate ?>"></input>	
										<button class="btn-date" type="button"></button>			
									</div>
								</td>
								<td width="20%"><label>Application No</label>
									<div class="input-control text" data-role="input-control">						
										<input type="text" id="ServiceHeaderID" name="ServiceHeaderID" value="<?php echo $ServiceHeaderID ?>"></input>									
									</div>
								</td>								
								<td><label>&nbsp;</label>
								<input name="btnSearch" type="button" onclick="loadmypage('clients_list.php?'+
											'&fromDate='+this.form.fromDate.value+								
											'&toDate='+this.form.toDate.value+
											'&search=1','content','loader','listpages','','applications','rolecenter=<?php echo $_SESSION['RoleCenter']; ?>:fromDate='+this.form.fromDate.value+':toDate='+this.form.toDate.value+':ServiceHeaderID='+this.form.ServiceHeaderID.value+'')" value="Search">
								</td>
							</tr>
						</table>
					</td>							  
				</tr>
                <tr>
                    <th  class="text-left"> Client Name</th>
                    <th  class="text-left">Inspection Date</th>                   
                    <th  class="text-left" width="20%">Service Name</th>
                    <th  class="text-left">User Names</th>
                    <th  class="text-left">Status</th>
                    <th  class="text-left">Region</th>
                    <th  class="text-left" width="40%">Comment</th>
                    <th  class="text-left">&nbsp;</th>
                    

					
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>
		</form>

		</div>