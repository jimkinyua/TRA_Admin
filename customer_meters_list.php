<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];


$UserDeviceID=0;
$custSql="";
$MarketID="";
$DeviceUserStatusID="";

$CustomerName=$_REQUEST['CustomerName'];
$CustomerID=$_REQUEST['CustomerID'];

if (isset($_REQUEST['delete']))
{
	$DeviceID = $_REQUEST['DeviceID'];
	$sql = "DELETE FROM Devices WHERE DeviceID = '$DeviceID'";
	
	$result = sqlsrv_query($db, $sql);
	if ($result)
	{
		$msg = "Record Deleted Successfully";
	} else
	{
		$msg = "Record Failed to be Deleted";
	}
}

if (isset($_REQUEST['return']))
{
	$UserDeviceID=$_REQUEST['UserDeviceID'];
	$sql="UPDATE UserDevices set DeviceUserStatusID=2 where UserDeviceID=$UserDeviceID";
}else if(isset($_REQUEST['block']))
{
	$UserDeviceID=$_REQUEST['UserDeviceID'];
	$sql="UPDATE UserDevices set DeviceUserStatusID=3 where UserDeviceID=$UserDeviceID";	
}else if(isset($_REQUEST['unblock']))
{
	$UserDeviceID=$_REQUEST['UserDeviceID'];
	$sql="UPDATE UserDevices set DeviceUserStatusID=1 where UserDeviceID=$UserDeviceID";	
}

if($UserDeviceID!=0)
{
	$result=sqlsrv_query($db,$sql);
	if ($result){
		$msg="Action Completed Successfully";
	}
}


if (isset($_REQUEST['save']))
{	
	$SerialNo=$_REQUEST['SerialNo'];
	$CustomerID=$_REQUEST['CustomerID'];
	$InitialMeterReading=$_REQUEST['InitialMeterReading'];
	$UserDeviceID=$_REQUEST['UserDeviceID'];

	//print_r($_REQUEST); exit;
	
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	
	if ($UserDeviceID=='0')
	{
		$sql = "SELECT * FROM UserDevices WHERE DeviceSerialNo = '$SerialNo' and CustomerID <>$CustomerID";
		$result = sqlsrv_query($db, $sql,$params,$options);
		
		if($result){

			$rws=sqlsrv_num_rows($result);
		
			if($rws>0)
			{	
				$msg = "The Device is already issued to someone else";
				goto Endd;
			}
			
			$sql = "INSERT INTO UserDevices ([DeviceSerialNo],[CustomerID],[CreatedBy],InitialMeterReading) 
				    VALUES('$SerialNo','$CustomerID','$CreatedUserID','$InitialMeterReading') 
				    SELECT SCOPE_IDENTITY() AS ID" ;						
		
		}else
		{
			$msg="Error in database query";
		}			
	}else{
		$sql="update UserDevices set DeviceSerialNo='$SerialNo',CustomerID=$CustomerID,InitialMeterReading=$InitialMeterReading where UserDeviceID=$UserDeviceID";
	}

Endd:
	
	if ($msg=='')
	{
		

		$result = sqlsrv_query($db, $sql);
		
		if(!$result){
			$msg =$sql;// "Details Failed to save";
			DisplayErrors();
			
		}else
		{	
			if ($custSql!="")
			{
				$result = sqlsrv_query($db, $custSql);
				if(!$result)
				{
					//DisplayErrors();
					$msg = "Device Issue Failed to Save";				
					
				}else
				{			
					$result = sqlsrv_query($db, $custID);
					if(!$result)
					{
						DisplayErrors();
						$msg ="Details 3 Failed to save";
						
					}		
				}
			}
			else
			{
				DisplayErrors();	
			}
		}
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
        <legend><?php echo $CustomerName; ?></legend>
        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th class="text-left"><a href="#" onClick="loadpage('customer_meter.php?add=1&CustomerID=<?php echo $CustomerID; ?>','content')">Issue Meter</a></th>
                    <th colspan="6" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>
                <tr>
                    <th  class="text-left">SerialNo</th>                    
                    <th  class="text-left">Issued To</th>                    
                    <th  class="text-left">Issued Date</th>
                    <th  class="text-left">Last Reading</th>
                    <th  class="text-left">Balance</th>
                    <th  align="right">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>


</div>
</div>