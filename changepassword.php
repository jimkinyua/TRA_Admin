<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
session_start();
$msg="";
$UserID = $_SESSION['UserID'];
$Email='';


?>
<div class="example">
	<fieldset>
	  <legend>Change Password
	  </legend>
      <form action="" method="post">
	  <table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
			<tr>
			  <td width="50%"><label>Old Password</label>
                <div class="input-control password" data-role="input-control">
                  <input name="OldPassword" type="password" id="OldPassword" placeholder="" />
                  <button class="btn-clear" tabindex="-1"></button>
              </div></td>
			  <td width="50%">&nbsp;</td>
		  </tr>
			<tr>
			  <td width="50%"><label>New Password</label>
                <div class="input-control password" data-role="input-control">
                  <input name="NewPassword" type="password" id="NewPassword" placeholder="" />
                  <button class="btn-clear" tabindex="-1"></button>
              </div></td>
              <td></td>

	    </tr>  
        <tr>
 			  <td width="50%"><label>Confirm Password</label>
                <div class="input-control password" data-role="input-control">
                  <input name="ConfirmPassword" type="password" id="ConfirmPassword" placeholder="" />
                  <button class="btn-clear" tabindex="-1"></button>
              </div></td>
              <td></td>       	
        </tr>                     
                     
      </table>
		<input name="Button" type="button" onclick="loadmypage('userprofile.php?'+
        '&OldPassword='+this.form.OldPassword.value+
        '&NewPassword='+this.form.NewPassword.value+
        '&ConfirmPassword='+this.form.ConfirmPassword.value+
        '&UserID='+<?php echo $_SESSION['UserID']; ?>+
        '&change=1','content')" value="Change">
        </form>
	  <div style="margin-top: 20px">
</div>
	</fieldset>
</div>