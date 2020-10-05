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
	$ParameterCategoryID=$_REQUEST['ParameterCategoryID'];
	$sql="Delete from ChecklistParameterCategories where ParameterCategoryID=$ParameterCategoryID";
	
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

	$ParameterCategoryID=$_REQUEST['ParameterCategoryID'];
	$ParameterCategoryName=$_REQUEST['ParameterCategoryName'];
	$ParameterCategoryDescription=$_REQUEST['ParameterCategoryDescription'];
	
	
	if ($ParameterCategoryID=='0')
	{
		$sql="Insert into ChecklistParameterCategories (ParameterCategoryName,ParameterCategoryDescription,CreatedBY)
		Values('$ParameterCategoryName','$ParameterCategoryDescription','$CreatedUserID')";

	} else
	{
		$sql="Update ChecklistParameterCategories 
		set ParameterCategoryName='$ParameterCategoryName'
		,ParameterCategoryDescription='$ParameterCategoryDescription'
		where ParameterCategoryID=$ParameterCategoryID";
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
<legend>Checklist Parameter Category
 </legend><table class="table striped hovered dataTable" id="dataTables-1" width="100%">
    <thead>
      <tr>
        <th class="text-left"><a href="#" onClick="loadmypage('checklistparameterCategory.php?add=1','content')">Add</a></th>
        <th colspan="3" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
      </tr>
       <tr>
          <th width="10%" class="text-left">Parameter ID</th>
          <th width="40%" class="text-left">Category Name</th>
          <th width="40%" class="text-left">Category Description</th>
          <th width="10%" class="text-left">&nbsp;</th>
       </tr>
    </thead>
  </table>
</div>
