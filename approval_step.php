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


$ServiceApprovalStepID=0;



if (isset($_REQUEST['edit']))
{	
	$ServiceApprovalStepID=$_REQUEST['ServiceApprovalStepID'];

	
	$sql="select * from ServiceApprovalSteps where ServiceApprovalStepID=$ServiceApprovalStepID";
	$result=sqlsrv_query($db,$sql);
	$rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
	if ($result)
	{
		$Step=$rw['step'];
		$ServiceStatusID=$rw['ServiceStatusID'];
		$ServiceCategoryID=$rw['ServiceCategoryID'];
	}
	
	
}
?>
<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit <?php echo $CategoryName; ?> Workflow</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
            <tr>
                <td width="50%">
                    <label>Step</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="Step" name="Step" value="<?php echo $Step; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr> 
			<tr>
			  <td><label>Approval Status</label>
                    <div class="input-control select" data-role="input-control">
                    	
                    	<select name="ServiceStatusID"  id="ServiceStatusID">
                            <option value="0" selected="selected"></option>
                            <?php 
                            	$s_sql = "SELECT * FROM ServiceStatus ORDER BY 1";							
                            
								$s_result = sqlsrv_query($db, $s_sql);
								if ($s_result) 
								{ //connection succesful 
									while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
									{
										$s_id = $row["ServiceStatusID"];
										$s_name = $row["ServiceStatusName"];
										if ($ServiceStatusID==$s_id) 
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
		<input name="Button" type="button" onclick="loadmypage('approval_steps_list.php?'+
        											'&Step='+this.form.Step.value+
                                                    '&ServiceStatusID='+this.form.ServiceStatusID.value+                                                    
                                                    '&ServiceApprovalStepID='+<?php echo $ServiceApprovalStepID; ?>+
                                                    '&ServiceCategoryID='+<?php echo $ServiceCategoryID; ?>+
                                                    '&save=1','content','loader','listpages','','AprovalSteps','<?php echo $ServiceCategoryID; ?>')" value="Save">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('approval_steps_list.php?ServiceCategoryID=<?php echo $ServiceCategoryID; ?>,'content','loader','listpages','','AprovalSteps','<?php echo $ServiceCategoryID; ?>')"> 												
                                                    
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>