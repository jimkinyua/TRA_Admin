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
	$ApplicationTypeID=$_REQUEST['ApplicationTypeID'];
	$sql="Delete from ApplicationTypes where ApplicationTypeID=$ApplicationTypeID";
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "MenuGroup Deleted Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Action failed";			
	}
	
}else if (isset($_REQUEST['save']))
{	
	$ApplicationTypeID=$_REQUEST['ApplicationTypeID'];
	$ApplicationTypeName=$_REQUEST['ApplicationTypeName'];		
	
	if ($ApplicationTypeID=='0')
	{
		$sql="Insert into ApplicationTypes (ApplicationTypeName,CreatedBY)
		Values('$ApplicationTypeName',$CreatedUserID)";

	} else
	{
		$sql="Update ApplicationTypes set ApplicationTypeName='$ApplicationTypeName' where ApplicationTypeID=$ApplicationTypeID";
	}	

	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Application Type Saved Successfully";			
	} else
	{
		DisplayErrors();
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
        <legend>ApplicationTypes</legend>        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th class="text-left"><a href="#" onClick="loadmypage('application_type.php?i=1','content')">Add</a></th>
                    <th colspan="5" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>
                <tr>
                    <th width="15%" class="text-left">ID</th>
                    <th width="60%" class="text-left">Application Type</th>
                    <th width="25%" class="text-left">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>


</div>
</div>