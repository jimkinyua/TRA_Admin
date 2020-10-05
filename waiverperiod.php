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
	if (isset($_REQUEST['PeriodID'])){$PeriodID=$_REQUEST['PeriodID'];}
	
	$sql = "SELECT * FROM WaiverPeriods where PeriodID = '$PeriodID'";
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$StartDate=$myrow['StartDate'];
		$EndDate=$myrow['EndDate'];
		$MemoNo=$myrow['MemoNo'];
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
	  <legend>Add/Edit Waiver Period</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
            <tr>
                <td width="50%">
                    <label>Start Date</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="StartDate" name="StartDate" value="<?php echo $StartDate; ?>"></input>
                        <button class="btn-date" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                 </td>
            </tr>
			<tr>
                <td width="50%">
                    <label>End Date</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="EndDate" name="EndDate" value="<?php echo $EndDate; ?>"></input>
                        <button class="btn-date" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                 </td>
            </tr>
            <tr>
                <td width="50%">
                    <label>Percentage to Waive</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="WaiverPercentage" name="WaiverPercentage" value="<?php echo $WaiverPercentage; ?>"></input>                        
                    </div>
                </td>
                <td width="50%">
            
                 </td>
            </tr>
			<tr>
                <td width="50%">
                    <label>MemoNo</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="MemoNo" name="MemoNo" value="<?php echo $MemoNo; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                 </td>
            </tr>            
        </table>
		<input name="Button" type="button" onclick="loadmypage('WaiverPeriods_list.php?'+
        											'&StartDate='+this.form.StartDate.value+
													'&EndDate='+this.form.EndDate.value+
													'&MemoNo='+this.form.MemoNo.value+
                                                    '&PeriodID='+<?php echo $PeriodID ?>+
                                                    '&WaiverPercentage='+this.form.WaiverPercentage.value+
                                                    '&save=1','content','loader','listpages','','WaiverPeriods')" value="Save">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('WaiverPeriods_list.php?i=1','content','loader','listpages','','WaiverPeriods')">
        <span class="table_text">
        <input name="UserID" type="hidden" id="DeviceID" value="<?php echo $DeviceID;?>" />

        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>