<?php 
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
require_once('GlobalFunctions.php');
require_once('county_details.php');
require_once('smsgateway.php');
require('password_compat/lib/password.php');
// require("/PHPMailer/src/PHPMailer.php");
// require("/PHPMailer/src/SMTP.php");
// require("/PHPMailer/src/Exception.php");
// require_once("mPDF/mpdf.php");



if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$UserID = $_SESSION['UserID'];

if (isset($_REQUEST['msg']))
{
	$msg = $_REQUEST['msg'];	
}

$ApplicationID='';
$CustomerName='';
$CustomerID="";
$ServiceName ='';
$ServiceID='';
$Charges=0;
$Notes='';
$ServiceState="";
$CurrentStatus="";
$NextStatus="";
$Customer;
$SubCountyName;
$BusinessZoneID;
$WardName;
$CustomerType="";
$RegNo="";
$PostalAddress="";
$PostalCode="";
$Pin="";
$Vat="";
$Town="";
$Country="";
$Telephone1="";
$Mobile1="";
$Telephone2="";
$Mobile2="";
$Mobile1="";
$url="";
$Email="";
$ServiceHeaderType="";
$SubSystemID=1;
$ApplicationDate='';
$today=date("d/m/Y");
//$DateLine=date('d/m/Y',strtotime('2018-03-31'));
$DateLine=$cosmasRow['SBPDateline'];
$DateLine=date('d/m/Y',strtotime($DateLine));
$BusinessIsOld=0;
$ConservancyCost=0;
$PermitYear=date("Y");
$InvoiceNo=0;
$ServiceCost=0;
$LicenceNumber = "";
$SubmisionDate = "";
$LicenceIssueDate = "";
$LicenceExpiryDate = "";
$ServiceHeaderID= "";

if (isset($_REQUEST['ApplicationID'])) 
{
    $ApplicationID = $_REQUEST['ApplicationID']; 	


}

$today=date('Y-m-d H:i:s');
$FirstDec=date(date('Y')."-12-01 00:00:00");
if($today>$FirstDec){
	$PermitYear=date("Y")+1;
}


if (isset($_REQUEST['save']))
    {
        // echo '<pre>';
        // print_r($_REQUEST);
        // exit;

        if($_REQUEST['ConfirmPassWord'] !== $_REQUEST['PassWord']){
            return $msg = 'Passwords Do Not Mach';
        }else{
            $PassWordHash= password_hash($_REQUEST['PassWord'], PASSWORD_BCRYPT, array("cost" => 10, 
            "salt" => "usesomesillystringfor god is GREAT AMEN AMEN AMEN HEEY")); 
            $FirstName=$_REQUEST['FirstName'];
            $LastName=$_REQUEST['LastName'];
            $RoleCenterID=$_REQUEST['RoleCenterID'];
            $MiddleName=$_REQUEST['MiddleName'];
            $Region=$_REQUEST['Region'];
            $Email = $_REQUEST['Email'];
            $IdNo = 60008726; //rand(80, );
            $UserStatusID =1; // $_REQUEST['UserStatusID'];
            $DateToday=date('Y-m-d H:i:s');
            $UserID = $_SESSION['UserID'];
            $Active =1;
            $MobileNumber = $_REQUEST['MobileNumber'];
             //Insert Into Table Agents First
            $InsertIntoAgentsSQL="INSERT INTO Agents (FirstName,MiddleName,LastName,password,
                IDNo, Mobile, Email, UserStatusID, CreatedBy, CreatedDate, Active, ChangePassword)
                Values('$FirstName',
                 '$MiddleName','$LastName',
                '$PassWordHash',
                '$IdNo','$MobileNumber','$Email', 
                '$UserStatusID',$UserID, 
                '$DateToday', $Active, 0) SELECT SCOPE_IDENTITY() AS ID";
            
            // echo $InsertIntoAgentsSQL; exit;
                     /* Begin the transaction. */
            if ( sqlsrv_begin_transaction( $db ) === false ) {
                die( print_r( sqlsrv_errors(), true ));
            }
            $InsertIntoAgentsResult = sqlsrv_query($db, $InsertIntoAgentsSQL);

            //Insert Now to Users

            //Get AgentNo
			$AgentNo=lastid($InsertIntoAgentsResult);
            
            $InsertIntoUsersSQL = "INSERT INTO Users([Mobile],[UserName],[Email],[agentID],IDNo,Password,CreatedBy,RegionID) 
            select [Mobile],[UserName],[Email],[agentID],IDNo,Password,".$UserID.",".$Region." from agents where AgentID=$AgentNo SELECT SCOPE_IDENTITY() AS ID" ;
            
            //    echo $InsertIntoUsersSQL; exit;

            // print_r($AgentNo); exit;
			$InsertIntoUserRolesSQL="Insert into UserRoles (UserID,RoleCenterID,CreatedBY)
			Values('$AgentNo',$RoleCenterID,$UserID)";	

            
            $InsertIntoUsersSQLResult = sqlsrv_query($db, $InsertIntoUsersSQL);
            $InsertIntoUserRolesSQLResult = sqlsrv_query($db, $InsertIntoUserRolesSQL);

            if($InsertIntoAgentsResult &&$InsertIntoUsersSQLResult && InsertIntoUserRolesSQLResult) {
                sqlsrv_commit( $db );
                // echo "Transaction committed.<br />";
                $Msg="Created the Account for ".$UserName;
                return  $Msg;

            } else {
                sqlsrv_rollback( $db );
                echo "Transaction rolled back.<br />";
                DisplayErrors();

            }

        }

              
    }



