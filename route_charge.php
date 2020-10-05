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

$ServiceID='';
$RouteID='';
$$ServiceCharge='';
$ServiceName='';
$PermitCost=0;

$ChargeID=0;
if (isset($_REQUEST['RouteID'])){$RouteID=$_REQUEST['RouteID'];}


if (isset($_REQUEST['edit']))
{   
    if (isset($_REQUEST['ChargeID'])){$ChargeID=$_REQUEST['ChargeID'];}
    
    $sql = "SELECT * FROM RouteCharges where ChargeID = '$ChargeID'";

    $result = sqlsrv_query($db, $sql);
    while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
    {
        $FromCapacity=$myrow['FromCapacity'];
        $ToCapacity=$myrow['ToCapacity'];
        $Amount=$myrow['Amount'];
        $RouteID=$myrow['RouteID'];
    }   
}

?>
<div class="example">
<form>
	<fieldset>
	  <legend>ROUTE CHARGES SETUP</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
          <tr>
              <td><label>Route</label>
                    <div class="input-control select" data-role="input-control">
                        <select name="RouteID"  id="RouteID">
                        <option value="0" selected="selected"></option>
                        <?php 
                        $s_sql = "SELECT * FROM MatatuRoutes ORDER BY 1";
                        
                        $s_result = sqlsrv_query($db, $s_sql);
                        if ($s_result) 
                        { //connection succesful 
                            while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                            {
                                $s_id = $row["RouteID"];
                                $s_name = $row["RouteName"];
                                if ($RouteID==$s_id) 
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
                	<label>Sitting Capacity (From)</label>
                	<div class="input-control text" data-role="input-control">   
                         <input type="text" name="FromCapacity" id="FromCapacity" value="<?php echo $FromCapacity; ?>">                   	
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">

                </td>
          	</tr>
            <tr>
                <td width="50%">
                    <label>Sitting Capacity (To)</label>
                    <div class="input-control text" data-role="input-control"> 
                        <input type="text" name="ToCapacity" id="ToCapacity" value="<?php echo $ToCapacity; ?>">                    
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">

                </td>
            </tr>          
			 
 
			<tr>
                <td width="50%">
                	<label>ROUTE Amount</label>
                	<div class="input-control text" data-role="input-control">
                    	<input type="text" name="Amount" id="Amount" value="<?php echo $Amount; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">

                </td>
          	</tr>                              
                     
        </table>
		<input name="Button" type="button" onclick="loadmypage('route_charges_list.php?'+
                                            '&RouteID='+this.form.RouteID.value+
                                            '&FromCapacity='+this.form.FromCapacity.value+ 
                                            '&ToCapacity='+this.form.ToCapacity.value+ 
                                            '&Amount='+this.form.Amount.value+   
                                            '&ChargeID='+<?= $ChargeID; ?>+      
        									'&save=1','content','loader','listpages','','RouteCharges')" value="Save">
      <input type="reset" value="Cancel" onClick="loadmypage('route_charges_list.php?1=1','content','loader','listpages','','RouteCharges')">
	  
	
	  
        <span class="table_text">

        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>