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
$FormName='';
$historyString=$_REQUEST['historyString'];
//echo $historyString;
$sql="select FormName from LicenenceRenewalForm where FormID=$FormID";
$result=sqlsrv_query($db,$sql);
$rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
// echo '<pre>';
// print_r($result);
// echo '</pre>';
// echo '<pre>';
// print_r($sql);
// exit;

if ($result)
{
	$FormName=$rw['FormName'];
}

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
		//DisplayErrors();		
		$msg = "Action failed";			
	}	
}

if (isset($_REQUEST['save']))
{
	
	$FormColumnID=$_REQUEST['FormColumnID'];
	$FormSectionID=$_REQUEST['FormSectionID'];
	$FormColumnName= str_replace("'","",$_REQUEST['FormColumnName']); //addslashes($_REQUEST['FormColumnName']);
	$ColumnDataTypeID=$_REQUEST['ColumnDataTypeID'];
	$ColumnSize=$_REQUEST['ColumnSize'];
	$Priority=$_REQUEST['Priority'];
	$Mandatory=$_REQUEST['Mandatory'];
	$FilterColumID=$_REQUEST['FilterColumnID'];
	$Notes=urldecode($_REQUEST['Notes']);
	$TodayDate = date();
	if(isset($Mandatory)){
		$ShowPublic = 1;
	}
	
	// $unpacked = unpack('H*hex', $data);
	// echo '<pre>';
	// print_r($Notes);
	// exit;
	

	//echo urldecode($Notes); exit;
	
	$Notes=str_replace("'","''",$Notes);
	
		
	$Mandatory=$Mandatory='true'?1:0;	

	if ($FormColumnID=='0')
	{
		$sql="Insert into LicenceRenewalColumns (LicenceRenewalFormID,FormColumnName,FormSectionID,ColumnDataTypeID,ColumnSize,Priority,Notes,Mandatory,FilterColumnID,CreatedBY, CreatedDate, ShowPublic)
		Values('$FormID','$FormColumnName','$FormSectionID','$ColumnDataTypeID','$ColumnSize','$Priority','$Notes','$Mandatory','$FilterColumnID','$CreatedUserID', '$TodayDate', '$ShowPublic')";	
		// exit($sql);
	} else
	{
		$sql="Update LicenceRenewalColumns set FormColumnName='$FormColumnName',FormSectionID='$FormSectionID',ColumnDataTypeID='$ColumnDataTypeID',ColumnSize='$ColumnSize',FormID='$FormID',Priority='$Priority',Notes='$Notes',Mandatory='$Mandatory',FilterColumnID='$FilterColumID' where LicenceRenewalFormColumnID='$FormColumnID'";
	}
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Form Column Saved Successfully";			
	} else
	{
		DisplayErrors();
		//echo '<br>'.$sql;
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
        <legend>Columns for [<?php echo $FormName ?>]</legend>
        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th class="text-left"><a href="#" onClick="loadmypage('licence_renewal_form_column_card.php?FormID=<?php echo $FormID ?>','content')">Add</a></th>
                    <th colspan="3" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
					<th class="text-left"><a href="#" onClick="<?php echo $historyString; ?>">Back</a></th>
                  </tr>
                <tr>
					<th width="10%" class="text-left">Coumn ID</th>
                    <th width="25%" class="text-left">Form Coumn</th>
                    <th width="25%" class="text-left">Form Section</th>
                    <th width="10%" class="text-left">Column DataType</th>
                    <th width="10%" class="text-left">Column Size</th>
                    <th width="10%" class="text-left">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>
</div>
</div>