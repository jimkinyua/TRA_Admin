<?php 
require 'DB_PARAMS/connect.php';
require('GlobalFunctions.php');
require_once('utilities.php');

if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$CreatedUserID = $_SESSION['UserID'];
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

if (isset($_REQUEST['msg']))
{
	$msg = $_REQUEST['msg'];	
}

$InvoiceHeaderID0=0;
$InvoiceHeaderID=0;
$InvoiceHeaderID1=0;
$InvoiceHeaderID2=0;
$InvoiceHeaderID3=0;
$InvoiceHeaderID4=0;

$Customer='';
$Customer1='';
$Customer2='';
$Customer3='';
$Customer4='';

$InvoiceAmount='';
$InvoiceAmount1='';
$InvoiceAmount2='';
$InvoiceAmount3='';
$InvoiceAmount4='';

$Balance='';
$Balance1='';
$Balance2='';
$Balance3='';
$Balance4='';

$ReceiptAmount='';
$ReceiptAmount1='';
$ReceiptAmount2='';
$ReceiptAmount3='';
$ReceiptAmount4='';

$DateReceived=Date("d/m/Y");
$RefNumber="";
$BankID=0;
$PaymentMethod="";
$ReceiptAmount=0;

$RefID='';
$mdata='';
$mdata2='';

$mdata2=getDetails($db,$CreatedUserID);
$mdata=$mdata2[2];

