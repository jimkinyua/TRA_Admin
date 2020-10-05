<?php
	require_once 'DB_PARAMS/connect.php';

/* 	if(isset($_REQUEST['ServiceName'])){$ServiceName = $_REQUEST['ServiceName'];}
	if(isset($_REQUEST['CustomerID'])){$CustomerID = $_REQUEST['CustomerID'];}*/
	if(isset($_REQUEST['id'])){$id = $_REQUEST['id'];}
	if(isset($_REQUEST['target'])){$target = $_REQUEST['target'];} 
	
	$keyword = '%'.$_POST['keyword'].'%';
	
	$sql = "SELECT ServiceName,ServiceID from Services WHERE ServiceName like '$keyword'";

	$result  = sqlsrv_query($db, $sql);
	//print_r($_REQUEST);
	while ($myrow = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) 
	{
		$ServiceID = $myrow['ServiceID'];
		$ServiceName = $myrow['ServiceName'];
		echo '<li onclick="set_item(\''.str_replace("'", "\'", $ServiceName).'\',\''.$id.'\',\''.$target.'\')">'.$ServiceName.'</li>';
	}
	
	
?>