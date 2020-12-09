<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
require_once('GlobalFunctions.php');
if (!isset($_SESSION))
{
	session_start();
}

$msg ='';
$CreatedUserID = $_SESSION['UserID'];
$DefaultMenuGroupID=$_SESSION['DefaultMenuGroupID'];
$TabActive="";

//print_r($_SESSION);

$myExcemptions;

$sql="
	select distinct MenuGroupID from menugroups where menugroupid not in (
	select  distinct p.MenuGroupID from UserRoles ur
	inner join Roles r on ur.RoleCenterID=r.RoleCenterID
	inner join Pages p  on r.PageID=p.PageID
	where ur.UserID=$CreatedUserID and MenuGroupID is not null and r.[View]=1)
	";

//echo $sql;
	
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

$result=sqlsrv_query($db,$sql,$params,$options);
$rows=sqlsrv_num_rows($result);
$i=1;
while ($i<=$rows)
{
	$row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
	$myExcemptions[$i]=$row['MenuGroupID'];
	$i+=1;
}



// $PageID=12;
$myRights=getrights($db,$UserID,$PageID);
// if ($myRights)
// {
// 	$View=$myRights['View'];
// 	$Edit=$myRights['Edit'];
// 	$Add=$myRights['Add'];
// 	$Delete=$myRights['Delete'];
// }

// echo '<pre>'; print_r($myRights); exit;

