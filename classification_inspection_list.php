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
$ApplicationID=$_REQUEST['ApplicationID'];
// print_r($_REQUEST); 

if($_REQUEST['submit']==1){
	//
	$InspectionID=$_REQUEST['InspectionID'];
	$Status=$_REQUEST['Status'];
	$Comment=$_REQUEST['Comment'];
	$AverageScore = $_REQUEST['AverageScore'];
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
	$LicenceNumber ='TEST/2020/LICENCE/RYWY/7'.rand(87, 600);
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
		$sql="Insert into InspectionComments (InspectionID,UserID,AverageScore,InspectionStatusID,UserComment) Values($InspectionID,$UserID,$AverageScore,$Status,'$Comment')";
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
	  <!-- <input name="Button" type="button" 
					onclick="loadmypage('setrating.php?ApplicationID=<?php echo $ApplicationID; ?>&CurrentStatus=<?php echo $CurrentStatus; ?>','content','loader','listpages','','Inspections','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" value="Set Rating for Classification and Grading"> || 
	 <input name="Button" type="button" 
					onclick="loadmypage('tradefacilitation_list.php?ApplicationID=<?php echo $ApplicationID; ?>&CurrentStatus=<?php echo $CurrentStatus; ?>','content','loader','listpages','','Inspections','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" value="Trade And Facilitation Applications">||
	<input name="Button" type="button" 
					onclick="loadmypage('classification_list.php?ApplicationID=<?php echo $ApplicationID; ?>&CurrentStatus=<?php echo $CurrentStatus; ?>','content','loader','listpages','','Inspections','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" value="Classification and Grading Applications"> -->
		<form>
            <table class="table striped hovered dataTable" id="dataTables-1">
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
            <?php 
            $sql1 = "set dateformat dmy SELECT distinct top 100 sh.SubmissionDate,sc.ServiceGroupID,sh.ServiceHeaderID 
			AS ApplicationID,sc.ServiceGroupID,ins.UserID,ins.InspectionDate, 
			s.ServiceName,c.CustomerName,ss.ServiceStatusDisplay,u.UserFullNames UserNames,
			ins.UserComment,ins.InspectionID FROM ServiceHeader AS sh 
			INNER JOIN Services AS s ON sh.ServiceID = s.ServiceID 
			inner join ServiceCategory sc on sc.ServiceCategoryID = sh.ServiceCategoryID 
			inner Join ServiceGroup sg on sg.ServiceGroupID = sc.ServiceGroupID 
			INNER JOIN Customer AS c ON sh.CustomerID = c.CustomerID 
			INNER JOIN ServiceStatus ss ON sh.ServiceStatusID=ss.ServiceStatusID 
			INNER JOIN Inspections ins on ins.ServiceHeaderID=sh.ServiceHeaderID 
			JOIN Users u on u.AgentID=ins.UserID 
			where ins.InspectionStatusID>0 and sh.ServiceHeaderID=$ApplicationID and sh.ServiceStatusID !=1 and sc.ServiceGroupID!=12 
			order by sh.SubmissionDate desc";
					// echo $sql1;exit;
		
	$result = sqlsrv_query($db, $sql1);	
	while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) 
	{
		$CustomerName = $row['CustomerName'];
		$InspectionDate = $row['InspectionDate'];
		$ServiceName = $row['ServiceName'];
		$UserNames = $row['UserNames'];
		$ServiceStatusDisplay = $row['ServiceStatusDisplay'];
		$Region = $row['Region'];
		$UserComment = $row['UserComment'];
		$InspectionID = $row['InspectionID'];

		?>
		<tr>
			<td><?php echo $CustomerName; ?></td>
			<td><?php echo $InspectionDate; ?></td>
			<td><?php echo $ServiceName; ?></td>
			<td><?php echo $UserNames; ?></td>
			<td><?php echo $ServiceStatusDisplay; ?></td>
			<td><?php echo $Region; ?></td>
			<td><?php echo $UserComment; ?></td>
			<td>
				<input name="Button" type="button" onclick="loadmypage('inspection_checklist.php?InspectionID=<?php echo $InspectionID; ?>&ApplicationID=<?php echo $ApplicationID; ?>','content','loader','listpages','','InspectionResults','<?php echo $InspectionID; ?>')" value="View Checklist">
				
			</td>
		</tr>
		<?php
		}
		?>				  
				
            </table>
		</form>

		</div>