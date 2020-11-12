<?php 
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
require_once('GlobalFunctions.php');
require_once('county_details.php');



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

if(isset($_POST['save'])){
	$RatingScore = $_POST['RatingScore'];
	$RatingName = $_POST['RatingName'];
}
// print_r($_POST);

if($_REQUEST['addrating']==1){
	$MinRatingScore = $_REQUEST['MinRatingScore'];
	$MaxRatingScore = $_REQUEST['MaxRatingScore'];
	$RatingName = $_REQUEST['RatingName'];
	$RatingDescription = $_REQUEST['RatingDescription'];
	$s_id = $_REQUEST['s_id'];

	$sql = "insert into Rating (MinRatingScore,MaxRatingScore,RatingName,RatingDescription,ServiceID) Values ($MinRatingScore,$MaxRatingScore,'$RatingName','$RatingDescription',$s_id)";
	// exit($sql);
	$result=sqlsrv_query($db,$sql);
		if($result){
			$msg="Rating Saved Successfully";
		}else
		{
			DisplayErrors();
			$msg="Failed to save rating, contact the technical teamss";
		}

}



if($_REQUEST['editrating']==1){
	$MinRatingScore = $_REQUEST['MinRatingScore'];
	$MaxRatingScore = $_REQUEST['MaxRatingScore'];
	$RatingName = $_REQUEST['RatingName'];
	$RatingDescription = $_REQUEST['RatingDescription'];
	$s_id = $_REQUEST['s_id'];
	$RatingID = $_REQUEST['RatingID'];

	$sql = "update Rating set MinRatingScore=$MinRatingScore,MaxRatingScore=$MaxRatingScore,RatingName='$RatingName',RatingDescription='$RatingDescription',ServiceID='$s_id' where RatingID = $RatingID";
	// exit($sql);
	$result=sqlsrv_query($db,$sql);
		if($result){
			$msg="Rating Updated Successfully";
		}else
		{
			DisplayErrors();
			$msg="Failed to update rating, contact the technical teamss";
		}
}
if($_REQUEST['deleterate']==1){

	$MinRatingScore = $_REQUEST['MinRatingScore'];
	$MaxRatingScore = $_REQUEST['MaxRatingScore'];
	$RatingName = $_REQUEST['RatingName'];
	$RatingDescription = $_REQUEST['RatingDescription'];
	$s_id = $_REQUEST['s_id'];
	$RatingID = $_REQUEST['RatingID'];

	$sql = "delete from Rating where RatingID = $RatingID";
	
	$result=sqlsrv_query($db,$sql);
		if($result){
			$msg="Record Deleted Successfully";
		}else
		{
			DisplayErrors();
			$msg="Failed to delete rating, contact the technical teamss";
		}
}

?>


 


<div class="row">

	
<form action="" name="" method="post">

	Select Service Type: 
	<div class="input-control select" data-role="input-control">
	<select name="s_id" required width="48">
        <option value="" selected="selected" >SELECT SERVICE</option>
        <?php 
		$status_sql = "select ServiceID,ServiceName 
		from Services s
		join ServiceCategory sc on sc.ServiceCategoryID = s.ServiceCategoryID
		where sc.ServiceGroupID = 11";
		$status_result = sqlsrv_query($db, $status_sql) or die ("failed to load Status");

		$selected = '';
	    while ($myrow = sqlsrv_fetch_array( $status_result, SQLSRV_FETCH_ASSOC)) 
	    {
			$s_id = $myrow ['ServiceID'];
			$s_name = $myrow['ServiceName'];
			if ($s_name==$s_id) 
			{
			   	$selected = 'SELECTED';
			} else
			{
				$selected = '';
			}	 
		 	?>
       <option value="<?php echo $s_id;?>"><?php echo $s_name;?></option> 

		
        <?php
	 }
	 ?>
      </select>
		</div>
      <br>
	Min Rating Score:  
	 <div class="-inputcontrol text" data-role="input-control">
	<input type="text" name="MinRatingScore" placeholder="input the min score for rating"><br>
	 </div>
	Max Rating Score:  
	 <div class="-inputcontrol text" data-role="input-control">
	<input type="text" name="MaxRatingScore" placeholder="input the max score for rating"><br>
	 </div>
	Rating:  
	<div class="input-control select" data-role="input-control">
	 <select name="RatingName" required>
        <option value="" selected="selected" >SELECT RATING</option>
        <option value="1 Star">1 Star</option> 
        <option value="2 Star">2 Star</option> 
        <option value="3 Star">3 Star</option> 
		<option value="4 Star">4 Star</option> 
		<option value="5 Star">5 Star</option> 
      </select>
	</div>
      <br>
	Rating Description:
	<div class="input-control textarea" data-role="input-control">
	 <textarea type="text" name="RatingDescription" cols="20"></textarea>
	</div>
	 <br>

	
					<input name="save" type="button" onclick="

                      var status=this.form.MinRatingScore.value;
                      var comment=this.form.RatingName.value;
                       
                      if(MinRatingScore==0 && RatingName==''){
                        alert('You must put the rating name for the appications');
                        exit;
                      }

                      deleteConfirm2('Are you sure you want to submit?','setrating.php?'+
                      'InspectionID=<?= $InspectionID ?>'+
                      '&s_id='+this.form.s_id.value+
                      '&MinRatingScore='+this.form.MinRatingScore.value+
                      '&MaxRatingScore='+this.form.MaxRatingScore.value+                 
                      '&RatingName='+this.form.RatingName.value+
                      '&RatingDescription='+this.form.RatingDescription.value+
                      '&addrating=1','content','loader','listpages','','Inspections','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" value="Add Rating">

</form>

<?php
	$sql_table = "select r.*,s.ServiceName from Rating r join Services s on s.ServiceID=r.ServiceID";
	$tresult = sqlsrv_query($db, $sql_table);
	if($tresult) 
	?>
	<table border="1px">
	<thead>
		<th width="30%">Establishment Type</th>
		<th width="20%">Rating Score</th>
		<th width="10%">Rating</th>
		<th width="30%">Description</th>
	</thead>	
	<?php
	while($row=sqlsrv_fetch_array($tresult,SQLSRV_FETCH_ASSOC)){
		$RatingID = $row['RatingID'];
		$ServiceName = $row['ServiceName'];
		$MinRatingScore = $row['MinRatingScore'];
		$MaxRatingScore = $row['MaxRatingScore'];
		$RatingName = $row['RatingName'];
		$RatingDescription = $row['RatingDescription'];
?>
		<tr>
			<td><?php echo $ServiceName; ?></td>
			<td><strong>Min:</strong><?php echo $MinRatingScore; ?>-<strong>Max:</strong><?php echo $MaxRatingScore; ?></td>
			<td><?php echo $RatingName; ?></td>
			<td><?php echo $RatingDescription; ?></td>
			<td>
				[<a href="#" onclick="loadpage('editrate.php?RatingID=<?php echo $RatingID;?>
				&editrate=1','content','loader','listpages','','Inspections','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')">edit</a>]
			
				[<a href="#" onclick="loadpage('setrating.php?RatingID=<?php echo $RatingID;?>
				&deleterate=1','content','loader','listpages','','Inspections','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')">Delete</a>]




			</td>
		</tr>
	

	<?php
	}

	?>
</table>
