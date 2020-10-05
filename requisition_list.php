<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';


$ServiceName='';
$Description='';
$ServiceCategoryID='';
$DepartmentID='';
$ServiceID=$_REQUEST['ServiceID'];

$CreatedUserID = $_SESSION['UserID'];

if (isset($_REQUEST['delete']))
{
	$ServiceID = $_REQUEST['ServiceID'];
	$sql = "DELETE FROM Services WHERE ServiceID = '$ServiceID'";
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
		
	$ApplicationID=$_REQUEST['ApplicationID'];
	$CurrentStatus=$_REQUEST['CurrentStatus'];
	$NextStatus=$_REQUEST['NextStatus'];
	$Notes=$_REQUEST['Notes'];
	$DepartmentID=$_REQUEST['Department'];
	$Amount=$_REQUEST['Amount'];
	
	

	if ($NextStatus=='1')//record is new
	{
		$s_sql="Insert into RequisitionHeader (DepartmentID,ApprovalStatusID,Notes)
		Values ('$DepartmentID',1,'$Notes')";
		
		$CurrentStatus='1'; //set the current status to 1
		
		$sql="Insert into RequisitionLines (RequisitionHeaderID,Description,Amount)
		Values($RequisitionHeaderID,$Notes,$Amount)";
								
	}else//old record
	{			
		//later	 return to $NextStatusID
		$s_sql="Update RequisitionHeader set ApprovalStatusID=$NextStatus where RequisitionHeaderID=$ApplicationID";				
	}

	if ($s_result = sqlsrv_query($db, $s_sql)==false)
	{
		DisplayErrors();
		exit;
	}else
	{
		$RequisitionHeaderID='0';
		
		$s_sql="Select max(RequisitionHeaderID)RequisitionHeaderID  from RequisitionHeader";
		if (($s_result = sqlsrv_query($db, $s_sql))==false)
		{
			DisplayErrors();
			//echo $s_sql;
			//exit;
		}
		

		
		while($row = sqlsrv_fetch_array($s_result, SQLSRV_FETCH_ASSOC))
		{
			$RequisitionHeaderID = $row['RequisitionHeaderID'];			
		}
		
		
		$s_sql="Insert into RequisitionApprovalActons(RequisitionHeaderID,RequisitionStatusID,NextRequisitionStatusID,Notes,CreatedBy) 
		Values ($RequisitionHeaderID,$CurrentStatus,$NextStatus,'$Notes',$CreatedUserID)";

		if ($s_result = sqlsrv_query($db, $s_sql)==false)
		{
			DisplayErrors();
			//echo $s_sql;
			exit;
		}
		
		if ($NextStatus=='1'){	
			
			$s_sql="Insert into RequisitionLines (RequisitionHeaderID,Description,Amount)
			Values('$RequisitionHeaderID','$Notes','$Amount')";
			
			if ($s_result = sqlsrv_query($db, $s_sql)==false)
			{
				DisplayErrors();
				//echo $s_sql;
				exit;
			}
		}
		
		echo 'Record Saved Successfully!';	
	}
}


?>
<div class="example">
<legend>Cash Requisition List</legend>

<table class="table striped hovered dataTable" id="dataTables-1" width="100%">
    <thead>
      <tr>
        <th class="text-left"><a href="#" onClick="loadpage('requisition.php?add=1','content')">New Requisition</a></th>
        <th colspan="7" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
      </tr>
       <tr>
          <th width="27%" class="text-left">DepartmentName</th>
          <th width="23%" class="text-left">Requisition Date</th>
          <th width="35%" class="text-left">Notes</th>
          <th width="22%" class="text-left">Amount</th>
          <th width="22%" class="text-left">Status</th>
          <th width="4%" class="text-left">&nbsp;</th>
          <th width="4%" class="text-left">&nbsp;</th>
          <th width="4%" class="text-left">&nbsp;</th>
       </tr>
    </thead>
    <tbody>
       <tr>
          <td class="text-left">DepartmentName</td>
          <td class="text-left">Requisition Date</td>
          <td class="text-left">Notes</td>
          <td class="text-left">Amount</td>
          <td class="text-left">Status</td>
          <td class="text-left">&nbsp;</td>
          <td class="text-left">&nbsp;</td>
          <td class="text-center">&nbsp;</td>
       </tr>
    </tbody>
  </table>
</div>
