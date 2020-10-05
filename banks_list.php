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
	$BankID=$_REQUEST['BankID'];
	$sql="Delete from Banks where BankID=$BankID";
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Bank Deleted Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Action failed";			
	}	
}

if (isset($_REQUEST['save']))
{
	$BankID=$_REQUEST['BankID'];
	$BankName=$_REQUEST['BankName'];	
	$AccountNumber=$_REQUEST['AccountNumber'];
	$Branch=$_REQUEST['Branch'];
	$ShowPublic=$_REQUEST['ShowPublic'];
	
	if ($BankID=='0')
	{
		$sql="Insert into Banks (BankName,AccountNumber,Branch,CreatedBy,ShowPublic)
		Values('$BankName',$AccountNumber,'$Branch','$CreatedUserID','$ShowPublic')";

	} else
	{
		$sql="Update Banks set BankName='$BankName',AccountNumber='$AccountNumber',Branch='$Branch',ShowPublic='$ShowPublic' where BankID=$BankID";
	}	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Banks Saved Successfully";			
	} else
	{
		DisplayErrors();
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
        <legend>Banks</legend>        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
				  <tr>
					<th class="text-left"><a href="#" onClick="loadmypage('bank.php?i=1','content')">Add</a></th>
					<th colspan="5" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
				  </tr>
                <tr>
                    <th width="35%" class="text-left">Bank Name</th>
                    <th width="25%" class="text-left">Account Number</th>
					<th width="25%" class="text-left">Branch</th>
                    <th width="12%" class="text-left">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table> 
		</div>
</div>


