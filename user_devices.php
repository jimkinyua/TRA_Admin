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
$DeviceUserID="";
$DeviceSerialNo="";
$UserDeviceID ="0";
$MarketID='';
$DeviceUserStatusID=1;
$UserName='';

if (isset($_REQUEST['UserDeviceID'])) { $UserDeviceID = $_REQUEST['UserDeviceID']; } 

if (isset($_REQUEST['edit']))
{	
	$UserDeviceID = $_REQUEST['UserDeviceID'];
	$sql = "SELECT ud.*,ag.UserName 
		FROM UserDevices ud 
		inner join agents ag on ud.DeviceUserID=ag.AgentID 
		where UserDeviceID = '$UserDeviceID'";
	
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$SerialNo=$myrow['DeviceSerialNo'];
		$DeviceUserID=$myrow['DeviceUserID'];
		$MarketID=$myrow['MarketID'];
		$UserName=$myrow['UserName'];
        $DevicePin=$myrow['DevicePinNo'];
	}	
	//echo $sql;
}
?>
<script src="choosen.js"></script>
<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit User-Devices</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
			<tr>
			  <td><label>User</label>
                    <div class="input-control select" data-role="input-control">
                    	
                    	<select class="js-example-basic-single"     name="DeviceUserID"  id="DeviceUserID">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT AgentID UserID,firstName+' '+MiddleName+' '+LastName UserFullNames FROM Agents ORDER BY firstName+' '+MiddleName+' '+LastName";
                            
                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                {
                                    $s_id = $row['UserID'];
                                    $s_name = $row['UserFullNames'];
                                    if ($DeviceUserID==$s_id) 
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
                    
                  </div></td>
			  <td><label>Device</label>
                    <div class="input-control select" data-role="input-control">
                    	
                    	<select name="SerialNo"  id="SerialNo">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT DeviceID,DeviceSerialNo FROM Devices ORDER BY DeviceSerialNo";
                            
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
                    
                  </div></td>
		  </tr> 
           <tr>
			  <td><label>Market</label>
                    <div class="input-control select" data-role="input-control">
                    	
                    	<select name="MarketID"  id="MarketID">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT * FROM Markets ORDER BY 1";
                            
                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                {
                                    $s_id = $row["MarketID"];
                                    $s_name = $row["MarketName"];
                                    if ($MarketID==$s_id) 
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
                    
                  </div></td>
			  <td><label>Device Pin</label>
                    <div class="input-control password" data-role="input-control">
                        <input type="password" id="DevicePin" name="DevicePin" value="<?php echo $DevicePin; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div></td>
		  </tr> 
          <tr>
          	<td width="50%">
            <label>UserName</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="UserName" name="UserName" value="<?php echo $UserName; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div></td>                    
            </td>
            <td></td>
          </tr>        
        </table>
		<input name="Button" type="button" onclick="loadmypage('user_devices_list.php?'+
        											'&SerialNo='+this.form.SerialNo.value+
                                                    '&DeviceUserID='+this.form.DeviceUserID.value+
                                                    '&DevicePin='+this.form.DevicePin.value+
                                                    '&MarketID='+this.form.MarketID.value+
                                                    '&UserName='+this.form.UserName.value+                                                    '&UserDeviceID='+<?php echo $UserDeviceID; ?>+                                                    
                                                    '&save=1','content','loader','listpages','','user_devices')" value="Save">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('user_devices_list.php?i=1','content','loader','listpages','','user_devices')">
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