<?php
require 'DB_PARAMS/connect.php';

require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';

$PageID=25;
$myRights=getrights($db,$UserID,$PageID);
$UserID = $_SESSION['UserID'];

if ($myRights)
{
	$View=$myRights['View'];
	$Edit=$myRights['Edit'];
	$Add=$myRights['Add'];
	$Delete=$myRights['Delete'];
}

$fromDate=date('d/m/Y');
$toDate=date('d/m/Y');
$RefNo='';
$InvoiceNo='';




if($_REQUEST['save']==1)
{
	//print_r($_REQUEST); 
	if(isset($_REQUEST['refno'])){$RefNo=$_REQUEST['refno'];}
	if(isset($_REQUEST['Description'])){$Description=$_REQUEST['Description'];}

	$sql="Insert into Complaints(CreatedBy,Description,RefNumber,Status) Values('$UserID','$Description','$RefNo',0)";
    //echo $sql;
	$result = sqlsrv_query($db, $sql);
	if($result)
	{
		$msg="Issue Reported successfully";
	}else{
		$msg="Report failed to save";		
	}
}

if($_REQUEST['response']==1)
{
    if(isset($_REQUEST['ComplaintID'])){$ComplaintID=$_REQUEST['ComplaintID'];}
    if(isset($_REQUEST['RefNo'])){$RefNo=$_REQUEST['RefNo'];}
    if(isset($_REQUEST['Description'])){$Description=$_REQUEST['Description'];}
    if(isset($_REQUEST['Status'])){$Status=$_REQUEST['Status'];}

    $sql="Insert into ComplaintLogs(ComplaintID,Comment,CreatedBy) Values('$ComplaintID','$Description','$UserID')";
    $result = sqlsrv_query($db, $sql);
    if($result)
    {
        $sql="Update Complaints Set StatusComment='$Description', Status='$Status' where ComplaintID='$ComplaintID'";
        $result = sqlsrv_query($db, $sql);
        if($result)
        {
            $msg="Response posted Successfully";
        }else{
            $msg="Response failed to save";       
        }

    }else{
        $msg="Response failed to save";       
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
<body class="metro">
        <div class="example">
        <legend>Complaints</legend>
<form>        
            <table class="table striped hovered dataTable" id="dataTables-1" width="100%">
                <thead>
                  <tr>
                  	<th class="text-left"><a href="#" onClick="loadmypage('complaint.php?i=1','content')">New</a></th>
                    <th colspan="5" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>				  
				  
                <tr>
                    <th  class="text-left">Report Date</th>
                    <th  class="text-left">Reported By</th>
                    <th  class="text-left">Description</th>
                    <th  class="text-left">Ref Number</th>
                    <th  class="text-left">Status</th>
                    <th  class="text-left">Status Comment</th>
					<th  class="text-left"></th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>
</form>

</div>
</div>