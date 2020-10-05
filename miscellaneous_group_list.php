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
	$MiscellaneousGroupID=$_REQUEST['MiscellaneousGroupID'];
	$sql="Delete from MiscellaneousGroups where MiscellaneousGroupID=$MiscellaneousGroupID";
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Miscellaneous Group Deleted Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Action failed";			
	}	
}

if (isset($_REQUEST['save']))
{	
	//print_r($_REQUEST);
	
	$MiscellaneousGroupID=$_REQUEST['MiscellaneousGroupID'];
	$MiscellaneousGroupName=$_REQUEST['MiscellaneousGroupName'];
	
	if ($MiscellaneousGroupID=='0')
	{
		$sql="Insert into MiscellaneousGroups (MiscellaneousGroupName,CreatedBY)
		Values('$MiscellaneousGroupName',$CreatedUserID)";

	} else
	{
		$sql="Update MiscellaneousGroups set MiscellaneousGroupName='$MiscellaneousGroupName' where MiscellaneousGroupID=$MiscellaneousGroupID";
	}	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Miscellaneous Group Saved Successfully";			
	} else
	{
		DisplayErrors();
		echo '<br>'. $sql;
		$msg = "Details Failed to save";				
	}	
}

?>
<div class="example">
<legend>Miscellaneous Groups
 </legend><table class="table striped hovered dataTable" id="dataTables-1" width="100%">
    <thead>
      <tr>
        <th class="text-left"><a href="#" onClick="loadmypage('miscellaneous_group.php?add=1','content')">Add</a></th>
        <th colspan="5" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
      </tr>
       <tr>
          <th width="30%" class="text-left">MiscellaneousGroupID</th>
          <th width="30%" class="text-left">Miscellaneous Group Name</th>
          <th width="30" class="text-left">Date Created</th>
          <th width="3%" class="text-left">&nbsp;</th>
       </tr>
    </thead>
  </table>
</div>
