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
$FarmID=0;

if (isset($_REQUEST['msg']))
{
    $msg = $_REQUEST['msg'];    
}

$FarmID="0";
$FarmName="";
$PerWhat=2;
$LocalAuthorityID='';
$fquery="";
$ID='';
$NAME='';
//print_r($_REQUEST);

if(isset($_REQUEST['LocalAuthorityID'])){
    $LocalAuthorityID=$_REQUEST['LocalAuthorityID'];
    
}else{
    $LocalAuthorityID=96;
}
if(isset($_REQUEST['PerWhat'])){
    $PerWhat=$_REQUEST['PerWhat'];
}else{
    $PerWhat=2;
}

//print_r($_REQUEST);

if($PerWhat==2){
    $fquery="SELECT distinct ltrim(rtrim(Lrn))  ID,ltrim(rtrim(Lrn)) Name FROM Land where LocalAuthorityID=$LocalAuthorityID ORDER BY ltrim(rtrim(Lrn))";
}else{    
    $fquery="SELECT  FirmID ID,FirmName Name FROM LandFirms where LocalAuthorityID=$LocalAuthorityID ORDER BY FirmName";
}

 //echo $fquery;
// exit;

$FarmID=$_REQUEST['FarmID'];
$lrn=$_REQUEST['lrn'];

if (isset($_REQUEST['create'])==1){
    $ID=$_REQUEST['ID']; 
    $PerWhat=$_REQUEST['PerWhat']; 
    $LocalAuthorityID=$_REQUEST['LocalAuthorityID']; 


    
    createDNotice($db,$cosmasRow,$ID,$PerWhat,$LocalAuthorityID);
    
}

if (isset($_REQUEST['create2'])==1)
{
    $ID=$_REQUEST['ID']; 
    $PerWhat=$_REQUEST['PerWhat']; 
    $LocalAuthorityID=$_REQUEST['LocalAuthorityID']; 
    $k=0;
    createDemandNotice($db,$cosmasRow,$ID,$k,$PerWhat,$LocalAuthorityID);

    $mfile=$ID."-0";
    
    zipFilesAndDownload($mfile);
    
}



if (isset($_REQUEST['download'])==1){
    $ID=$_REQUEST['ID']; 
    $PerWhat=$_REQUEST['PerWhat']; 
    
    $mfile= 'Block '.$ID; 
    zipFilesAndDownload($mfile);
}
    


function zipFilesAndDownload($FirmName)
{
    $file_path='/pdfdocs/DemandNotices/';
    //$zip = new ZipArchive();

    //$zip_name =getcwd().$file_path.$FirmName.".zip"; // Zip name
    $zip_name =getcwd().$file_path.$FirmName.".pdf"; // Zip name

    //$zip->open($zip_name,  ZipArchive::CREATE);
    
    // if ( $zip->open($zip_name, ZipArchive::CREATE) !== TRUE) {
    //     exit("message");
    // }

    // foreach (new DirectoryIterator(getcwd().$file_path) as $file) 
    // {
    //   $path = getcwd().$file_path;
    //     if(file_exists($path))
    //     {
    //         if ($handle = opendir($path)) 
    //         {                 
    //           while (false !== ($entry = readdir($handle))) 
    //           {                
    //             $zip->addFile($path.$FirmName.'.zip', $FirmName.'.pdf');                
    //           }
    //           closedir($handle);
    //         }
    //         else
    //         {
    //             echo"file does not exist";
    //         }   
    //     }                                                                       
    // } 

    // $zip->close();



    //header('Content-Type: application/zip');
    header('Content-Type: application/pdf');

    //print_r('Hei'); exit;

    header("Content-Disposition: attachment; filename='".$zip_name."'");

    header('Content-Length: ' . filesize($zip_name));
    header("Pragma: no-cache");
    header("Expires: 0");

    ob_clean();
    flush();

    readfile($zip_name);
    unlink($zip_name);
    unlink($path.$FirmName.'.pdf');
    
    


    //echo $zip_name; exit;

   

    exit;
}

?>
<div class="example">
<form action="download.php" method="post">
    <fieldset>
      <legend>Create Demand Notices</legend>
        <table width="100%" border="0" cellspacing="0" cellpadding="3">
        <tr>
          <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
        </tr>       
        <tr>
            <td><label>Local Authority</label>
                    <div class="input-control select" data-role="input-control">
                        
                        <select name="lai"  id="lai" onchange="loadmypage('DemandNotices.php?LocalAuthorityID='+this.form.lai.value+'&PerWhat='+this.form.PerWhat.value+'','content','loader','listpages','','','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')">
                            <option value="0" selected="selected"></option>
                            <?php 
                            $s_sql = "SELECT * FROM LocalAuthority ORDER BY 1";
                            
                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                {
                                    $s_id = $row["LocalAuthorityID"];
                                    $s_name = $row["LocalAuthorityName"];
                                    if ($LocalAuthorityID==$s_id) 
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
            <td></td>
        </tr>
        <tr>
            <td><label>Selection Cretaria</label>
                    <div class="input-control radio inline-block" data-role="input-control"> 
                       
                            <label class="inline-block">
                                <input name="PerWhat" id="PerWhat"  type="radio" <?php if($PerWhat=="1"){ echo 'checked=""'; } ?> value="1"   onClick="loadmypage('DemandNotices.php?PerWhat=1','content','loader','listpages','','','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')">
                                <span class="check"></span>
                                Per Firm
                            </label>
                            <label class="inline-block">
                                <input name="PerWhat" id="PerWhat" <?php if($PerWhat==2){ echo 'checked=""'; } ?> type="radio" value=2 onClick="loadmypage('DemandNotices.php?PerWhat=2','content','loader','listpages','','','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')">
                                <span class="check"></span>
                                Per Block
                            </label>
                        
                    </div>  
            </td>
            <td>
                
            </td>
        </tr>
        <tr>
            <td><label>Farm/Block</label>
                    <div class="input-control select" data-role="input-control">
                        
                        <select name="ID"  id="SID" >
                            <option value="0" selected="selected"></option>
                            <?php 

                            $s_sql = $fquery;//"SELECT * FROM LandFirms ORDER BY FirmName";
                            echo $s_sql;

                            $s_result = sqlsrv_query($db, $s_sql);
                            if ($s_result) 
                            { //connection succesful 
                                while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
                                {
                                    $s_id = $row["ID"];
                                    $s_name = $row["Name"];
                                    if ($ID==$s_id) 
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
            <td></td>
        </tr> 
        </table>

        <input name="Button" type="button" onclick="loadmypage('DemandNotices.php?'+
                                                    '&ID='+this.form.SID.value+
                                                    '&LocalAuthorityID='+this.form.lai.value+
                                                    '&PerWhat='+<?php echo $PerWhat; ?>+                                    
                                                    '&create=1','content','loader','listpages','','','<?php echo $_SESSION['RoleCenter']; ?>','<?php echo $_SESSION['UserID']; ?>')" value="Create">


        <!-- <a target="_blank">Download</a> --> 
        <input type="submit" value="Download" formtarget="_blank">
        <!-- <a href="download.php?download_file=some_file.pdf">PHP download file</a> -->
        

                </span>
        <div style="margin-top: 20px">
</div>

    </fieldset>
</form>
</div>