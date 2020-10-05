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
	echo 'hi';
	$PageID=$_REQUEST['PageID'];
	$sql="Delete from Pages where PageID=$PageID";
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Page Deleted Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Action failed";			
	}
	
}else if (isset($_REQUEST['save']))
{	
	$PageID=$_REQUEST['PageID'];
	$PageName=$_REQUEST['PageName'];
	$PageGroupID=$_REQUEST['PageGroupID'];
	$MenuGroupID=$_REQUEST['MenuGroupID'];	
	$ApproverOne=$_REQUEST['ApproverOne'];	
	$ApproverTwo=$_REQUEST['ApproverTwo'];	
	$ApproverThree=$_REQUEST['ApproverThree'];	

	// print_r($_REQUEST);
	// exit;
	
	if ($PageID=='0')
	{
		$sql="Insert into Pages (PageName,PageGroupID,MenuGroupID,ApproverOne,ApproverTwo,ApproverThree,CreatedBY)
		Values('$PageName',$PageGroupID,$MenuGroupID,$ApproverOne,$ApproverTwo,$ApproverThree,$CreatedUserID)";
	} else
	{
		$sql="Update Pages set 	PageName='$PageName',PageGroupID='$PageGroupID',MenuGroupID=$MenuGroupID,ApproverOne='$ApproverOne',ApproverTwo=$ApproverTwo,ApproverThree=$ApproverThree 
			where PageID=$PageID";
	}	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Page Saved Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Details Failed to save";
		echo '<br>'.$sql;
			
	}	
}
?>
    <link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/prettify/prettify.css" rel="stylesheet">
<body class="metro">
        <div class="example">
        <legend>Pages</legend>        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th class="text-left"><a href="#" onClick="loadmypage('pages.php?i=1','content')">Add</a></th>
                    <th colspan="5" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>
                <tr>
                    <th width="5%" class="text-left">PageID</th>
                    <th width="35%" class="text-left">Page Name</th>
                    <th width="35%" class="text-left">Menu Group</th>
                    <th width="35%" class="text-left">First Approver</th>
                    <th width="35%" class="text-left">Second Approver</th>
                    <th width="35%" class="text-left">Third Approver</th>
                    <th width="25%" class="text-left">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>


</div>
</div>