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

$WardID="0";
$WardName="";
$SubCountyID="";

if (isset($_REQUEST['edit']))
{	
	if (isset($_REQUEST['WardID'])){$WardID=$_REQUEST['WardID'];}
	
	$sql = "SELECT * FROM Wards where WardID = '$WardID'";
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$WardName=$myrow['WardName'];
		$SubCountyID=$myrow['SubCountyID'];
	}	
}
?>
<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit Wards</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
            <tr>
                <td width="50%">
                    <label>Ward Name</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="WardName" name="WardName" value="<?php echo $WardName; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr> 
			<tr>
			  <td><label>SubCounty</label>
                    <div class="input-control select" data-role="input-control">
                    	
                    	<select name="SubCountyID"  id="SubCountyID">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT * FROM SubCounty ORDER BY SubCountyID";
                            
                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                {
                                    $s_id = $row["SubCountyID"];
                                    $s_name = $row["SubCountyName"];
                                    if ($SubCountyID==$s_id) 
                                    {
                                        $selected = 'selected="selected"';
                                    } else
                                    {
                                        $selected = '';
                                    }												
                                 ?>
                            <option value="<?php echo $s_id; ?>" <?php echo $selected; ?>><?php echo $s_name; ?></option>
                            <?php }
                            }
                            ?>
                      </select>
                    
                  </div>
			</td>
			  <td></td>
		  </tr>                           
                     
        </table>
		<input name="Button" type="button" onclick="loadmypage('wards_list.php?'+
        											'&WardName='+this.form.WardName.value+
                                                    '&SubCountyID='+this.form.SubCountyID.value+
                                                    '&WardID='+<?php echo $WardID ?>+
                                                    '&save=1','content','loader','listpages','','wards')" value="Save">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('wards_list.php?i=1','content','loader','listpages','','wards')">
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>