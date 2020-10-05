<?php

require 'DB_PARAMS/connect.php';
require_once('utilities.php');

$ParameterCategoryID=$_REQUEST['ParameterCategoryID'];
$sql = "SELECT * FROM ChecklistParameters WHERE ParameterCategoryID='$ParameterCategoryID' ORDER BY 1";

$result = sqlsrv_query($db, $sql);

?>
<select name="ParameterID"  id="ParameterID">
<option value="0" selected="selected"></option>
<?php 
if ($result) 
{ //connection succesful 
    while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC))
    {
        $s_id = $row["ParameterID"];
        $s_name = $row["ParameterName"];
        if ($ParameterID==$s_id) 
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


