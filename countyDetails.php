<?php 
	require 'DB_PARAMS/connect.php';
  require_once('GlobalFunctions.php');

	$msg="";
	
	//require("phpToPDF.php"); 

	if (!isset($_SESSION))
	{
		session_start();
	}
	$msg ='';
	$UserID = $_SESSION['UserID'];
	
	$CountyName="";
	$ContyAddress="";
	$CountyTown="";
	$CountyTelephone="";
	$CountyMobile="";
	$CountyEmail="";
	
	
		//County Details
	$sql="SELECT [CountyName],[PostalAddress],[PostalCode],[Town],[Telephone1],[Mobile1],[Email],Url Website,SBPDateline
	FROM CountyDetails";

	$cosmasRow= array();
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$cosmasRow=$row;			
	}
	
	$CountyName=$cosmasRow['CountyName'];
	$CountyAddress=$cosmasRow['PostalAddress'];
	$Town=$cosmasRow['Town'];
	$Telephone=$cosmasRow['Telephone1'];
	$Mobile=$cosmasRow['Mobile1'];
	$Email=$cosmasRow['Email'];	
	$Website=$cosmasRow['Website'];	
	$CountyPostalCode=$cosmasRow['PostalCode'];
  $SBPDateline=$cosmasRow['SBPDateline'];

  if (isset($_REQUEST['save']))
  {
    
    $CountyName=$_REQUEST['CountyName'];
    $CountyAddress=$_REQUEST['CountyAddress'];
    $Town=$_REQUEST['Town'];
    $Telephone=$_REQUEST['Telephone'];
    $Mobile=$_REQUEST['Mobile'];
    $Email=$_REQUEST['Email'];
    $Website=$_REQUEST['Website'];
    $SBPDateline=$_REQUEST['SBPDateline'];


    $sql="set dateformat dmy Update CountyDetails set SBPDateline='$SBPDateline',PostalAddress='$CountyAddress',CountyName='$CountyName',Mobile1='$Mobile',Telephone1='$Telephone',Town='$Town'";

   $result = sqlsrv_query($db, $sql);

    if ($result)
    { 
        $rst=SaveTransaction($db,$UserID,"Updated County Profile");  
        $msg = "Saved Details Successfully";
    } else
    {
      DisplayErrors();
      $msg = "Details Failed to save";     
    } 
  }


?>
<script type="text/javascript">
      $(".datepicker").datepicker();
    </script>

<body>  

<div class="example">
<form>
	<fieldset>
	  <legend>County Details</legend>
	  <table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
			<tr>
                <td width="50%">
                	<label>County Name</label>
                	<div class="input-control text" data-role="input-control">
                    	<input name="CountyName" type="text" id="CountyName" value="<?php echo $CountyName; ?>" placeholder="" disabled="disabled">
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%"><label>City</label>
                	<div class="input-control text" data-role="input-control">
                    	<input name="Town" type="text" id="Town" value="<?php echo $Town; ?>" placeholder="" disabled="disabled">
                        <button class="btn-clear" tabindex="-1"></button>
                    </div></td>
          	</tr>
			<tr>
                <td width="50%">
               	  <label>Mobile</label>
               	  <div class="input-control text" data-role="input-control">
                   	  <input name="Mobile" type="text" id="Mobile" value="<?php echo $Mobile; ?>" placeholder="" disabled="disabled">
                        <button class="btn-clear" tabindex="-1"></button>
                  </div>
                </td>
                <td width="50%">
                	<label>Telephone</label>
                	<div class="input-control text" data-role="input-control">
                        <input name="Telephone" type="text" id="Telephone" value="<?php echo $Telephone; ?>" placeholder="" disabled="disabled">
                        <button class="btn-clear" tabindex="-1"></button>
                  </div>
				</td>
          	</tr>                      
			<tr>
                <td width="50%">
                	<label>Email</label>
                	<div class="input-control text" data-role="input-control">
                    	<input name="Email" type="text" id="Email" value="<?php echo $Email; ?>" placeholder="" disabled="disabled">
                        <button class="btn-clear" tabindex="-1"></button>
                  </div>
                </td>
                <td width="50%">
                	<label>Website</label>
                    <div class="input-control text" data-role="input-control">
                        <input name="Url" type="text" id="Url" value="<?php echo $Website; ?>" placeholder="" disabled="disabled">
                        <button class="btn-clear" tabindex="-1"></button>
                  </div>
				</td>
          	</tr>
        <tr>
                <td width="50%">
                  <label>SBP Dateline</label>
                  <div class="input-control text datepicker" data-role="input-control" data-format="dd/mm/yyyy">          
                      <input type="text" id="SBPDateline" name="SBPDateline" value="<?php echo $SBPDateline ?>"></input>
                      <button class="btn-date" type="button"></button>        
                    </div>
                </td>                
        </td>
            </tr>
		</table>
		<input name="Button" type="button" onclick="loadpage('countyDetails.php?'+
            '&Town='+this.form.Town.value+
            '&CountyName='+this.form.CountyName.value+            
            '&Telephone='+this.form.Telephone.value+
            '&Mobile='+this.form.Mobile.value+
            '&Email='+this.form.Email.value+
            '&Website='+this.form.Url.value+
            '&SBPDateline='+this.form.SBPDateline.value+
        	'&save=1','content')" value="Save">
      <input type="reset" value="Cancel" onClick="loadpage('profilehome.php?edit=1&UserID=<?php echo $UserID;?>','content')">
        <input name="UserID" type="hidden" id="UserID" value="<?php echo $UserID;?>" />
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>
</body>