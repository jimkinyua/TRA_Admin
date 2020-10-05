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

$RoleCenterID=$_REQUEST['RoleCenterID'];
$RoleCenterName=$_REQUEST['RoleCenterName'];

if (isset($_REQUEST['save']))
{
	$RoleCenterName=$_REQUEST['RoleCenterName'];
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
   <legend>Role Center Approval Rights for <?php echo $RoleCenterName; ?> </legend>
   <form>
      <fieldset>
			<table>
            <!--	<tr>
			  		<td><label>Service Category</label>
                        <div class="input-control select" data-role="input-control">
                            <select name="ServiceCategoryID"  id="ServiceCategoryID">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT * FROM ServiceCategory ORDER BY 1";
                           
                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                {
                                    $s_id = $row["ServiceCategoryID"];
                                    $s_name = $row["CategoryName"];
                                    if ($ServiceCategoryID==$s_id) 
                                    {
                                        $selected = 'selected="selected"';
                                    } else
                                    {
                                        $selected = '';
                                    }												
                                 ?>
                            <option value="<?php echo $s_id; ?>" <?php echo $selected; ?>><?php echo $s_name; ?></option>
                            <?php 
                                }
                            }
                            ?>
                          </select>
                      </div>
                      
                     </td>
                  <td>
                  </td>
          </tr> -->
            	<tr>
                	<th width="50%" align="left">Service Status</th>
                    <td></td>
                </tr>
            	<?php 
					$sql="select rca.RoleCenterApprovalID,isnull(rca.RoleCenterID,'')RoleCenterID,iif(rca.ServiceStatusID is null,'0','1') Accesses, 
					ss.ServiceStatusName, ss.ServiceStatusID 
					from RoleCenterApproval rca
					right join ServiceStatus ss 
					on ss.ServiceStatusID=rca.ServiceStatusID AND RoleCenterID = $RoleCenterID
					order by ss.ServiceStatusID,rca.RoleCenterID";
						
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
							$myID= 'FD_'.$row['ServiceStatusID'];
							
							$link .= "+'&".$myID."='+this.form.".$myID.'.checked';	
							
							$AccesValue = '';	
							if ($row['Accesses']== 1) {$AccesValue = 'checked="checked"';}
							
												
							echo"<tr>
								<td width='50%'>									
									<label>
										<input name=".$myID." id=".$myID." type='checkbox' ".$AccesValue."/>
										<span class='check'></span>"
										. $row['ServiceStatusName']."
									</label>					
								  </div>
								</td>						  
							  <td width='50%'></td>   
							</tr>";		
									
						}						
								
				?>  
                <tr>
                <td width="50%">
                	<input name="Button" type="button" onclick="loadpage('role_center_approval.php?save=1$RoleCenterName=<?php echo $RoleCenterName ?>&RoleCenterID=<?php echo $RoleCenterID ?>'<?php echo $link; ?>,'content')" value="Save">
                </td>
                </tr>          
            </table>      		
      
      </fieldset>
  </form>                  
</div>