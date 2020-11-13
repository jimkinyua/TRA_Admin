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
	$ServiceCategoryID=$_REQUEST['ServiceCategoryID'];
	$sql="Delete from ServiceCategory where ServiceCategoryID=$ServiceCategoryID";
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "ServiceCategory Deleted Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Action failed";			
	}	
}

if (isset($_REQUEST['save']))
{	

	$ServiceCategoryID=$_REQUEST['ServiceCategoryID'];
	$CategoryName=$_REQUEST['CategoryName'];	
	$FormID=$_REQUEST['FormID'];
	$ServiceGroupID=$_REQUEST['ServiceGroupID'];
	$ServiceCode=$_REQUEST['ServiceCode'];
	$Description=$_REQUEST['Description'];
	$PrimaryService=$_REQUEST['PrimaryService'];
	$InvoiceStageID=$_REQUEST['InvoiceStageID'];
	$LastStageID=$_REQUEST['LastStageID'];
	
	
	if ($ServiceCategoryID=='0')
	{
		$sql="Insert into ServiceCategory (CategoryName,ServiceGroupID,ServiceCode,FormID,Description,PrimaryService,InvoiceStage,LastStage,CreatedBY)
		Values('$CategoryName','$ServiceGroupID','$ServiceCode','$FormID','$Description','$PrimaryService',$InvoiceStageID,$LastStageID,'$CreatedUserID')";

	} else
	{
		$sql="Update ServiceCategory set CategoryName='$CategoryName',ServiceGroupID=$ServiceGroupID,ServiceCode='$ServiceCode',Description='$Description',FormID=$FormID,PrimaryService=$PrimaryService,InvoiceStage=$InvoiceStageID,LastStage=$LastStageID where ServiceCategoryID=$ServiceCategoryID";
	}

	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{
//********************About the workflow*************************//
		$sql="select step,servicestatusid,servicecategoryid,CreatedBy from serviceapprovalsteps where servicecategoryid='$ServiceCategoryID'";
		$result = sqlsrv_query($db, $sql);
		$has_rows=sqlsrv_has_rows($result);
		
		IF ($has_rows==false){				
			for($i=1;$i<6;$i++)
			{
				$sql="Insert into serviceapprovalsteps(step,servicestatusid,servicecategoryid,CreatedBy)
				values($i,$i,$ServiceCategoryID,'$CreatedUserID')";
				//echo '$i'.$i;
				$result=sqlsrv_query($db,$sql);				
				if ($result)
				{
					$msg='Workflow done!';
				}else
				{
					DisplayErrors();
					echo $sql;
					die;
				}
			}			
			$sql="select ServiceApprovalStepID from serviceapprovalsteps where servicecategoryid='$ServiceCategoryID'";
			$result = sqlsrv_query($db, $sql);
			
			$i=2;
			while($rows=sqlsrv_fetch_array($result))
			{					
				$SASID=$rows['ServiceApprovalStepID'];			

				 $sql1="Insert into ServiceAlternateSteps(ServiceApprovalStepid,ServiceStatusID)
				values($SASID,$i)";	
				
				$sql2="Insert into ServiceAlternateSteps(ServiceApprovalStepid,ServiceStatusID)
				values($SASID,6)";
			
				$sql3="Insert into ServiceAlternateSteps(ServiceApprovalStepid,ServiceStatusID)
				values($SASID,8)";																
				
				$result1=sqlsrv_query($db,$sql1);
				$result2=sqlsrv_query($db,$sql2);	
				$result3=sqlsrv_query($db,$sql3);	
				
				$i+=1;	
				
				if ($i>5)
				{
					break;
				}			
			}		
		}	
//*****************		
		$msg = "Service Category Saved Successfully";			
	} else
	{
		DisplayErrors();
		$msg = "Details Failed to save";				
	}	
}

?>
<div class="example">
<legend>Sevice Category
 </legend><table class="table striped hovered dataTable" id="dataTables-1" width="100%">
    <thead>
      <tr>
        <th class="text-left"><a href="#" onClick="loadmypage('servicecategory.php?add=1','content')">Add</a></th>
        <th class="text-left"><a href="#" onClick="loadmypage('documents.php?i=1','content','loader','listpages','','ServiceCategories')">Document</a></th>
        <th colspan="3" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
      </tr>
       <tr>
          <th width="10%" class="text-left">Service Code</th>
          <th width="50%" class="text-left">Category Name</th>
          <th width="30%" class="text-left">Service Group</th>
          <th width="3%" class="text-left">&nbsp;</th>
       </tr>
    </thead>
  </table>
</div>
