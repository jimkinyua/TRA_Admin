<?php
require 'DB_PARAMS/connect.php';

require 'DB_PARAMS/connect.php';
require_once('utilities.php');
require_once('GlobalFunctions.php');
require_once('county_details.php');

if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];

if ($_REQUEST['resend']=='1')
{
	$ServiceHeaderID=$_REQUEST['ServiceHeaderID'];	
	$feedBack=createPermit($db,$ServiceHeaderID,$cosmasRow);
}

?>

<body class="metro">
    <div class="example">
		<a class="media" href="C:\inetpub\wwwroot\revenueadmin\pdfdocs\sbps\1453387403.pdf">PDF File</a> 
	</div>
</body>