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
	$DeviceID = $_REQUEST['DeviceID'];
	$sql = "DELETE FROM Devices WHERE DeviceID = '$DeviceID'";
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
	$SerialNo=$_REQUEST['SerialNo'];
	$Description=$_REQUEST['Description'];
	$MacAddress=$_REQUEST['MacAddress'];
	$DeviceTypeID=$_REQUEST['DeviceTypeID'];
	$DeviceID=$_REQUEST['DeviceID'];
	
	if ($DeviceID=='0')
	{
		echo 'No DeviceID';
		$sql = "SELECT * FROM Devices 
				WHERE DeviceSerialNo = '$SerialNo'";
		$result = sqlsrv_query($db, $sql);
		if ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
		{
	   		$msg = "The Serial Number you entered already exists";
			redirect($_REQUEST, $msg, "device.php");
		}
		
		$sql = "SELECT * FROM Devices 
				WHERE MacAddress = '$MacAddress'";
		$result = sqlsrv_query($db, $sql);
		if ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
		{
	   		$msg = "The Mac Address you entered already used";
			redirect($_REQUEST, $msg, "device.php");
		}		
		
		//else part	
		$GeneratedPassword = generatePassword();
		$Password = md5($GeneratedPassword);
		$sql = "INSERT INTO Devices (
			  [Description]
			  ,[DeviceSerialNo]
			  ,[MacAddress]
			  ,[CreatedBy]
			  ,[DeviceTypeID]
			) VALUES 
			(
			'$Description'
			,'$SerialNo'
			,'$MacAddress'
			,'$CreatedUserID'
			,'$DeviceTypeID'
				) SELECT SCOPE_IDENTITY() AS ID
			" ;

	} else
	{
		echo 'Update DeviceID';
		$sql = "UPDATE Devices SET
					[Description]='$Description'
					,[DeviceSerialNo]='$SerialNo'
					,[MacAddress]='$MacAddress'
					,[CreatedBy]='$CreatedUserID'
					,[DeviceTypeID]='$DeviceTypeID' where DeviceID='$DeviceID'";		
	}	

	$result = sqlsrv_query($db, $sql);
	
	if(!$result){
		DisplayErrors();
		echo "<BR>";
		}
	
	if ($result)
	{
		if ($UserID=='') 
		{
			$UserID = lastId($result);
			$msg = "Saved Details Successfully";
		} else
		{
			$msg = "Saved Details Successfully";	
		}		
	} else
	{
		$msg = "Details Failed to save";
		redirect($_REQUEST, $msg, "Devices.php");				
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
        <legend>Users</legend>
        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th class="text-left"><a href="#" onClick="loadpage('device.php?add=1','content')">New</a></th>
                    <th colspan="7" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>
                <tr>
                    <th width="14%" class="text-left">SerialNo</th>
                    <th width="12%" class="text-left">Description</th>
                    <th width="20%" class="text-left">MacAddress</th>
                    <th width="12%" class="text-left">DeviceType</th>
                    <th width="12%" class="text-left">Status</th>
                    <th width="12%" class="text-left">&nbsp;</th>
                    <th width="12%" class="text-left">&nbsp;</th>


                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>


</div>
</div>