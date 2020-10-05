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

$myExcemptions;

$sql="select distinct MenuGroupID from menugroups where menugroupid not in (
	select  distinct p.MenuGroupID from UserRoles ur
	inner join Roles r on ur.RoleCenterID=r.RoleCenterID
	inner join Pages p  on r.PageID=p.PageID
	where ur.UserID=$CreatedUserID and MenuGroupID is not null)";
	
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


$PageID=2;
$myRights=getrights($db,$UserID,$PageID);
if ($myRights)
{
	$View=$myRights['View'];
	$Edit=$myRights['Edit'];
	$Add=$myRights['Add'];
	$Delete=$myRights['Delete'];
}

?>
        <div class="fluent-menu" data-role="fluentmenu">
        	<ul class="tabs-holder">
            	<li class="special"><a href="#" onClick="loadmypage('profilehome.php?ContactTypeID=1','content')">Home</a></li>
                <?php 
					if (!in_array('1',$myExcemptions))
					{ ?>
                <li class="active"><a href="#tab_manage">Manage</a></li>                    
                    <?php
					}										
				 ?>                
           </ul>
           <div class="tabs-content">
                <div class="tab-panel" id="tab_manage">                	
                    <div class="tab-panel-group">
                    	<div class="tab-group-content">
                         	
                         	<button class="fluent-big-button" onClick="loadmypage2('dashboard.php?i=1','content','loader','listpages','','TestTable')" style="width:80px">
                            	<span class="icon-file-openoffice fg-darkBlue"></span>
                                <span class="button-label fg-darkBlue">Dash Board</span>
                            </button>
                         								
                        </div>
                        <div class="tab-group-caption fg-darkBlue">Invoices</div>
					</div>                                             
				</div>
                
			</div>
		</div>
