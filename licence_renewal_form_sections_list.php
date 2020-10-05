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

$sql="select FormName from LicenenceRenewalForm where FormID=$FormID";
$result=sqlsrv_query($db,$sql);
$rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
if ($result)
{
	$FormName=$rw['FormName'];
}

if (isset($_REQUEST['delete']))
{
	$FormSectionID=$_REQUEST['FormSectionID'];
	$sql="Delete from FormSections where FormSectionID=$FormSectionID";
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "FormSection Deleted Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Action failed";			
	}	
}

if (isset($_REQUEST['save']))
{	

	$FormSectionID=$_REQUEST['FormSectionID'];
	$FormSectionName=$_REQUEST['FormSectionName'];
		
	
	if ($FormSectionID=='0')
	{
		$TodayDate = date('Y-M-D');
		$sql="Insert into LicenceRenewalFormSection (FormSectionName,LicenceRenewalFormID,CreatedDate,CreatedBY)
		Values('$FormSectionName','$FormID','$TodayDate','$CreatedUserID')";
		// ECHO '<PRE>';
		// print_r($sql);
		// exit;


	} else
	{
		$sql="Update LicenceRenewalFormSection set FormSectionName='$FormSectionName',LicenceRenewalFormID=$FormID where FormSectionID=$FormSectionID";
	}	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Licence Renewal Form Section Added Successfully";			
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
        <legend>FormSections for [<?php echo $FormName ?>]</legend>
        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th class="text-left"><a href="#" onClick="loadmypage('licence_renewal_form_section_card.php?i=1','content')">Add</a></th>
					<th class="text-left"><a href="#" onClick="<?php echo $historyString; ?>">Back</a></th>
                  </tr>
                <tr>
                    <th width="70%" class="text-left">Form Sections</th>
					<th width="10%" class="text-left">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>


</div>
</div>