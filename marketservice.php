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

$MarketServiceID="0";
$MarketID="";
$ServiceID="";

if (isset($_REQUEST['edit']))
{	
	if (isset($_REQUEST['MarketServiceID'])){$MarketServiceID=$_REQUEST['MarketServiceID'];}
	
	$sql = "SELECT * FROM MarketServices where MarketServiceID = '$MarketServiceID'";
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$MarketID=$myrow['MarketID'];
		$ServiceID=$myrow['ServiceID'];
	}
}
?>
<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit Market Service</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
            <tr>
			  <td><label>Market</label>
                    <div class="input-control select" data-role="input-control">
                    	
                    	<select name="MarketID"  id="MarketID">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT * FROM Markets ORDER BY MarketID";
                            
                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                {
                                    $s_id = $row["MarketID"];
                                    $s_name = $row["MarketName"];
                                    if ($MarketID==$s_id) 
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
                <td width="50%">
            
                    </td>
            </tr> 
			<tr>
			  <td><label>Service</label>
                    <div class="input-control select" data-role="input-control">
                    	
                    	<select name="ServiceID"  id="ServiceID">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT * FROM Services ORDER BY serviceID";
                            
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
		<input name="Button" type="button" onclick="loadmypage('marketservice_list.php?'+
                                                    '&ServiceID='+this.form.ServiceID.value+
                                                    '&MarketID='+this.form.MarketID.value+
                                                    '&MarketServiceID='+<?php echo $MarketServiceID ?>+
                                                    '&save=1','content','loader','listpages','','marketservices')" value="Save">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('marketservice_list.php?i=1','content','loader','listpages','','marketservices')">
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>