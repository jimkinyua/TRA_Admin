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

$MiscellaneousServiceID="0";
$MiscellaneousServiceName="";
$ServiceID=$_REQUEST['ServiceID'];
$ServiceName='';



if (isset($_REQUEST['edit']))
{	
	if (isset($_REQUEST['MiscellaneousServiceID'])){$MiscellaneousServiceID=$_REQUEST['MiscellaneousServiceID'];}
	
	$sql = "SELECT ms.*,s.ServiceName FROM MiscellaneousServices ms inner join Services s on ms.ServiceID=s.ServiceID where ms.MiscellaneousServiceID = '$MiscellaneousServiceID'";
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$MiscellaneousServiceName=$myrow['MiscellaneousServiceName'];
		$ServiceID=$myrow['ServiceID'];
		$ServiceName=$myrow['ServiceName'];
	}	
}
?>
<div class="example">
<Service>
	<fieldset>
	  <legend>Add/Edit <?php echo $MiscellaneousServiceName; ?> Services</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
            <tr>
                <td width="50%">
                    <label>Miscellaneous Service Name</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="MiscellaneousServiceName" name="MiscellaneousServiceName" value="<?php echo $MiscellaneousServiceName; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr> 
			<tr>
			  <td><label>Service</label>
                    <div class="input-control select" data-role="input-control">
                    	
                    	<select name="ServiceID"  id="ServiceID">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT * FROM Services ORDER BY ServiceID";
                            
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
		<input name="Button" type="button" onclick="loadmypage('miscellaneous_services_list.php?'+
        											'&MiscellaneousServiceName='+this.Service.MiscellaneousServiceName.value+
                                                    '&ServiceID='+this.Service.ServiceID.value+
                                                    '&MiscellaneousServiceID='+<?php echo $MiscellaneousServiceID; ?>+
                                                    '&save=1','constent','loader','listpages','','MiscellaneousServices','<?php echo $ServiceID; ?>')" value="Save">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('Service_sections_list.php?ServiceID=<?php echo $ServiceID; ?>&ServiceName=<?php echo $ServiceName; ?>','content','loader','listpages','','MiscellaneousServices','<?php echo $ServiceID; ?>')">
      												
                                                    <!--loadmypage('Service_sections_list.php?ServiceID=2&ServiceName=Single Business Permit','content','loader','listpages','','MiscellaneousServices',2) -->
        <div style="margin-top: 20px">
</div>

	</fieldset>
</Service>
</div>