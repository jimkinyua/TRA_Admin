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

$CustomerID=0;
$ParkID=0;
$RouteID=0;
$CapacityID=0;
$CustomerName='';
$VehicleID=0;
$RegNo='';

$ChargeID=0;

if (isset($_REQUEST['VehicleID'])){$VehicleID=$_REQUEST['VehicleID'];}


$sql="select cv.RegNo,bp.ParkID,bp.ParkName,mr.RouteID,mr.RouteName,bp.ParkID,sc.ID,sc.SittingCapacity,c.CustomerName,c.CustomerID 
        from CustomerVehicles cv
        join BusParks bp on cv.BusParkID=bp.ParkID 
        join MatatuRoutes mr on cv.[Route]=mr.RouteID
        join SittingCapacity sc on cv.SittingCapacity=sc.ID
        join Customer c on cv.CustomerID=c.CustomerID
        where cv.VehicleID=$VehicleID";

//echo $sql;

$result = sqlsrv_query($db, $sql);
while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
{
    $ParkID=$myrow['ParkID'];
    $CapacityID=$myrow['ID'];
    $RouteID=$myrow['RouteID'];
    $CustomerName=$myrow['CustomerName'];
    $RegNo=$myrow['RegNo'];
    $CustomerID=$myrow['CustomerID'];

}




?>
<div class="example">
<form>
	<fieldset>
	  <legend><?php echo $CustomerName; ?> Vehicles</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
          <tr>
              <td width="50%">
                  <div class="input-control text" data-role="input-control">
                      <input name="RegNo" type="text" id="RegNo" value="<?php echo $RegNo; ?>">                          
                  </div>                
             </td>
             <td>
                 
             </td>
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
                <td><label>Sitting Capacity</label>
                    <div class="input-control select" data-role="input-control">
                        <select name="CapacityID"  id="CapacityID">
                        <option value="0" selected="selected"></option>
                        <?php 
                        $s_sql = "SELECT * FROM SittingCapacity ORDER BY SittingCapacity";
                        
                        $s_result = sqlsrv_query($db, $s_sql);
                        if ($s_result) 
                        { //connection succesful 
                            while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                            {
                                $s_id = $row["ID"];
                                $s_name = $row["SittingCapacity"];
                                if ($CapacityID==$s_id) 
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
                <td><label>Sitting Capacity</label>
                    <div class="input-control select" data-role="input-control">
                        <select name="ParkID"  id="ParkID">
                        <option value="0" selected="selected"></option>
                        <?php 
                        $s_sql = "SELECT * FROM BusParks ORDER BY ParkName";
                        
                        $s_result = sqlsrv_query($db, $s_sql);
                        if ($s_result) 
                        { //connection succesful 
                            while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                            {
                                $s_id = $row["ParkID"];
                                $s_name = $row["ParkName"];
                                if ($ParkID==$s_id) 
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
                     
        </table>
		<input name="Button" type="button" onclick="loadmypage('sacco_vehicles_list.php?'+
                                            '&RouteID='+this.form.RouteID.value+
                                            '&RegNo='+this.form.RegNo.value+
                                            '&CapacityID='+this.form.CapacityID.value+ 
                                            '&ParkID='+this.form.ParkID.value+                                               
                                            '&VehicleID='+<?= $VehicleID; ?>+      
        									'&save=1','content','loader','listpages','','CustomerVehicles','<?php echo $CustomerID; ?>')" value="Save">
      <input type="reset" value="Cancel" onClick="loadmypage('sacco_vehicles_list.php?CustomerID=<?php echo $CustomerID; ?>','content','loader','listpages','','CustomerVehicles','<?php echo $CustomerID; ?>')">
	  
	
	  
        <span class="table_text">

        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>