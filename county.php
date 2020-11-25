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

$PeriodID="0";
$StartDate="";

if (isset($_REQUEST['edit']))
{	
	if (isset($_REQUEST['CountyID'])){$CountyID=$_REQUEST['CountyID'];}
	
    $sql = "select * from Counties as C join 
    SubSystems as SubSys on SubSys.SubSystemID =
     C.TraRegionCode WHERE C.CountyId =  $CountyID";
    // exit($sql);
    
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$TraRegionCode=$myrow['TraRegionCode'];
		$CountyId=$myrow['CountyId'];
		$CountyName=$myrow['CountyName'];
	}	
}
?>
<link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/prettify/prettify.css" rel="stylesheet">
<script type="text/javascript">
        $(".datepicker").datepicker();
</script>

<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit County</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
          <tr>
                <td width="50%">
                    <label> County ID</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" readonly id="CountyID" name="CountyID" value="<?php echo $CountyId; ?>"></input>
                    </div>
                </td>
                <td width="50%">
            
                 </td>
            </tr>
          
			<tr>
                <td width="50%">
                    <label> County Name</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="CountyName" name="CountyName" value="<?php echo $CountyName; ?>"></input>
                    </div>
                </td>
                <td width="50%">
            
                 </td>
            </tr>
            <tr>
                <td width="50%">
                    <label>TRA Region Associated With</label>
                    <div class="input-control text" data-role="input-control">
                    <select name="Region"  id="Region">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT * FROM SubSystems ORDER BY 1";
                            
                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                {
                                    $s_id = $row["SubSystemID"];
                                    $s_name = $row["SubSystemName"];
                                    if ($TraRegionCode==$s_id) 
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
                <td width="50%">
            
                 </td>
            </tr>
           
        </table>
		<input name="Button" type="button" onclick="loadmypage('county_list.php?'+
        											'&Region='+this.form.Region.value+
                                                    '&CountyID='+this.form.CountyID.value+
													'&CountyName='+this.form.CountyName.value+
                                                    '&save=1','content','loader','listpages','','Counties')" value="Save">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('county_list.php?i=1','content','loader','listpages','','Counties')">
        <span class="table_text">
        <input name="UserID" type="hidden" id="DeviceID" value="<?php echo $DeviceID;?>" />

        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>