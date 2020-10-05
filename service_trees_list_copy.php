<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';


$IsService='';
$Description='';
$ParentID='';
$ServiceID='';
$ServiceTreeID='0';

$CreatedUserID = $_SESSION['UserID'];

if (isset($_REQUEST['delete']))
{
	$ServiceTreeID = $_REQUEST['ServiceTreeID'];
	$sql = "DELETE FROM ServiceTrees WHERE ServiceTreeID = '$ServiceTreeID'";
	//echo $sql;
	$result = sqlsrv_query($db, $sql);
	if ($result)
	{
		$msg = "Record Deleted Successfully";
	} else
	{
		$msg = "Record Failed to be Deleted";
	}
}

if (isset($_REQUEST['save']))
{
/*	print_r($_REQUEST);
	exit;*/	
	$Description=$_REQUEST['Description'];
	$ParentID=$_REQUEST['ParentID'];
	$IsService=$_REQUEST['IsService'];
	$ServiceID=$_REQUEST['ServiceID'];
	if (isset($_REQUEST['ServiceTreeID'])){$ServiceTreeID=$_REQUEST['ServiceTreeID'];}
	if ($ServiceTreeID=='0')
	{		
		$sql = "INSERT INTO ServiceTrees (
			  [Description]
			  ,[ParentID]
			  ,[IsService]
			  ,[ServiceID]
			  ,CreatedBy
			) VALUES 
			(
			'$Description'
			,'$ParentID'
			,'$IsService'
			,'$ServiceID'
			,'$CreatedUserID'
			) SELECT SCOPE_IDENTITY() AS ID
			" ;

	} else
	{
		$sql = "UPDATE ServiceTrees SET
					[Description]='$Description'
					,[ParentID]='$ParentID'
					,[IsService]='$IsService'
					,[ServiceID]='$ServiceID'					
					,[CreatedBy]='$CreatedUserID'
					 where ServiceTreeID='$ServiceTreeID'";		
	}	 
	$result = sqlsrv_query($db, $sql);
	
	if(!$result){
		DisplayErrors();
		echo "<BR>";
		echo $sql;
		//redirect($_REQUEST, $msg, "service_trees.php");	
	}else
	{
		$msg = "Service Tree Saved Successfully";					
	}	
}


?>
<div class="example">
<legend>Service Trees</legend>

<table class="table striped hovered dataTable" id="dataTables-1" width="100%">
    <thead>
      <tr>
        <th class="text-left"><a href="#" onClick="loadpage('treeview.php?add=1','content')">New ServiceTree</a></th>
        <th colspan="6" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
      </tr>
       <tr>
          <th width="15%" class="text-left">Description</th>
          <th width="15%" class="text-left">Parent</th>
          <th width="10%" class="text-left">Is Service</th>
          <th width="52%" class="text-left">Service</th>
          <th width="8%" class="text-left">&nbsp;</th>
       </tr>
    </thead>
  </table>
</div>