?>
        <div class="fluent-menu" data-role="fluentmenu">
        	<ul class="tabs-holder">
            	<li class="special"><a href="#" onClick="loadmypage('profilehome.php?ContactTypeID=1','content')">Home</a></li>
                <?php 
					if (!in_array('1',$myExcemptions))
					{ 
						if($DefaultMenuGroupID==1){
							$TabActive="active";
						}
				
				?>
                <li class=<?php echo $TabActive; ?>><a href="#tab_manage">Manage</a></li>                    
                    <?php
					}
					if (!in_array('2',$myExcemptions))
					{
						if($DefaultMenuGroupID==2){
							$TabActive="active";
						}						
				?>
                <li class=<?php echo $TabActive; ?>><a href="#tab_user_security">Users and Security</a></li>                   
                    <?php
					}
					if (!in_array('3',$myExcemptions))
					{ 
						if($DefaultMenuGroupID==3){
							$TabActive="active";
						}				
				?>
                <li class=<?php echo $TabActive; ?>><a href="#tab_setup">Setup</a></li>                   
                    <?php
					}
					if (!in_array('4',$myExcemptions))
					{ ?>
                <li class=<?php echo $TabActive; ?>><a href="#tab_support">Support</a></li>                    
                    <?php
					}
					if (!in_array('5',$myExcemptions))
					{ 
						if($DefaultMenuGroupID==3){
							$TabActive="active";
						}				
				?>
               <li class=<?php echo $TabActive; ?>><a href="#tab_finance">Finance</a></li>                    
                    <?php }	?> 			 
				 
           </ul>

           <div class="tabs-content">

                <div class="tab-panel" id="tab_manage">
                	<div class="tab-panel-group">
                    	<div class="tab-group-content">
                    		<?php 
                    			$PageID=14;
								$myRights=getrights($db,$UserID,$PageID);						
								if ($myRights['View']==1){  
							?>

								<button class="fluent-big-button" onClick="loadmypage('approved_licences_pending_approval.php?i=1','content','loader','listpages','','approved_pending_approval','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" style="width:80px">
									<span class="icon-layers-alt fg-darkBlue"></span>
									<span class="button-label fg-darkBlue">Approved Licences Pending Approval</span>
								</button> 
							<?php } 	
							?>

										<?php 
                    		$PageID=69; //Regional Officer Views
								$myRights=getrights($db,$UserID,$PageID);	
								// echo '<pre>';
								// print_r($UserID);
								// exit;					
								if ($myRights['View']==1){  
							?>

								<button class="fluent-big-button" onClick="loadmypage('approved_licences_pending_approval.php?i=1','content','loader','listpages','','approved_pending_approval','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" style="width:80px">
									<span class="icon-layers-alt fg-darkBlue"></span>
									<span class="button-label fg-darkBlue">Approved Licences Pending Approval</span>
								</button> 
							
							<?php } 	
							?>	


							<?php 
                    		$PageID=71; //Regional Officer Views
								$myRights=getrights($db,$UserID,$PageID);	
								// echo '<pre>';
								// print_r($UserID);
								// exit;					
								if ($myRights['View']==1){  
							?>

								<button class="fluent-big-button" onClick="loadmypage('approved_facilitation_pending_approval.php?i=1','content','loader','listpages','','approved_facilitation_approval','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" style="width:80px">
									<span class="icon-layers-alt fg-darkBlue"></span>
									<span class="button-label fg-darkBlue">Trade and Facilitation Approval</span>
								</button> 
							
							<?php } 	
							?>	
							<?php 
                    		$PageID=1071; //Regional Officer Views
								$myRights=getrights($db,$UserID,$PageID);	
								// echo '<pre>';
								// print_r($UserID);
								// exit;					
								if ($myRights['View']==1){  
							?>

								<button class="fluent-big-button" onClick="loadmypage('classification_applications.php?i=1','content','loader','listpages','','classificationapplications','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" style="width:80px">
									<span class="icon-layers-alt fg-darkBlue"></span>
									<span class="button-label fg-darkBlue">Classification and Grading Applications</span>
								</button> 
								<button class="fluent-big-button" onClick="loadmypage('licence-application-invoices.php?i=1','content','loader','listpages','','licence-application-invoices-b','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" style="width:80px">
                            	<span class="icon-libreoffice fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">Classification Application Invoices</span>
                            
							<?php } 	
							?>

							<?php 
                    		$PageID=2071; //Regional Officer Views
								$myRights=getrights($db,$UserID,$PageID);	
								// echo '<pre>';
								// print_r($UserID);
								// exit;					
								if ($myRights['View']==1){  
							?>

								<button class="fluent-big-button" onClick="loadmypage('classification_approval.php?i=1','content','loader','listpages','','classificationapproval','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" style="width:80px">
									<span class="icon-layers-alt fg-darkBlue"></span>
									<span class="button-label fg-darkBlue">Classification and Grading Management</span>
								</button> 
								<button class="fluent-big-button" onClick="loadmypage('classification_list.php?ApplicationID=<?php echo $ApplicationID; ?>&CurrentStatus=<?php echo $CurrentStatus; ?>','content','loader','listpages','','Inspections','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" style="width:80px">
									<span class="icon-layers-alt fg-darkBlue"></span>
									<span class="button-label fg-darkBlue">Inspections</span>
							</button>
							<?php } 	
							?>
								
								<?php 
                    		$PageID=2072; //Regional Officer Views
								$myRights=getrights($db,$UserID,$PageID);	
								// echo '<pre>';
								// print_r($UserID);
								// exit;					
								if ($myRights['View']==1){  
							?>

								<button class="fluent-big-button" onClick="loadmypage('approved_classification_pending_approval.php?i=1','content','loader','listpages','','approved_classification_approval','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" style="width:80px">
									<span class="icon-layers-alt fg-darkBlue"></span>
									<span class="button-label fg-darkBlue">Classification and Grading Review</span>
								</button> 
								<button class="fluent-big-button" onClick="loadmypage('classification_list.php?ApplicationID=<?php echo $ApplicationID; ?>&CurrentStatus=<?php echo $CurrentStatus; ?>','content','loader','listpages','','Inspections','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" style="width:80px">
									<span class="icon-layers-alt fg-darkBlue"></span>
									<span class="button-label fg-darkBlue">Inspections</span>
								</button>
								
								<button class="fluent-big-button" onClick="loadmypage('setrating.php?ApplicationID=<?php echo $ApplicationID; ?>&CurrentStatus=<?php echo $CurrentStatus; ?>','content','loader','listpages','','Inspections','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" style="width:80px">
									<span class="icon-layers-alt fg-darkBlue"></span>
									<span class="button-label fg-darkBlue">Set Ratings for Establishments</span>
								</button>
							<?php } 	
							?>

							<?php 
                    		$PageID=68; //Chief Officer Views
								$myRights=getrights($db,$UserID,$PageID);
								

								if ($myRights['View']==1){  
							?>

							<button class="fluent-big-button" onClick="loadmypage('licences_approved_by_officer.php?i=1','content','loader','listpages','','LicenceApplicationApprovedByOfficer','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" style="width:80px">
                            	<span class="icon-layers-alt fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue"> Licence Applications</span>
                            </button>
							
							<button class="fluent-big-button" onClick="loadmypage('renewal_applications_list.php?i=1','content','loader','listpages','','renewalapplications','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" style="width:80px">
                            	<span class="icon-layers-alt fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">Licence Renewal Applications</span>
                            </button>

							<button class="fluent-big-button" onClick="loadmypage('inspections_list.php?i=1','content','loader','listpages','','Inspections','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" style="width:80px">
									<span class="icon-layers-alt fg-darkBlue"></span>
									<span class="button-label fg-darkBlue">Inspections</span>
							</button>
							
							<?php } 	
							?>

							<?php

								$PageID=1;
								$myRights=getrights($db,$UserID,$PageID);						
								if ($myRights['View']==1){	?>	
                         	<button class="fluent-big-button" onClick="loadmypage('SubmittedApplications.php?i=1','content','loader','listpages','','SubmittedApplications','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" style="width:80px">
                            	<span class="icon-layers-alt fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue"> Submitted Licence Application 1s</span>
                            </button>
							
							
							<button class="fluent-big-button" onClick="loadmypage('renewal_applications_list.php?i=1','content','loader','listpages','','renewalapplications','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" style="width:80px">
                            	<span class="icon-layers-alt fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue"> Submitted Licence Renewal Applications</span>
                            </button>

							<?php } ?>

							<?php 
                    		$PageID=70; //Regional Officer Views
								$myRights=getrights($db,$UserID,$PageID);	
								// echo '<pre>';
								// print_r($UserID);
								// exit;					
								if ($myRights['View']==1){  
							?>

								<button class="fluent-big-button" onClick="loadmypage('tradefacilitationapplications.php?i=1','content','loader','listpages','','tradefacilitationapplications','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" style="width:80px">
									<span class="icon-layers-alt fg-darkBlue"></span>
									<span class="button-label fg-darkBlue">Trade Facilitation Applications</span>
								</button> 
							
							<?php } 	
							?>	
								
							<?php							
								$PageID=57;
								$myRights=getrights($db,$UserID,$PageID);						
								if ($myRights['View']==1){	?>	
								<button class="fluent-big-button" onClick="loadmypage('inspections_list.php?i=1','content','loader','listpages','','Inspections','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" style="width:80px">
									<span class="icon-layers-alt fg-darkBlue"></span>
									<span class="button-label fg-darkBlue">Inspections</span>
								</button>
                             <?php } ?>
                             	   							
						</div>                        
					</div>
                    <div class="tab-panel-group">
						<div class="tap-content-segment">
							<?php
								$PageID=31;
								$myRights=getrights($db,$UserID,$PageID);						
								if ($myRights['View']==1){  ?>

							<button class="fluent-big-button dropdown-toggle">
                                <span class="icon-folder fg-darkBlue"></span>
                                <span class="button-label">POS Invoices</span>
                            </button>

							<ul class="dropdown-menu" data-role="dropdown">  
								<li><a href="#" onClick="loadmypage('pos_invoices.php?i=1','content','loader','listpages','','invoices-b')">Detailed</a></li>
								<li><a href="#" onClick="loadmypage('pos_invoices_summary.php?i=1','content','loader','listpages','','invoices-c')">Summary</a></li>
								<li><a href="#" onClick="loadmypage('pos_invoices_revenuestream.php?i=1','content','loader','listpages','','invoices-e')">Per Revenue Stream</a></li>
								<li><a href="#" onClick="loadmypage('pos_invoices_parking.php?i=1','content','loader','listpages','','invoices-d')">Parking</a></li>
							</ul>
							<?php } ?>
						</div>
					</div>
					<div class="tab-panel-group">
                    	<div class="tab-group-content">
                    		<?php
								$PageID=2;
								$myRights=getrights($db,$UserID,$PageID);						
								if ($myRights['View']==1){  ?>
							<button class="fluent-big-button" onClick="loadmypage('licence-application-invoices.php?i=1','content','loader','listpages','','licence-application-invoices-a','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" style="width:80px">
                            	<span class="icon-libreoffice fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">Licence Application Invoices</span>
                            </button>
                         	<button class="fluent-big-button" onClick="loadmypage('invoices_list.php?i=1','content','loader','listpages','','invoices-a','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" style="width:80px">
                            	<span class="icon-libreoffice fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">Licence Renewal Invoices</span>
                            </button>
                       <?php }

                        	$PageID=25;
							$myRights=getrights($db,$UserID,$PageID);						
							if ($myRights['Add']==1){  ?>

                            <button class="fluent-big-button" onClick="loadmypage('receipt32.php?i=1','content','loader','listpages','','','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" style="width:80px">
                            	<span class="icon-libreoffice fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">Multiple Receipting</span>
                            </button> 

                            <?php } 

                            $PageID=32;
								$myRights=getrights($db,$UserID,$PageID);						
								if ($myRights['View']==1){  ?>							
							<button class="fluent-big-button" onClick="loadmypage('permits_list.php?i=1','content','loader','listpages','','Permits','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" style="width:80px">
                            	<span class="icon-file-openoffice fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">Permits</span>
                            </button>

                            <?PHP }

                            	$PageID=56;
								$myRights=getrights($db,$UserID,$PageID);						
								if ($myRights['View']==1){  ?>

                         	<!-- <button class="fluent-big-button" onClick="loadmypage('matatu_saccos_list.php?i=1','content','loader','listpages','','MatatuSaccos','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" style="width:80px">
                            	<span class="icon-file-openoffice fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">Bus Parks</span>
                            </button> -->

                            <?php } 
                            	$PageID=39;
								$myRights=getrights($db,$UserID,$PageID);						
								if ($myRights['View']==1){  ?>	

                         
                         	<button class="fluent-big-button" onClick="loadmypage('reporting.php?i=1','content','loader','listpages')" style="width:80px">
                            	<span class="icon-file-openoffice fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">Reports</span>
                            </button>

                            <?php } 

                            	$PageID=39;
								$myRights=getrights($db,$UserID,$PageID);						
								if ($myRights['View']==1){  ?>

                            <button class="fluent-big-button" onClick="loadmypage('GetReports.php?i=1','content','loader','listpages')" style="width:80px">
                            	<span class="icon-file-openoffice fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">SSRS Reports</span>
                            </button>
                            <?php }?>
                            <!-- <button class="fluent-big-button" onClick="loadmypage('reportslist.php?i=1','content','loader','listpages')" style="width:80px">
                            	<span class="icon-file-openoffice fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">SSRS Reports2</span>
                            </button> -->											
                        </div>
                        <div class="tab-group-caption fg-darkBlue">Invoices</div>
					</div>
					<?php 
						$PageID=40;
						$myRights=getrights($db,$UserID,$PageID);						
						if ($myRights['View']==1){  ?> 
					<div class="tab-panel-group">
						<div class="tap-content-segment">
							<button class="fluent-big-button dropdown-toggle">
                                <span class="icon-folder fg-darkBlue"></span>
                                <span class="button-label">Miscellaneous</span>
                            </button>
							<ul class="dropdown-menu" data-role="dropdown" > 
								<li><a href="#" onClick="loadmypage('miscellaneous_list.php?i=1','content','loader','listpages','','Miscellaneous','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')">List</a></li>
								<li><a href="#" onClick="loadmypage('miscellaneous_yf.php?i=1','content','loader','listpages','','','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')">Yellow Fever</a></li>
								<li><a href="#" onClick="loadmypage('miscellaneous_liq_applic.php?i=1','content','loader','listpages','','','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')">Liquor Application</a></li>
								<li><a href="#" onClick="loadmypage('miscellaneous_liq.php?i=1','content','loader','listpages','','','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')">Liquor Licence</a></li>
								<li><a href="#" onClick="loadmypage('miscellaneous.php?i=1','content','loader','listpages','','','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')">Others</a></li>
							</ul>
						</div>
					</div>

					<?php } ?>

					<button class="fluent-big-button" onClick="loadmypage('complaints_list.php?i=1','content','loader','listpages','','Complaints','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" style="width:60px">
                            	<span class="icon-file-openoffice fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">Complaints</span>
                    </button>

                    <button class="fluent-big-button" onClick="loadmypage('checkpermit.php?i=1','content','loader')" style="width:60px">
                            	<span class="icon-file-openoffice fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">Check Permit</span>
                    </button>

				</div>
