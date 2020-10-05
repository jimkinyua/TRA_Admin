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

$RoleCenterID="0";
$RoleCenterName="";
$DefaultMenuGroupID="";
$BeyondLimitApproverID=0;
$MaximumApprovalLimit=0;
if (isset($_REQUEST['edit']))
{	
	if (isset($_REQUEST['RoleCenterID'])){$RoleCenterID=$_REQUEST['RoleCenterID'];}
	
	$sql = "SELECT * FROM RoleCenters where RoleCenterID = '$RoleCenterID'";
	$result = sqlsrv_query($db, $sql);

   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$RoleCenterName=$myrow['RoleCenterName'];
		$IsAdmin=$myrow['isAdmin'];
		$DefaultMenuGroupID=$myrow['DefaultMenuGroupID'];
		$MaximumApprovalLimit=$myrow['MaximumApprovalLimit'];
		$BeyondLimitApproverID=$myrow['BeyondLimitApproverID'];
	}
}
?>
<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit RoleCenter</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
		<tr>
		  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		</tr>
		<tr>
			<td width="50%">
				<label>RoleCenter Name</label>
				<div class="input-control text" data-role="input-control">
					<input type="text" id="RoleCenterName" name="RoleCenterName" value="<?php echo $RoleCenterName; ?>"></input>
					<button class="btn-clear" tabindex="-1"></button>
				</div>
			</td>
			<td width="50%">
		
				</td>
		</tr>
            <tr>
			 <td><label>Default Menu Group</label>
                    <div class="input-control select" data-role="input-control">
                    	
                    	<select name="DefaultMenuGroupID"  id="DefaultMenuGroupID">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT * FROM MenuGroups ORDER BY 1";
                            
                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                {
                                    $s_id = $row["MenuGroupID"];
                                    $s_name = $row["MenuGroupName"];
                                    if ($DefaultMenuGroupID==$s_id) 
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
				<label>Maximum Approval Limit</label>
				<div class="input-control text" data-role="input-control">
					<input type="text" id="MaximumApprovalLimit" name="MaximumApprovalLimit" value="<?php echo number_format($MaximumApprovalLimit,0); ?>"></input>
					<button class="btn-clear" tabindex="-1"></button>
				</div>
			</td>
			<td width="50%">
		
				</td>
		</tr> 
		<tr>
			<td width="50%">
				<label>Beyond Limit Approver</label>
				<div class="input-control select" data-role="input-control">
                    	
                    	<select name="BeyondLimitApproverID"  id="BeyondLimitApproverID">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT * FROM RoleCenters ORDER BY 1";
                            
                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                {
                                    $s_id = $row["RoleCenterID"];
                                    $s_name = $row["RoleCenterName"];
                                    if ($BeyondLimitApproverID==$s_id) 
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
			</td>
			<td width="50%">
		
				</td>
		</tr>
		  <tr>
			  <td><label>Is Administrator</label>
					<div class="input-control select" data-role="input-control">
						<select name="IsAdmin"  id="IsAdmin">
						<?php 
							$selected="";
							if ($IsAdmin=="1")
							{
								$selected="selected";
							}							
							
						?>
						<option value="0" <?php echo $selected; ?>>No</option>
						<option value="1" <?php echo $selected; ?>>Yes</option>
					  </select>
					  
				  </div></td>
				  <td>
				  </td>
		  </tr>			
                     
        </table>
		<input name="Button" type="button" onclick="loadmypage('role_centers_list.php?'+
        											'&RoleCenterName='+this.form.RoleCenterName.value+
													'&IsAdmin='+this.form.IsAdmin.value+
													'&DefaultMenuGroupID='+this.form.DefaultMenuGroupID.value+
													'&MaximumApprovalLimit='+this.form.MaximumApprovalLimit.value+
													'&BeyondLimitApproverID='+this.form.BeyondLimitApproverID.value+
                                                    '&RoleCenterID='+<?php echo $RoleCenterID ?>+
                                                    '&save=1','content','loader','listpages','','RoleCenters')" value="Save">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('role_centers_list.php?i=1','content','loader','listpages','','RoleCenters')">
        <span class="table_text">
        

        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>