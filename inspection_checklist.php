<?php

  require 'DB_PARAMS/connect.php';
  require_once('utilities.php');
  require_once('GlobalFunctions.php');
  require_once('county_details.php');
  require_once('smsgateway.php');


  if (!isset($_SESSION))
  {
    session_start();
  }

  $InspectionID=$_REQUEST['InspectionID'];
  $ApplicationID=$_REQUEST['ApplicationID'];
  // exit(  $InspectionID);
  





?>
<div class="example">
<legend>Inspection Results</legend>


<thead>
  <tr>
    <th colspan="3">
      <table width="100%">

        <tr>
       <?php

       $s_sql = "select sh.ServiceID,s.ServiceName,s.IsAppliedByIndividuals,sh.ServiceStatusID,s.ServiceCategoryID,sc.ServiceGroupID from ServiceHeader sh join Services s 
        on sh.ServiceID = s.ServiceID join ServiceCategory sc on sc.ServiceCategoryID=sh.ServiceCategoryId
        join ServiceGroup sg on sg.ServiceGroupID=sc.ServiceGroupID where ServiceHeaderID = $ApplicationID";
        $t_result=sqlsrv_query($db,$s_sql);
        // echo $s_sql;
        if($t_result){
          ?>
          
          <?php
  while($row=sqlsrv_fetch_array($t_result,SQLSRV_FETCH_ASSOC))
        {
          $ServiceID = $row['ServiceID'];
          $ServiceName = $row['ServiceName'];
          $ServiceCategoryID = $row['ServiceCategoryID'];
          $ServiceGroupID = $row['ServiceGroupID'];
          $ServiceStatusID = $row['ServiceStatusID'];
          $IsAppliedByIndividuals = $row['IsAppliedByIndividuals'];
        }
        // echo $ServiceCategoryID;
      }
         ?>


         <?php
         if($ServiceGroupID == 11){
          $sql="select sum(cr.ParameterScore) as TotalScore, ag.FirstName,ag.LastName 
          from ChecklistResults cr join Agents ag on ag.AgentID=cr.CreatedBy join Users u on u.AgentID = ag.AgentID 
            join Inspections ins on ins.InspectionID = cr.InspectionID
          where ins.ServiceHeaderID = $ApplicationID group by ag.FirstName,ag.LastName";
          // echo $sql;
          $s_result=sqlsrv_query($db,$sql);
          if ($s_result){
           ?>
      <table>
        <th>Inspection Officers' Score</th>

      <?php
      while($row=sqlsrv_fetch_array($s_result,SQLSRV_FETCH_ASSOC))
        {                 
          $FirstName = $row['FirstName'];
          $LastName = $row['LastName'];
          $TotalScore = $row['TotalScore'];

          ?>
            <tr>
              <td><?php echo $FirstName; ?> <?php echo $LastName; ?></td>
              <td><?php echo $TotalScore; ?></td>
            </tr>
          <?php
          
            }

$row = sqlsrv_has_rows( $s_result );
  if ($row == false)
  {
  ?>
       <tr class="tabletype2tdOdd" >
      <td colspan="8" align="center">Inspection has not been done yet</td>
      </tr>
      <?php
      }     
    }

?>
<tr>
  <td>
     <?php
            $numrows = 0;
            $r_sql = "
          Select COUNT(distinct cr.CreatedBy) AS OfficersNum FROM ChecklistResults cr join Inspections ins on ins.InspectionID = cr.InspectionID  
          where ServiceHeaderID = $ApplicationID";
           // echo $r_sql; echo '<br>';
            $result = sqlsrv_query($db, $r_sql);
            if ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
            {   
             $numrows = $myrow['OfficersNum'];
            }

            $inspections = 0;
            $i_sql = "SELECT distinct COUNT(sh.ServiceHeaderID) as inspections FROM ServiceHeader AS sh 
                      INNER JOIN Services AS s ON sh.ServiceID = s.ServiceID 
                      inner join ServiceCategory sc on sc.ServiceCategoryID = sh.ServiceCategoryID 
                      inner Join ServiceGroup sg on sg.ServiceGroupID = sc.ServiceGroupID 
                      INNER JOIN Customer AS c ON sh.CustomerID = c.CustomerID 
                      INNER JOIN ServiceStatus ss ON sh.ServiceStatusID=ss.ServiceStatusID 
                      INNER JOIN Inspections ins on ins.ServiceHeaderID=sh.ServiceHeaderID 
                      JOIN Users u on u.AgentID=ins.UserID where ins.InspectionStatusID>0 and sh.ServiceHeaderID=$ApplicationID and sh.ServiceStatusID !=1 and sc.ServiceGroupID!=12";
           // echo $r_sql; echo '<br>';
            $iresult = sqlsrv_query($db, $i_sql);
            if ($myrow = sqlsrv_fetch_array( $iresult, SQLSRV_FETCH_ASSOC)) 
            {   
             $inspections = $myrow['inspections'];
            }



            $Totals = 0;
            $r_sql = "select sum(cr.ParameterScore) as Totals from ChecklistResults cr join Inspections ins on ins.InspectionID = cr.InspectionID  
            where ServiceHeaderID = $ApplicationID";
// echo $r_sql; echo '<br>';
            $result = sqlsrv_query($db, $r_sql);
            if ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
            {   
             $Totals = $myrow['Totals'];
            }

            $Average = $Totals/$numrows;
        ?>
    The average score from the inspection is: <strong> <?php echo $Average; ?> </strong>
<?php 
}else{
    // echo 'Continue';
  }?>

  </td>
