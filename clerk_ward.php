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

$SerialNo="";
$DevicePin="";
$UserID="";
$WardID="";
$RecordID ="0";

$UserName='';

if (isset($_REQUEST['RecordID'])) { $RecordID = $_REQUEST['RecordID']; } 

if (isset($_REQUEST['edit']))
{	
	$RecordID = $_REQUEST['RecordID'];
	$sql = "SELECT cl.*,ag.UserName 
		FROM ClerkWard cl 
		inner join Users ag on cl.UserID=ag.AgentID 
        inner join Wards wd on cl.WardID=wd.WardID
		where ID = '$RecordID'";
	
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$WardID=$myrow['WardID'];
		$UserID=$myrow['UserID'];		
		
	}	
	//echo $sql;
}
?>

<div class="example">
<form>
	<fieldset>
	  <legend>Clerk to Ward Mapping</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		    </tr>
			<tr>
			  <td width="50%"><label>User</label>
                    <div class="input-control select" data-role="input-control">

                        <select name="UserID1"  id="UserID1">
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
                    
                  </div></td>			  
		  </tr> 
          <tr>
              <td><label>Ward</label>
                    <div class="input-control select" data-role="input-control">
                        
                        <select name="WardID"  id="WardID">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT WardID,WardName FROM Wards ORDER BY WardName";
                            
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
		<input name="Button" type="button" onclick="loadmypage('clerk_wards_list.php?'+    											
                                                    '&UserID='+this.form.UserID1.value+
                                                    '&WardID='+this.form.WardID.value+
                                                    '&RecordID='+<?php echo $RecordID; ?>+
                                                    '&save=1','content','loader','listpages','','ClerkWards')" value="Save">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('clerk_wards_list.php?i=1','content','loader','listpages','','user_devices')">
        <span class="table_text">
        <input name="UserID" type="hidden" id="DeviceID" value="<?php echo $DeviceID;?>" />
        <input name="add" type="hidden" id="add" value="<?php echo $new;?>" />
        <input name="edit" type="hidden" id="edit" value="<?php echo $edit;?>" />
        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>