<?php 
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
$UserGroupID = $_REQUEST['UserGroupID'];
$UserGroupName = $_REQUEST['UserGroupName'];
$update = 0;
$msg = '';
$vstr1 = '';
if (isset($_REQUEST['update'])) { $update = $_REQUEST['update']; }
function getObjects($db)
{
	$sql = "SELECT * FROM UserRightsForms";
	$result = sqlsrv_query($db, $sql);	
	$ObjectArray=array();
  	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
  	{
  		$UserRightsFormID = $myrow['UserRightsFormID'];
		$ObjectArray[] = $UserRightsFormID;
	}
	
	return $ObjectArray;
}

function getObjectsCount($db)
{
	$sql = "SELECT Count(UserRightsFormID) AS Count FROM UserRightsForms";
	$result = sqlsrv_query($db, $sql);
	$Count= 0;
	if ($result)
	{
		while ($myrow = sqlsrv_fetch_array($result)) 
		{
			$Count = $myrow['Count'];
		}
	}
	//echo"the $Count";
	return $Count;
}

if (isset($_REQUEST['update']))
{
	$ObjectArray = getObjects($db);
	$ObjectCount = getObjectsCount($db);
	$UserGroupID = $_REQUEST['UserGroupID'];
	$sql = "Update UserRights set UserRightsView = 0, UserRightsEdit = 0 , UserRightsDelete = 0 WHERE UserGroupID = '$UserGroupID'";
	$i_result = sqlsrv_query($db, $sql);
	print_r($_REQUEST);
	for($i = 0; $i < $ObjectCount; $i++)
	{ 	
		$Create = 0;
		$Delete = 0;
		$Edit = 0;
		$View = 0;
		$ObjectID = 0;
		if (isset($_REQUEST["view$i"])) 
		{ 
			$ViewValue 	= $_REQUEST["view$i"]; 
			$ValueArray = explode('_',$ViewValue);
			$ObjectID 	= $ValueArray[0];
			$RightID 	= getUserGroupRightID($db,$ObjectID,$UserGroupID);
			$View			= 1;
			echo"View : $ObjectID --> $RightID <br>";
		} 
		if (isset($_REQUEST["edit$i"]))
		{ 
			$EditValue = $_REQUEST["edit$i"];
			$ValueArray = explode('_',$EditValue);
			$ObjectID 	= $ValueArray[0];
			$RightID 	= getUserGroupRightID($db,$ObjectID,$UserGroupID);
			$Edit			= 1;
			//echo"Edit : $ObjectID --> $RightID <br>";
		} 
		if (isset($_REQUEST["delete$i"]))
		{
			$DeleteValue = $_REQUEST["delete$i"];
			$ValueArray = explode('_',$DeleteValue);
			$ObjectID 	= $ValueArray[0];
			$RightID 	= getUserGroupRightID($db,$ObjectID,$UserGroupID);
			$Delete		= 1;
			//echo"Delete : $ObjectID --> $RightID <br>";
		}
		if ($ObjectID!=0)
		{
			
			if ($RightID !='')
			{		
				$sql = "UPDATE UserRights SET 
							   UserRightsView = '$View'
							  ,UserRightsEdit = '$Edit' 
							  ,UserRightsDelete = '$Delete'
							  ,LastUpdatedTime = '$LastUpdatedTime'
							  ,UserID = '$UserID'
							WHERE UserRightsID = '$RightID' AND UserGroupID = '$UserGroupID'";
				$result = sqlsrv_query($db, $sql);				
			} 		
			else
			{
				$sql = "INSERT INTO UserRights 
							(
								 UserRightsFormID
								,UserRightsView
								,UserRightsEdit
								,UserRightsDelete
								,ProfileID
								,LastUpdatedTime
								,UserID)
							VALUES 
							(
								 '$UserRightsFormID'
								,'$View'
								,'$Edit'
								,'$Delete'
								,'$ProfileID'
								,'$LastUpdatedTime'
								,'$UserID') SELECT SCOPE_IDENTITY() AS ID";
				$result = sqlsrv_query($db, $sql);
				$rid = lastId($result);
			}
			
		} 	
	}
	
	// clear all rights 		
	//$msg = "Update Sucessfull";
}

$sql = "Select UserRightsForms.*,UserRights.UserRightsID,UserRights.UserRightsView, 
		UserRights.UserRightsEdit,UserRights.UserRightsDelete 
		FROM UserRightsForms
		LEFT JOIN UserRights ON UserRights.UserRightsFormID = UserRightsForms.UserRightsFormID
		AND UserRights.UserGroupID = $UserGroupID
		WHERE UserRightsForms.UserRightsFormOption <> 0
		ORDER by UserRightsForms.UserRightsFormCategory, UserRightsForms.UserRightsFormName	
		";

$result = sqlsrv_query($db, $sql);
?>

<link href="css/style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="script.js"></script>

<form action="" method="post" name="deleteform" id="deletform">
<div>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="0" class="main_table">
  
  <tr class="table_text">
    <td colspan="4"><div align="center" class="error_text"><?php echo $msg; ?></div></td>
    </tr>
  <tr>
    <th colspan="4" align="left" class="table_text">GROUP NAME: <?php echo $UserGroupName; ?></th>
    </tr>
   <tr>
    <th colspan="4" align="right">
	<input type="button" class="button" value="Save" 
		onClick="loadpage('users_rights.php?update=1'+
					'&UserGroupID=<?php echo $UserGroupID; ?>'+
					'&UserGroupName=<?php echo $UserGroupName;?>'+
					'&CustomerID=<?php echo $CustomerID; ?>'+
					'&UserID=<?php echo $UserID?>'+
					'&ProfileID=<?php echo $ProfileID;?>'+
					'&ObjectCount=<?php echo $ObjectCount;?>' + deleteform.dest.value,'content','progressbar')"/>
  <input name="close" type="button" class="button" id="close" value="Close" 
		onclick="loadpage('usergroups_list.php?i=1','content','User Groups')"/>
         
         </th>
  </tr>
  <tr class="ui-state-highlight">
    <th colspan="4" align="right">
