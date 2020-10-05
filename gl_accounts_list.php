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
	
	$GlAccountID=$_REQUEST['GlAccountID'];
	$sql="Delete from GlAccounts where GlAccountID=$GlAccountID";
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "GL Account Deleted Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Action failed";			
	}
	
}else if (isset($_REQUEST['save']))
{	
	$GlAccountID=$_REQUEST['GlAccountID'];
	$GlAccountNo=$_REQUEST['GlAccountNo'];
	$GlAccountName=$_REQUEST['GlAccountName'];
	
	if ($GlAccountID=='0')
	{
		$sql="Insert into GLAccounts (GlAccountNo,GLAccountName,CreatedBY)
		Values('$GlAccountNo','$GlAccountName',$CreatedUserID)";

	} else
	{
		$sql="Update GlAccounts set GlAccountName='$GlAccountName' where GlAccountID=$GlAccountID";
	}	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "GlAccount Saved Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Details Failed to save";
		
			
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
        <legend>Gl Accounts</legend>        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th class="text-left"><a href="#" onClick="loadmypage('gl_accounts_setup.php?i=1','content')">Add</a></th>
                    <th colspan="5" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>
                <tr>
                    <th width="5%" class="text-left">AccNo</th>
                    <th width="85%" class="text-left">Account Name</th>
                    <th width="25%" class="text-left">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>


</div>
</div>