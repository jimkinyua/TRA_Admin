<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];

if (isset($_REQUEST['delete']))
{
	$RoleCenterID=$_REQUEST['RoleCenterID'];
	$sql="Delete from RoleCenters where RoleCenterID=$RoleCenterID";
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Role Center Deleted Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Action failed";			
	}
	
}else if (isset($_REQUEST['save']))
{	
	$RoleCenterID=$_REQUEST['RoleCenterID'];
	$RoleCenterName=$_REQUEST['RoleCenterName'];
	$IsAdmin=$_REQUEST['IsAdmin'];
	$DefaultMenuGroupID=$_REQUEST['DefaultMenuGroupID'];
	$MaximumApprovalLimit=$_REQUEST['MaximumApprovalLimit'];
	$BeyondLimitApproverID=$_REQUEST['BeyondLimitApproverID'];
	
	if ($RoleCenterID=='0')
	{
		$sql="Insert into RoleCenters (RoleCenterName,IsAdmin,DefaultMenuGroupID,CreatedBY,MaximumApprovalLimit,BeyondLimitApproverID)
		Values('$RoleCenterName','$IsAdmin','$DefaultMenuGroupID',$CreatedUserID,$MaximumApprovalLimit,'$BeyondLimitApproverID')";

	} else
	{
		$sql="Update RoleCenters set RoleCenterName='$RoleCenterName',IsAdmin='$IsAdmin',DefaultMenuGroupID='$DefaultMenuGroupID',MaximumApprovalLimit='$MaximumApprovalLimit',BeyondLimitApproverID='$BeyondLimitApproverID' where RoleCenterID='$RoleCenterID'";
	}	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Role Center Saved Successfully";			
	} else
	{
		DisplayErrors();
		$msg = "Details Failed to save";
		echo $sql;
		//redirect($_REQUEST, $msg, "RoleCenters.php?RoleCenterID=$RoleCenterID");			
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
        <legend>RoleCenters</legend>        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th class="text-left"><a href="#" onClick="loadmypage('role_center.php?i=1','content')">Add</a></th>
                    <th colspan="5" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>
                <tr>                    
                    <th width="58%" class="text-left">RoleCenter Name</th>
                    <th width="12%" class="text-left">&nbsp;</th>
                    <th width="15%" class="text-left">&nbsp;</th>
                    <th width="15%" class="text-left">&nbsp;</th>
                    
                    
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>


</div>
</div>