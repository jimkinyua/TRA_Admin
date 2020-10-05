<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}

$msg ='';
$UserID = $_SESSION['UserID'];

if (isset($_REQUEST['msg']))
{
	$msg = $_REQUEST['msg'];	
}

$MarketID=$_REQUEST['MarketID'];
$MarketName=$_REQUEST['MarketName'];

if (isset($_REQUEST['save']))
{
	$MarketName=$_REQUEST['MarketName'];
	$input=array_slice($_REQUEST,2,count($input)-1);
	
	echo $RoleCenterName;
	
	foreach ($input AS $id => $value)
	{	
		$newID=substr($id,3,strlen($id)-3);	
		
		if ($value=="true"){
			$sql="if not exists (select 1 from RolecenterApproval where RoleCenterID=$RoleCenterID and ServiceStatusID=$newID) insert into RoleCenterApproval (RoleCenterID,ServiceStatusID) Values($RoleCenterID,$newID)";
		}else{
			$sql="Delete from RolecenterApproval where RoleCenterID=$RoleCenterID and ServiceStatusID=$newID";	
		}		
				
		$result=sqlsrv_query($db,$sql);
		
		if(!$result)
		{
			DisplayErrors();
			continue;
		}else{
				
		}		

	}
	echo 'Done Successfully!';
}


?>
<div class="example">
   <legend>Services For <?php echo $MarketName; ?> Market </legend>
   <form>
      <fieldset>
			<table>
            	<?php 
					$sql="select st.ServiceID,s.ServiceName from 
						ServiceTrees st 
						join services s on st.ServiceID=s.ServiceID
						where st.IsService=1 and st.ServiceID<>0";
						
						$data=array();
						
					  	$result=sqlsrv_query($db,$sql);
						if ($result==false)
						{
						  DisplayErrors();
						  die;
						}
						$link="";						
						while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
						{	
							$myID= 'FD_'.$row['ServiceID'];
							
							$link .= "+'&".$myID."='+this.form.".$myID.'.checked';	
							
							$AccesValue = '';	
							if ($row['Accesses']== 1) {$AccesValue = 'checked="checked"';}
							
												
							echo"<tr>
								<td colspan=2>									
									<label>
										<input name=".$myID." id=".$myID." type='checkbox' ".$AccesValue."/>
										<span class='check'></span>"
										. $row['MarketName']."
									</label>					
								  </div>
								</td>						  
							  
							</tr>";		
									
						}						
								
				?>  
                <tr>
                <td width="50%">
                	<input name="Button" type="button" onclick="loadpage('market_service_assignment.php?save=1$MarketName=<?php echo $MarketName ?>&MarketID=<?php echo $MarketID ?>'<?php echo $link; ?>,'content')" value="Save">
                </td>
                </tr>          
            </table>      		
      
      </fieldset>
  </form>                  
</div>