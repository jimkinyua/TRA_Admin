<?php 
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];

 //print_r($_REQUEST);

if (isset($_REQUEST['msg']))
{
	$msg = $_REQUEST['msg'];	
}

//print_r($_REQUEST);


$ServiceID='';
$SubSystemID='';
$LinkServiceID='0';
$FinancialYearID='';
$ChargeTypeID ='';
$$ServiceCharge='';
$ServiceName='';


    if($_REQUEST['search']==1)
    {
        $SearchBy=$_REQUEST['SearchBy'];
        $searchvalue=$_REQUEST['searchvalue'];

        if($SearchBy==1)//ID
        {   
            $filter="where IDNO='$searchvalue'";

        }else if($SearchBy==2)//Email
        {
            $filter="where Email='$searchvalue'";

        }else if($SearchBy==3)//Names
        {
            $filter="where FirstName+ ' '+MiddleName+' '+LastName like '%$searchvalue%'";
        }

        $sql = "select * from agents $filter";

        //echo ($sql);  
       // exit;

        $result=sqlsrv_query($db,$sql);
        while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
        {
            $mdata.='<tr>
                <td>'.$row['IDNO'].'</td>
                <td>'.$row['FirstName'].' '.$row['MiddleName'].' '.$row['LastName'].'</td>
                <td>'.$row['Email'].'</td>
                <td>
                <input name="Button" type="button"  value="Add" 
                    onClick="loadmypage(\'users_list.php?save=1&AgentID='.$row['AgentID'].'\',\'content\',\'loader\',\'listpages\',\'\',\'users\')">
                </td>        
                </tr>';
        }
    }


    if ($_REQUEST['add']==1){

       // print_r($_REQUEST);
       // exit;
        $ServiceID=$_REQUEST['ServiceID'];
        $ApplicationID=$_REQUEST['ApplicationID'];
        $Amount=$_REQUEST['Amount'];;
        
        $s_sql="";
        $result = sqlsrv_query($db, $s_sql);
        if (!$result)
        {
            DisplayErrors();
            //echo $s_sql;
            $msg="Action failed";
        }else{
            //echo $s_sql;
            $msg="Charge Applied Successfully";
        }
    }
    


?>
<!DOCTYPE html>
<html>
<head>
    <title>Add User</title>   
</head>
<body class="metro" >
    <div class="example" id="charge">
        <form action="add_user.php" method="POST">
            <fieldset>
                <table border="0" cellspacing="0"  width="100%">
                    <tr>
                      <td align="center" colspan="2" style="color:#F00"><?php echo $msg; ?></td>
                    </tr>
                   
                    <tr>
                        <td colspan="2">
                            
                            <table>
                                <tr>
                                    <td> <label>Search By</label>
                                        <div class="input-control select" data-role="input-control">
                                            
                                            <select name="SearchBy"  id="SearchBy">
                                                <option value="1" selected="selected">ID No</option>
                                                <option value="2" selected="selected">Email</option>
                                                <option value="3" selected="selected">Names</option>
                                          </select>                                        
                                      </div>
                                    </td>
                                      <td >
                                        <label>Value</label>
                                        <div class="input-control text" data-role="input-control">
                                            <input type="text" id="searchvalue" name="searchvalue" ></input>
                                            <button class="btn-clear" tabindex="-1"></button>
                                        </div>
                                    </td>  
                                  <td >
                                        <label>&nbsp</label>
                                      <input name="Button" type="button"  value="Search" onClick="loadmypage('add_user.php?SearchBy='+ this.form.SearchBy.value +'&searchvalue='+ this.form.searchvalue.value +'&search=1','charge')">
                                  </td>
                                </tr>
                            </table>
                        </td>                                         
                  </tr>                         
                             
                </table>                                      
             
                <div style="margin-top: 20px">
        </div>

            </fieldset>
        </form>

        <table class="table striped bordered hovered">
            <thead>             
            <tr>
                <th class="text-left">Id Number</th>
                <th class="text-left">Full Names</th>
                <th class="text-left">Email</th>
                 <th class="text-left"></th>                   
            </tr>
            </thead>
            <tbody>
                <?php
                    echo $mdata;
                ?>
            <tbody>
            
            </tbody>
        </table>      
</BODY>
</html>