<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';


$PageID=8;
$myRights=getrights($db,$UserID,$PageID);
if ($myRights)
{
	
	$View=$myRights['View'];
	$Edit=$myRights['Edit'];
	$Add=$myRights['Add'];
	$Delete=$myRights['Delete'];
}

$ServiceName='';
$Description='';
$ServiceCategoryID='';
$DepartmentID='';
$ServiceID=$_REQUEST['ServiceID'];
$historyString='';

$CreatedUserID = $_SESSION['UserID'];
$historyString="loadmypage('services_list.php?i=1','content','loader','listpages','','services')";
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
	$Description=$_REQUEST['Description'];
	$ServiceCategoryID=$_REQUEST['ServiceCategoryID'];
	$Chargeable=$_REQUEST['Chargeable'];
	$ServiceName=$_REQUEST['ServiceName'];
	$ServiceCode=$_REQUEST['ServiceCode'];
	$GlAccountNo=$_REQUEST['GlAccountNo'];
	$RevenueStreamID=$_REQUEST['RevenueStreamID'];
	
	if ($ServiceID=='0')
	{		
		$sql = "INSERT INTO Services (
			  [ServiceName]
			  ,[ServiceCode]
			  ,[Description]
			  ,[ServiceCategoryID]
			  ,[RevenueStreamID]
			  ,[GlAccountNo]
			  ,[Chargeable]
			  ,CreatedBy
			) VALUES 
			(  
			'$ServiceName'
			,'$ServiceCode'
			,'$Description'
			,'$ServiceCategoryID'
			,'$RevenueStreamID'
			,'$GlAccountNo'
			,'$Chargeable'
			,'$CreatedUserID'
				) SELECT SCOPE_IDENTITY() AS ID
			" ;

	} else
	{
		$sql = "UPDATE Services SET
					[Description]='$Description'
					,[ServiceName]='$ServiceName'
					,[ServiceCode]='$ServiceCode'
					,[ServiceCategoryID]='$ServiceCategoryID'
					,RevenueStreamID='$RevenueStreamID'
					,GlAccountNo='$GlAccountNo'
					,[Chargeable]='$Chargeable'
					,[CreatedBy]='$CreatedUserID'
					 where ServiceID='$ServiceID'";		
	}	 
	$result = sqlsrv_query($db, $sql);
	
	if(!$result){
		DisplayErrors();
		echo "<BR>";
		echo $sql;
		echo "<BR>";
	}
	
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
				values($i,$i,$ServiceCategoryID,$CreatedUserID)";
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
		
		//********************End of workflow*****************************
		
	} else
	{
		DisplayErrors();
		$msg = "Details Failed to save";
		//redirect($_REQUEST, $msg, "services.php");				
	}	
}


?>
<div class="example">
<legend>County Services </legend>

<table class="table striped hovered dataTable" id="tableToolsTable" width="100%">
    <thead>
      <tr>
        <th class="text-left"><a href="#" onClick="loadpage('services_r.php?add=1','content')">New Service</a></th>
        <th colspan="4" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
      </tr>
       <tr>
          <th width="10%" class="text-left">Service ID</th>
		  <th width="10%" class="text-left">Service Code</th>
          <th width="40%" class="text-left">Service Name</th>
          <th width="20%" class="text-left">Revenue Stream</th>
          <th width="20%" class="text-left">&nbsp;</th>
       </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
</div>
