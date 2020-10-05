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
	$ServiceGroupID=$_REQUEST['ServiceGroupID'];
	$sql="Delete from ServiceGroup where ServiceGroupID=$ServiceGroupID";
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "ServiceGroup Deleted Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Action failed";			
	}	
}

if (isset($_REQUEST['save']))
{	
	//print_r($_REQUEST);
	
	$ServiceGroupID=$_REQUEST['ServiceGroupID'];
	$ServiceGroupName=$_REQUEST['ServiceGroupName'];
	
	if ($ServiceGroupID=='0')
	{
		$sql="Insert into ServiceGroup (ServiceGroupName,CreatedBY)
		Values('$ServiceGroupName',$CreatedUserID)";

	} else
	{
		$sql="Update ServiceGroup set ServiceGroupName='$ServiceGroupName' where ServiceGroupID=$ServiceGroupID";
	}	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "ServiceGroup Saved Successfully";			
	} else
	{
		DisplayErrors();
		echo '<br>'. $sql;
		$msg = "Details Failed to save";				
	}	
}

?>
<div class="example">
<legend>Sevice Groups
 </legend><table class="table striped hovered dataTable" id="dataTables-1" width="100%">
    <thead>
      <tr>
        <th class="text-left"><a href="#" onClick="loadmypage('servicegroup.php?add=1','content')">Add</a></th>
        <th colspan="2" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
      </tr>
       <tr>
          <th width="10%" class="text-left">ServiceGroupID</th>
          <th width="80%" class="text-left">Service Group Name</th>
          <th width="10%" class="text-left">&nbsp;</th>
       </tr>
    </thead>
  </table>
</div>