function getDetails($db,$UserID)
{
	
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$mdata='';
	$mdata2=[];

	$sql="select a.RefID,a.InvoiceHeaderID,
	(select top 1 CustomerName from dbo.fnInvoiceDetails(a.InvoiceHeaderID)) CustomerName,
	(select top 1 Amount from dbo.fnInvoiceDetails(a.InvoiceHeaderID)) InvoiceAmount,
	(select top 1 Balance from dbo.fnInvoiceDetails(a.InvoiceHeaderID)) Balance,
	a.Amount ReceiptAmount 
	from TempReceiptLines a 
	where UserID=$UserID and posted=0";



	$result=sqlsrv_query($db,$sql,$params,$options);
	if(!$result)
	{
		DisplayErrors();
	}
	
	$rows=sqlsrv_num_rows($result);
	//echo $sql;
	$i=0;
	if ($rows>0)
	{
		while($rws=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
		{
			$RefID=$rws['RefID'];
			$mdata.=
			'<tr>			
				<td>'.$rws['InvoiceHeaderID'].'</td>
				<td>'.$rws['CustomerName'].'</td>
				<td>'.number_format($rws['InvoiceAmount'],2).'</td>
				<td>'.number_format($rws['Balance'],2).'</td>
				<td><div class="input-control text" data-role="input-control">
                    <input type="text" id="InvoiceNo-'.$rws['InvoiceHeaderID'].'" name="InvoiceNo-'.$rws['InvoiceHeaderID'].'"  ></input>
                    <button class="btn-clear" tabindex="-1"></button>
                </div></td>
                <td>
		            <input name="Button" type="button"  value="Remove" 
		                onClick="loadmypage(\'receipt32.php?remove=1&InvoiceHeaderID='.$rws['InvoiceHeaderID'].'&UserID='.$UserID.'\',\'content\')">

            	</td>
			</tr>';
			
			$i=$i+1;
		}
	}else{
		$RefID=time();
	}

	$mdata2[0]=$RefID;
	$mdata2[1]=$rows;
	$mdata2[2]=$mdata;
	
	return $mdata2;
}



if (isset($_REQUEST['add'])){
	$InvoiceHeaderID0=isset($_REQUEST['InvoiceHeaderID0'])?$_REQUEST['InvoiceHeaderID0']:0;
	$InvoiceHeaderID=isset($_REQUEST['InvoiceHeaderID'])?$_REQUEST['InvoiceHeaderID']:0;

	//print_r($_REQUEST);
  
	$mdata2=getDetails($db,$CreatedUserID);



	$sql="if not exists(select 1 from TempReceiptLines where RefID='$mdata2[0]' and InvoiceHeaderID=$InvoiceHeaderID) 
		Insert into TempReceiptLines (RefID,UserID,InvoiceHeaderID,Amount) 
		Values('$mdata2[0]','$CreatedUserID','$InvoiceHeaderID','0')";
	
	$result=sqlsrv_query($db,$sql);
	if(!$result)
	{
		DisplayErrors();
		//echo $sql;
	}else{
		
	}

	$mdata2=getDetails($db,$CreatedUserID);

	$mdata=$mdata2[2];
}

if (isset($_REQUEST['remove'])){
	$InvoiceHeaderID=$_REQUEST['InvoiceHeaderID'];
	$UserID=$_REQUEST['UserID'];

	$sql="delete from TempReceiptLines where UserID=$UserID and InvoiceHeaderID=$InvoiceHeaderID and Posted=0";
	$result=sqlsrv_query($db,$sql);

	$mdata2=getDetails($db,$UserID);
	$mdata=$mdata2[2];
	
}

if ($_REQUEST['Receipt']==1)
{
	// print_r($_REQUEST);
	// exit();

	$DateReceived=$_REQUEST['DateReceived'];
	$SlipAmount=$_REQUEST['SlipAmount'];
	$PaymentMethod=$_REQUEST['PaymentMethod'];
	$RefNumber=$_REQUEST['RefNumber'];
	$BankID=$_REQUEST['BankID'];
	$invoices=$_REQUEST['invoices'];

	$inv_a=explode(",", $invoices);

	if(sqlsrv_begin_transaction($db)===false)
	{
		$msg=sqlsrv_errors();
		$Sawa=false;
	}

	$success=1;
	for($i=0;$i<count($inv_a);$i++){
		$invoice=str_replace('InvoiceNo-', '',$inv_a[$i]);
		$InvoiceHeaderID=explode('=', $invoice)[0];
		$InvoiceAmount=explode('=', $invoice)[1];

		$result=ReceiptMoney($db,$DateReceived,$BankID,$RefNumber,$PaymentMethod,$InvoiceHeaderID,$SlipAmount,$InvoiceAmount,$CreatedUserID);

		if($result[0]==0){
			$msg=$result[1];
			$success=0;
			break;
		}

	}

	if($success==1)
	{
		$sql="update TempReceiptLines set posted=1 where UserID	=$CreatedUserID	and posted=0";
		$result=sqlsrv_query($db,$sql);

		$rst=SaveTransaction($db,$CreatedUserID,"Posted neceipt number  ".$RefNumber);

		sqlsrv_commit($db);//Commit The Transaction

		$msg="Receipt Posted Successfully";
	}else{
		sqlsrv_rollback($db);
	}	
}

?>

<div class="example">
	<form class="ui form" action="receipt.php" name="fnForm2" id="fnForm2">
    <h3> Receipt Payment </h3>
	<fieldset>
    <div>
      <table class="ui selectable attached basic table">
        <thead>
		  <tr>
			  <td colspan="6" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
          <tr>
            <th class="three wide">Invoice No</th>            
            <th class="three wide">Customer</th>
            <th class="four wide">Amount Invoiced</th>
			<th class="four wide">Balance</th>
            <th class="four wide">Amount Receipted</th>
            <td class="one Wide"></td>
          </tr>
        </thead>
        <tbody id="tbody"> 
        	<tr>
              <td>
                <div class="input-control text" data-role="input-control">
                    <input type="text" id="InvoiceHeaderID" name="InvoiceHeaderID"  ></input>
                    <button class="btn-clear" tabindex="-1"></button>
                </div>
              </td>
              <td>
              <input name="button" type="button" onclick="loadmypage('receipt32.php?'+
              										'&add=1'+
              										'&InvoiceHeaderID='+this.form.InvoiceHeaderID.value+                               
                                                    '','content','loader','listpages','','')" value="ADD INVOICE">
              </td>
          	  <td></td>
          	  <td></td>
          	  <td></td>
          	  <td></td>
          </tr>
        	<?php 
        		echo $mdata;
        	?>                     
        </tbody>
        
      </table>
    </div>

    <div class="required field">
      <label>Date</label>
      <input type="text" name="DateReceived" value="<?php echo $DateReceived; ?>" placeholder="Date of Payment">
    </div>
    <div class="field">
      <label>Bank Slip Amount</label>
      <input type="text" name="SlipAmount" placeholder="Slip Amount">
    </div>
    <div class="required field" width="30%">
      <label>Payment Method</label>
      <select class="ui fluid search dropdown" name="PaymentMethod">
        <option value="0" selected="selected"></option>
		<?php 
		$s_sql = "SELECT ReceiptMethodID,ReceiptMethodName FROM ReceiptMethod ORDER BY 1";
		
		$s_result = sqlsrv_query($db, $s_sql);
		if ($s_result) 
		{ //connection succesful 
			while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
			{
				$s_id = $row["ReceiptMethodID"];
				$s_name = $row["ReceiptMethodName"];
				if ($SubCountyID==$s_id) 
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
    <div class="required field">
      <label>Receiving Bank</label>
      <select class="ui fluid search dropdown" name="BankID">
        <option value="0" selected="selected"></option>
		<?php 
		$s_sql = "SELECT BankID,BankName FROM bANKS ORDER BY 1";
		
		$s_result = sqlsrv_query($db, $s_sql);
		if ($s_result) 
		{ //connection succesful 
			while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
			{
				$s_id = $row["BankID"];
				$s_name = $row["BankName"];
				if ($SubCountyID==$s_id) 
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
    <div class="field">
      <label>Reference Number</label>
      <input type="text" name="RefNumber" value="<?php echo $RefNumber; ?>" placeholder="RefNumber">
    </div>
	<div class="field">
		<input name="btnReceipt" type="button" Value="Save" class="ui button" onclick="

							var elem = document.getElementById('fnForm2').elements;

							var invoices=new Array();

							for(var i = 0; i < elem.length; i++)
					        {
					        	if(elem[i].type='text')
					        	{
					        		if (elem[i].name.indexOf('nvoiceNo')>0)
					        		{
					        			
					        			if(elem[i].value==''){
					        				alert(elem[i].name+' amount is empty');
					        				exit;
					        			}

					        			invoices.push(elem[i].name+'='+elem[i].value);
					        		}
					        		
					        	}
					            
					        } 

							loadmypage('receipt32.php?'+							
							'&SlipAmount='+this.form.SlipAmount.value+
							'&DateReceived='+this.form.DateReceived.value+
							'&PaymentMethod='+this.form.PaymentMethod.value+
							'&BankID='+this.form.BankID.value+
							'&RefNumber='+this.form.RefNumber.value+
							'&invoices='+invoices+												
							'&Receipt=1'+
							'','content')"/>

		<input type="button" value="Test" onclick="DisplayFormValues();" />

	</div>
	</fieldset>							
</form>
</div>
</body>


    