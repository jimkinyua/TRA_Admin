<?php 
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];

if (isset($_REQUEST['msg']))
{
	$msg = $_REQUEST['msg'];	
}

$PeriodID="0";
$StartDate="";

if (isset($_REQUEST['edit']))
{	
	if (isset($_REQUEST['CountyID'])){$CountyID=$_REQUEST['CountyID'];}
	
    $sql = "select * from Counties as C join 
    SubSystems as SubSys on SubSys.SubSystemID =
     C.TraRegionCode WHERE C.CountyId =  $CountyID";
    // exit($sql);
    
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$TraRegionCode=$myrow['TraRegionCode'];
		$CountyId=$myrow['CountyId'];
		$CountyName=$myrow['CountyName'];
	}	
}
?>
<link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/prettify/prettify.css" rel="stylesheet">
<script type="text/javascript">
        $(".datepicker").datepicker();
</script>

<link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/prettify/prettify.css" rel="stylesheet">
		
<body class="metro">
        <div class="example">
        <legend>Request Types For <?= $CountyName ?> Region</legend>        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
				  <tr>
					<th class="text-left"><a href="#" onClick="loadmypage('WaiverPeriod.php?i=1','content')">Add Request Type</a></th>
					<th colspan="5" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
				  </tr>
                <tr>
                    <th width="15%" class="text-left">Id</th>
                    <th width="15%" class="text-left">Request Type</th>
					<th width="15%" class="text-left">Action</th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table> 
		</div>
</div>