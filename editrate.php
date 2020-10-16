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

$RatingID = '';

$RatingID = $_REQUEST['RatingID'];


$sql = "select r.*,s.ServiceName from Rating r join Services s on s.ServiceID=r.ServiceID where r.RatingID = $RatingID";
// exit($sql);
$result = sqlsrv_query($db,$sql);
if($result){
	while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
		$RatingID = $row['RatingID'];
		$ServiceName = $row['ServiceName'];
		$ServiceID = $row['ServiceID'];
		$MinRatingScore = $row['MinRatingScore'];
		$MaxRatingScore = $row['MaxRatingScore'];
		$RatingName = $row['RatingName'];
		$RatingDescription = $row['RatingDescription'];


}
}
?>


 


<div class="example">




<form action="" name="" method="post">

	Select Service Type: 
	<div class="input-control select" data-role="input-control">
	<select name="s_id" required>
        <option value="<?php echo $ServiceID; ?>" selected="selected" ><?php echo $ServiceName; ?></option>
        <?php 
		$status_sql = "select ServiceID,ServiceName from Services where ServiceCategoryID = 2033";
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
  <div class="input-control text" data-role="input-control">
	Min Rating Score: <input type="text" name="MinRatingScore" value="<?php echo $MinRatingScore; ?>">
	</div>
	<br>
	<div class="input-control text" data-role="input-control">
	Max Rating Score: <input type="text" name="MaxRatingScore" value="<?php echo $MaxRatingScore; ?>">
	</div>
	<br>
	<div class="input-control select" data-role="input-control">
	Rating Name: <select name="RatingName" required>
        <option value="<?php echo $RatingName; ?>" selected="selected" ><?php echo $RatingName; ?></option>
        <option value="1 Star">1 Star</option> 
        <option value="2 Star">2 Star</option> 
        <option value="3 Star">3 Star</option> 
		<option value="4 Star">4 Star</option> 
		<option value="5 Star">5 Star</option> 
      </select>
	</div>
      <br>
      <div class="input-control textarea" data-role="input-control">
	Rating Description: <textarea type="text" name="RatingDescription" value="<?php echo $RatingDescription; ?>"> <?php echo $RatingDescription; ?></textarea>
	</div><br>
<input type="hidden" name="RatingID" value="<?php echo $RatingID;?>">
	
					<input name="save" type="button" onclick="

                      var status=this.form.MinRatingScore.value;
                      var comment=this.form.RatingName.value;
                       
                      if(MinRatingScore==0 && RatingName==''){
                        alert('You must put the rating name for the applications');
                        exit;
                      }

                      deleteConfirm2('Are you sure you want to update?','setrating.php?'+
                      'InspectionID=<?= $InspectionID ?>'+
                      '&s_id='+this.form.s_id.value+
                      '&RatingID='+this.form.RatingID.value+
                      '&MinRatingScore='+this.form.MinRatingScore.value+ 
                      '&MaxRatingScore='+this.form.MaxRatingScore.value+               
                      '&RatingName='+this.form.RatingName.value+
                      '&RatingDescription='+this.form.RatingDescription.value+
                      '&editrating=1','content','loader','listpages','','Inspections','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" value="update">

</form>

