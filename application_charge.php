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

$SetupID=0;
$ServiceID='';
$ApplicationTypeID=0;
$LinkServiceID='0';
$ApplicationCategoryID='';
$ChargeTypeID ='';
$StoreyedAmount='';
$NonStoreyedAmount='';
$ServiceName='';
$UnitOfCharge=1;

	


if ($_REQUEST['edit']==1)
{
    $SetupID=$_REQUEST['SetupID'];

    $sql = "select 
    pas.SetupID,apt.ApplicationTypeID,apt.ApplicationTypeName,apc.ApplicationCategoryID,apc.ApplicationCategoryName,pas.UnitOfCharge,pas.NonStoreyedAmount,pas.StoreyedAmount,pas.ApplyToNonStoreyed,s.ServiceName,s.ServiceID 
    from PlanApprovalSetup pas 
    join ApplicationTypes apt on pas.ApplicationTypeID=apt.ApplicationTypeID
    join ApplicationCategories apc on pas.ApplicationCategoryID=apc.ApplicationCategoryID
    join services s on pas.ServiceID=s.ServiceID where pas.SetupID=$SetupID";

    $result=sqlsrv_query($db,$sql);

    

    while ($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) 
    {
        $ServiceName=$rw['ServiceName'];
        $ApplicationCategoryID=$rw['ApplicationCategoryID'];
        $ApplicationTypeID=$rw['ApplicationTypeID'];
        $StoreyedAmount=$rw['StoreyedAmount'];
        $NonStoreyedAmount=$rw['NonStoreyedAmount'];
        $ServiceID=$rw['ServiceID'];
        $UnitOfCharge=$rw['UnitOfCharge'];
        $ApplyToNonStoreyed=$rw['ApplyToNonStoreyed'];
    }

}


?>
<div class="example">
<form>
	<fieldset>
	  <legend>Edit Plan Approval Charge</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
			
            <tr>
              <td><label>Application Type</label>
                    <div class="input-control select" data-role="input-control">
                        <select name="ApplicationTypeID"  id="ApplicationTypeID">
                        <option value="0" selected="selected"></option>
                        <?php 
                        $s_sql = "SELECT * FROM ApplicationTypes ORDER BY 1";
                        
                        $s_result = sqlsrv_query($db, $s_sql);
                        if ($s_result) 
                        { //connection succesful 
                            while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                            {
                                $s_id = $row["ApplicationTypeID"];
                                $s_name = $row["ApplicationTypeName"];
                                if ($ApplicationTypeID==$s_id) 
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
           	<td><label>Service Category</label>
                    <div class="input-control select" data-role="input-control">
                        <select name="ApplicationCategoryID"  id="ApplicationCategoryID">
                        <option value="0" selected="selected"></option>
                        <?php 
                        $s_sql = "select * from ApplicationCategories order by 1";
                        $s_result = sqlsrv_query($db, $s_sql);
                        if ($s_result) 
                        { //connection succesful 
                            while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                            {
                                $s_id = $row["ApplicationCategoryID"];
                                $s_name = $row["ApplicationCategoryName"];
                                if ($ApplicationCategoryID==$s_id) 
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
              <td>
              </td>
          </tr>

          <tr>
              <td><label>Service Name</label>
                    <div class="input-control select" data-role="input-control">
                        <select name="ServiceID"  id="ServiceID">
                        <option value="0" selected="selected"></option>
                        <?php 
                        $s_sql = "SELECT * FROM Services ORDER BY ServiceName";
                        
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
          <tr>
              <td><label>Does It Apply to Non-Storeyed Building?</label>
                    <div class="input-control select" data-role="input-control">
                        <select name="ApplyToNonStoreyed"  id="ApplyToNonStoreyed">                        
                          <option value="0" <?php if($ApplyToNonStoreyed==0){ ?>selected<?php } ?>>No</option>
                          <option value="1" <?php if($ApplyToNonStoreyed==1){ ?>selected<?php } ?>>Yes</option>                       
                      </select>                      
                  </div></td>
                  <td>
                  </td>
          </tr>
          <tr>
              <td><label>Unit Of Charge</label>
                    <div class="input-control select" data-role="input-control">
                        <select name="UnitOfCharge"  id="UnitOfCharge">
                        
                        <option value="1" <?php if($UnitOfCharge==1){ ?>selected<?php } ?>>Fixed</option>
                        <option value="2" <?php if($UnitOfCharge==2){ ?>selected<?php } ?>>Square Meters</option>
                        <option value="3" <?php if($UnitOfCharge==3){ ?>selected<?php } ?>>No Of Floors</option>
                        <option value="4" <?php if($UnitOfCharge==4){ ?>selected<?php } ?>>Portions</option>
                        
                      </select>
                      
                  </div></td>
                  <td>
                  </td>
          </tr>
      <tr>
          <td width="50%">
            <label>Rate if not Storeyed</label>
            <div class="input-control text" data-role="input-control">
                <input type="text" name="NonStoreyedAmount" id="NonStoreyedAmount" value="<?php echo $NonStoreyedAmount; ?>"></input>
                  <button class="btn-clear" tabindex="-1"></button>
              </div>
          </td>
          <td width="50%">

            </td>
      </tr> 
      <tr>
          <td width="50%">
            <label>Rate if Storeyed</label>
            <div class="input-control text" data-role="input-control">
                <input type="text" name="StoreyedAmount" id="StoreyedAmount" value="<?php echo $StoreyedAmount; ?>"></input>
                  <button class="btn-clear" tabindex="-1"></button>
              </div>
          </td>
          <td width="50%">

            </td>
      </tr>                            
                     
        </table>

		<input type="button" value="Save" onclick="loadmypage('application_charges_list.php?'+
        '&ApplicationTypeID='+this.form.ApplicationTypeID.value+
        '&ApplicationCategoryID='+this.form.ApplicationCategoryID.value+ 
        '&NonStoreyedAmount='+this.form.NonStoreyedAmount.value+ 
        '&StoreyedAmount='+this.form.StoreyedAmount.value+ 
        '&UnitOfCharge='+this.form.UnitOfCharge.value+ 
        '&ServiceID='+this.form.ServiceID.value+
        '&ApplyToNonStoreyed='+this.form.ApplyToNonStoreyed.value+ 
        '&SetupID='+<?php echo $SetupID; ?>+    
		'&save=1','content','loader','listpages','','application_charges')" >

      <input type="button" value="Cancel" onClick="loadmypage('application_charges_list.php?i=1','content','loader','listpages','','application_charges')">
	  
        <span class="table_text">

        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>