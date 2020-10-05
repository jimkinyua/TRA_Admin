<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
require_once('GlobalFunctions.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$UserID = $_SESSION['UserID'];
$result="";

$PageID=55;

if($_REQUEST['approve']==1)
{
    //print_r($_REQUEST); exit;
    $PageID=$_REQUEST['PageID'];
    $ReferenceNumber=$_REQUEST['DocumentNo'];    
    $Comments=$_REQUEST['Comments'];

    if ($PageID==25)//Receipts
    {
        $Amount=(double)str_replace(',', '',$_REQUEST['amount']);

        if($Amount>$_SESSION['MaximumApprovalLimit'])
        {

            $sql="insert into ApprovalEntry(SenderID,ApproverID,PageID,Action,DocumentNo,RefNumber,Comments,ApprovalComments,ApprovalStatus)
                select SenderID,'$UserID',PageID,[Action],DocumentNo,RefNumber,Comments,'$Comments','2' 
                from ApprovalEntry where DocumentNo='$ReferenceNumber'";

            //echo $sql; exit;

            $result=sqlsrv_query($db,$sql);
            if(!$result)
            {
                DisplayErrors();
                echo $sql;
            }else{
                $msg="Request forwarded to the higher level Approvel"; 
            }

        }else
        {
            $msg=ReverseMoney($db,$ReferenceNumber,$UserID);
            $sql="Update ApprovalEntry set ApprovalStatus=1 where DocumentNo='$ReferenceNumber'";
            $result=sqlsrv_query($db,$sql);

            $rst=SaveTransaction($db,$UserID,"Approved Reversal Request for Receipt number  ".$ReferenceNumber); 

        }

        

    }else if($PageID==49)//Plots
    {
        $LastStage=0;
       $sql="insert into ApprovalLogs(RequestID,Comments,ApprovalStatus,ApprovalStage,CreatedBy)
                select top 1 RequestID, '',iif(ApprovalStage+1>=3,1,'0'),ApprovalStage+1,$UserID 
                from ApprovalEntry where DocumentNo='$ReferenceNumber' order by ApprovalStage desc";


        $result=sqlsrv_query($db,$sql);
        if(!$result)
        {
            DisplayErrors();
            //echo $sql;
        }else{

            $sql="Update ApprovalEntry set ApprovalStage=ApprovalStage+1 where DocumentNo='$ReferenceNumber'";
            $result=sqlsrv_query($db,$sql);

            $sql="select max(ApprovalStage)LastStage from ApprovalEntry where DocumentNo='$ReferenceNumber'";
            echo $sql;
            $qry=sqlsrv_query($db,$sql);

            while ($myrow = sqlsrv_fetch_array( $qry, SQLSRV_FETCH_ASSOC)) 
            {
                $LastStage=$myrow['LastStage'];                
            }   

            
        }

        if($LastStage>=3){
            $fdback=AdjustPlot($db,$ReferenceNumber,$UserID);

            $sql="Update ApprovalEntry set ApprovalStatus=1 where DocumentNo='$ReferenceNumber'";
            $result=sqlsrv_query($db,$sql);

            $msg=$fdback[1];
        }        
    } 
    
}else{

    $PageID=$_REQUEST['PageID'];
    $ReferenceNumber=$_REQUEST['DocumentNo'];
    $ReceiptID=$_REQUEST['ReceiptID'];
    $Comments=$_REQUEST['Comments'];

    $sql="update ApprovalEntry set ApprovalStatus=2,DeclineComments='$Comments' where DocumentNo='$ReferenceNumber'";
    $result=sqlsrv_query($db,$sql);

    $rst=SaveTransaction($db,$UserID,"Declined a reversal Request for Receipt number  ".$ReferenceNumber); 


}


?>
    <link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/prettify/prettify.css" rel="stylesheet">

    
<body class="metro">
        <div class="example">
        <legend>Approval Requests</legend>
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th class="text-left"><a href="#" onClick="loadpage('clients.php?add=1','content')"></a></th>
                    <th colspan="6" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>
                <tr>
                    <th  class="text-left">Date</th>
                    <th  class="text-left">Sender</th>
                    <th  class="text-left">Request Type</th>                   
                    <th  class="text-left">Document No</th>
                    <th  class="text-left">Coments</th>
                    <th  class="text-left">Module</th>
					<th  class="text-left"></th>
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>
</div>
</div>