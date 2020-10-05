<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];
$default='0';

if (isset($_REQUEST['delete']))
{
	$FinancialYearID=$_REQUEST['FinancialYearID'];
	$sql="Delete from FinancialYear where FinancialYearID=$FinancialYearID";
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "FinancialYear Deleted Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Action failed";			
	}	
}

if (isset($_REQUEST['save']))
{	

	$FinancialYearID=$_REQUEST['FinancialYearID'];
	$FinancialYearName=$_REQUEST['FinancialYearName'];	
	$TargetCollection=$_REQUEST['TargetCollection'];
	$default=$_REQUEST['default'];
	if ($FinancialYearID=='0')
	{
		$sql="Insert into FinancialYear (FinancialYearName,TargetCollection,CreatedBY,isCurrentYear)
		Values('$FinancialYearName',$TargetCollection,$CreatedUserID,$default)";

	} else
	{
		$sql="Update FinancialYear set FinancialYearName='$FinancialYearName',isCurrentYear=$default where FinancialYearID=$FinancialYearID";
	}	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{
		if($default=='1')
		{
			$sql="Update FinancialYear set isCurrentYear=0 where FinancialYearID<>$FinancialYearID";	
			$result = sqlsrv_query($db, $sql);
			if($result)
			{
				$msg = "FinancialYear Saved Successfully";	
			}
		}				
	} else
	{
		DisplayErrors();
		$msg = "Details Failed to save";
				
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
        	<legend>FinancialYears</legend>        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th class="text-left"><a href="#" onClick="loadmypage('financialyear.php?i=1','content')">Add</a></th>
                    <th colspan="5" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>
                <tr>
                    <th width="14%" class="text-left">FinancialYearID</th>
                    <th width="12%" class="text-left">Target Collection</th>
                    <th width="12%" class="text-left">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table> 
		</div>
</div>


