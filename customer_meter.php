<?php 
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];

if (isset($_REQUEST['msg']))
{
	$msg = $_REQUEST['msg'];	
}

$SerialNo="";
$DevicePin="";
$CustomerID="";
$DeviceSerialNo="";
$CustomerID ="0";
$MarketID='';
$UserDeviceID=0;
$InitialMeterReading;
$CustomerName='';

    if (isset($_REQUEST['CustomerID'])) { $CustomerID = $_REQUEST['CustomerID'];}


	$sql = "SELECT ud.DeviceSerialNo,isnull(ud.DeviceUserID,0)DeviceUserID
    ,ud.InitialMeterReading,isnull(ud.UserDeviceID,0)UserDeviceID
    ,c.CustomerName 
    FROM Customer c
    left join UserDevices ud on ud.CustomerID=c.CustomerID
    join Devices d on ud.DeviceSerialNo=d.DeviceSerialNo 
    where   c.CustomerID = '$CustomerID'";


	
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$SerialNo=$myrow['DeviceSerialNo'];
		$DeviceUserID=$myrow['DeviceUserID'];		
		$CustomerName=$myrow['CustomerName'];
        $InitialMeterReading=$myrow['InitialMeterReading'];
        $UserDeviceID=$myrow['UserDeviceID'];
	}	
	   

?>
<div class="example">
<form>
	<fieldset>
	  <legend>Meter Assignment</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
		  <tr>
            <td width="50%">
                <label>Customer</label>
                <div class="input-control text" data-role="input-control">
                    <input type="text" id="CustomerName" name="CustomerName" value="<?php echo $CustomerName; ?>" disabled></input>
                    <button class="btn-clear" tabindex="-1"></button>
                </div></td>                    
            </td>
            <td></td>
          </tr> 
          <tr>
			  <td><label>Device</label>
                <div class="input-control select" data-role="input-control">
                	
                	<select name="SerialNo"  id="SerialNo">
                        <option value="0" selected="selected"></option>
                        <?php 
                        $s_sql = "SELECT DeviceID,DeviceSerialNo 
                                FROM Devices WHERE DeviceSerialNo 
                                NOT IN (SELECT DeviceSerialNo FROM UserDevices WHERE DeviceUserStatusID<>1)  
                                AND DeviceTypeID=3
                                ORDER BY 1";
                        
                        $s_result = sqlsrv_query($db, $s_sql);
                        if ($s_result) 
                        { //connection succesful 
                            while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                            {
                                $s_id = $row["DeviceSerialNo"];
                                $s_name = $row["DeviceSerialNo"];
                                if ($SerialNo==$s_id) 
                                {
                                    $selected = 'selected="selected"';
                                } else
                                {
                                    $selected = '';
                                }												
                             ?>
                        <option value="<?php echo $s_id; ?>" <?php echo $selected; ?>><?php echo $s_name; ?></option>
                        <?php 
                            }
                        }
                        ?>
                  </select>
                
              </div>
              </td>
		  </tr>
          <tr>
            <td width="50%">
                <label>Initial Meter Reading</label>
                <div class="input-control text" data-role="input-control">
                    <input type="text" id="InitialMeterReading" name="InitialMeterReading" value="<?php echo $InitialMeterReading; ?>" ></input>
                    <button class="btn-clear" tabindex="-1"></button>
                </div></td>                    
            </td>
            <td></td>
          </tr> 
        </table>
		<input name="Button" type="button" 
                onclick="loadmypage('customer_meters_list.php?'+
    			'&SerialNo='+this.form.SerialNo.value+                        
                '&CustomerName='+this.form.CustomerName.value+
                '&InitialMeterReading='+this.form.InitialMeterReading.value+
                '&CustomerID='+<?php echo $CustomerID; ?>+
                '&UserDeviceID='+<?php echo $UserDeviceID; ?>+                            
                '&save=1','content','loader','listpages','','user_devices')" value="Save">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypce('user_devices_list.php?i=1','content','loader','listpces','','user_devices')">
        <span class="table_text">
        <input name="UserID" type="hidden" id="DeviceID" value="<?php echo $DeviceID;?>" />
        <input name="add" type="hidden" id="add" value="<?php echo $new;?>" />
        <input name="edit" type="hidden" id="edit" value="<?php echo $edit;?>" />
        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>