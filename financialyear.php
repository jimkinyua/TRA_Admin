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

$FinancialYearID="0";
$FinancialYearName="";
$TargetCollection="0";
$default='0';

if (isset($_REQUEST['edit']))
{	
	print_r($_REQUEST);
	if (isset($_REQUEST['financialyearID'])){$FinancialYearID=$_REQUEST['financialyearID'];}
	
	$sql = "SELECT * FROM FinancialYear where FinancialYearID = '$FinancialYearID'";
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$FinancialYearName=$myrow['FinancialYearName'];
		$TargetCollection=$myrow['TargetCollection'];
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
	  <legend>Add/Edit FinancialYears</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
            <tr>
                <td width="50%">
                    <label>FinancialYear Name</label>
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="FinancialYearName" name="FinancialYearName" value="<?php echo $FinancialYearName; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                    </td>
            </tr> 
            <tr>
                <td width="50%">
                    <label>Start Date</label>
                    <div class="input-control text datepicker" data-role="input-control" data-format="dd/mm/yyyy">
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
                    <div class="input-control text datepicker" data-role="input-control" data-format="dd/mm/yyyy">
                        <input type="text" id="EndDate" name="EndDate" value="<?php echo $EndDate; ?>"></input>
                        <button class="btn-date" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">
            
                 </td>
            </tr>
			<tr>
			  <td width="50%"><label>Target Collection</label>                
                    <div class="input-control text" data-role="input-control">
                        <input type="text" id="TargetCollection" name="TargetCollection" value="<?php echo $TargetCollection; ?>"></input>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
			  <td>
			  </td>
			</tr>

          <tr>
			  <td><label>Is Default Year</label>
                    <div class="input-control select" data-role="input-control">
                    	<select name="default"  id="default">
						<?php 
							$selected="";
							if ($default=="1")
							{
								$selected="selected";
							}							
							
						?>
                        <option value="0" <?php echo $selected; ?>>No</option>
                        <option value="1" <?php echo $selected; ?>>Yes</option>
                      </select>
					  
                  </div></td>
                  <td>
                  </td>
          </tr>			
                     
        </table>
		<input name="Button" type="button" onClick="loadmypage('FinancialYear_list.php?'+
        											'&FinancialYearName='+this.form.FinancialYearName.value+ 
													'&TargetCollection='+this.form.TargetCollection.value+
                                                    '&FinancialYearID='+<?php echo $FinancialYearID ?>+
													'&default='+this.form.default.value+
                                                    '&save=1','content','loader','listpages','','FinancialYears')" value="Save">
                                                    
      <input type="reset" value="Cancel" onClick="loadmypage('financialyear_list.php?i=1','content','loader','listpages','','FinancialYear')">
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>