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

$BusinessID="0";
$BusinessName="";
$WardID="";

if (isset($_REQUEST['edit']))
{	
	if (isset($_REQUEST['BusinessID'])){$BusinessID=$_REQUEST['BusinessID'];}
	
	$sql = "SELECT * FROM Businesses where BusinessID = '$BusinessID'";
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$BusinessName=$myrow['BusinessName'];
		$BusinessActivity=$myrow['BusinessActivity'];
		$BusinessOwner=$myrow['BusinessOwner'];
		$PhoneNo=$myrow['PhoneNo'];
		$WardID=$myrow['WardID'];
		$IDNO=$myrow['IDNO'];
		$SBPNO=$myrow['SBP_NO'];	
		$WardID=$myrow['WardId'];		
	}	
}
?>
<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit Businesss</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
            <tr>
                <td width="50%">
                    <label>Business Name</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="BusinessName" name="BusinessName" value="<?php echo $BusinessName; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr> 
            <tr>
                <td width="50%">
                    <label>Business Activity</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="BusinessActivity" name="BusinessActivity" value="<?php echo $BusinessActivity; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr>
            <tr>
                <td width="50%">
                    <label>Business Owner</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="BusinessOwner" name="BusinessOwner" value="<?php echo $BusinessOwner; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr> 
            <tr>
                <td width="50%">
                    <label>ID No</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="IDNO" name="IDNO" value="<?php echo $IDNO; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr> 
            <tr>
                <td width="50%">
                    <label>PhoneNo No</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="PhoneNo" name="PhoneNo" value="<?php echo $PhoneNo; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr> 			
            <tr>
                <td width="50%">
                    <label>SBP No</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="SBPNO" name="SBPNO" value="<?php echo $SBPNO; ?>"></input>
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
                            $s_sql = "SELECT * FROM Wards ORDER BY WardID";
                            
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
		<input name="Button" type="button" onclick="loadmypage('businesses_list.php?'+
        											'&BusinessName='+this.form.BusinessName.value+
                                                    '&BusinessActivity='+this.form.BusinessActivity.value+
                                                    '&BusinessOwner='+this.form.BusinessOwner.value+
                                                    '&WardID='+this.form.WardID.value+
                                                    '&PhoneNo='+this.form.PhoneNo.value+
                                                    '&IDNO='+this.form.IDNO.value+
                                                    '&SBPNO='+this.form.SBPNO.value+
                                                    '&BusinessID='+<?php echo $BusinessID ?>+
                                                    '&save=1','content','loader','listpages','','Businesses')" value="Save">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('businesses_list.php?i=1','content','loader','listpages','','Businesses')">
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>