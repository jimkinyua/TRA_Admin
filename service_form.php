<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
/*print_r ($_REQUEST);

foreach ($_REQUEST AS $id => $name)
{
	echo "$id=$name <br>";	
	$newID=substr($id,3,strlen($id)-3);	
	echo '****************';
	echo "$newID=>$name <br>";
}*/
$msg ='';
$UserID = $_SESSION['UserID'];

if (isset($_REQUEST['msg']))
{
	$msg = $_REQUEST['msg'];	
}

$ApplicationID=$_REQUEST['ApplicationID'];
$FormColumnID='';
$Value='';

?>
<div class="example">
   <legend>Application Approval Form</legend>
   <form>
      <fieldset>
			<table>
            	<?php 
					/* $sql="select fc.FormColumnID, fc.FormColumnName,fd.Value, fs.FormSectionID 
					from FormColumns fc 
					join FormSections fs on fc.FormSectionID=fs.FormSectionID
					left join FormData fd on fd.FormColumnID=fc.FormColumnID
					where fs.FormID=(select distinct FormID from FormColumns where FormColumnID in
					(select FormColumnID from FormData where ServiceHeaderID=$ApplicationID)
					)"; */
					
					$sql="select fd.FormColumnID,fd.ServiceHeaderID,fd.Value
						,fc.FormColumnName,fs.FormSectionName 
						from FormData fd 
						left join FormColumns fc on fd.FormColumnID=fc.FormColumnID
						inner join FormSections fs on fc.FormSectionID=fs.FormSectionID
						where fd.ServiceHeaderID= $ApplicationID";
					
					//and Show=0
						
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
							$myID= 'FD_'.$row['FormColumnID'];
							
							$link .= "+'&".$myID."='+this.form.".$myID.'.value';	
												
							echo"<tr>
								<td width='50%'><label>".$row['FormColumnName']."</label>
								  <div class='input-control textarea' data-role='input-control'>
									
									<textarea id='".$myID."' name='".$myID."' value='".$row['Value']."'>".$row['Value']."</textarea>
								  </div>
								</td>						  
							  <td width='50%'></td>   
							</tr>";		
							
							
							$data[] = array(
     
     							 $ServiceHeaderID,
								 $row['FormColumnID'],
								 $FormColumnName,
								 $row['Value'] 
								
 							 );
							//$data=array_merge($data,array_values($row));				
						}						
						//print_r($data);
						//echo $link;									
				?>  
                <tr>
                <td width="50%">
                	<input name="Button" type="button" onclick="loadmypage('service_approval.php?approve=1&ApplicationID=<?php echo $ApplicationID ?>'<?php echo $link; ?>,'content','loader','listpages','','applications')" value="Save">
                </td>
                </tr>          
            </table>      		
      
      </fieldset>
  </form>                  
</div>