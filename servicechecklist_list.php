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
$CreatedUserID = $_SESSION['UserID'];


$ServiceGroupID=$_REQUEST['ServiceGroupID'];


$sql="Select ServiceGroupName from ServiceGroup where ServiceGroupID=$ServiceGroupID";

$result=sqlsrv_query($db,$sql);
while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
	$ServiceGroupName=$row['ServiceGroupName'];
}




if (isset($_REQUEST['delete']))
{
	$ServiceChecklistID=	$_REQUEST['ServiceChecklistID'];

	
	$sql = "DELETE FROM ServiceChecklists WHERE ServiceChecklistID=$ServiceChecklistID";
	//echo $sql;
	$result = sqlsrv_query($db, $sql);
	if ($result)
	{
		$rst=SaveTransaction($db,$UserID," Deleted the service checklist for service number ".$ServiceGroupID." Parameter ".$ParameterID);
		$msg = "Record Deleted Successfully";
	} else
	{
		$msg = "Record Failed to be Deleted";
	}
}

if (isset($_REQUEST['save']))
{
	$ServiceGroupID=	$_REQUEST['ServiceGroupID'];
	$ParameterID=$_REQUEST['ParameterID'];
	$ParameterCategoryID=$_REQUEST['ParameterCategoryID'];
	
	
	$sql="if not exists(select * from ServiceChecklists where ServiceGroupID=$ServiceGroupID and ParameterID=$ParameterID)
	insert into ServiceChecklists(ServiceGroupID,ParameterID) values($ServiceGroupID,$ParameterID)
	else
	update ServiceChecklists set ParameterID=$ParameterID where ServiceGroupID=$ServiceGroupID and ParameterID=$ParameterID";

	
	$result = sqlsrv_query($db, $sql);

	$rst=SaveTransaction($db,$UserID," Created/Update the service charges for service number ".$ServiceGroupID." SubSystem ".$ParameterID);

	if (!$result)
	{
		DisplayErrors();
		$msg ="Commit Failed";	
		redirect($_REQUEST, $msg, "servicechecklist.php");
			
	}else
	{
		$msg = "Saved Details Successfully";			
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
        <legend>Service Checklist for [<?= $ServiceGroupName; ?>]</legend>        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th class="text-left" colspan="3"><?php echo $ServiceGroupName; ?></th>                  
                  </tr>                                  
                  <tr>
                    <th class="text-left"><a href="#" onClick="loadpage('servicechecklist.php?add=1&ServiceGroupID='+<?php echo $ServiceGroupID; ?>+'','content')">New</a></th>
                    <th  class="text-center" style="color:#F00"><?php echo $msg; ?></th>
					<th class="text-left"><a href="#" onClick="<?php echo $historyString; ?>">Back</a></th> 
                  </tr>
                <tr>
                    <th width="40%" class="text-left">Parameter Category</th>
                    <th width="50%" class="text-left">Parameter Name</th>
                    <th width="10%" class="text-left">&nbsp;</th>                    
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>


</div>
</div>