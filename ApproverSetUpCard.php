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
	if (isset($_REQUEST['ApproverId'])){$ApproverId=$_REQUEST['ApproverId'];}
	
    $sql = "select *  from RequestTypeApprovers as RTY 
    join Agents as Ag on Ag.AgentId = RTY.ApproverID 
    join RequestTypes as Rt on Rt.RequestTypeID = RTY.RequestTypeID 
	WHERE RTY.RequestTypeID = 14 AND RTY.ApproverId= $ApproverId";
    // exit($sql);
    
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$ApproverId=$myrow['ApproverID'];
		$ApprovalOrder=$myrow['ApprovalOrder'];
        $ID=$myrow['ID'];
        $PeriodID="1";
        $RequestTypeID = $myrow['RequestTypeID'];
        $RequestType = $myrow['RequestType'];

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
	  <legend>Add/Edit Approver</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
          <tr>
                <td width="50%">
                    <label> ApproverOrder </label>
                    <div class="input-control text" data-role="input-control">
                        <input type="hidden"  id="RequestTypeID" name="RequestTypeID" value="<?php echo $RequestTypeID; ?>"></input>
                        <input type="hidden"  id="ID" name="ID" value="<?php echo $ID; ?>"></input>
                        <input type="number"  id="ApproverOrder" name="ApproverOrder" value="<?php echo $ApprovalOrder; ?>"></input>
                    </div>
                </td>
                <!-- <td width="50%">
            
                 </td> -->
            </tr>

            <tr>
                <td width="50%">
                    <label>Approver</label>
                    <div class="input-control select" data-role="input-control">
                    <select name="Approver"  id="Approver">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT AgentID, FirstName, MiddleName, LastName FROM Agents ORDER BY 1";
                            
                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                {
                                    $s_id = $row["AgentID"];
                                    $s_name = $row["FirstName"].' '.$row["MiddleName"].' '.$row["LastName"];
                                    if ($ApproverId==$s_id) 
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
            <tr>
            <td width="50%">
            <label>Request Type</label>

                <div class="input-control text" data-role="input-control">
                    <input type="number"  id="RequestType" name="RequestType" value="<?php echo $RequestType; ?>"></input>

                </div>
            </td>
            </tr>

           
        </table>
		<input name="Button" type="button" onclick="loadmypage('RequestApprovers.php?'+
        											'&ApproverOrder='+this.form.ApproverOrder.value+
                                                    '&Approver='+this.form.Approver.value+
                                                    '&ID='+this.form.ID.value+
                                                    '&RequestTypeID'+this.form.RequestTypeID.value+
                                                    '&save=1','content','loader','listpages','','RequestTypeApprovers')" value="Save">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('RequestApprovers.php?i=1','content','loader','listpages','','RequestTypeApprovers')">
        <span class="table_text">
        <input name="UserID" type="hidden" id="DeviceID" value="<?php echo $DeviceID;?>" />

        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>