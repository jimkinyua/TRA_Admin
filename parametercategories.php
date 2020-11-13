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



?>

<tr>
            <td><label>Parameter Category from another page</label>
              
              <div class="input-control select" data-role="input-control">

                <select name="ParameterCategoryID"  id="ParameterCategoryID">
                  <option value="0" selected="selected"></option>
                  <?php 
                  $s_sql = "select * from ChecklistParameterCategories order by ParameterCategoryID";
                  echo $s_sql;
                  $s_result = sqlsrv_query($db, $s_sql);
                  if ($s_result) 
                  { //connection succesful 
                      while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                      {
                          $s_id = $row["ParameterCategoryID"];
                          $s_name = $row["ParameterCategoryName"];
                          if ($ParameterCategoryID==$s_id) 
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