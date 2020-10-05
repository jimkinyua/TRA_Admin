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
	$DeviceUserID=$_REQUEST['DeviceUserID'];
	$DevicePin=$_REQUEST['DevicePin'];
	$MarketID=$_REQUEST['MarketID'];
	$UserDeviceID=$_REQUEST['UserDeviceID'];
	$UserName=$_REQUEST['UserName'];
	$DeviceUserStatusID=$_REQUEST['DeviceUserStatusID'];
	
	/* print_r($_REQUEST);
	exit; */

	if ($UserDeviceID=='0')
	{
		//check if the guy already have another active device
		
		$sql="select 1 from userdevices where DeviceUserStatusID in (1,3) and DeviceUserID=$DeviceUserID";
		$result=sqlsrv_query($db,$sql);
		if($result)
		{
			$ccount=0;
			while(sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
			{
				$ccount+=1;
			}
			
			if ($ccount>0)
			{
				echo $sql;
				$msg=$sql;//"This person already has another device to returned";
				goto Endd;				
			}
		}else
		{
			$msg="Database Error";	
			goto Endd;
		}
		
		$sql = "SELECT * FROM UserDevices WHERE DeviceSerialNo = '$SerialNo' and DeviceUserStatusID <>3";
		$result = sqlsrv_query($db, $sql);

		//echo $sql; exit;
		
		if($result){
			
			while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
			{
				if($rw['DeviceUserStatusID']==1)
				{	
					$msg = "The Device is already issued to someone else two";
					goto Endd;
				}
			} 
		
			if ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
			{
				$DeviceUserStatusID=$myrow['DeviceUserStatusID'];	
				echo $DeviceUserStatusID; 
				if ($DeviceUserStatusID!==2)
				{			
					if ($myrow['DeviceUserID']==$DeviceUserID)
					{
						$msg = "The Device is already issued to this user";
						goto Endd;
					}else
					{						
						$msg = "The Device is already issued to someone else one";
						goto Endd;
					}	
				}					
			}
			
			$sql = "INSERT INTO UserDevices ([DeviceSerialNo],[DeviceUserID],[CreatedBy],[DevicePinNo],[MarketID],DeviceUserStatusID) VALUES('$SerialNo','$DeviceUserID','$CreatedUserID','$DevicePin','$MarketID',1) SELECT SCOPE_IDENTITY() AS ID" ;
			/* echo $sql; 
			exit;*/
			
			//To make him as a user
			$custSql="if not exists(select * from customer where Email in (select email from Agents where AgentID=$DeviceUserID)) 
			insert into customer(CustomerName,ContactPerson,PostalAddress,PostalCode,Town,Telephone1,Telephone2,Mobile1,Mobile2,Email,CreatedBy,IDNO) 
			select FirstName+' '+MiddleName+' '+LastName,FirstName+' '+MiddleName+' '+LastName,PostalAddress,PostalCode,Town,Telephone,Telephone,Mobile,Mobile,Email,CreatedBy,IDNO from Agents where agentid=$DeviceUserID";
				
			//TO UPDATE THE USERDEVICES TABLE WITH CUSTOMERID
			$custID="UPDATE UserDevices SET CustomerID=(select top 1 CustomerID from Customer where Email in(select Email from Agents where AgentID=$DeviceUserID)) WHERE DeviceUserID=$DeviceUserID AND DeviceSerialNo='$SerialNo'";						
		
		}else
		{
			$msg="Error in database query";
		}			
	} else
	{
//To make him as a user
		$custSql="if not exists(select * from customer where Email in (select email from Agents where AgentID=$DeviceUserID)) 
		insert into customer(CustomerName,ContactPerson,PostalAddress,PostalCode,Town,Telephone1,Telephone2,Mobile1,Mobile2,Email,CreatedBy,IDNO) 
		select FirstName+' '+MiddleName+' '+LastName,FirstName+' '+MiddleName+' '+LastName,PostalAddress,PostalCode,Town,Telephone,Telephone,Mobile,Mobile,Email,CreatedBy,IDNO from Agents where agentid=$DeviceUserID";		
		
		$sql = "UPDATE UserDevices SET [DeviceUserID]='$DeviceUserID',DevicePinNo='$DevicePin',[MarketID]='$MarketID',DeviceUserStatusID='1',[CreatedBy]='$CreatedUserID' where UserDeviceID='$UserDeviceID'";		
	}	
Endd:
	
	if ($msg=='')
	{

		$result = sqlsrv_query($db, $sql);
		
		if(!$result){
			$msg =$sql;// "Details Failed to save";
			DisplayErrors();
			redirect($_REQUEST, $msg, "user_devices.php");
			exit;
		}else
		{	
			if ($custSql!="")
			{
				$result = sqlsrv_query($db, $custSql);
				if(!$result)
				{
					//DisplayErrors();
					$msg = "Customer Failed to save";				
					redirect($_REQUEST, $msg, "user_devices.php");
					exit;
				}else
				{			
					$result = sqlsrv_query($db, $custID);
					if(!$result)
					{
						DisplayErrors();
						$msg =$custID;// "Details 3 Failed to save";
						//echo $custID;
						redirect($_REQUEST, $msg, "user_devices.php");
						exit;
					}else
					{
						
						//TO UPDATE THE Agents TABLE WITH UserName, Temporary Measure Though, It should be done through 		public Portal
						$sql_uName="Update agents set username='$UserName' where agentId=$DeviceUserID";
						$result = sqlsrv_query($db, $sql_uName);
						if(!$result)
						{
							DisplayErrors();
							$msg="Database Error in updating Users";
							//redirect($_REQUEST, $msg, "user_devices.php");
						}else{
							$msg = "Record Saved Successfuly";
						}
					}			
				}
			}
			else
			{
				DisplayErrors();	
			}
		}
	}else
	{
		redirect($_REQUEST, $msg, "user_devices.php");
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
        <legend>Users & Devices</legend>
        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th class="text-left"><a href="#" onClick="loadpage('user_devices.php?add=1','content')">Issue Device</a></th>
                    <th colspan="6" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>
                <tr>
                    <th width="14%" class="text-left">SerialNo</th>
                    <th width="12%" class="text-left">Device Type</th>
                    <th width="20%" class="text-left">Issued To</th>
                    <th width="20%" class="text-left">Status</th>
                    <th width="12%" class="text-left">Issued Date</th>
                    <th width="12%" class="text-left">Market</th>
                    <th width="12%" class="text-left">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>


</div>
</div>