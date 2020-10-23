<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
require_once('GlobalFunctions.php');
require_once('county_details.php');

if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$ActiveUserID = $_SESSION['UserID'];

$PageID=5;
$myRights=getrights($db,$ActiveUserID,$PageID);
if ($myRights)
{
	$View=$myRights['View'];
	$Edit=$myRights['Edit'];
	$Add=$myRights['Add'];
	$Delete=$myRights['Delete'];
}


checkSession($db,$ActiveUserID);


if (isset($_REQUEST['reset']))
{

	$GeneratedPassword = 'UasinGishuREV2017!.';// generatePassword();
	
	$Password='$2y$10$eTAGuIJUEBeiNNiN0TwU8eWtq7XbiaL.Uum2WvB.kskN.KKdYh83q';	
	$UserID = $_REQUEST['UserID'];
	$UserName = $_REQUEST['UserName'];	


	
	$sql = "SELECT u.Email,ag.FirstName+' '+ag.Middlename+' '+ag.LastName UserNames 
	FROM Users u 
	join agents ag on u.agentid=ag.agentID 
	WHERE ag.AgentID = '$UserID'";
	


	$result = sqlsrv_query($db, $sql);
	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$Email=$myrow['Email'];
		$UserName=$myrow['UserNames'];
	}
	
	$sql = "UPDATE Users SET PAssword = '$Password',UserStatusID=1 WHERE AgentID = '$UserID'";
	$result = sqlsrv_query($db, $sql);
	if ($result)
	{
		$sql = "UPDATE agents SET PAssword = '$Password' WHERE AgentID = '$UserID'";
		$result = sqlsrv_query($db, $sql);

		$rst=SaveTransaction($db,$ActiveUserID,"User Reset for ".$UserName. " succeeded");

		if ($rst[0]==0){
			$msg=$rst[1];
		}else{
			$msg="User Account Reset Successfully";
		}

	} else
	{
		$rst=SaveTransaction($db,$ActiveUserID,"User Reset for ".$UserName.' Failed');

		if ($rst[0]==0){
			$msg=$rst[1];
		}else{
			$msg=$rst[1];
		}
		
	}
}

if(isset($_REQUEST['edit']))
{
	
	$Mobile=$_REQUEST['Mobile'];
	$pfno=$_REQUEST['pfno'];
	$idno=$_REQUEST['idno'];
	$Email=$_REQUEST['email'];
	$AgentID=$_REQUEST['AgentID'];
	$RoleCenterID=$_REQUEST['RoleCenterID'];
	$UserStatusID=$_REQUEST['UserStatusID'];
	$UserName=$_REQUEST['UserName'];
	$UserID=$_REQUEST['UserID'];

	$UserName=GetUser($db,$AgentID); 

	$sql = "UPDATE Users SET [Mobile]='$Mobile',[Email]='$Email',[UserName]='$UserName',[pfno]='$pfno',RoleCenterID=$RoleCenterID,Password='$Password',UserStatusID =$UserStatusID where AgentID=$AgentID";

	$sql2="Update UserRoles set RoleCenterID=$RoleCenterID where UserID=$AgentID";

	$transMsg="Update the Account of ".$UserName;
	
	$result = sqlsrv_query($db, $sql);

	$result2 = sqlsrv_query($db, $sql2);
	
	if(!($result||$result2))
	{		
		DisplayErrors();		
	}	
	else
	{
		$rst=SaveTransaction($db,$ActiveUserID,$transMsg);
		

		if ($rst[0]==0){
			$msg=$rst[1];
		}else{
			$msg=$rst[1];
		}
		// $mail_txt="Welcome to the County Portal.<br> Your password is $GeneratedPassword. You are however required to change the password upon first logging in.";
		// $msg=php_mailer($Email,$cosmasRow['Email'],$cosmasRow['CountyName'],'Login Created',$mail_txt,'','');		
				
	}
	
}

