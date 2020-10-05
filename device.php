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
$Description="";
$MacAddress="";
$DeviceType ="";
$DeviceID ="0";

if (isset($_REQUEST['SerialNo'])) { $SerialNo = $_REQUEST['SerialNo']; } 

if (isset($_REQUEST['edit']))
{	
	$sql = "SELECT * FROM Devices where DeviceSerialNo = '$SerialNo'";
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$Description=$myrow['Description'];
		$MacAddress=$myrow['MacAddress'];
		$DeviceType=$myrow['DeviceType'];
		$DeviceID=$myrow['DeviceID'];
	}	
}
?>
<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit Device</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
            <tr>
                <td width="50%">
                    <label>Description</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="Description" name="Description" value="<?php echo $Description; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr> 
			<tr>
			  <td><label>Device Type</label>
                    <div class="input-control select" data-role="input-control">
                    	
                    	<select name="DeviceTypeID"  id="DeviceTypeID">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT * FROM DeviceType ORDER BY 1";
                            
                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                {
                                    $s_id = $row["DeviceTypeID"];
                                    $s_name = $row["DeviceTypeName"];
                                    if ($DeviceID==$s_id) 
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
			  <td></td>
		  </tr> 
            <tr>
                <td width="50%">
                    <label>Serial No</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="SerialNo" name="SerialNo" value="<?php echo $SerialNo; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr>
            <tr>
                <td width="50%">
                    <label>Mac Address</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="MacAddress" name="MacAddress" value="<?php echo $MacAddress; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr>               
                     
        </table>
		<input name="Button" type="button" onclick="loadmypage('device_list.php?'+
        											'&SerialNo='+this.form.SerialNo.value+
                                                    '&Description='+this.form.Description.value+
                                                    '&MacAddress='+this.form.MacAddress.value+
                                                    '&DeviceTypeID='+this.form.DeviceTypeID.value+
                                                    '&DeviceID='+<?php echo $DeviceID ?>+
                                                    '&save=1','content','loader','listpages','','devices')" value="Save">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('device_list.php?i=1','content','loader','listpages','','devices')">
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