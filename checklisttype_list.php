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
	$ChecklistTypeID=$_REQUEST['ChecklistTypeID'];
	$sql="Delete from ChecklistTypes where ChecklistTypeID=$ChecklistTypeID";
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Categories Deleted Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Action failed";			
	}	
}

if (isset($_REQUEST['save']))
{	

	$ChecklistTypeID=$_REQUEST['ChecklistTypeID'];
	$ChecklistTypeName=$_REQUEST['ChecklistTypeName'];
	$ParameterCategoryDescription=$_REQUEST['ParameterCategoryDescription'];
	
	
	if ($ChecklistTypeID=='0')
	{
		$sql="Insert into ChecklistTypes (ChecklistTypeName,CreatedBY)
		Values('$ChecklistTypeName','$ParameterCategoryDescription','$CreatedUserID')";

	} else
	{
		$sql="Update ChecklistTypes 
		set ChecklistTypeName='$ChecklistTypeName'		
		where ChecklistTypeID=$ChecklistTypeID";
	}

	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Parameter Category Saved Successfully";			
	} else
	{
		DisplayErrors();
		$msg = "Details Failed to save";				
	}	
}

?>
<div class="example">
<legend>Checklist Types
 </legend><table class="table striped hovered dataTable" id="dataTables-1" width="100%">
    <thead>
      <tr>
        <th class="text-left"><a href="#" onClick="loadmypage('checklisttype.php?add=1','content')">Add</a></th>
        <th colspan="3" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
      </tr>
       <tr>
          <th width="10%" class="text-left">Checklist ID</th>
          <th width="80%" class="text-left">Checklist Name</th>          
          <th width="10%" class="text-left">&nbsp;</th>
       </tr>
    </thead>
  </table>
</div>
