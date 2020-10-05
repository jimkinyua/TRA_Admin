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

$RevenueStreamName='';
$Description='';
$RevenueStreamCode='';
$RevenueCategoryID='';
$FormID='';
$CreatedDate="";
$PrimaryService="";
$Amount=0;

$RevenueStreamID=$_REQUEST['RevenueStreamID'];
$sql="Select rs.RevenueStreamName,isnull(rb.Amount,0)Amount,rb.FinancialYearID 
from RevenueStreams rs 
left join RevenueBudget rb on rb.RevenueStreamID=rs.RevenueStreamID 
left join FinancialYear fy on rb.FinancialYearID=fy.FinancialYearID
where rs.RevenueStreamID='$RevenueStreamID'";

$result = sqlsrv_query($db, $sql);
while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
{
	$RevenueStreamName=$myrow['RevenueStreamName'];
	$Amount=$myrow['Amount'];
}

if ($_REQUEST['save']==1)
{
	$RevenueStreamID=0;
	$Amount=0;
	$FinancialYearID=0;
	if (isset($_REQUEST['RevenueStreamID'])){$RevenueStreamID=$_REQUEST['RevenueStreamID'];};
	if (isset($_REQUEST['FinancialYearID'])){$FinancialYearID=$_REQUEST['FinancialYearID'];};
	if (isset($_REQUEST['Amount'])){$Amount=$_REQUEST['Amount'];};
	
	
	if ($Amount==0){
		$msg="The Amount must be set";
	}else if($FinancialYearID==0){
		$msg="The FinancialYearID must be set";
	}else{
		$sql="if not Exists(select 1 from RevenueBudget where RevenueStreamID='$RevenueStreamID' and FinancialYearID='$FinancialYearID')
		begin Insert into RevenueBudget (RevenueStreamID,FinancialYearID,Amount) Values('$RevenueStreamID','$FinancialYearID',$Amount) 
		end else begin
		Update RevenueBudget 
		set Amount='$Amount' 
		where RevenueStreamID=$RevenueStreamID and FinancialYearID='$FinancialYearID' End";
		$result=sqlsrv_query($db,$sql);
		if($result){
			$msg="Revenue Budget Saved Successfully";
		}else{
			DisplayErrors();
		}
		
	}
}


?>
<div class="example">
<form>
	<fieldset>
	  <legend>Revenue Stream Budget</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
			<tr>
                <td width="50%">
                	<label>Revenue Stream Name</label>
					
                	<div class="input-control text" data-role="input-control">
						<input name="RevenueStreamName" id="RevenueStreamName" type="text" value="<?php echo $RevenueStreamName; ?>" disabled></input>                    	
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">

				</td>
			</tr>
			<tr>
				<td><label>Financial Year</label>
                    <div class="input-control select" data-role="input-control">
                    	
                    	<select name="FinancialYearID"  id="FinancialYearID">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT * FROM FinancialYear ORDER BY FinancialYearID";
                            
                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                {
                                    $s_id = $row["FinancialYearID"];
                                    $s_name = $row["FinancialYearName"];
                                    if ($FinancialYearID==$s_id) 
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
			</tr>
			<tr>
                <td width="50%">
                	<label>Amount</label>
					
                	<div class="input-control text" data-role="input-control">
						<input name="Amount" id="Amount" type="text" value="<?php echo $Amount; ?>"></input>                    	
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">

				</td>
			</tr>			
                     
        </table>
		<input name="Button" type="button" onclick="loadmypage('revenue_stream_budget.php?'+                                            
											'&FinancialYearID='+this.form.FinancialYearID.value+
                                            '&RevenueStreamID='+<?php echo $RevenueStreamID; ?>+ 
											'&Amount='+this.form.Amount.value+ 											
        									'&save=1','content','loader','listpages')" value="Save">
      <input type="reset" value="Cancel" onClick="loadmypage('revenue_stream_list.php?i=1','content','loader','listpages','','RevenueStreams')">      
        <span class="table_text">
        <input name="UserID" type="hidden" id="UserID" value="<?php echo $UserID;?>" />
        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>