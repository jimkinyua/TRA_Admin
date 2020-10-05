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

$HouseID="0";
$HouseNumber="";
$EstateID="";
//print_r($_REQUEST);
$EstateID=$_REQUEST['EstateID'];
$EstateName=$_REQUEST['EstateName'];
$MonthlyRent=0;

if (isset($_REQUEST['edit']))
{	
	if (isset($_REQUEST['HouseID'])){$HouseID=$_REQUEST['HouseID'];}
	
	$sql = "SELECT * FROM Houses where HouseID = '$HouseID'";
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$HouseNumber=$myrow['HouseNumber'];
		$EstateID=$myrow['EstateID'];
	}	
}
?>
<script type="text/javascript">
        $(".datepicker").datepicker();
</script>

<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit Houses</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
          <tr>
              <td><label>Estate</label>
                    <div class="input-control select" data-role="input-control">
                        
                        <select name="EstateID"  id="EstateID" disabled="">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT * FROM Estates ORDER BY EstateID";
                            
                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                {
                                    $s_id = $row["EstateID"];
                                    $s_name = $row["EstateName"];
                                    if ($EstateID==$s_id) 
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
                <td width="50%">
                    <label>House Number</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="HouseNumber" name="HouseNumber" value="<?php echo $HouseNumber; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr>
            <tr>
                <td width="50%">
                    <label>Monthly Rent</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="MonthlyRent" name="MonthlyRent" value="<?php echo $MonthlyRent; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr>
            <tr>
                <td width="50%">
                    <label>Start Of Billing</label>
                    <div class="input-control text datepicker" data-role="input-control" data-format="dd/mm/yyyy">           
                        <input type="text" id="FromDate" name="FromDate"></input>
                        <button class="btn-date" type="button"></button>        
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr> 
			                           
                     
        </table>
		<input name="Button" type="button" onclick="loadmypage('Houses_list.php?'+
        											'&HouseNumber='+this.form.HouseNumber.value+
                                                    '&EstateID='+this.form.EstateID.value+
                                                    '&MonthlyRent='+this.form.MonthlyRent.value+
                                                    '&FromDate='+this.form.FromDate.value+
                                                    '&HouseID='+<?php echo $HouseID ?>+
                                                    '&save=1','content','loader','listpages','','Houses','<?php echo $EstateID ?>')" value="Save">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('Houses_list.php?EstateID=<?php echo $EstateID; ?>&EstateName=<?php echo $EstateName; ?>','content','loader','listpages','','Houses','<?php echo $EstateID ?>')">
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>