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


 if ($_REQUEST['save']=="1")
 {
 	$CustomerID=$_REQUEST['CustomerID'];
    $Surname=$_REQUEST['Surname'];
    $Othernames=$_REQUEST['Othernames'];
    $idno=$_REQUEST['idno'];
    
    $plotno=$_REQUEST['plotno'];   
    $Mobile=$_REQUEST['Mobile'];
    $CustomerTypeID=$_REQUEST['CustomerTypeID'];        
    $Email=$_REQUEST['Email'];  

    //print_r($_REQUEST); 

 	if ($CustomerID==0){
        $sql=" Insert into Customer (Surname,OtherNames,idno,plotno,Mobile1,CustomerTypeID,Email,CreatedBy) 
              Values('$Surname','$Othernames','$idno','$plotno','$Mobile','$CustomerTypeID','$Email','$CreatedUserID')";
    }else{
        $sql="Update Customer set Surname='$Surname',OtherNames='$Othernames',idno='$idno',plotno='$plotno',Mobile1='$Mobile',CustomerTypeID='$CustomerTypeID',Email='$Email' where CustomerID='$CustomerID'";
    }

    $result=sqlsrv_query($db,$sql);

    if ($result){
        $msg='Record saved successfully';
        
    }else{
        DisplayErrors();
    }
 }


?>
    <link href="css/metro-bootstrap.css" rel="stylesheet">
    <link href="css/metro-bootstrap-responsive.css" rel="stylesheet">
    <link href="css/iconFont.css" rel="stylesheet">
    <link href="css/docs.css" rel="stylesheet">
    <link href="js/prettify/prettify.css" rel="stylesheet">
	<body class="metro">
			<div class="example">
			<legend>Customer and Services</legend>  
			<form>      
			<table class="table striped hovered dataTable" id="dataTables-1">
				<thead>
                <tr>
                    <th class="text-left"><a href="#" onClick="loadmypage('customer.php?i=1','content')">Add</a></th>
                    <th colspan="5" class="text-center" style="color:#F00"><?php echo $msg; ?></th>
                  </tr>
				<tr>
                    <td colspan="6">
                        <table width="100%">
                            <tr>
                                
                                <td><label>Customer Name</label>
                                        <div class="input-control text" data-role="input-control">                      
                                            <input type="text" width="100" id="CustomerName" name="CustomerName" value="<?php echo $CustomerName ?>"></input>                
                                        </div>
                                </td>
                                <td><label>ID Number</label>
                                    <div class="input-control text" data-role="input-control">                      
                                        <input type="text" width="15" id="IDNO" name="IDNO" value="<?php echo $IDNO ?>"></input>  
                                    </div>
                                </td>
                                <td><label>Email</label>
                                    <div class="input-control text" data-role="input-control">                      
                                        <input type="text" width="15" id="Email" name="Email" value="<?php echo $Email ?>"></input>             
                                    </div>
                                </td>                                
                                <td><label>&nbsp;</label>
                                <input name="btnSearch" type="button" onclick="loadmypage('customers_list.php?'+
                                            '&CustomerName='+this.form.CustomerName.value+                              
                                            '&IDNO='+this.form.IDNO.value+
                                            '&Email='+this.form.Email.value+                                
                                            '&search=1','content','loader','listpages','','Customers','CustomerName='+this.form.CustomerName.value+':IDNO='+this.form.IDNO.value+':Email='+this.form.Email.value+'')" value="Search">
                                </td>
                            <tr>
                        </table>
                    </td>
                </tr>
				<tr>
					<th  class="text-left">CustomerID</th>
					<th  class="text-left">CustomerName</th>
					<th  class="text-left">Mobile No</th>
					<th  class="text-left">Email</th>
					<th  class="text-left">ID No</th>
					<th  class="text-left">Customer Type</th>
					<th  class="text-left"></th>
				</tr>
				</thead>

				<tbody>
				</tbody>
			</table> 
		</form>
	</div>
	</div>


