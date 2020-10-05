<?php 
require 'DB_PARAMS/connect.php';
require_once('GlobalFunctions.php');
require('password_compat/lib/password.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$UserID = $_SESSION['UserID'];
	
$sql = "SELECT ag.*,u.Email OfficialEmail,u.PfNo FROM Users u
join Agents ag on u.AgentID=ag.AgentID
where ag.agentid = $UserID";

$result = sqlsrv_query($db, $sql);
if ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
{
	$UserID=$myrow['UserID'];
	$UserName=$myrow['UserName'];
	$LastName=$myrow['LastName'];
	$FirstName=$myrow['FirstName'];
	$MiddleName=$myrow['MiddleName'];
	$Telephone=$myrow['Mobile'];
	$Mobile=$myrow['Mobile'];
    $IDNo=$myrow['IDNO'];
    $PfNo=$myrow['PfNo'];
    $OfficialEmail=$myrow['OfficialEmail'];
	$Email=$myrow['Email'];	
}	

if (isset($_REQUEST['save']))
{
    print_r($_REQUEST);
	$UserID = $_SESSION['UserID'];
	$AgentID=$_REQUEST['AgentID'];
	$UserName=$_REQUEST['UserName'];
	$LastName=$_REQUEST['LastName'];
	$FirstName=$_REQUEST['FirstName'];
	$MiddleName=$_REQUEST['MiddleName'];
	$PfNo=$_REQUEST['PfNo'];
	$Mobile=$_REQUEST['Mobile'];
	$Email=$_REQUEST['Email'];
    $IDNo=$_REQUEST['IDNo'];
    $OfficialEmail=$_REQUEST['OfficialEmail'];
    $SBPDateline=$_REQUEST['SBPDateline'];



	$sql = "UPDATE Agents SET
				 UserName='$UserName'
				,FirstName='$FirstName'
				,MiddleName='$MiddleName'
				,LastName='$LastName'				
				,Telephone='$Telephone'
				,Mobile='$Mobile'
				,Email='$Email'
                ,IDNo='$IDNo'
			where AgentID = $UserID";	

    $sql2 = 
    "UPDATE Users SET UserName='$UserName',PfNo='$PfNo',IDNo='$IDNo',Email='$OfficialEmail' where AgentID = $UserID";



	$result = sqlsrv_query($db, $sql);

    $result2 = sqlsrv_query($db, $sql2);

    if(!$result){
        DisplayErrors();
    }else if(!$result2){
        DisplayErrors();
    }

	if ($result and $result2)
	{	
        $rst=SaveTransaction($db,$UserID,"Profile Update ");	
		$msg = "Saved Details Successfully";

	} else
	{
		DisplayErrors();
		$msg = "User Details Failed to save";			
	}	
}

if (isset($_REQUEST['change']))
{
    $OldPassword = $_REQUEST['OldPassword'];
    $NewPassword = $_REQUEST['NewPassword'];
    $ConfirmPassword = $_REQUEST['ConfirmPassword'];
    $UserID=$_REQUEST['UserID'];

    $error = 0;
    if ((trim($OldPassword)=='') OR (trim($NewPassword)=='') OR (trim($ConfirmPassword)==''))
    {
        $msg = "Empty Passwords are not permitted";
        $error = 1;     
    } else if (($NewPassword != $ConfirmPassword))
    {
        $msg = "Your Passwords do not Match";
        $error = 1; 
    } else
    {
    
        
        $sql = "Select Password,Email FROM Agents WHERE AgentID = '$UserID'";

        $result = sqlsrv_query($db, $sql);
        if ($result)
        {

            if ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC))
            {
                $Email=$myrow['Email'];
                if(!password_verify($OldPassword,$myrow['Password']))
                {
                    $msg = "Invalid Password";
                    $error = 1;
                }
            } else 
            {
                DisplayErrors();
                
                $msg = "An error occured and we were not able to complete your request";
                $error = 1;
            }
        } else
        {
            $msg = "An error occured and we were not able to complete your request";
            $error = 1; 
        }
    }
    

    if ($error==0)
    {   
        $UserID=$_SESSION['UserID'];
        $NewPassword = password_hash('cosmas',PASSWORD_DEFAULT);

        //ECHO $NewPwd; exit;

        //$NewPassword=md5($NewPassword);
        
        $sql = "UPDATE users SET Password = '$NewPassword',UserStatusID=1 WHERE AgentID = '$UserID'";
        
        $result = sqlsrv_query($db, $sql);
        if ($result)
        {
            $sql = "UPDATE Agents SET Password = '$NewPassword',UserStatusID=1 WHERE AgentID = '$UserID'";
            $result = sqlsrv_query($db, $sql);
            if ($result)
            {
                $msg = "Your password has been changed sucessfully";   
            } 
        } else
        {
            $msg = "An error occured and we were not able to complete your request";
        }
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
                <td width="50%"><label>Last Name</label>
                    <div class="input-control text" data-role="input-control">
                        <input name="LastName" type="text" id="LastName" value="<?php echo $LastName; ?>" placeholder="">
                        <button class="btn-clear" tabindex="-1"></button>
                    </div></td>                
                <td width="50%">
                    <label>Username</label>
                    <div class="input-control text" data-role="input-control">
                        <input name="UserName" type="text" id="UserName" value="<?php echo $UserName; ?>" >
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
          	</tr>
			<tr>
                <td width="50%">
                    <label>ID Number</label>
                    <div class="input-control text" data-role="input-control">
                        <input name="IDNo" type="text" id="IDNo" value="<?php echo $IDNo; ?>" placeholder="">
                        <button class="btn-clear" tabindex="-1"></button>
                  </div>
                </td>
                <td width="50%">
               	  <label>Mobile</label>
               	  <div class="input-control text" data-role="input-control">
                   	  <input name="Mobile" type="text" id="Mobile" value="<?php echo $Mobile; ?>" placeholder="">
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
          	</tr>
            <tr>
                <td width="50%">
                    <label>Pf Number</label>
                    <div class="input-control text" data-role="input-control">
                        <input name="PfNo" type="text" id="PfNo" value="<?php echo $PfNo; ?>" placeholder="">
                        <button class="btn-clear" tabindex="-1"></button>
                  </div>
                </td>
                <td width="50%">
                  <label>Official Email</label>
                  <div class="input-control text" data-role="input-control">
                      <input name="OfficialEmail" type="text" id="OfficialEmail" value="<?php echo $OfficialEmail; ?>" placeholder="">
                        <button class="btn-clear" tabindex="-1"></button>
                  </div>
                </td>                
            </tr>
		</table>
		<input name="Button" type="button" onclick="loadpage('userprofile.php?'+
            '&UserName='+this.form.UserName.value+
            '&PfNo='+this.form.PfNo.value+
            '&LastName='+this.form.LastName.value+
            '&FirstName='+this.form.FirstName.value+
            '&MiddleName='+this.form.MiddleName.value+
            '&Mobile='+this.form.Mobile.value+
            '&IDNo='+this.form.IDNo.value+
            '&Email='+this.form.Email.value+
            '&OfficialEmail='+this.form.OfficialEmail.value+
        	'&save=1','content')" value="Save">

        <input name="Button" type="button" onclick="loadpage('changepassword.php?'+
            '&UserName='+this.form.UserName.value+
            '&UserID='+<?= $_SESSION['UserID'];; ?>
            ,'content')" value="Change Password">

        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>