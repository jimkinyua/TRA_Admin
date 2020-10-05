<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];
$CustomerName=$_REQUEST['Customer'];


?>
    <link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/prettify/prettify.css" rel="stylesheet">
<body class="metro">
        <div class="example">
        <legend><?php echo $CustomerName."'s"; ?> Services</legend>        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                <tr>
                    <th width="25%" class="text-left">ApplicationID</th>
					<th width="75%" class="text-left">ServiceName</th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table> 


</div>
</div>


