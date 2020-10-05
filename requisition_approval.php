<?php 
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
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

$Department='';
$Description='';
$Amount='0';
$ApprovalStatusID='';
$NextStatusID='';

//get the service details

$RequisitionHeaderID=$_REQUEST['RequisitionHeaderID'];

$sql="  select r.*,dp.DepartmentName,ras.Name Status,(select sum(amount) from requisitionlines 
			where RequisitionHeaderID=r.requisitionheaderid)Amount
			from requisitionheader r
			inner join Departments dp on r.departmentid=dp.DepartmentID
			inner join RequisitionApprovalStatus ras on r.ApprovalStatusID=ras.RequisitionApprovalStatusID where RequisitionHeaderID=$RequisitionHeaderID";
  			
			//echo $sql;
			$result=sqlsrv_query($db,$sql);
		  if ($result==false)
			{
			  DisplayErrors();
			  die;
			}
			//echo 'Sawa';
			while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
			{
				//echo $row['DepartmentName'];
				$Department=$row['DepartmentName'].' Department';
				$Description=$row['Notes'];
				$Amount=$row['Amount'];
				$ApprovalStatusID=$row['ApprovalStatusID'];
				
			}
			//echo $sql;
			
?>

<div class="example">
   <legend>Requisition Approval</legend>
   <form>
      <fieldset>
          <table width="100%" border="0" cellspacing="0" cellpadding="3">
              <tr>
                <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
            </tr>
              <tr>
                  <td width="50%">
                      <label><?php echo $Department; ?></label>
                  </td>
                  <td width="50%"></td>                  
              </tr>
              <tr>
                  <td width="50%">
                  <label>Description</label>
                   		<div class="input-control textarea" data-role="input control">
                        <textarea name="Description" id="Description" disabled="disabled" ><?php echo $Description; ?></textarea>
                      </div>					
                  </td>
                  <td width="50%">
                  </td>   
              </tr>
              <tr>
                <td width="50%"><label>Amount</label>
                  <div class="input-control text" data-role="input-control">
                   	<input type="text" name="Amount" id="Amount" value="<?php echo $Amount; ?>" disabled="disabled" />                    
                  </div></td>
                  
                  <td width="50%"></td>   
            </tr>
              <tr>
                <td width="50%"><label>Notes</label>
                  <div class="input-control textarea" data-role="input-control">
                    <textarea name="Notes" type="textarea> id="Notes" placeholder=""><?php echo $Notes; ?></textarea>  
                  </div>
               </td>
                  
                  <td width="50%"></td>   
            </tr>            
            <tr>
                <td width="50%">
                    <label>Forward To</label>
                    <div class="input-control select" data-role="input-control">
                      <select name="NextStatus"  id="NextStatus">
                        
                        <?php 
                            
                            $s_sql="select a.AlternateNextStepID,b.Name from requisitionalternatesteps a 
                            inner join requisitionapprovalstatus b on a.AlternateNextStepID=b.RequisitionApprovalStatusID
                            where a.requisitionapprovalstatusid=$ApprovalStatusID";						
    
    
                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                              while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                              {							  
                                  $s_name = $row["Name"];											
                               ?>
                              <option value="<?php echo $row['AlternateNextStepID']; ?>"><?php echo $s_name; ?></option>
                            <?php 
                              }
                            }
                              ?>
                      </select> 
                     <!-- <?php echo $s_sql; ?> -->
                    </div>            
                </td>
                
                <td>
                
                </td>                      
            </tr>			
          </table>      																																										
          
          <input name="Button" type="button" onclick="loadmypage('requisition_list.php?save=1&ApplicationID=<?php echo $RequisitionHeaderID ?>&CurrentStatus=<?php echo $ApprovalStatusID ?>&NextStatus='+this.form.NextStatus.value+'&Notes='+this.form.Notes.value,'content','loader','listpages','','requisitions')" value="Save">
          <input type="reset" value="Cancel" onClick="loadmypage('requisition_list.php?i=1','content','loader','listpages','','requisitions')">          
          <span class="table_text">
          <input name="ApplicationID" type="hidden" id="ApplicationID" value="<?php echo $ApplicationID;?>" />
  <input name="edit" type="hidden" id="edit" value="<?php echo $edit;?>" />
                  </span>
          <div style="margin-top: 20px">
  </div>

  <!--	<input name="Button" type="button" onclick="loadmypage('pdf.php.php?save=1,'content','loader','clients')" value="View"> -->

      </fieldset>
  </form>                  
       <HR>         
<div class="tab-control" data-role="tab-clontrol">
<div class="tab-control" data-role="tab-control">
    <ul class="tabs">        
        <li class=""><a href="#_page_1">Notes</a></li>      
    </ul>
    
    <div class="frames">
          <div class="frame" id="_page_1" style="display: block;">
              <table class="hovered" cellpadding="3" cellspacing="1">
				<?php 
                	$sql="SELECT SH.ServiceHeaderID, SS.ServiceStatusName, SAA.Notes, SAA.CreatedDate, U.UserFullNames
							FROM dbo.ServiceApprovalActions AS SAA INNER JOIN
                         	dbo.ServiceHeader AS SH ON SAA.ServiceHeaderID = SH.ServiceHeaderID INNER JOIN
                         	dbo.Users AS U ON SAA.CreatedBy = U.UserID INNER JOIN
                         	dbo.ServiceStatus AS SS ON SAA.ServiceStatusID = SS.ServiceStatusID
							where SH.ServiceHeaderID=$ApplicationID";

							$s_result=sqlsrv_query($db,$sql);
							
							if ($s_result){
								while($row=sqlsrv_fetch_array($s_result,SQLSRV_FETCH_ASSOC)){									
									echo "<tr><td>".$row["ServiceStatusName"]."</td><td>".$row["Notes"]."</td><td>".$row["CreatedDate"]."</td><td>".$row["UserFullNames"]."</td></tr>";
									}
							}
                ?>             	
              </table>              
          </div>
      	</div>
  </div>
</div>