<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];
$FormID=$_REQUEST['FormID'];
$historyString=$_REQUEST['historyString'];
$FormName='';

$sql="select FormName from Forms where FormID=$FormID";
$result=sqlsrv_query($db,$sql);
$rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
if ($result)
{
	$FormName=$rw['FormName'];
}

if (isset($_REQUEST['delete']))
{
	$MiscellaneousServiceID=$_REQUEST['MiscellaneousServiceID'];
	$sql="Delete from MiscellaneousServices where MiscellaneousServiceID=$MiscellaneousServiceID";
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "MiscellaneousService Deleted Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Action failed";			
	}	
}

if (isset($_REQUEST['save']))
{	

	$MiscellaneousServiceID=$_REQUEST['MiscellaneousServiceID'];
	$MiscellaneousServiceName=$_REQUEST['MiscellaneousServiceName'];
		
	
	if ($MiscellaneousServiceID=='0')
	{
		$sql="Insert into MiscellaneousServices (MiscellaneousServiceName,FormID,CreatedBY)
		Values('$MiscellaneousServiceName',$FormID,$CreatedUserID)";

	} else
	{
		$sql="Update MiscellaneousServices set MiscellaneousServiceName='$MiscellaneousServiceName',FormID=$FormID where MiscellaneousServiceID=$MiscellaneousServiceID";
	}	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Form Section Saved Successfully";			
	} else
	{
		DisplayErrors();
		$msg = $sql;//"Details Failed to save";
				
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
        <legend>MiscellaneousServices for [<?php echo $FormName ?>]</legend>
        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th class="text-left"><a href="#" onClick="loadmypage('form_sections.php?i=1','content')">Add</a></th>
					<th class="text-left"><a href="#" onClick="<?php echo $historyString; ?>">Back</a></th>
                  </tr>
                <tr>
                    <th width="70%" class="text-left">Form Section</th>
					<th width="10%" class="text-left">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>


</div>
</div>