</tr>
</table>


 
<form>
<table class="table striped hovered dataTable" id="dataTables-1" width="100%">
<thead>
  <tr>
    <th colspan="3">
      <table width="100%">
        <?php 
                  if($ServiceGroupID == 11 && $numrows !=3){
                    ?>

                    <p style="color:red;">

                      Classification Inspection needs to be completed by 3 officers before approval
                    </p>
                  <?php
                  }elseif($ServiceGroupID == 11 && $inspections !=3){
                    ?>

                    <p style="color:red;">

                      Re-Inspection has to be conducted by 3 officers
                    </p>
                  <?php
                }elseif($ServiceGroupID == 11 && $ServiceStatusID==4 || $ServiceStatusID==1|| $ServiceStatusID==6|| $ServiceStatusID==3){?>
                    <p style="color:red;">
                     Verdict has been given...
                    </p>
                   
                  <?php }else{
                  ?>
        <tr>
              <tr>                                                          
                <td><label>Final Verdict </label>
                  <div class="input-control select" data-role="input-control">            
                    <select name="Status" id="Status">
                      <option value="4">Pass</option>
                      <option value="1">Re-Inspect</option>
                      <option value="6">Fail</option>
                    </select>        
                  </div>
                </td>

                <td><label>Comment</label>
                  <div class="input-control textarea" data-role="input-control">            
                    <textarea id="Comment" name="Comment" cols="20"></textarea>  
                    <input type="hidden" name="ServiceGroupID" value="<?php echo $ServiceGroupID; ?>">
                    <input type="hidden" name="IsAppliedByIndividuals" value="<?php echo $IsAppliedByIndividuals; ?>">
                    
                    <?php
                      if($ServiceGroupID == 11){
                        ?>
                        <input type="hidden" name="AverageScore" value="<?php echo $Average; ?>">
                        <?php
                      }else{
                    ?>  
                    <input type="hidden" name="AverageScore" value="0">
                      <?php
                      }
                      ?>
                  </div>
                </td>                
                <td><label>&nbsp;</label>

                  

                <input name="btnSubmit" type="button" onclick="

                      var status=this.form.Status.value;
                      var comment=this.form.Comment.value;
                       
                      if(status==0 && comment==''){
                        alert('You must put a Comment for declining the appication');
                        exit;
                      }

                      deleteConfirm2('Are you sure you want to submit?','inspections_list.php?'+
                      'InspectionID=<?= $InspectionID ?>'+
                      '&Status='+this.form.Status.value+                
                      '&Comment='+this.form.Comment.value+
                      '&AverageScore='+this.form.AverageScore.value+
                      '&ServiceGroupID='+this.form.ServiceGroupID.value+
                      '&submit=1','content','loader','listpages','','Inspections','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" value="Submit">
                    <?php } ?>
                </td>
              </tr>
            </table> 
            
    </th>
  </tr>
  <tr>  
    <th width="10%" class="text-left">#</th>
    <th width="20%" class="text-left">Description Of The essential Item</th>
    <th width="70%" class="text-left">Compliance Item</th>    
  </tr>
  </thead>
  <tbody>
  <tr>
    <td>&nbsp;</td>
  <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
   </tbody>   
</table>
</form>
<br/>

</div>
<script>

</script>