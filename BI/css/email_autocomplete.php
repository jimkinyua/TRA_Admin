<?php
	/* require_once 'DB_PARAMS/connect.php';

	if(isset($_REQUEST['UserID'])){$UserID = $_REQUEST['UserID'];}
	if(isset($_REQUEST['CustomerID'])){$CustomerID = $_REQUEST['CustomerID'];}
	if(isset($_REQUEST['id'])){$id = $_REQUEST['id'];}
	if(isset($_REQUEST['target'])){$target = $_REQUEST['target'];}
	$keyword = '%'.$_POST['keyword'].'%';
	$sql = "SELECT Email,FirstName+' '+LastName AS Name FROM Profiles WHERE CompanyID = '$CustomerID'
	UNION
	SELECT UserEmail AS Email,UserFullName AS Name FROM Users
	WHERE (UserEmail like '%$keyword%' OR UserFullName like '%$keyword%')";
	$result  = sqlsrv_query($db, $sql);
	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$Email = $myrow['Email'];
		$Name = $myrow['Name'];
		echo '<li onclick="set_item(\''.str_replace("'", "\'", $Email).'\',\''.$id.'\',\''.$target.'\')">'.$Email.'</li>';
	} */
	
	echo 'hi';
?>