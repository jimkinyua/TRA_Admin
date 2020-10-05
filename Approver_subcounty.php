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

$SetupID="0";

if (isset($_REQUEST['edit']))
{	
	if (isset($_REQUEST['SetupID'])){$SetupID=$_REQUEST['SetupID'];}
	
	$sql = "SELECT * FROM ApproverSetup where ID = '$SetupID'";
	$result = sqlsrv_query($db, $sql);	
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
        $UserID=$myrow['UserID'];
		$SubCountyID=$myrow['SubCountyID'];
        $ApproverTypeID=$myrow['ApproverTypeID'];
	}	
}
?>
<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit Approver and Designated Regions</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
            <tr>
			  <td><label>UserName</label>
                    <div class="input-control select" data-role="input-control">
                    	
                    	<select name="UserID"  id="UserID">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "select a.agentid UserID,a.FirstName+' '+a.MiddleName+' '+a.LastName UserFullNames 
								from agents a join users u on a.AgentID=u.AgentID order by a.FirstName+' '+a.MiddleName+' '+a.LastName";
                            
                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                {
                                    $s_id = $row["UserID"];
                                    $s_name = $row["UserFullNames"];
                                    if ($UserID==$s_id) 
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
                    
                  </div>
				  </td>
			  <td></td>
		  </tr> 
			<tr>
			  <td><label>Subcounty</label>
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
                            <?php 
                                }
                            }
                            ?>
                      </select>
                    
                  </div></td>
			  <td></td>
		  </tr>
                                  
                     
        </table>
		<input name="Button" type="button" onclick="loadmypage('ApproverSetup.php?'+
                                                    '&SubCountyID='+this.form.SubCountyID.value+
                                                    '&UserID='+this.form.UserID.value+                                             
                                                    '&SetupID='+<?php echo $SetupID ?>+
                                                    '&edit=1','content','loader','listpages','','ApproversList','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" value="Save">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('user_roles_list.php?i=1','content','loader','listpages','','UserRoles')">
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>