if (isset($_REQUEST['save']))
{
	
	$AgentID=$_REQUEST['AgentID'];
	$UserID=$_REQUEST['UserID'];
	$RoleCenterID=$_REQUEST['RoleCenterID'];
	$UserStatusID=$_REQUEST['UserStatusID'];

	$UserName=GetUser($db,$AgentID); 
	
	
	if ($UserID!=0)//Editting
	{

		$sql = "UPDATE Users SET RoleCenterID=$RoleCenterID,UserStatusID =$UserStatusID where AgentID=$AgentID";

		$sql2=" If not exists (select 1 from UserRoles where UserID=$AgentID) Begin 
			Insert into UserRoles (UserID,RoleCenterID,CreatedBY)
			Values('$AgentID',$RoleCenterID,$ActiveUserID)
		end else begin
		Update UserRoles set RoleCenterID=$RoleCenterID where UserID=$AgentID End";

		$transMsg="Update the Account of ".$UserName;

	}else//Adding
	{
		$sql="select 1 from Users where agentID=$AgentID";
		$result=sqlsrv_query($db,$sql);	
		if($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
		{
			$msg = "The Login Name you entered already exists";
		}else
		{

			$sql = "INSERT INTO Users([Mobile],[UserName],[Email],[agentID],IDNo,Password,CreatedBy) 
			select [Mobile],[UserName],[Email],[agentID],IDNo,Password,".$ActiveUserID." from agents where AgentID=$AgentID SELECT SCOPE_IDENTITY() AS ID" ;

			$sql2="Insert into UserRoles (UserID,RoleCenterID,CreatedBY)
			Values('$AgentID',$RoleCenterID,$ActiveUserID)";	

			$transMsg="Created the Account for ".$UserName;	
		}
	}
	//echo $sql2;
	$result = sqlsrv_query($db, $sql);

	$result2 = sqlsrv_query($db, $sql2);
	
	if(!($result||$result2))
	{	
		
		DisplayErrors();		
	}	
	else
	{

		$rst=SaveTransaction($db,$ActiveUserID,$transMsg);
		

		if ($rst[0]==0){
			$msg=$rst[1];
		}else{
			$msg=$rst[1];
		}

		$mail_txt="Welcome to the County Portal.<br> Your password is $GeneratedPassword. You are however required to change the password upon first logging in.";
		$msg=php_mailer($Email,$cosmasRow['Email'],$cosmasRow['CountyName'],'Login Created',$mail_txt,'','');		
				
	}	
}
?>
<script type="text/javascript">
        
        $("#add_user").on('click', function(ev){
            var url = 'add_user.php?ApplicationID=' 
                + ev.target.dataset.appId + '&SubSystemID=' + ev.target.dataset.ssId+ '&ServiceID=' + ev.target.dataset.sId
            //console.log(url)
            $.get(url, function(res) {
                $.Dialog({
                    shadow: true,
                    overlay: false,
                    flat:true;
                    draggable: true,
                    icon: '<span class="icon-rocket"></span>',
                    title: 'Import Users',      
                    padding: 5,
                    content: res
                });
            })
            
        });
 </script>

    <link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/prettify/prettify.css" rel="stylesheet">
<body class="metro">
        <div class="example">
        <legend>Users</legend>
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                  	<?php 
						if ($myRights['Add']==0)
						{
							?>
                            <th></th><?php
						}else
						{ ?>
							<th class="text-left"><a href="#" onClick="loadmypage('add_officer_card.php?i=1','content','loader','listpages','','','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')">Add</a></th>

                            <?php
						}
					?>
                    
                    <th colspan="7" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>
                <tr>
					<th  class="text-left">User ID</th>
                    <th  class="text-left">User Full Names</th>
                    <th  class="text-left">UserName</th>
					<th  class="text-left">Created Date</th>
                    <th  class="text-left">Status</th>
                    <th  class="text-left">Role Center</th>
                    <th  class="text-left">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                </tbody>

                <tfoot>

                </tfoot>
            </table>


</div>
</div>