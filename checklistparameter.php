<?php 
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];

if (isset($_REQUEST['msg']))
{
	$msg = $_REQUEST['msg'];	
}

$ParameterName='';
$ParameterCategoryDescription='';
$ParameterID='0';
$FormID='';
$ServiceGroupID='';
$CreatedDate="";
$ServiceCode='';



if (isset($_REQUEST['edit']))
{	
	$ParameterID=	$_REQUEST['ParameterID'];
	
	$sql = "Select * FROM ChecklistParameters where ParameterID = $ParameterID";
//echo $sql;
	$result = sqlsrv_query($db, $sql);
   	while ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		$ParameterName=$myrow['ParameterName'];
		$ParameterCategoryID=$myrow['ParameterCategoryID'];
    $ChecklistTypeID=$myrow['ChecklistTypeID'];
    $ParameterScore=$myrow['ParameterScore'];
	}	
}

 if(!empty($_POST['typeid'])){

    $paramsql = "Select * from ChecklistParameterCategories where ChecklistTypeID = ".$_POST['typeid']."";
    $paramresult = sqlsrv_query($db,$paramsql);

    echo '<option value = "">select parameter category</option>';
    while($row=sqlsrv_fetch_array($paramresult,SQLSRV_FETCH_ASSOC)){
     
      echo '<option value="'.$row['ParameterCategoryID'].'">'.$row['ParameterCategoryName'].'</option>';
    }
    die();
 }



?>
<div class="example">
  <script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
    <script>
      $(document).ready(function(){
        $('#ctid').on('change',function(){
          var typeid = $(this).val();
          if(typeid){
            $.ajax({
              type: 'POST',
              url: 'checklistparameter.php',
              data: 'typeid='+typeid,
              // contentType: "application/json; charset=utf-8",
              // dataType: "json",
              success:function(params){
                // params = JSON.parse(params);
                // alert(omonso);
                console.log(params);
                $('#params').empty();
                $('#params').append(params);

              }
            });
          }else{
            $('#paramcatlist').append('<option value="">Select Checklist Type First</option>')

          }
        });
      });
    </script>

<form>
	<fieldset>
	  <legend>Edit Parameter Category</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
			<tr>
                <td width="50%">
                	<label>Parameter Name</label>
                	<div class="input-control textarea" data-role="input-control">
                    	<textarea name="ParameterName" id="ParameterName"><?php echo $ParameterName; ?></textarea>
                        <button class="btn-clear" tabindex="-1"></button>
                    </div>
                </td>
                <td width="50%">

                	</td>
          	</tr>

          <tr>
            <td><label>Checklist Type</label>
              <div class="input-control select" data-role="input-control">
                <select name="ChecklistTypeID"  id="ctid">
                  <option value="0" selected="selected"></option>
                  <?php 
                  $s_sql = "select * from ChecklistTypes order by 1";
                  $s_result = sqlsrv_query($db, $s_sql);
                  if ($s_result) 
                  { //connection succesful 
                      while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                      {
                          $s_id = $row["ChecklistTypeID"];
                          $s_name = $row["ChecklistTypeName"];
                          if ($ChecklistTypeID==$s_id) 
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
          </tr>
 
          <tr>
            <td><label>Parameter Category</label>
              <div class="input-control select" data-role="input-control">
                
                  <select name="ParameterCategoryID"  id="params">
                      <option value="">Select parameter checklist first</option>
                  </select>
              </div>
            </td>
            <td>
            </td>
          </tr>	
          <tr>
            <td>
              <label>Parameter Score</label>
              <div class="input-control text" data-role="input-control">
                <input type="text" id="ParameterScore" name="ParameterScore" value="<?= $ParameterScore ?>" />
              </div>
            </td>
            <td></td>
          </tr>	  
                        
        </table>
		<input name="Button" type="button" 
    onclick="loadmypage('checklistparameters_list.php?'+
                        '&ParameterName='+this.form.ParameterName.value+
                        '&ParameterScore='+this.form.ParameterScore.value+
                        '&ParameterCategoryID='+this.form.ParameterCategoryID.value+
                        '&ChecklistTypeID='+this.form.ChecklistTypeID.value+
                        '&ParameterID='+<?php echo $ParameterID; ?>+       
                        '&save=1','content','loader','listpages','','ChecklistParameters')" 
                        value="Save">
      <input type="reset" value="Cancel" onClick="loadmypage('checklistparameters_list.php?i=1','content','loader','listpages','','ChecklistParameters')">      
        <span class="table_text">
        <input name="UserID" type="hidden" id="UserID" value="<?php echo $UserID;?>" />
        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>