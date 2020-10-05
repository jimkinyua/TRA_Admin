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

$AssetID="0";
$AssetName="";
$AssetTypeID="";
$RegistrationNumber='';

if (isset($_REQUEST['edit']))
{	
	if (isset($_REQUEST['AssetID'])){$AssetID=$_REQUEST['AssetID'];}
	
	$sql = "SELECT * FROM Assets where AssetID = '$AssetID'";
    
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$AssetName=$myrow['AssetName'];
        $RegistrationNumber=$myrow['RegistrationNumber'];
		$AssetTypeID=$myrow['AssetTypeID'];
        $DepartmentID=$myrow['DepartmentID'];
        $DepreciationRate=$myrow['DepreciationRate'];
        $AcquisitionDate=$myrow['AcquisitionDate'];
        $Remarks=$myrow['Remarks'];
        $AcquisitionCost=$myrow['AcquisitionCost'];
        
	}	
}
?>
<script type="text/javascript">     
    $(document).ready(function() {
            $("#Depreciation").keydown(function(event) {
                // Allow only backspace and delete
                if ( event.keyCode == 46 || event.keyCode == 8|| event.keyCode == 190 ) {
                    // let it happen, don't do anything
                }
                else {
                    // Ensure that it is a number and stop the keypress
                    if (event.keyCode < 48 || event.keyCode > 57 ) {
                        event.preventDefault(); 
                    }   
                }
            });
        });

</script>
<script type="text/javascript">
     $(".datepicker").datepicker();
</script>

<body class="metro">
<div class="example">
<form>
	<fieldset>
	  <legend>Add/Edit Asset</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
            <tr>
                <td width="50%">
                    <label>Asset Name</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="AssetName" name="AssetName" value="<?php echo $AssetName; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr> 
            <tr>
                <td width="50%">
                    <label>Registration Number (Identyty Number)</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="RegistrationNumber" name="RegistrationNumber" value="<?php echo $RegistrationNumber; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr>
            <tr>
                <td width="50%">
                    <label>Acquisition Cost</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="AcquisitionCost" name="AcquisitionCost" value="<?php echo $AcquisitionCost; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr>
            <tr>
                <td width="50%"><label>Acquisition Date</label>
                    <div class="input-control text datepicker" data-role="input-control">                       
                        <input type="text" id="AcquisitionDate" name="AcquisitionDate" value="<?php echo $AcquisitionDate; ?>"></input>
                        <button class="btn-date" type="button"></button>                
                    </div>
                </td>
                <td width="50%">
            
                </td>
          </tr>
            <tr>
                <td width="50%">
                    <label>Depreciation Rate</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="Depreciation" name="Depreciation" value="<?php echo $DepreciationRate; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr>
            <tr>
              <td><label>Department</label>
                    <div class="input-control select" data-role="input-control">
                        
                        <select name="DepartmentID"  id="DepartmentID">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT * FROM Departments ORDER BY 1";
                            
                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                {
                                    $s_id = $row["DepartmentID"];
                                    $s_name = $row["DepartmentName"];
                                    if ($DepartmentID==$s_id) 
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
                    <label>Remarks</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="Remarks" name="Remarks" value="<?php echo $Remarks; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr>
			<tr>
			  <td><label>Asset Type</label>
                    <div class="input-control select" data-role="input-control">
                    	
                    	<select name="AssetTypeID"  id="AssetTypeID">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT * FROM AssetTypes ORDER BY 1";
                            
                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                {
                                    $s_id = $row["AssetTypeID"];
                                    $s_name = $row["AssetTypeName"];
                                    if ($AssetTypeID==$s_id) 
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
		<input name="Button" type="button" onclick="loadmypage('assets_list.php?'+
        											'&AssetName='+this.form.AssetName.value+
                                                    '&AssetTypeID='+this.form.AssetTypeID.value+
                                                    '&DepartmentID='+this.form.DepartmentID.value+
                                                    '&DepreciationRate='+this.form.Depreciation.value+
                                                    '&Remarks='+this.form.Remarks.value+
                                                    '&AcquisitionDate='+this.form.AcquisitionDate.value+
                                                    '&RegistrationNumber='+this.form.RegistrationNumber.value+
                                                    '&AcquisitionCost='+this.form.AcquisitionCost.value+
                                                    '&AssetID='+<?php echo $AssetID ?>+
                                                    '&save=1','content','loader','listpages','','Assets')" value="Save">
       
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('asset_list.php?i=1','content','loader','listpages','','Assets')">
        <span class="table_text">

        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>
</body>