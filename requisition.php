<?php 
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
require_once("GlobalFunctions.php");

if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];

$NextStatus='1';

if (isset($_REQUEST['msg']))
{
	$msg = $_REQUEST['msg'];	
}



?>
<div class="example">
<form action="uploader.php" enctype="multipart/form-data" method="post">
	<fieldset>
	  <legend>CASH REQUISITION FORM</legend>
		<table width="100%" border="0" cellspacing="0" cellpadding="3">
			<tr>
			  <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
			<tr>
                 <td width="50%"> 
                    <label>Department</label>
                       <div class="input-control select" data-role="input-control">
                        <select name="Department" id="Department">
                             <?php 
                                  
                                $s_sql="SELECT DepartmentID,[DepartmentName] FROM [COUNTYREVENUE].[dbo].[Departments]";
                                  
                                $s_result = sqlsrv_query($db, $s_sql);
                                if ($s_result) 
                                { //connection succesful 
                                  while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                  {
                                      $s_name = $row["DepartmentName"];											
                                   ?>
                                  <option value="<?php echo $row["DepartmentID"]; ?>"><?php echo $s_name; ?></option>
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
                 <td width="50%"> 

                    <label>Requisition Type</label>
                       <div class="input-control select" data-role="input-control">
                        <select name="RType" id="RType">
                             <option value="recurrent"  selected>Recurrent</option>                          
                        	<option value="development"  selected>Development</option> 
                        </select>  
                      </div>                                                              
                </td> 
                <td>
                </td>
            </tr>            
			<tr>
                <td width="50%">
                	<label>Description</label>
                	<div class="input-control textarea" data-role="input-control">
                    	<textarea name="Description" id="Description"><?php echo $Description; ?></textarea>
                    </div>
                </td>
                <td>

                </td>
          	</tr>
			<tr>
                <td width="50%">
                    <div id="theForm">
                        <label>Upload Budget Breakdown<br>File Title</label>
                        <input type="text" name="mName_b" id="mName_b" />                        
                        <label>File
                        <span class="small">Choose a File</span>
                        </label>
                        <input type="file" name="mFile_b" id="mFile_" />
                         
                       <!-- <input name="Button" value="Upload (<?php echo ini_get('upload_max_filesize').'B'; ?>)" type="button" onClick="loadmypage('requisition_list.php?i=1','content','loader','listpages','','requisitions')" />-->
                        <div class="spacer"></div>
                    </div>
                </td>
                <td width="50%">

                </td>
          	</tr>             
            <hr>
			<tr>
                <td width="50%">
                    <div id="theForm">
                        <label>Upload Request Breakdown<br>File Title</label>
                        <input type="text" name="mName" id="mName" />                        
                        <label>File
                        <span class="small">Choose a File</span>
                        </label>
                        <input type="file" name="mFile" id="mFile" />
                         
                       <!-- <input name="Button" value="Upload (<?php echo ini_get('upload_max_filesize').'B'; ?>)" type="button" onClick="loadmypage('requisition_list.php?i=1','content','loader','listpages','','requisitions')" />-->
                        <div class="spacer"></div>
                    </div>
                </td>
                <td width="50%">

                </td>
          	</tr> 
			<tr>
                <td width="50%">
                	<label>Amount</label>
                	<div class="input-control textarea" data-role="input-control">
                    	<input type="text" name="Amount" id="Amount" value="" />                    	
                    </div>
                </td>
                <td>
                </td>
          	</tr>                   
        </table>
        <button type="submit" class="red-button" id="sendmail">SAVE</button>                             
 			
                                            
      <input type="reset" value="Cancel" onClick="loadmypage('requisition_list.php?i=1','content','loader','listpages','','requisitions')">
      
        <span class="table_text">
        <input name="UserID" type="hidden" id="UserID" value="<?php echo $UserID;?>" />
        <input name="add" type="hidden" id="add" value="<?php echo $new;?>" />
        <input name="edit" type="hidden" id="edit" value="<?php echo $edit;?>" />
        <input name="NextStatus" type="hidden" id="NextStatus" value="<?php echo $NextStatus;?>" />
        		</span>
        <div style="margin-top: 20px">
</div>

	</fieldset>
</form>
</div>

                                         