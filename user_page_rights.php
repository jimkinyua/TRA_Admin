<?php 
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];

 //print_r($_REQUEST);

if (isset($_REQUEST['msg']))
{
	$msg = $_REQUEST['msg'];	
}

//print_r($_REQUEST);


$RoleCenterID='';
$PageID='';




	
	$sql = "select [View],[Edit],[Add],[Delete] from Roles where RoleCenterID=$RoleCenterID";

	//print_r($sql);		
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$View=$myrow['View'];
        $Edit=$myrow['Edit'];
        $Add=$myrow['Add'];
        $Delete=$myrow['Delete'];
	}
    

?>
<script type="text/javascript">
        $("#user_rights").on('click', function(ev){
            var url = 'add_charge.php?ApplicationID=' 
                + ev.target.dataset.appId + '&SubSystemID=' + ev.target.dataset.ssId+ '&ServiceID=' + ev.target.dataset.sId
            //console.log(url)
            $.get(url, function(res) {
                $.Dialog({
                    shadow: true,
                    overlay: false,
                    draggable: true,
                    icon: '<span class="icon-rocket"></span>',
                    title: 'Application Charges',
                    width: 500,
                    padding: 10,
                    content: res
                });
            })
            
        });
 </script>
<!DOCTYPE html>
<html>
<head>
    <title>Aplication Charges</title>   
</head>
<body class="metro" >
    <div class="example" id="charge">
        <form action="add_charge.php" method="POST">
            <fieldset>
              <legend>MODIFY ROLE CENTER PAGE ROLES</legend>
                <table border="0" cellspacing="0" cellpadding="3" width="100%">
                    <tr>
                      <td align="center" colspan="2" style="color:#F00">
                      <?php echo $msg; ?>
                          <div class="input-control checkbox" data-role="input-control">
                                <label class="inline-block">
                                    <input type="checkbox" />
                                    <span class="check"></span>
                                    Edit
                                </label>
                            </div>
                      </td>
                  </tr>
                    <tr>
                      <td align="center" colspan="2" style="color:#F00">
                      <?php echo $msg; ?>
                          <div class="input-control checkbox" data-role="input-control">
                                <label class="inline-block">
                                    <input type="checkbox" />
                                    <span class="check"></span>
                                    Add
                                </label>
                            </div>
                      </td>
                  </tr>  
                  <tr>
                  <td align="center" colspan="2" style="color:#F00">
                      <?php echo $msg; ?>
                          <div class="input-control checkbox" data-role="input-control">
                                <label class="inline-block">
                                    <input type="checkbox" />
                                    <span class="check"></span>
                                    Delete
                                </label>
                            </div>
                      </td>
                  </tr>                     
                             
                </table>                                      
             
                <div style="margin-top: 20px">
        </div>

            </fieldset>
        </form>      
</BODY>
</html>