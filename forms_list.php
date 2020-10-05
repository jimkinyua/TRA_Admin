<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];

// echo '<pre>';
// print_r($_REQUEST);
// exxit;


if (isset($_REQUEST['delete']))
{
	$FormID=$_REQUEST['FormID'];
	$sql="Delete from Forms where FormID=$FormID";
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Form Deleted Successfully";			
	} else
	{
		DisplayErrors();
		$msg = "Action failed";			
	}	
}

if (isset($_REQUEST['save']))
{	

	$FormID=$_REQUEST['FormID'];
	$FormName=$_REQUEST['FormName'];
	$ServiceHeaderType=$_REQUEST['ServiceHeaderType'];
	
	if ($FormID=='0')
	{
		$sql="Insert into Forms (FormName,ServiceHeaderType,CreatedBY)
		Values('$FormName','$ServiceHeaderType','$CreatedUserID')";

	} else
	{
		$sql="Update Forms set FormName='$FormName',ServiceHeaderType='$ServiceHeaderType' where FormID='$FormID'";
	}	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Form Saved Successfully";			
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
        <legend>Forms </legend>
        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th class="text-left"><a href="#" onClick="loadmypage('form.php?i=1','content')">Add</a></th>
                    <th colspan="3" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>
                <tr>
                    <th width="10%" class="text-left">FormID</th>
                    <th width="70%" class="text-left">Form Name</th>
                    <th width="20%" class="text-left">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>


</div>
</div>