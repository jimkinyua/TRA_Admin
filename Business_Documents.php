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
	$DocTypeID=$_REQUEST['DocTypeID'];
	$sql="Delete from BusinessRegistrationDocumentTypes where DocTypeID=$DocTypeID";
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Document Deleted Successfully";			
	} else
	{
		DisplayErrors();
		$msg = "Action failed";			
	}	
}

if (isset($_REQUEST['save']))
{	

	$FormID=$_REQUEST['FormID'];
	$DocumentName=$_REQUEST['DocumentName'];
    $Mandatory=$_REQUEST['Mandatory'];
    
    // ECHO '<PRE>';
    // print_r($_REQUEST);
    // exit;

	
	if ($FormID=='0')
	{
		$sql="Insert into BusinessRegistrationDocumentTypes (DocumentName,Mandatory,CreatedBY)
		Values('$DocumentName','$Mandatory','$CreatedUserID')";

	} else
	{
		$sql="Update BusinessRegistrationDocumentTypes set DocumentName='$DocumentName',Mandatory='$Mandatory' where FormID='$FormID'";
	}	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Document Saved Successfully";			
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
        <legend>Business Registration Documents </legend>
        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th class="text-left"><a href="#" onClick="loadmypage('BusinessRegistrationDocument.php?i=1','content')">Add Document</a></th>
                    <th colspan="3" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>
                <tr>
                    <th width="10%" class="text-left">Documnet ID</th>
                    <th width="70%" class="text-left">Documnet Name</th>
                    <th width="20%" class="text-left">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>


</div>
</div>