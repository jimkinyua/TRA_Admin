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

$UserID=$_REQUEST['UserID'];
$UserNames=$_REQUEST['UserNames'];




?>
<div class="example">
<form>
	<fieldset>
	  <legend>Reset Password For</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
		  <tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
            <tr>
                <td width="50%">
                    <h2><?php echo strtoupper($UserNames); ?></h2>
                </td>
                <td width="50%">
            
                </td>
            </tr>                                      
                     
        </table>
        <br>
		<input name="Button" type="button" onclick="deleteConfirm2('Are you sure you want to Reset the password?',
        'users_list.php?'+
        '&UserID='+<?php echo $UserID; ?>+        
        '&reset=1','content','loader','listpages','','users')" value="Reset">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('users_list.php?reset=1','content','loader','listpages','','Users')">
        <span class="table_text">
        

        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>