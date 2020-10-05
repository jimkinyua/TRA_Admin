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
$RoleCenterName=$_REQUEST['RoleCenterName'];
$RoleCenterID=$_REQUEST['RoleCenterID'];
$PageName=$_REQUEST['PageName'];
$PageID=$_REQUEST['PageID'];


$sql="select RolecenterName from RoleCenters where RolecenterID=$RoleCenterID";

$result=sqlsrv_query($db,$sql);
if($result){
    while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
    {
            $RoleCenterName=$rw['RolecenterName'];
    }
}

$sql="select PageName from Pages where PageID=$PageID";

$result=sqlsrv_query($db,$sql);
if($result){
    while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
    {
            $PageName=$rw['PageName'];
    }
}

if (isset($_REQUEST['update']))
{

	$RoleCenterID=$_REQUEST['RoleCenterID'];
	$PageID=$_REQUEST['PageID'];
	$View=$_REQUEST['view']=='true'?1:0;
	$Edit=$_REQUEST['edit']=='true'?1:0;
	$Add=$_REQUEST['add']=='true'?1:0;
	$Delete=$_REQUEST['delete']=='true'?1:0;

	$sql="if exists(select 1 from roles where RoleCenterID=$RoleCenterID and PageID=$PageID)
	update Roles set [View]=$View, [Edit]=$Edit, [Add]=$Add, [Delete]=$Delete where RoleCenterID=$RoleCenterID and PageID=$PageID else
	Insert into Roles(RolecenterID,PageID,[View],[Edit],[Add],[Delete]) values($RoleCenterID,$PageID,$View,$Edit,$Add,$Delete)
	";

	
	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{

		$rst=SaveTransaction($db,$CreatedUserID,"Created/Updated Role Center Roles for [".$RoleCenterName."] on [".$PageName."] Page");

		$msg = "Role Updated Successfully";			
	} else
	{
		DisplayErrors();
		$msg = "Action failed";			
	}
	
}else if (isset($_REQUEST['save']))
{
	$RoleID=$_REQUEST['RoleID'];
	$PageID='';
	$RoleID='';
	$Column='';
	//$RoleCenterID=1;

	$input=array_slice($_REQUEST,3,count($input)-1);
	
	$sql="update roles set [View]=0,[Edit]=0,[Add]=0,[Delete]=0 Where RoleCenterID=$RoleCenterID";

	/* print_r($_REQUEST);
	exit; */
	
	$result=sqlsrv_query($db,$sql);
	if(!$result){
		DisplayErrors();
		break;
	}

	foreach ($input AS $key => $value)
	{
		//print_r ($input);
		if($value=='true'){	
		
			//echo 'Key: '. $key.'<br>';		
			$str=explode('_',$key);
			$Column=$str[0];
			$PageID=$str[1];
			$RoleID=$str[2];

			if (!$RoleID==''){
				if ($Column=='V'){
					$sql="Update Roles set [View]=1 where RoleID=$RoleID";
				}else if($Column=='E'){
					$sql="Update Roles set [Edit]=1 where RoleID=$RoleID";
				}else if($Column=='A'){
					$sql="Update Roles set [Add]=1 where RoleID=$RoleID";
				}else if($Column=='D'){
					$sql="Update Roles set [Delete]=1 where RoleID=$RoleID";
				}
			}else{
				if ($Column='V'){
					$sql="if exists(select 1 from Roles where PageID=$PageID and RoleCenterID=$RoleCenterID)
					 	  Update Roles set [View]=1 where PageID=$PageID and RoleCenterID=$RoleCenterID ELSE
						  Insert into Roles (RoleCenterID,PageID,[View],CreatedBy) Values ($RoleCenterID,$PageID,1,$CreatedUserID)";
				}else if($Column=='E'){
					$sql="if exists(select 1 from Roles where PageID=$PageID and RoleCenterID=$RoleCenterID)
					 	  Update Roles set [Edit]=1 where PageID=$PageID and RoleCenterID=$RoleCenterID ELSE
						  Insert into Roles (RoleCenterID,PageID,[Edit],CreatedBy) Values ($RoleCenterID,$PageID,1,$CreatedUserID)";
				}else if($Column=='A'){
					$sql="if exists(select 1 from Roles where PageID=$PageID and RoleCenterID=$RoleCenterID)
					 	  Update Roles set [Add]=1 where PageID=$PageID and RoleCenterID=$RoleCenterID ELSE
						  Insert into Roles (RoleCenterID,PageID,[Add],CreatedBy) Values ($RoleCenterID,$PageID,1,$CreatedUserID)";
				}else if($Column=='D'){
					$sql="if exists(select 1 from Roles where PageID=$PageID and RoleCenterID=$RoleCenterID)
					 	  Update Roles set [Delete]=1 where PageID=$PageID and RoleCenterID=$RoleCenterID ELSE
						  Insert into Roles (RoleCenterID,PageID,[Delete],CreatedBy) Values ($RoleCenterID,$PageID,1,$CreatedUserID)";
				}				
			}
			$result=sqlsrv_query($db,$sql);
			if(!$result){
				DisplayErrors();
				break;
			}
			
		}else{
			
		}	

	}	
	
	if ($RoleID=='0')
	{
		$sql="";

	} else
	{
		$sql="";
	}	
	$result = sqlsrv_query($db, $sql);
	
	if ($result)
	{	
		$msg = "Roles Saved Successfully";			
	} else
	{
		//DisplayErrors();
		$msg = "Details Failed to save";
		//redirect($_REQUEST, $msg, "markets.php?MarketID=$MarketID");			
	}	
}
	
?>

        <div class="example">
        <form action="" method="get" name="MyForm">
        <legend>Role Center Roles For <?php echo $RoleCenterName; ?></legend>        
            <table class="table striped hovered dataTable" id="dataTables-1">
                <thead>
                  <tr>
                    <th class="text-left"><a href="#" onClick="loadmypage('role_center.php?i=1','content')">Add</a></th>
                    <th colspan="4" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                    <!-- <th class="text-left"><input name="btnAll" type="button" onClick="check_all()" value="Check All"></th> -->
                    <th class="text-left"><input name="chkAll" type="checkbox" onClick="check_all()" >Check All</input></th> 
                  </tr>
                <tr>
                    <th width="20%" class="text-left">PageName</th>
					<th width="20%" class="text-left">Menu Group</th>
                    <th width="10%" class="text-left">View</th>
                    <th width="10%" class="text-left">Edit</th>
                    <th width="10%" class="text-left">Add</th>
                    <th width="10%" class="text-left">Delete</th>
                    <th width="20%" class="text-left"></th>
                </tr>
                </thead>

                <tbody>
                </tbody>
                <tfoot>
                	<th class="text-left" colspan="5">&nbsp;</th>
                    <!-- <th class="text-left"><input name="Button" type="button" onClick="save_roles('role_center_roles.php?save=1&RoleCenterName=<?php echo $RoleCenterName; ?>&RoleCenterID=<?php echo $RoleCenterID; ?>','content','loader','listpages','','RoleCenterRoles',<?php echo $RoleCenterID; ?>)" value="Save"></th> -->
                </tfoot>
            </table>
            </form>
            </div>
</div>