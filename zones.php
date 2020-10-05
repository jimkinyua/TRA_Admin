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

$ZoneID="0";
$ZoneName="";
$WardID="";

if (isset($_REQUEST['edit']))
{	
	if (isset($_REQUEST['ZoneID'])){$ZoneID=$_REQUEST['ZoneID'];}
	
	$sql = "SELECT * FROM BusinessZones where ZoneID = '$ZoneID'";
	//echo $sql;
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$ZoneName=$myrow['ZoneName'];
		$WardID=$myrow['WardID'];
	}	
}
?>
<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit Business Zone</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
            <tr>
                <td width="50%">
                    <label>Zone Name</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="ZoneName" name="ZoneName" value="<?php echo $ZoneName; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr> 
			<tr>
			  <td><label>Ward</label>
                    <div class="input-control select" data-role="input-control">
                    	
                    	<select name="WardID"  id="WardID">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT * FROM Wards ORDER BY 1";
                            
                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                {
                                    $s_id = $row["WardID"];
                                    $s_name = $row["WardName"];
                                    if ($WardID==$s_id) 
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
                     
        </table>
		<input name="Button" type="button" onclick="loadmypage('zones_list.php?'+
        											'&ZoneName='+this.form.ZoneName.value+
                                                    '&WardID='+this.form.WardID.value+
                                                    '&ZoneID='+<?php echo $ZoneID ?>+
                                                    '&save=1','content','loader','listpages','','zones')" value="Save">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('zones_list.php?i=1','content','loader','listpages','','zones')">
        <span class="table_text">
        <input name="UserID" type="hidden" id="DeviceID" value="<?php echo $DeviceID;?>" />

        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>