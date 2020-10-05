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

$EstateID="0";
$EstateName="";
$ServiceID="0";

if (isset($_REQUEST['edit']))
{   
    //print_r($_REQUEST);
    $EstateID=$_REQUEST['EstateID'];
    $EstateName=$_REQUEST['EstateName'];
    //$ServiceID=$_REQUEST['ServiceID'];

    $sql="select isnull(ServiceID,0)ServiceID from Estates where EstateID='$EstateID'";
    $result=sqlsrv_query($db,$sql);
    while($rs=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
    	$ServiceID=$rs['ServiceID'];
    }
}

?>
<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit Estate</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
		<tr>
		  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		</tr>
		<tr>
			<td width="50%">
				<label>Esate Name</label>
				<div class="input-control text" data-role="input-control">
					<input type="text" id="EstateName" name="EstateName" value="<?php echo $EstateName; ?>"></input>
					<button class="btn-clear" tabindex="-1"></button>
				</div>
			</td>
			<td width="50%">
		
				</td>
		</tr>
		<tr>
			  <td><label>Associated Service</label>
				<div class="input-control select" data-role="input-control">
					<select name="ServiceID"  id="ServiceID">
					<option value="0" selected="selected"></option>
					<?php 
					$s_sql = "SELECT * FROM Services ORDER BY ServiceName";
					
					$s_result = sqlsrv_query($db, $s_sql);
					if ($s_result) 
					{ //connection succesful 
						while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
						{
							$s_id = $row["ServiceID"];
							$s_name = $row["ServiceName"];
							if ($ServiceID==$s_id) 
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
		<input name="Button" type="button" onclick="loadmypage('estates_list.php?'+
				'&EstateName='+this.form.EstateName.value+
				'&ServiceID='+this.form.ServiceID.value+
				'&EstateID='+<?php echo $EstateID; ?>+			
                '&save=1','content','loader','listpages','','Estates')" value="Save">
        

        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>