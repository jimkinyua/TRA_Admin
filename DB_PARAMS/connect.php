<?php	
//require_once('config.php');

<<<<<<< HEAD
$myServer = "TRA\SQ2017";
$myUser = "portalman";
=======
<<<<<<< HEAD
$myServer = "TRA\SQL2017";
$myUser = "portalman";
=======

$myServer = "TRA-EDMS\SQLTRA";
$myUser = "sa";
>>>>>>> master
>>>>>>> 3ad107e96943b1c876a16b55803172dfb084f656
$myPass = 'portalman';
$myDB = "TRANEW";


$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

$connectionInfo = array("UID" => $myUser, "PWD" => $myPass,
 "Database"=> $myDB, "ReturnDatesAsStrings" => true,
 "CharacterSet" => "UTF-8");
//$db = mssql_connect($myServer, $myUser, $myPass) or die("Couldn't connect to SQL Server on $myServer");
$db = sqlsrv_connect( $myServer, $connectionInfo);
if ($db)
{
	//echo "Database Connection successful";
} else
{
	echo "Database Connection Failed";
	// echo "Parameters are $myUser $myPass $myDB";
}

?>