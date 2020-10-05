<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];

$ServiceCategoryID = $_REQUEST['ServiceCategoryID'];
$RequirementID=0;

$sql="select CategoryName from ServiceCategory where ServiceCategoryID=$ServiceCategoryID";

$result=sqlsrv_query($db,$sql);
while ($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {
	$CategoryName=$row['CategoryName'];
}

if (isset($_REQUEST['delete']))
{
	$RequirementID=$_REQUEST['RequirementID'];
	$sql="Delete from RequiredDocuments where RequirementID=$RequirementID";
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Requirement Deleted Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Action failed";			
	}	
}

if (isset($_REQUEST['save']))
{
	$RequirementID=$_REQUEST['RequirementID'];
	$ServiceCategoryID=$_REQUEST['ServiceCategoryID'];
	$DocumentID=$_REQUEST['DocumentID'];
	
	if ($RequirementID=='0' || $RequirementID='')
	{
		$sql="if not exists(select * from RequiredDocuments where ServiceCategoryID='$ServiceCategoryID' 
		and DocumentID='$DocumentID') Insert into RequiredDocuments (ServiceCategoryID,DocumentID,CreatedBY)
		Values('$ServiceCategoryID',$DocumentID,$CreatedUserID)";

	} else
	{
		$sql="Update RequiredDocuments set ServiceCategoryID=$ServiceCategoryID,DocumentID='$DocumentID' where RequirementID=$RequirementID";
	}	
	$result = sqlsrv_query($db, $sql);

	if ($result)
	{	
		$msg = "Requirement Saved Successfully";			
	} else
	{
		DisplayErrors();
		//echo '<br>'. $sql;
		$msg = "Details Failed to save";				
	}	
}
?>
<div class="example">
<legend>Required Attachments for <?= $CategoryName; ?>
 </legend><table class="table striped hovered dataTable" id="dataTables-1" width="100%">
    <thead>
      <tr>
        <th class="text-left"><a href="#" onClick="loadmypage('required_document.php?add=1&ServiceCategoryID=<?= $ServiceCategoryID ?>','content')">Add</a></th>
      </tr>
      <tr>        
        <th class="text-center" style="color:#F00"><?php echo $msg; ?></th>
      </tr>
       <tr>
		  <th width="90%" class="text-left">Attachment</th>
		  <th width="10%" class="text-left">&nbsp;</th>
       </tr>
    </thead>
  </table>
</div>
