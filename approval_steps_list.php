<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];
$ServiceCategoryID=$_REQUEST['ServiceCategoryID'];
$historyString=$_REQUEST['historyString'];

$sql="select * from ServiceCategory where ServiceCategoryID=$ServiceCategoryID";
$result = sqlsrv_query($db, $sql);
$row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
if($row)
{
	//echo 'Yes';
	$CategoryName=$row['CategoryName'];
}else
{
	//echo 'No';
	}
$ServiceApprovalStepID=0;

if (isset($_REQUEST['delete']))
{
	
	$FormColumnID=$_REQUEST['FormColumnID'];
	$sql="Delete from FormColumns where FormColumnID=$FormColumnID";
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "FormColumn Deleted Successfully";			
	} else
	{
		DisplayErrors();		
		$msg = "Action failed";			
	}	
}

if (isset($_REQUEST['save']))
{	

	$ServiceApprovalStepID=$_REQUEST['ServiceApprovalStepID'];
	$ServiceCategoryID=$_REQUEST['ServiceCategoryID'];
	$Step=$_REQUEST['Step'];
	$ServiceStatusID=$_REQUEST['ServiceStatusID'];
	
	if ($ServiceApprovalStepID=='0')
	{
		$sql="Insert into ServiceApprovalSteps (Step,ServiceStatusID,ServiceCategoryID,CreatedBY)
		Values($Step,$ServiceStatusID,$ServiceCategoryID,$CreatedUserID)";
		

	} else
	{
		$sql="Update ServiceApprovalSteps set Step='$Step',ServiceStatusID=$ServiceStatusID,CreatedBy=$CreatedUserID where ServiceApprovalStepID=$ServiceApprovalStepID";
	}	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Form Column Saved Successfully";			
	} else
	{
		//DisplayErrors();
		//echo '<br>'.$sql;
		$msg = "Details Failed to save";
				
	}	
}
?>
    <link href="file:///C|/inetpub/wwwroot/testmail/css/metro-bootstrap.css" rel="stylesheet">
    <link href="file:///C|/inetpub/wwwroot/testmail/css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="file:///C|/inetpub/wwwroot/testmail/css/iconFont.css" rel="stylesheet">
    <link href="file:///C|/inetpub/wwwroot/testmail/css/docs.css" rel="stylesheet">
    <link href="file:///C|/inetpub/wwwroot/testmail/js/prettify/prettify.css" rel="stylesheet">
<body class="metro">
        <div class="example">
        <legend>Approval Workflow for <?php echo $CategoryName; ?></legend>
        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th class="text-left"><a href="#" onClick="loadmypage('form_column.php?FormID=<?php echo $FormID ?>','content')">Add</a></th>
                    <th class="text-center" style="color:#F00"><?php echo $msg; ?></th>
					<th class="text-left"><a href="#" onClick="<?php echo $historyString; ?>">Add</a></th>
                  </tr>
                <tr>
                    <th width="25%" class="text-left">Step No</th>
                    <th width="25%" class="text-left">Status</th>
                    <th width="10%" class="text-left">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>
</div>
</div>