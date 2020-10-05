<?php
require 'DB_PARAMS/connect.php';

require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';



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
        <legend>Complaint Logs for Complaint Number <?php echo $ComplaintID; ?></legend>
<form>        
            <table class="table striped hovered dataTable" id="dataTables-1" width="100%">
                <thead>			  
                <tr>
                    <th  class="text-left">Action Date</th>
                    <th  class="text-left">Action By</th>
                    <th  class="text-left">Comment</th>                    
                </tr>
                </thead>

                <tbody>
                </tbody>
            </table>
</form>

</div>
</div>