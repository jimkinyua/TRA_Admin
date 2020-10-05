<?php
require 'DB_PARAMS/connect.php';
require('password_compat/lib/password.php');

function _login($db,$uname,$passwd)
{
	session_defaults();
	//$Passwd = md5($passwd);
	//BCrypt
	$Passwd=password_hash($passwd);
	//$sql   = "select u.*,ur.RoleCenterID from users u join UserRoles ur on ur.UserID=u.UserID WHERE (u.Email = '$uname') AND (u.Password = '$Passwd')";
	$sql   = "select u.AgentID UserID,u.UserName,u.[Password], u.FirstName+' '+u.MiddleName+' '+u.LastName UserFullNames,
	u.Active UserStatusID,ur.RoleCenterID 
	from Agents u inner join UserRoles ur on ur.UserID=u.AgentID WHERE (u.Email = '$uname')";
	$result  = sqlsrv_query($db, $sql);
	
	if ($result)
	{
		if ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC))
		{
			//echo $sql;
			if(password_verify($passwd,$myrow['Password']))
			{	//echo 'user verified';	
				$UserName 		= $myrow["UserName"];	
				$UserID 		= $myrow["UserID"];
				$UserStatusID 	= $myrow["UserStatusID"];			
				$UserFullNames 	= $myrow['UserFullNames'];
				$RoleCenterID	= $myrow['RoleCenterID'];
					
				$_SESSION["UserFullNames"] = $UserFullNames;
				
				$_SESSION["UserStatusID"] = $UserStatusID;
				if ($UserStatusID==0)
				{   //echo 'hei';	
					$_SESSION["ChangePassword"] = 1;
					$_SESSION["UserID"] = $UserID;				
					return TRUE;	
				}			
				else if ($UserStatusID==1)
				{
					//echo 'hai';
					$_SESSION["logged_in"] = 1;
					$_SESSION["ChangePassword"]=0;
					$_SESSION["UserName"] = $UserName;
					$_SESSION["UserID"] = $UserID;
					$_SESSION["RoleCenter"]=$RoleCenterID;
					$_SESSION["UserFullNames"] = $UserFullNames;
					setcookie('PROJECTMAN', $_SESSION["UserFullNames"], time() + 3600);
					return TRUE;
				}
			}else
			{
				//echo 'user not verified';
				$_SESSION["logged_in"] = 0;
				return FALSE;
			}
		} else
		{
			$_SESSION["logged_in"] = 0;
			return FALSE;
		}	
	} else
	{
		return FALSE;
	}
}

function session_defaults() 
{
	unset($_SESSION['logged_in']);
	unset($_SESSION['UserName']);
	unset($_SESSION['UserID']);	
}

session_start();
$date = gmdate("'Y-m-d'");
?>
