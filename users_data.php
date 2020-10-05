<?php
require 'DB_PARAMS/connect.php';
$DisplayArray = array(0=>'False', 1=>'True');
$Option = 'users';
$channel = array();
$sql = "Select Users.UserID,Users.UserFullNames,users.UserName,users.CreatedDate,ur.UserRoleName FROM Users 
		LEFT JOIN UserRoles ur ON Users.UserRoleId = ur.UserRoleId";
$result = sqlsrv_query($db, $sql);	
while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
{
	extract($row);
	$Date 	= date('d/m/Y',strtotime($CreatedDate));
	$channel[] = array(
				$UserName,
				$UserFullNames,
				$Date,
				$UserRoleName,
				'<a href="#" onClick="deleteConfirm2(\'Are you sure you wish to reset the password for '.$UserName.'\',\'users_list.php?reset=1&UserName='.$UserName.'&UserID='.$UserID.'\',\'content\',\'loader\',\''.$Option.'\')">Reset Password</a>',
				'<a href="#" onClick="loadpage(\'users.php?edit=1&UserID='.$UserID.'\',\'content\')">Edit</a>',
				'<a href="#" onClick="deleteConfirm2(\'Are you sure you wish to delete this record\',\'users_list.php?delete=1&UserID='.$UserID.'\',\'content\',\'loader\',\''.$Option.'\')">Delete</a>'
	);
}  
$rss = (object) array('aaData'=>$channel);
$json = json_encode($rss);
echo $json;
?>