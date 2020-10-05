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
	$ParameterID=$_REQUEST['ParameterID'];
	$sql="Delete from ChecklistParameters where ParameterID=$ParameterID";
	
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

	$ParameterID=$_REQUEST['ParameterID'];
	$ParameterName=$_REQUEST['ParameterName'];
	$ParameterCategoryID=$_REQUEST['ParameterCategoryID'];
	$ChecklistTypeID=$_REQUEST['ChecklistTypeID'];
	$ParameterScore=$_REQUEST['ParameterScore'];

	//print_r($_REQUEST); 
	
	
	if ($ParameterID=='0')
	{
		$sql="Insert into ChecklistParameters (ParameterName,ParameterCategoryID,ParameterScore,CreatedBY,ChecklistTypeID)
		Values('$ParameterName','$ParameterCategoryID','$ParameterScore','$CreatedUserID','$ChecklistTypeID')";

	} else
	{
		$sql="Update ChecklistParameters 
		set ParameterName='$ParameterName'
		,ParameterCategoryID='$ParameterCategoryID'
		,ParameterScore='$ParameterScore'
		,ChecklistTypeID=$ChecklistTypeID
		where ParameterID=$ParameterID";
	}
	//echo $sql;
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Parameter Saved Successfully";			
	} else
	{
		DisplayErrors();
		$msg = "Details Failed to save";				
	}	
}

?>
<div class="example">
<legend>Checklist Parameters
 </legend><table class="table striped hovered dataTable" id="dataTables-1" width="100%">
    <thead>
      <tr>
        <th class="text-left"><a href="#" onClick="loadmypage('checklistparameter.php?add=1','content')">Add</a></th>
        <th colspan="4" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
      </tr>
       <tr>
          <th width="10%" class="text-left">Parameter ID</th>
          <th width="30%" class="text-left">Parameter Name</th>
          <th width="20%" class="text-left">Parameter Category</th>
          <th width="20%" class="text-left">Checklist Type</th>
          <th width="10%" class="text-left">Parameter Scrore</th>
          <th width="10%" class="text-left">&nbsp;</th>
       </tr>
    </thead>
  </table>
</div>
