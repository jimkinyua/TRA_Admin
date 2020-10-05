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

?>
    <link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/prettify/prettify.css" rel="stylesheet">

    
<body class="metro">
        <div class="example">
        <legend>Receipts</legend>
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th class="text-left"><a href="#" onClick="loadpage('clients.php?add=1','content')"></a></th>
                    <th colspan="6" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>
                <tr>
                    <th width="5%" class="text-left"> Receipt Date</th>
                     <th width="12%" class="text-left">Reference Number</th>                   
                    <th width="20%" class="text-left">Amount</th>
                    <th width="20%" class="text-left">Receipt Method</th><strong></strong>
					<th width="20%" class="text-left"></th><strong></strong>
                </tr>
                </thead>

                <tbody>
                </tbody>

                <tfoot>
                <tr>
                    <th class="text-left"> Receipt Date</th>
                     <th class="text-left">Reference Number</th>                   
                    <th class="text-left">Amount</th>    
                    <th class="text-left">Receipt Method</th>    
                    <th class="text-left"></th>    
                </tr>
                </tfoot>
            </table>


</div>
</div>