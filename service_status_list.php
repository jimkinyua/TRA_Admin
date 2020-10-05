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
	$ServiceStatusID=$_REQUEST['ServiceStatusID'];
	$sql="Delete from ServiceStatus where ServiceStatusID=$ServiceStatusID";
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "ServiceStatus Deleted Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Action failed";			
	}	
}

if (isset($_REQUEST['save']))
{	
	$ServiceStatusID=$_REQUEST['ServiceStatusID'];
	$ServiceStatusName=$_REQUEST['ServiceStatusName'];	
	$ServiceStatusDisplay=$_REQUEST['StatusToDisplay'];
	
	if ($ServiceStatusID=='0')
	{
		$sql="Insert into ServiceStatus (ServiceStatusName,ServiceStatusDisplay,CreatedBY)
		Values('$ServiceStatusName','$ServiceStatusDisplay',$CreatedUserID)";

	} else
	{
		$sql="Update ServiceStatus set ServiceStatusName='$ServiceStatusName', ServiceStatusDisplay='$ServiceStatusDisplay' where ServiceStatusID=$ServiceStatusID";
	}	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "ServiceStatus Saved Successfully";			
	} else
	{
	    DisplayErrors(); 
		echo "<br>".$sql;
		
		$msg = "Details Failed to save";
				
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
        <legend>Service Status List</legend>
        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th class="text-left"><a href="#" onClick="loadmypage('service_status.php?1=1','content')">Add</a></th>
                    <th colspan="3" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>
                <tr>
                    <th width="10%" class="text-left">ServiceStatusID</th>
                    <th width="35%" class="text-left">ServiceStatusName</th>
                    <th width="35%" class="text-left">ServiceStatusDisplay</th>
                    <th width="20%" class="text-left">&nbsp;</th>

                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>


</div>
</div>