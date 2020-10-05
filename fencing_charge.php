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
$FencingTypeID=0;
$LinkServiceID='0';
$ApplicationCategoryID='';
$ChargeTypeID ='';
$Minimum='';
$Amount='';
$ServiceName='';
$Fixed=1;
$Percentage=0;

	


if ($_REQUEST['edit']==1)
{
    $SetupID=$_REQUEST['SetupID'];

    $sql = "select 
    fs.SetupID,ft.ApplicationTypeID FencingTypeID,fs.Fixed,fs.Percentage,fs.Amount,fs.Minimum,ft.ApplicationTypeName,s.ServiceName,s.ServiceID 
    from FencingSetup fs 
    join ApplicationTypes ft on fs.FencingTypeID=ft.ApplicationTypeID    
    join services s on fs.ServiceID=s.ServiceID where fs.SetupID=$SetupID";

    $result=sqlsrv_query($db,$sql);

    while ($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) 
    {
        $ServiceName=$rw['ServiceName'];        
        $FencingTypeID=$rw['FencingTypeID'];
        $Amount=$rw['Amount'];
        $ServiceID=$rw['ServiceID'];
        $Fixed=$rw['Fixed'];
        $Minimum=$rw['Minimum'];
        $Percentage=$rw['Percentage'];
    }

}

?>
<script type="text/javascript">     
    $(document).ready(function() {
            $("#Amount").keydown(function(event) {
                // Allow only backspace and delete
                if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 190 ) {
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
    $(document).ready(function() {
            $("#Minimum").keydown(function(event) {
                // Allow only backspace and delete
                if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 190) {
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
<body>
<div class="example">
<form>
	<fieldset>
	  <legend>Edit Fencing Charge</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
			
            <tr>
              <td><label>Fencing Type</label>
                    <div class="input-control select" data-role="input-control">
                        <select name="FencingTypeID"  id="FencingTypeID">
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
                                if ($FencingTypeID==$s_id) 
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
              <td><label>Fixed</label>
                    <div class="input-control select" data-role="input-control">
                        <select name="Fixed"  id="Fixed">
                        
                        <option value="1" <?php if($Fixed==1){ ?>selected<?php } ?>>Yes</option>
                        <option value="0" <?php if($Fixed==0){ ?>selected<?php } ?>>No</option>
                        
                      </select>
                      
                  </div></td>
                  <td>
                  </td>
          </tr>
      <tr>
          <td width="50%">
              <label>Is the Value a percentage</label>
              <div class="input-control select" data-role="input-control">
                  <select name="Percentage"  id="Percentage">
                  
                  <option value="1" <?php if($Percentage==1){ ?>selected<?php } ?>>Yes</option>
                  <option value="0" <?php if($Percentage==0){ ?>selected<?php } ?>>No</option>
                  
                </select>
                      
                  </div>
          </td>
          <td width="50%">

            </td>
      </tr>
      <tr>
          <td width="50%">
            <label>Amount</label>
            <div class="input-control text" data-role="input-control">
                <input type="text" name="Amount" id="Amount" value="<?php echo number_format($Amount,2); ?>"></input>
                  <button class="btn-clear" tabindex="-1"></button>
              </div>
          </td>
          <td width="50%">

            </td>
      </tr>  
      <tr>
          <td width="50%">
            <label>Minimum</label>
            <div class="input-control text" data-role="input-control">
                <input type="text" name="Minimum" id="Minimum" value="<?php echo number_format($Minimum,2); ?>"></input>
                  <button class="btn-clear" tabindex="-1"></button>
              </div>
          </td>
          <td width="50%">

            </td>
      </tr>                            
                     
        </table>

		<input type="button" value="Save" onclick="loadmypage('fencing_charges_list.php?'+
        '&FencingTypeID='+this.form.FencingTypeID.value+        
        '&Amount='+this.form.Amount.value+ 
        '&Minimum='+this.form.Minimum.value+ 
        '&Fixed='+this.form.Fixed.value+
        '&Percentage='+this.form.Percentage.value+ 
        '&ServiceID='+this.form.ServiceID.value+        
        '&SetupID='+<?php echo $SetupID; ?>+    
		'&save=1','content','loader','listpages','','FencingCharges')" >

      <input type="button" value="Cancel" onClick="loadmypage('application_charges_list.php?i=1','content','loader','listpages','','application_charges')">
	  
        <span class="table_text">

        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>
</body>