//get the Arrears

if (isset($_REQUEST['approve']))
{	
	$input=array_slice($_REQUEST,2,count($input)-1);	
	foreach ($input AS $id => $value)
	{	
		$newID=substr($id,3,strlen($id)-3);	
			
		$sql="if exists(select * from FormData where FormColumnID=$newID)
				Update FormData set Value='$value' where FormColumnID=$newID and ServiceHeaderID=$ApplicationID
			  else
				insert into FormData (FormColumnID,ServiceHeaderID,Value)
			    values($newID,$ApplicationID,'$value')";
				
		$result=sqlsrv_query($db,$sql);
		
		if(!$result)
		{
			DisplayErrors();
			continue;
		}		

	}	
}



?>

<div class="example">
   <legend>New User</legend>
   <form>
      <fieldset>
          <table width="100%" border="0" cellspacing="0" cellpadding="3">
              <tr>
                <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
            </tr>
              <tr>
                 <td >
					 <label>First Name</label>
					  <div class="input-control text" data-role="input-control">
						  <input name="FirstName" type="text" id="FirstName"  placeholder="First Name">	  
					  </div>                 	
                  </td>                
              </tr>
			  <tr>
                <td width="50%">
                    <label>Middle Name </label>
					  <div class="input-control text" data-role="input-control">
						  <input name="MiddleName" type="text" id="MiddleName"   placeholder=" Middle Name">						  
					  </div>				  
                  </td>
              </tr>
              <tr>
                  <td width="50%">
                  <label>Last Name</label>
					  <div class="input-control text" data-role="input-control">
						  <input name="LastName" type="text" id="LastName"  placeholder="Last Name">						  
					  </div>				  
                  </td>
                  <td width="50%">				  
                  </td>   
              </tr>
              <tr>
                  <td width="50%">
                  <label>UserName</label>
					  <div class="input-control text" data-role="input-control">
						  <input name="UserName" type="text" id="UserName" placeholder="UserName">						  
					  </div>				  
                  </td>
                  <td width="50%">				  
                  </td>  
                   
              </tr>
              <tr>
                  <td width="50%">
                  <label>Role</label>
                  <div class="input-control select" data-role="input-control">
                    <select name="RoleCenterID"  id="RoleCenterID">
                            <option value="0" selected="selected"></option>
                                <?php 
                                $s_sql = "SELECT * FROM RoleCenters ORDER BY RoleCenterID";
                                
                                $s_result = sqlsrv_query($db, $s_sql);
                                if ($s_result) 
                                { //connection succesful 
                                    while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                    {
                                        $s_id = $row["RoleCenterID"];
                                        $s_name = $row["RoleCenterName"];
                                        if ($RoleCenterID==$s_id) 
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
 
              </tr>	

              <tr>
                  <td width="50%">
                  <label>Email</label>
					  <div class="input-control text" data-role="input-control">
						  <input name="Email" type="email" id="Email" placeholder="Email">						  
					  </div>				  
                  </td>
                  <td width="50%">				  
                  </td>  
                   
              </tr>

              <tr>
                  <td width="50%">
                  <label>Mobile Number</label>
					  <div class="input-control text" data-role="input-control">
						  <input name="MobileNumber" type="number" id="MobileNumber" placeholder="Email">						  
					  </div>				  
                  </td>
                  <td width="50%">				  
                  </td>   
              </tr>

              
              <tr>
                  <td width="50%">
                  <label>Region </label>
                  <div class="input-control select" data-role="input-control">
                    <select name="Region"  id="Region">
                            <option value="0" selected="selected"></option>
                                <?php 
                                $s_sql = " SELECT SubSystemID, SubSystemName FROM SubSystems ORDER BY SubSystemID";
                                
                                $s_result = sqlsrv_query($db, $s_sql);
                                if ($s_result) 
                                { //connection succesful 
                                    while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                    {
                                        $s_id = $row["SubSystemID"];
                                        $s_name = $row["SubSystemName"];
                                        if ($RoleCenterID==$s_id) 
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
                  <td width="50%">				  
                  </td>   
              </tr>

              <tr>
                  <td width="50%">
                  <label>PassWord</label>
					  <div class="input-control text" data-role="input-control">
						  <input name="PassWord" type="PassWord" id="PassWord" placeholder="Email">						  
					  </div>				  
                  </td>
                  <td width="50%">				  
                  </td>   
              </tr>

              <tr>
                  <td width="50%">
                  <label>Confirm PassWord</label>
					  <div class="input-control text" data-role="input-control">
						  <input name="ConfirmPassWord" type="password" id="ConfirmPassWord" placeholder="Email">						  
					  </div>				  
                  </td>
                  <td width="50%">				  
                  </td>   
              </tr> 
              


<tr>



                     
            		
          </table> 

          

		  
           <input type="reset" value="Cancel" onClick="loadmypage('users_list.php?i=1','content','loader','listpages','','users')">

		  <input name="Button" type="button" onClick="
                FirstName=this.form.FirstName.value;
                RoleCenterID=this.form.RoleCenterID.value;
                UserName=this.form.UserName.value;
                MiddleName=this.form.MiddleName.value;
                PassWord=this.form.PassWord.value;
                MobileNumber=this.form.MobileNumber.value;
                ConfirmPassWord=this.form.ConfirmPassWord.value;
                Region=this.form.Region.value;


		  	    loadpage('add_officer_card.php?save=1&FirstName='+this.form.FirstName.value+'&LastName='+this.form.LastName.value+'&RoleCenterID='+this.form.RoleCenterID.value+'&UserName='+this.form.UserName.value+'&MiddleName='+this.form.MiddleName.value+'&PassWord='+this.form.PassWord.value+'&MobileNumber='+this.form.MobileNumber.value+'&ConfirmPassWord='+this.form.ConfirmPassWord.value+'&Region='+this.form.Region.value+'&Email='+this.form.Email.value+'&MobileNumber='+this.form.MobileNumber.value+'',
                  
                  'content')
		  

		    " value="Add User">




          <div style="margin-top: 20px">
  </div>


      </fieldset>
  </form>                  