<?php //------------------------------------------------------------------------------------------------------------------------------  ?>				
                <div class="tab-panel" id="tab_user_security">
                	<div class="tab-panel-group">
                    	<div class="tab-group-content">
                        <?php
							$PageID=5;

							$myRights=getrights($db,$UserID,$PageID);						
							if ($myRights['View']==1){
						?>
                         	<button class="fluent-big-button" onClick="loadmypage('users_list.php?i=1','content','loader','listpages','','users','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" style="width:60px">
                            	<span class="icon-user-2 fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">Users</span>
                            </button> 
                            <?php }
							$PageID=6;
							$myRights=getrights($db,$UserID,$PageID);						
							
							if ($myRights['View']==1){
						?>
							
							
                          	<button class="fluent-big-button" onClick="loadmypage('user_roles_list.php?i=1','content','loader','listpages','','UserRoles','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" style="width:60px">
                            	<span class="icon-user-2 fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">Users Roles</span>
                            </button> 
                            <?php } 
							$PageID=4;
							$myRights=getrights($db,$UserID,$PageID);						
							
							if ($myRights['View']==1){
						?>					
							                         
                            <button class="fluent-big-button" onClick="loadmypage('pages_list.php?i=1','content','loader','listpages','','Pages','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" style="width:60px">
                            	<span class="icon-clipboard fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">System Pages</span>
                            </button>
                            <?php } 
								$PageID=7;
								$myRights=getrights($db,$UserID,$PageID);						
								
								if ($myRights['View']==1){							
							?>
                         	<button class="fluent-big-button" onClick="loadmypage('role_centers_list.php?i=1','content','loader','listpages','','RoleCenters','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" style="width:60px">
                            	<span class="icon-copy fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">Role Centers</span>
                            </button>							
                            <?php }	
								$PageID=29;
								$myRights=getrights($db,$UserID,$PageID);						
								
								if ($myRights['View']==1){							
							?>
                         	<button class="fluent-big-button" onClick="loadmypage('active_users.php?i=1','content','loader','listpages','','ActiveUsers','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" style="width:60px">
                            	<span class="icon-copy fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">Active Users</span>
                            </button>
                            <?php }	
								$PageID=22;
								$myRights=getrights($db,$UserID,$PageID);						
								
								if ($myRights['View']==1){							
							?>
                         	<button class="fluent-big-button" onClick="loadmypage('menu_groups_list.php?i=1','content','loader','listpages','','MenuGroups','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" style="width:60px">
                            	<span class="icon-copy fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">Menu Groups</span>
                            </button>
                            <button class="fluent-big-button" onClick="loadmypage('logs_list.php?i=1','content','loader','listpages','','UserLogs','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" style="width:60px">
                            	<span class="icon-copy fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">Transaction Logs</span>
                            </button>                            
                            <?php } ?>                 
						</div>
                        <div class="tab-group-caption fg-darkBlue">Users and Security</div>
					</div>
					<div class="tab-panel-group">
						<div class="tap-content-segment">
							<?php
								$PageID=31;
								$myRights=getrights($db,$UserID,$PageID);						
								if ($myRights['View']==1){  ?>
									<button class="fluent-big-button dropdown-toggle">
		                                <span class="icon-user fg-darkBlue"></span>
		                                <span class="button-label">Clerk/Approver Setup</span>
		                            </button>

									<ul class="dropdown-menu" data-role="dropdown">  
										<li><a href="#" onClick="loadmypage('ApproverSetup.php?i=1','content','loader','listpages','','ApproversList')">Licencing Officers</a></li>
										<li><a href="#" onClick="loadmypage('clerk_wards_list.php?i=1','content','loader','listpages','','ClerkWards')">Licencing Clerks</a></li>								
									</ul>
							<?php } ?>
						</div>
					</div>                                                               
				</div>

