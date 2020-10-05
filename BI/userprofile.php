<?php 
require 'DB_PARAMS/connect.php';
//print_r (session_name());
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$UserID = $_SESSION['UserID'];
	
$sql = "SELECT * FROM Users where UserID = $UserID";
$result = sqlsrv_query($db, $sql);
if ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
{
	$UserID=$myrow['UserID'];
	$UserName=$myrow['UserName'];
	$LastName=$myrow['LastName'];
	$FirstName=$myrow['FirstName'];
	$MiddleName=$myrow['MiddleName'];
	$Telephone=$myrow['Telephone'];
	$Mobile=$myrow['Mobile'];
	$Email=$myrow['Email'];
	$Url=$myrow['Url'];
}	

if (isset($_REQUEST['save']))
{
	//$UserID= $_REQUEST['UserID'];
	$UserID=$_REQUEST['UserID'];
	$UserName=$_REQUEST['UserName'];
	$LastName=$_REQUEST['LastName'];
	$FirstName=$_REQUEST['FirstName'];
	$MiddleName=$_REQUEST['MiddleName'];
	$Telephone=$_REQUEST['Telephone'];
	$Mobile=$_REQUEST['Mobile'];
	$Email=$_REQUEST['Email'];

	$sql = "UPDATE agents SET
				 UserName='$UserName'
				,FirstName='$FirstName'
				,MiddleName='$MiddleName'
				,LastName='$LastName'				
				,Telephone='$Telephone'
				,Mobile='$Mobile'
				,Email='$Email'
			where AgentID = $UserID";					
	$result = sqlsrv_query($db, $sql);
	//echo $sql;
	if ($result)
	{		
		$msg = "Saved Details Successfully";
	} else
	{
		DisplayErrors();
		$msg = "User Details Failed to save";			
	}	
}
?>
<div class="example">
<form>
	<fieldset>
	  <legend>User Profile</legend>
	  <table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
			<tr>
                <td width="50%">
                	<label>Username</label>
                	<div class="input-control text" data-role="input-control">
                    	<input name="UserName" type="text" id="UserName" value="<?php echo $UserName; ?>" placeholder="" disabled="disabled">
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%"><label>Last Name</label>
                	<div class="input-control text" data-role="input-control">
                    	<input name="LastName" type="text" id="LastName" value="<?php echo $LastName; ?>" placeholder="">
                        <button class="btn-clear" tabindex="-1"></button>
                    </div></td>
          	</tr>
			<tr>
                <td width="50%">
                	<label>First Name</label>
                	<div class="input-control text" data-role="input-control">
                    	<input name="FirstName" type="text" id="FirstName" value="<?php echo $FirstName; ?>" placeholder="">
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
                	<label>Middle Names</label>
                    <div class="input-control text" data-role="input-control">
                        <input name="MiddleName" type="text" id="MiddleName" value="<?php echo $MiddleName; ?>" placeholder="">
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
                <td width="50%">
                	<label>Email</label>
                	<div class="input-control text" data-role="input-control">
                    	<input name="Email" type="text" id="Email" value="<?php echo $Email; ?>" placeholder="">
                        <button class="btn-clear" tabindex="-1"></button>
                  </div>
                </td>
                <td width="50%">
                	<label>Website</label>
                    <div class="input-control text" data-role="input-control">
                        <input name="Url" type="text" id="Url" value="<?php echo $Url; ?>" placeholder="">
                        <button class="btn-clear" tabindex="-1"></button>
                  </div>
				</td>
          	</tr>
		</table>
		<input name="Button" type="button" onclick="loadpage('userprofile.php?'+
            '&UserID='+this.form.UserID.value+
            '&UserName='+this.form.UserName.value+
            '&LastName='+this.form.LastName.value+
            '&FirstName='+this.form.FirstName.value+
            '&MiddleName='+this.form.MiddleName.value+
            '&Telephone='+this.form.Telephone.value+
            '&Mobile='+this.form.Mobile.value+
            '&Email='+this.form.Email.value+
            '&Url='+this.form.Url.value+
        	'&save=1','content')" value="Save">
      <input type="reset" value="Cancel" onClick="loadpage('profilehome.php?edit=1&UserID=<?php echo $UserID;?>','content')">
        <input name="UserID" type="hidden" id="UserID" value="<?php echo $UserID;?>" />
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>