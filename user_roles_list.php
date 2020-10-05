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
	$UserRoleID=$_REQUEST['UserRoleID'];
	$sql="Delete from UserRoles where UserRoleID=$UserRoleID";
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "UserRole Deleted Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Action failed";			
	}	
}

if (isset($_REQUEST['save']))
{	

	$UserRoleID=$_REQUEST['UserRoleID'];
	$UserID=$_REQUEST['UserID'];	
	$RoleCenterID=$_REQUEST['RoleCenterID'];
	
	if ($UserRoleID=='0')
	{
		$sql="Insert into UserRoles (UserID,RoleCenterID,CreatedBY)
		Values('$UserID',$RoleCenterID,$CreatedUserID)";

	} else
	{
		$sql="Update UserRoles set UserID='$UserID',RoleCenterID=$RoleCenterID where UserRoleID=$UserRoleID";
	}	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "User Role Saved Successfully";			
	} else
	{
		//DisplayErrors();
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
        <legend>Users & Roles</legend>
        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th class="text-left"><a href="#" onClick="loadmypage('user_role.php?i=1','content')">Add</a></th>
                    <th colspan="5" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>
                <tr>
                    <th width="30%" class="text-left">User Full Names</th>
					<th width="20%" class="text-left">UserName</th>
                    <th width="30%" class="text-left">Role Center</th>
                    <th width="10%" class="text-left">&nbsp;</th>
                    <th width="10%" class="text-left">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>


</div>
</div>