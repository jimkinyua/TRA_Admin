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
$CreatedUserID = $_SESSION['UserID'];

$ServiceName=$_REQUEST['ServiceName'];
$SetupID=$_REQUEST['SetupID'];
$ServiceID=$_REQUEST['ServiceID'];
$Fixed=$_REQUEST['Fixed'];



//print_r($_REQUEST); exit;

if (isset($_REQUEST['delete']))
{
	$SetupID=	$_REQUEST['SetupID'];
	$FencingTypeID=$_REQUEST['FencingTypeID'];
	
	$sql = "DELETE FROM FencingSetup WHERE SetupID=$SetupID ";
	//echo $sql;
	$result = sqlsrv_query($db, $sql);
	if ($result)
	{
		$rst=SaveTransaction($db,$UserID," Deleted the service charges for Fencing Type $FencingTypeID");
		$msg = "Record Deleted Successfully";
	} else
	{
		DisplayErrors();

		$msg = $sql;//"Record Failed to be Deleted";
	}
}

if (isset($_REQUEST['save']))
{	
	$SetupID=$_REQUEST['SetupID'];
	$FencingTypeID=$_REQUEST['FencingTypeID'];	
	$ServiceID=$_REQUEST['ServiceID'];
	$Amount=$_REQUEST['Amount'];
	$Fixed=$_REQUEST['Fixed'];
	$Percentage=$_REQUEST['Percentage'];
	$Minimum=$_REQUEST['Minimum'];
	
	$Amount=(double)str_replace(",", "", $Amount);
	$Minimum=(double)str_replace(",", "", $Minimum);
	
	$sql="if not exists(select * from FencingSetup where SetupID=$SetupID)
	insert into FencingSetup(FencingTypeID,ServiceID,Amount,Fixed,Minimum,CreatedBy,Percentage) 
		values($FencingTypeID,$ServiceID,$Amount,$Fixed,$Minimum,$CreatedUserID,$Percentage)
	else
		update FencingSetup set Amount=$Amount,Fixed='$Fixed',
	ServiceID=$ServiceID, FencingTypeID=$FencingTypeID,Minimum=$Minimum,Percentage=$Percentage
	where SetupID=$SetupID";


	$result = sqlsrv_query($db, $sql);

	

	if (!$result)
	{
		DisplayErrors();
		echo $sql;
		$msg = "Commit Failed";			
	}else
	{
		$rst=SaveTransaction($db,$UserID," Created/Updated the fencing setup for Type number $FencingTypeID.");
		$msg = "Saved Details Successfully";			
	}
}
?>
    <link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/prettify/prettify.css" rel="stylesheet">
<body class="metro">
        <div class="example">
        <legend>Other Plan Related Charges</legend>        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th class="text-left" colspan="5"><?php echo $ServiceName; ?></th>                  
                  </tr>                                  
                  <tr>
                    <th class="text-left"><a href="#" onClick="loadmypage('fencing_charge.php?add=1','content')">New</a></th>
                    <th colspan="4" class="text-center" style="color:#F00"><?php echo $msg; ?></th					 
                  </tr>
                <tr>
                    <th  class="text-left">Fencing Type</th>
                    <th  class="text-left">Service</th>
                    <th  class="text-left">Fixed</th>
                    <th  class="text-left">Amount</th>
                    <th  class="text-left">Minimum</th>
                    <th  class="text-left">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>


</div>
</div>