Select:[<a href="#" onclick="rights_selectall(true,deleteform.dest)"> All</a>, <a href="#" onclick="rights_selectall(false,deleteform.dest)">None</a>]
    </th>
    </tr>
  <tr class="table_header">
    <th width="697" align="left"> Form</th>
    <th width="98"><div align="center">View<br />
    </div></th>
    <th width="93"><div align="center">Edit</div></th>
    <th width="76"><div align="center">Delete</div></th>
    </tr>
  <?php 
  $i=1;
  $dest = "";
  $cat = "";
  $i = 0;
  $r = 1;
  while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
  {
     if (($i % 2) == 0)
	 {
		$class = "table_rows_even";
	 } else
	 {
		$class = "table_rows_odd";
	 }
	  	   
  	 $vstr="";
	 $estr="";
	 $dstr="";
	 
  	$fid = $myrow['UserRightsFormID'];
	$rid = $myrow['UserRightsID'];
	$sid = $fid."_".$rid;
	$fname = $myrow['UserRightsFormName'];
	$view = $myrow['UserRightsView'];
	$edit = $myrow['UserRightsEdit'];
	$delete = $myrow['UserRightsDelete'];
	$category = $myrow['UserRightsFormCategory'];
	$option = $myrow['UserRightsFormOption'];
	if ($view==1){
	 	$vstr= 'checked="checked"';
		$dest = "$dest&view$i=$fid"."_"."$rid";
	} else {
		$dest = "$dest&view$i=$fid"."_"."$rid";	
	}
	if ($edit==1){
	 	$estr= 'checked="checked"';
		$dest = "$dest&edit$i=$fid"."_"."$rid";
	} else {
		$dest = "$dest&edit$i=$fid"."_"."$rid";
	}
	if ($delete==1)	{
	 	$dstr= 'checked="checked"';
		$dest = "$dest&delete$i=$fid"."_"."$rid";
	} else {
		$dest = "$dest&edit$i=$fid"."_"."$rid";
	}

	if ($UserRightsFormCategory != $cat)
	{ ?>
  		<tr class="currentvalues">
    	<th colspan="4"><div align="left"><?php echo $UserRightsFormCategory; ?></div></th>
    	</tr>
    	<?php
		$cat = $UserRightsFormCategory;				
    } 

	 $i=$i+1;
	 $option2str = "";
	 $option3str = "";
	 $option2disable = 0;
	 $option3disable = 0;	 
	 if ($option<2)
	 { 
		$option2str = 'style="visibility:hidden"';
		$option2disable = 1;
	 }
	 if ($option<3)
	 { 
		$option3str = 'style="visibility:hidden"';
		$option3disable = 1;
	 }
		 if (($r % 2) == 0)
		 {
			$class = 'table_text_even';
		 } else
		 {		
			$class = 'table_text_odd';
		 }
		 $r=$r+1; 
		?>   
   <tr class="<?php echo $class; ?>">
    <td align="left"><?php echo $fname;?></td>
    <td align="center">
		<div align="center">
			<input name="checkbox_name" type="checkbox" onchange="rights_array(deleteform.dest)" value="<?php echo $sid;?>" <?php echo $vstr; ?> />
		</div>
	</td>
    <td align="center">
		<div align="center">
		  <input type="checkbox" name="checkbox_name1" value="<?php echo $sid;?>" onchange="rights_array(deleteform.dest)" <?php echo $estr; ?> <?php echo $option2str; ?>/>
		</div>
	</td>
    <td align="center">	
		<div align="center">
			<input type="checkbox" name="checkbox_name2" value="<?php echo $sid;?>" onchange="rights_array(deleteform.dest)" <?php echo $dstr; ?> <?php echo $option3str; ?>/>
		</div> 
	</td>
    </tr>
  <?php    
  }
  ?>
  <tr class="ui-state-highlight">
    <th colspan="4" align="right">
	<input name="dest" type="hidden" id="dest" value="<?php echo $dest;?>" />
    Select:[<a href="#" onclick="rights_selectall(true,deleteform.dest)"> All</a>, <a href="#" onclick="rights_selectall(false,deleteform.dest)">None</a>]
    </th>
    </tr>
   <tr>
    <th colspan="4" align="right">
	<input type="button" class="button" value="Save" 
		onClick="loadpage('users_rights.php?update=1'+
					'&UserGroupID=<?php echo $UserGroupID; ?>'+
					'&UserGroupName=<?php echo $UserGroupName;?>'+
					'&CustomerID=<?php echo $CustomerID; ?>'+
					'&UserID=<?php echo $UserID?>'+
					'&ProfileID=<?php echo $ProfileID;?>'+
					'&ObjectCount=<?php echo $ObjectCount;?>' + deleteform.dest.value,'content','progressbar')"/>
      <input name="close2" type="button" class="button" id="close2" value="Close" 
		onclick="loadpage('usergroups_list.php?i=1','content','User Groups')"/></th>
  </tr>
</table>
</div>
</form>