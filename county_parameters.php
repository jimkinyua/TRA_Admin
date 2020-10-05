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

if (isset($_REQUEST['UserID'])) { $UserID = $_REQUEST['UserID']; } 

$UserID='';
$UserName='';
$UserFullNames='';
$PostalAddress='';
$PostalCode='';
$Town='';
$Telephone='';
$Mobile='';
$Email='';
$Url='';

if (isset($_REQUEST['UserID'])) { $UserID = $_REQUEST['UserID']; }
if (isset($_REQUEST['UserName'])) { $UserName = $_REQUEST['UserName']; }
if (isset($_REQUEST['password'])) { $password = $_REQUEST['password']; }
if (isset($_REQUEST['UserFullNames'])) { $UserFullNames = $_REQUEST['UserFullNames']; }
if (isset($_REQUEST['PostalAddress'])) { $PostalAddress = $_REQUEST['PostalAddress']; }
if (isset($_REQUEST['PostalCode'])) { $PostalCode = $_REQUEST['PostalCode']; }
if (isset($_REQUEST['Town'])) { $Town = $_REQUEST['Town']; }
if (isset($_REQUEST['Mobile'])) { $Mobile = $_REQUEST['Mobile']; }
if (isset($_REQUEST['Email'])) { $Email = $_REQUEST['Email']; }
if (isset($_REQUEST['RoleCenterID'])) { $RoleCenterID = $_REQUEST['RoleCenterID']; }
if (isset($_REQUEST['Telephone'])) { $Telephone = $_REQUEST['Telephone']; }

if (isset($_REQUEST['edit']))
{	
	$UserID = $_REQUEST['UserID'];	
	$sql = "SELECT * FROM Users where UserID = $UserID";
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$UserID=$myrow['UserID'];
		$UserName=$myrow['UserName'];
		$UserFullNames=$myrow['UserFullNames'];
		$PostalAddress=$myrow['PostalAddress'];
		$Town=$myrow['Town'];
		$Telephone=$myrow['Telephone'];
		$Mobile=$myrow['Mobile'];
		$Email=$myrow['Email'];
		$Password=$myrow['Password'];
		$UserStatusID=$myrow['UserStatusID'];
		$RoleCenterID=$myrow['RoleCenterID'];
		$CreatedDate=$myrow['CreatedDate'];
		$CreatedUserID=$myrow['UserID'];
	}	
}
?>
<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit User</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
			<tr>
                <td width="50%">
                	<label>Email</label>
                	<div class="input-control text" data-role="input-control">
                    	<input name="Email" type="text" id="Email" value="<?php echo $Email; ?>" placeholder="">
                        <button class="btn-clear" tabindex="-1"></button>
                  </div>
                </td>
                <td width="50%"><label>User Full Names</label>
                	<div class="input-control text" data-role="input-control">
                    	<input name="FullNames" type="text" id="FullNames" value="<?php echo $UserFullNames; ?>" placeholder="">
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                	</td>
          	</tr>
			<tr>
                <td width="50%">
                <label>Postal Address</label>
                    <div class="input-control text" data-role="input-control">
                        <input name="PostalAddress" type="text" id="PostalAddress" value="<?php echo $PostalAddress; ?>" placeholder="">
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>	
                </td>
                <td width="50%">
                <label>Postal Code</label>
                    <div class="input-control text" data-role="input-control">
                        <input name="PostalCode" type="text" id="PostalCode" value="<?php echo $PostalCode; ?>" placeholder="">
                        <button class="btn-clear" tabindex="-1"></button>
              </div>	
				</td>
          	</tr>
			<tr>
                <td width="50%">
               	  <label>Mobile</label>
               	  <div class="input-control text" data-role="input-control">
                   	  <input name="Mobile" type="text" id="Mobile" value="<?php echo $Mobile; ?>" placeholder="">
                        <button class="btn-clear" tabindex="-1"></button>
                  </div>
                </td>
                <td width="50%">
                	<label>Telephone</label>
                	<div class="input-control text" data-role="input-control">
                        <input name="Telephone" type="text" id="Telephone" value="<?php echo $Telephone; ?>" placeholder="">
                        <button class="btn-clear" tabindex="-1"></button>
                  </div>
				</td>
          	</tr> 
 			<tr>
            <td><label>Role Center</label>
                    <div class="input-control select" data-role="input-control">
                    	<select name="RoleCenterID"  id="RoleCenterID">
                        <option value="0" selected="selected"></option>
                        <?php 
                        $s_sql = "select * from RoleCenters order by RoleCenterID";
                        $s_result = sqlsrv_query($db, $s_sql);
                        if ($s_result) 
                        { //connection succesful 
                            while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                            {
                                $s_id = $row["RoleCenterID"];
                                $s_name = $row["RoleCenterName"];
                                if ($UserRoledID==$s_id) 
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
                <td width="50%">
                	<label>Town</label>
                    <div class="input-control text" data-role="input-control">
                        <input name="Town" type="text" id="Town" value="<?php echo $Town; ?>" placeholder="">
                        <button class="btn-clear" tabindex="-1"></button>
                  </div>
				</td>              
          	</tr>
			<tr>
			  <td><label>Status</label>
                    <div class="input-control select" data-role="input-control">
                    	<select name="UserStatusID"  id="UserStatusID">
                        <option value="0" selected="selected"></option>
                        <?php 
                        $s_sql = "SELECT * FROM UserStatus ORDER BY UserStatusName";
						
                        $s_result = sqlsrv_query($db, $s_sql);
                        if ($s_result) 
                        { //connection succesful 
                            while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                            {
                                $s_id = $row["UserStatusID"];
                                $s_name = $row["UserStatusName"];
                                if ($UserStatusID==$s_id) 
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
		<input name="Button" type="button" onclick="loadmypage('users_list.php?'+
        '&UserID='+this.form.UserID.value+
        '&UserFullNames='+this.form.FullNames.value+
        '&Town='+this.form.Town.value+
        '&PostalCode='+this.form.PostalCode.value+
        '&Telephone='+this.form.Telephone.value+
        '&Mobile='+this.form.Mobile.value+
        '&Email='+this.form.Email.value+
        '&PostalAddress='+this.form.PostalAddress.value+
        '&UserStatusID='+this.form.UserStatusID.value+
        '&RoleCenterID='+this.form.RoleCenterID.value+
        '&save=1','content','loader','users')" value="Save">
      <input type="reset" value="Cancel" onClick="loadmypage('users_list.php?i=1','content','loader','listpages','','users')">
      									 
        <span class="table_text">
        <input name="UserID" type="hidden" id="UserID" value="<?php echo $UserID;?>" />
        <input name="add" type="hidden" id="add" value="<?php echo $new;?>" />
        <input name="edit" type="hidden" id="edit" value="<?php echo $edit;?>" />
        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>