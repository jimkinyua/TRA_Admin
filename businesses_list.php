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
	$BusinessID=$_REQUEST['BusinessID'];
	$sql="Delete from Businesses where BusinessID=$BusinessID";
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Business Deleted Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Action failed";			
	}	
}

if (isset($_REQUEST['save']))
{	

	$BusinessID=$_REQUEST['BusinessID'];
	$BusinessName=$_REQUEST['BusinessName'];
	$BusinessActivity=$_REQUEST['$BusinessActivity'];	
	$PhoneNo=$_REQUEST['PhoneNo'];
	$WardID=$_REQUEST['WardID'];
	$BusinessOwner=$_REQUEST['BusinessOwner'];
	$IDNo=$_REQUEST['IDNo'];
	
	if ($BusinessID=='0')
	{
		$sql="Insert into Businesses (BusinessName,WardID,BusinessActivity,BusinessOwner,IDNo,PhoneNo,CreatedBY)
		Values('$BusinessName',$WardID,'$BusinessActivity','$BusinessOwner','$IDNo','$PhoneNo',$CreatedUserID)";

	} else
	{
		$sql="Update Businesses set BusinessName='$BusinessName',WardID='$WardID',BusinessActivity='$BusinessActivity',BusinessOwner='$BusinessOwner',IDNO='$IDNo',PhoneNo='$PhoneNo' where BusinessID=$BusinessID";
	}	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Business Saved Successfully";			
	} else
	{
		DisplayErrors();
		$msg = "Details Failed to save";
				
	}	
}
?>
    <link href="file:///C|/inetpub/wwwroot/SmartApps/table_example/css/metro-bootstrap.css" rel="stylesheet">
    <link href="file:///C|/inetpub/wwwroot/SmartApps/table_example/css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="file:///C|/inetpub/wwwroot/SmartApps/table_example/css/iconFont.css" rel="stylesheet">
    <link href="file:///C|/inetpub/wwwroot/SmartApps/table_example/css/docs.css" rel="stylesheet">
    <link href="file:///C|/inetpub/wwwroot/SmartApps/table_example/js/prettify/prettify.css" rel="stylesheet">
<body class="metro">
        <div class="example">
        <legend>Businesss</legend>
        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th class="text-left"><a href="#" onClick="loadmypage('business.php?i=1','content')">Add</a></th>
                    <th colspan="6" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>
                <tr>
                    <th width="10%" class="text-left">WardName</th>
                    <th width="20%" class="text-left">Business Name</th>
                    <th width="15%" class="text-left">Business Activity</th>
                    <th width="15%" class="text-left">Business Owner</th>
                    <th width="10%" class="text-left">IDNO</th>
                    <th width="10%" class="text-left">PhoneNo</th>
                    <th width="10%" class="text-left">SBP_NO</th>
                    <th width="10%" class="text-left">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>


</div>
</div>