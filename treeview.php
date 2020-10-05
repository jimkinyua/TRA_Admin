<?php
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';


$IsService='';
$Description='';
$ParentID='';
$ServiceID='';
$ServiceTreeID='0';

$CreatedUserID = $_SESSION['UserID'];

if (isset($_REQUEST['delete']))
{
	$ServiceTreeID = $_REQUEST['ServiceTreeID'];
	$sql = "DELETE FROM ServiceTrees WHERE ServiceTreeID = '$ServiceTreeID'";
	//echo $sql;
	$result = sqlsrv_query($db, $sql);
	if ($result)
	{
		$msg = "Record Deleted Successfully";
	} else
	{
		$msg = "Record Failed to be Deleted";
	}
}

if (isset($_REQUEST['save']))
{
/*	print_r($_REQUEST);
	exit;*/	
	$Description=$_REQUEST['Description'];
	$ParentID=$_REQUEST['ParentID'];
	$IsService=$_REQUEST['IsService'];
	$ServiceID=$_REQUEST['ServiceID'];
	if (isset($_REQUEST['ServiceTreeID'])){$ServiceTreeID=$_REQUEST['ServiceTreeID'];}
	if ($ServiceTreeID=='0')
	{		
		$sql = "INSERT INTO ServiceTrees (
			  [Description]
			  ,[ParentID]
			  ,[IsService]
			  ,[ServiceID]
			  ,CreatedBy
			) VALUES 
			(
			'$Description'
			,'$ParentID'
			,'$IsService'
			,'$ServiceID'
			,'$CreatedUserID'
			) SELECT SCOPE_IDENTITY() AS ID
			" ;

	} else
	{
		$sql = "UPDATE ServiceTrees SET
					[Description]='$Description'
					,[ParentID]='$ParentID'
					,[IsService]='$IsService'
					,[ServiceID]='$ServiceID'					
					,[CreatedBy]='$CreatedUserID'
					 where ServiceTreeID='$ServiceTreeID'";		
	}	 
	$result = sqlsrv_query($db, $sql);
	
	if(!$result){
		DisplayErrors();
		echo "<BR>";
		echo $sql;
		//redirect($_REQUEST, $msg, "service_trees.php");	
	}else
	{
		$msg = "Service Tree Saved Successfully";					
	}	
}


?>
<div class="example">
<legend>POS Service Hierachy</legend>
<a href="#" onClick="loadpage('service_tree.php?add=1','content')">New ServiceTree</a>
<br><br>	


	<?php 
		$sql="select * from ServiceTrees where ParentID not in (select ServiceTreeID from ServiceTrees)";
		$result0=sqlsrv_query($db,$sql);		
		if ($result0)
		{	?>
		<ul class="treeview" data-role="treeview">
			<?php 
			while ($level0 = sqlsrv_fetch_array( $result0, SQLSRV_FETCH_ASSOC))
			{	
			?>	
			<li class="node collapsed">				
				<a href="#" onClick="loadpage('service_tree.php?edit=1&ServiceTreeID=<?php echo $level0['ServiceTreeID']; ?>','content')"><span class="node-toggle"></span><?php echo $level0['Description'] ?></a>
				<ul>
				<?php 
					$ParentID=$level0['ServiceTreeID'];
					$sql="select * from ServiceTrees where ParentID=$ParentID";					
					$result=sqlsrv_query($db,$sql);		
					if ($result)
					{
						while ($level1 = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC))
						{
							if($level1['IsService']==true)
							{
								$toggle='';
							}else
							{
								$toggle='<span class="node-toggle"></span>';
							}							
							?>
								<li class="node collapsed">
									<a href="#" onClick="loadpage('service_tree.php?edit=1&ServiceTreeID=<?php echo $level1['ServiceTreeID']; ?>','content')"><?php echo $toggle.$level1['Description'] ?></a>
									<ul>
									
									<?php 
									$ParentID=$level1['ServiceTreeID'];
									$sql="select * from ServiceTrees where ParentID=$ParentID";	
									
									$result2=sqlsrv_query($db,$sql);		
									if ($result2)
									{
										while ($level2 = sqlsrv_fetch_array( $result2, SQLSRV_FETCH_ASSOC))
										{ 
											if($level2['IsService']==true)
											{
												$toggle='';
											}else
											{
												$toggle='<span class="node-toggle"></span>';
											}
									?>
										<li class="node collapsed">
											<a href="#" onClick="loadpage('service_tree.php?edit=1&ServiceTreeID=<?php echo $level2['ServiceTreeID']; ?>','content')"><?php echo $toggle.$level2['Description'] ?></a>
											<ul>
											
											<?php 
												$ParentID=$level2['ServiceTreeID'];
												$sql="select * from ServiceTrees where ParentID=$ParentID";	
												
												$result3=sqlsrv_query($db,$sql);		
												if ($result3)
												{
													while ($level3 = sqlsrv_fetch_array( $result3, SQLSRV_FETCH_ASSOC))
													{ 
														if($level2['IsService']==true)
														{
															$toggle='';
														}else
														{
															$toggle='<span class="node-toggle"></span>';
														}
																											
											?>
													<li class="node collapsed">
														<a href="#"><?php echo $toggle.$level3['Description'] ?></a>
														<ul>
															<li><a href="">node</a></li>
														</ul>
													</li>									
											<?php 	}
												}
											?>
											</ul>
										</li>									
									<?php }
									}
									?>
									</ul>
								</li>
							<?php
						}
					}
				?>

				</ul>				
			</li>
			<?php	
			}				
			
			?>
			
		</ul>
			<?php 
		}	?>			

</div>