<?php   ?>				
				<div class="tab-panel" id="tab_setup">
					<div class="tab-panel-group">
                    	<div class="tap-content-segment">
	                    	<?php
								$PageID=8;
								$myRights=getrights($db,$UserID,$PageID);						
								if ($myRights['View']==1){
							?>
                            <button class="fluent-big-button dropdown-toggle">
                                <span class="icon-folder fg-darkBlue"></span>
                                <span class="button-label">Service Details</span>
                            </button> 
                            <?php }else{
                            	} ?>  
                            <ul class="dropdown-menu" data-role="dropdown" style="display: block;">                           
                            <?php 
								$PageID=8;
								$myRights=getrights($db,$UserID,$PageID);						
									
								if ($myRights['View']==1){
									
							?>  
								<li><a href="#" onClick="loadmypage('services_list.php?i=1','content','loader','listpages','','services','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')">Services List</a></li>
                            <?php } 
								$PageID=9;
								$myRights=getrights($db,$UserID,$PageID);						
								
								if ($myRights['View']==1){							
							?>
                         		<li><a href="#" onClick="loadmypage('forms_list.php?i=1','content','loader','listpages','','Forms')">Service Forms</a></li>
                            <?php } 
								$PageID=10;
								$myRights=getrights($db,$UserID,$PageID);
								
								if ($myRights['View']==1){							
							?>
                            	<li><a href="#" onClick="loadmypage('marketservice_list.php?i=1','content','loader','listpages','','marketservices')">Market Services</a></li>
                            <?php } 
								$PageID=11;
								$myRights=getrights($db,$UserID,$PageID);					
								
								if ($myRights['View']==1){							
							?>
                            	<li><a href="#" onClick="loadmypage('service_status_list.php?i=1','content','loader','listpages','','ServiceStatus')">Service Approval Status</a></li>  
                            </ul>                  	

							<?php } ?>								
									                        								
                        </div>   
                         
                    </div>

                    <?php 
                    $PageID=12;
					$myRights=getrights($db,$UserID,$PageID);					
					
					if ($myRights['View']==1){ ?>

                    <!-- <div class="tab-panel-group">
                    	<button class="fluent-big-button" onClick="loadmypage('departments_list.php?i=1','content','loader','listpages','','Departments')">
                            	<span class="icon-grid-view fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">Departments</span>
                        </button>
                    </div> -->

                    <?php }
                    $PageID=57;
					$myRights=getrights($db,$UserID,$PageID);					
					
					if ($myRights['View']==1){ ?>

                    <div class="tab-panel-group">
                    	<button class="fluent-big-button" onClick="loadmypage('banks_list.php?i=1','content','loader','listpages','','Banks')">
                            	<span class="icon-grid-view fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">Banks</span>
                        </button>
                    </div>

                    <?php }
                    $PageID=58;
					$myRights=getrights($db,$UserID,$PageID);					
					
					if ($myRights['View']==1){ ?>

                    <div class="tab-panel-group">
                    	<div class="tab-group-content">
                    		<button class="fluent-big-button dropdown-toggle" onClick="loadmypage('revenue_stream_list.php?i=1','content','loader','listpages','','RevenueStreams','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')">
                            	<span class="icon-grid fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">Revenue Streams</span>
                            </button>
                    	</div>
                    </div>

                    <?php }
                    $PageID=59;
					$myRights=getrights($db,$UserID,$PageID);					
					
					if ($myRights['View']==1){ ?>

                    <div class="tab-panel-group">
                    	<div class="tab-group-content">
                    		<button class="fluent-big-button" onClick="loadmypage('servicegroup_list.php?i=1','content','loader','listpages','','ServiceGroups','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')">
                            	<span class="icon-grid fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">Service Groups</span>
                            </button>
                    	</div>
                    </div>

                    <?php }
                    $PageID=60;
					$myRights=getrights($db,$UserID,$PageID);					
					
					if ($myRights['View']==1){ ?>

					<div class="tab-panel-group">
                    	<div class="tab-group-content">
                    		<button class="fluent-big-button" onClick="loadmypage('county_list.php?i=1','content','loader','listpages','','Counties')">
                            	<span class="icon-grid fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">TRA Regions Set Up</span>
                            </button>
                    	</div>
                    </div>

                    <!-- <div class="tab-panel-group">
                    	<div class="tab-group-content">
                    		<button class="fluent-big-button" onClick="loadmypage('waiverperiods_list.php?i=1','content','loader','listpages','','WaiverPeriods')">
                            	<span class="icon-grid fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">TRA Regions Set Up</span>
                            </button>
                    	</div>
                    </div> -->

                    <?php }
                    $PageID=61;
					$myRights=getrights($db,$UserID,$PageID);					
					
					if ($myRights['View']==1){ ?>

                    <div class="tab-panel-group">
                    	<div class="tab-group-content">
                    		<button class="fluent-big-button" onClick="loadmypage('financialyear_list.php?i=1','content','loader','listpages','','FinancialYear')" style="width:60px">
                            	<span class="icon-copy fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">Financial Years</span>
                            </button>
                    	</div>
                    </div>

                    <?php }
                    $PageID=62;
					$myRights=getrights($db,$UserID,$PageID);					
					
					if ($myRights['View']==1){ ?>

                    <div class="tab-panel-group">
                    	<div class="tab-group-content">
                         	<button class="fluent-big-button" onClick="loadmypage('servicecategory_list.php?i=1','content','loader','listpages','','ServiceCategories')" style="width:80px">
                            	<span class="icon-layers-alt fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">Service Categories</span>
                            </button>     							
						</div> 					
					</div>								

					<?php }
                    $PageID=63;
					$myRights=getrights($db,$UserID,$PageID);					
					
					if ($myRights['View']==1){ ?>
<!-- 
					<div class="tab-panel-group">
                    	<div class="tab-group-content">
                         	<button class="fluent-big-button" onClick="loadmypage('countyDetails.php?i=1','content')" style="width:80px">
                            	<span class="icon-layers-alt fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">Organization Details</span>
                            </button>     							
						</div>                        
					</div> -->

					<div class="tab-panel-group">
                    	<div class="tab-group-content">
                         	<button class="fluent-big-button" onClick="loadmypage('licence_renewal_form.php?i=1','content','loader','listpages','','LicenceRenewalForm')" style="width:80px">
                            	<span class="icon-layers-alt fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">Licence Renewal Form</span>
                            </button>     							
						</div>                        
					</div>

					<div class="tab-panel-group">
                    	<div class="tab-group-content">
                         	<button class="fluent-big-button" onClick="loadmypage('Business_Documents.php?i=1','content','loader','listpages','','BusinessDocuments')" style="width:80px">
                            	<span class="icon-folder fg-darkBlue fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">Business Registration Documents Set Up</span>
                            </button>     							
						</div>                        
					</div>

					<?php } ?> 
						
                   

                    <div class="tab-panel-group">                     	
                        <div class="tab-content-segment">
                            <button class="fluent-big-button dropdown-toggle">
                                <span class="icon-folder fg-darkBlue"></span>
                                <span class="button-label">Checklists</span>
                            </button>
                            <ul class="dropdown-menu" data-role="dropdown" style="display: block;">
                              <?php  
                                    $PageID=15;
                                    $myRights=getrights($db,$UserID,$PageID);						
                                    
                                    if ($myRights['View']==1){							
                                ?> 
                                <li><a href="#" onClick="loadmypage('checklisttype_list.php?i=1','content','loader','listpages','','ChecklistTypes')">Checklist Types</a></li>

                                <li><a href="#" onClick="loadmypage('checklistparametercategory_list.php?i=1','content','loader','listpages','','ChecklistParameterCategories')">Checklist Parameter Categories</a></li>

                                <li><a href="#" onClick="loadmypage('checklistparameters_list.php?i=1','content','loader','listpages','','ChecklistParameters')">Checklist Parameters</a></li>                               
								<?php }?>
                            </ul>
                        </div>                       
                        <div class="tab-group-caption fg-darkBlue">County Zoning</div> 
                   </div>
                </div>
               
				<div class="tab-panel" id="tab_support">
					<div class="tab-panel-group">
                    	<div class="tab-group-content">
                         	<button class="fluent-big-button" onClick="loadpage('userprofile.php?i=1','content')">
                            	<span class="icon-user fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">My Profile</span>
                            </button>
							<button class="fluent-big-button" onClick="loadpage('changepassword.php?i=1','content')">
                            	<span class="icon-cog fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">Change Password</span>
                            </button>                           
						</div>
                        <div class="tab-group-caption fg-darkBlue">Profile</div>
                        </div>
					<div class="tab-panel-group">
                    	<div class="tab-group-content">						
                            <button class="fluent-big-button" onClick="loadpage('aboutus.php?i=1','content')" style="width:70px">
                            	<span class="icon-accessibility fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">About Us</span>
                            </button>
							<button class="fluent-big-button" onClick="loadpage('help.php?1=1','content')" style="width:70px">
                            	<span class="icon-help-2 fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">Help</span>
                            </button>     
                            </div>                                               
                        <div class="tab-group-caption fg-darkBlue">Assistance</div>                       
					</div>

                </div>	

               
                <div class="tab-panel" id="tab_finance">
                	<div class="tab-panel-group">
                    	<div class="tab-group-content">
                        <?php  
								$PageID=25;
								$myRights=getrights($db,$UserID,$PageID);						
								
								if ($myRights['View']==1){							
							?>
                         	<button class="fluent-big-button" onClick="loadmypage('receipts_list.php?i=1','content','loader','listpages','','receipts','')" style="width:80px">
                            	<span class="icon-layers fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">Receipts</span>
                            </button>
                            <?php }
                            	$PageID=51;
								$myRights=getrights($db,$UserID,$PageID);						
								
								if ($myRights['View']==1){							
							?>							
                         	<button class="fluent-big-button" onClick="loadmypage('mpesa_list.php?i=1','content','loader','listpages','','Mpesa','')" style="width:80px">
                            	<span class="icon-layers fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">MPESA</span>
                            </button> 	
							<?php }
                            	$PageID=52;
								$myRights=getrights($db,$UserID,$PageID);						
								
								if ($myRights['View']==1){							
							?>
							<button class="fluent-big-button" onClick="loadmypage('GetReports.php?i=1','content','loader','listpages')" style="width:80px">
                            	<span class="icon-file-openoffice fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">Reports</span>
                            </button>							
                            
                            <?php } 
								$PageID=26;
								$myRights=getrights($db,$UserID,$PageID);						
								
								if ($myRights['View']==1){							
							?>                       
                         	<button class="fluent-big-button" onClick="loadmypage('requisition_list.php?i=1','content','loader','listpages','','requisitions')" style="width:80px">
                            	<span class=" icon-printer fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">Requisitions</span>
                            </button> 
                            <?php } 
								$PageID=55;
								$myRights=getrights($db,$UserID,$PageID);						
								
								if ($myRights['View']==1){							
							?>  
                            <button class="fluent-big-button" onClick="loadmypage('approval_requests.php?i=1','content','loader','listpages','','ApprovalRequests','<?php echo $_SESSION['RoleCenter'].':'.$_SESSION['UserID']; ?>')" style="width:80px">
                            	<span class=" icon-printer fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">Approval Requests</span>
                            </button>
                            
                           <?php } 
							   $PageID=27;
									$myRights=getrights($db,$UserID,$PageID);						
									
									if ($myRights['View']==1){							
								?>                       
								<button class="fluent-big-button" onClick="loadmypage('gl_accounts_list.php?i=1','content','loader','listpages','','GLAccounts')" style="width:80px">
									<span class=" icon-printer fg-darkBlue"></span>
									<span class="button-label fg-darkBlue">GL Accounts</span>
								</button> 
						   
						   
						   <?php }
								$PageID=48;

								$myRights=getrights($db,$UserID,$PageID);
								if ($myRights['View']==1){}	
										
						   ?>                                                                                                  
						</div> 
                        <div class="tab-group-caption fg-darkBlue">Pending Approvals</div>
					</div>                                                               
				</div>
									
			</div>
			
		</div>
