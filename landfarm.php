<?php 
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];
$FarmID=0;

if (isset($_REQUEST['msg']))
{
	$msg = $_REQUEST['msg'];	
}

$FarmID="0";
$FarmName="";

if (isset($_REQUEST['FarmID']))
{
    $FarmID=$_REQUEST['FarmID'];
    $FarmName = $_REQUEST['FarmName']; 
    $LocalAuthorityID=$_REQUEST['LocalAuthorityID'];   
}

?>
<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit Land Farm</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
		<tr>
		  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		</tr>
		<tr>
			<td width="50%">
				<label>Farm Name</label>
				<div class="input-control text" data-role="input-control">
					<input type="text" id="FarmName" name="FarmName" value="<?php echo $FarmName; ?>"></input>
					<button class="btn-clear" tabindex="-1"></button>
				</div>
			</td>
			<td width="50%">
		
			</td>
		</tr>
		<tr>
			 <td><label>Local Authority</label>
                    <div class="input-control select" data-role="input-control">
                    	
                    	<select name="LocalAuthorityID"  id="LocalAuthorityID">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT * FROM LocalAuthority ORDER BY 1";
                            
                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                {
                                    $s_id = $row["LocalAuthorityID"];
                                    $s_name = $row["LocalAuthorityName"];
                                    if ($LocalAuthorityID==$s_id) 
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
		<input name="Button" type="button" onclick="loadmypage('landfarms_list.php?'+
        											'&FarmName='+this.form.FarmName.value+
        											'&LocalAuthorityID='+this.form.LocalAuthorityID.value+
        											'&FarmID='+<?php echo $FarmID; ?>+									
                                                    '&save=1','content','loader','listpages','','LandFarms')" value="Save">
        

        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>