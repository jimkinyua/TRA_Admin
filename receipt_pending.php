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

if (isset($_REQUEST['msg']))
{
	$msg = $_REQUEST['msg'];	
}

$InvoiceHeaderID=$_REQUEST['InvoiceHeaderID'];
$InvoiceAmount=$_REQUEST['InvoiceAmount'];
$Balance=$_REQUEST['Balance'];

$DateReceived=Date("d/m/Y");
$RefNumber="";
$BankID=0;
$PaymentMethod="";
$ReceiptAmount=0;

if ($_REQUEST['Receipt']==1){
	$DateReceived=$_REQUEST['DateReceived'];
	$ReceiptAmount=$_REQUEST['ReceiptAmount'];
	$PaymentMethod=$_REQUEST['PaymentMethod'];
	$RefNumber=$_REQUEST['RefNumber'];
	$BankID=$_REQUEST['BankID'];
	
	if ((Double)$ReceiptAmount<=0){
		$msg="The Receipt Amount is not set";
	}else if($RefNumber==""){
		$msg="The Reference Number is not set";
	}else if ($PaymentMethod=="0"){
		$msg="The Payment Method is not set";
	}else if($BankID=="0"){
		$msg="The Receiving Bank is not set";
	}else{	
		$msg=ReceiptMoney($db,$DateReceived,$BankID,$RefNumber,$PaymentMethod,$InvoiceHeaderID,$ReceiptAmount,$CreatedUserID);
	}
	
	$page="<script type='text/javascript'>
							loadmypage('services_list.php?i=1','content','loader','listpages','','services')
						</script>";
    echo $page;						
}

?>
<div class="example">
	<form class="ui form" action="receipt.php" name="fnForm2">
    <h3 class="ui dividing header"> Receipt Payment </h3>
	<fieldset>
    <div id="subform" class="ui basic padded segment">
      <table class="ui selectable attached basic table">
        <thead>
		  <tr>
			  <td colspan="4" align="center" style="color:#F00"><?php echo $msg; ?></td>
		  </tr>
          <tr>
            <th class="four wide">Invoice No</th>
            <th class="four wide">Amount Invoiced</th>
			<th class="four wide">Balance</th>
            <th class="four wide">Amount Receipted</th>
          </tr>
        </thead>
        <tbody id="tbody">          
            <tr>
              <td><?php echo $InvoiceHeaderID ?> </td>
              <td class='due' data-due="">KSh. &nbsp; <?php echo $InvoiceAmount ?></td>
			  <td class='due' data-due="">KSh. &nbsp; <?php echo $Balance ?></td>
              <td  class="required">
                <div class="ui left corner labeled mini input" style="border-radius: 0;">
                  <div class="ui left corner label"> <i class="asterisk icon"  style="color: #d95c5c; font-size: 70%;"></i> </div>
                  <input class='receipted' type="text" name="ReceiptAmount" placeholder="KSh. 0.00" style="padding-left: 25px;">
                </div>
              </td>
            </tr>          
        </tbody>
        <tfoot id="tfoot">
          <tr>

            <th>
              <div id="add_invoice" class="ui basic small button" style="border-radius: 0;">
                <i class="plus outline icon"></i>
                Add Invoice
              </div>
            </th>
            <th>
              <div>
                <strong> Total Due: </br> </strong>
                KSh. <span id="total_due"> Add Invoice </span>
              </div>
            </th>
            <th>
              <div>
                <strong> Total Receipted: </br> </strong>
                KSh. <span id="total_receipted"> Add Invoice </span>
              </div>
            </th>
          </tr>
      </tfoot>
        
      </table>
    </div>

    <div class="required field">
      <label>Date</label>
      <input type="text" name="DateReceived" value="<?php echo $DateReceived; ?>" placeholder="Date of Payment">
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
		<input name="btnReceipt" type="button" Value="Save" class="ui button" onclick="loadmypage('receipt.php?'+							
							'&ReceiptAmount='+this.form.ReceiptAmount.value+
							'&DateReceived='+this.form.DateReceived.value+
							'&PaymentMethod='+this.form.PaymentMethod.value+
							'&BankID='+this.form.BankID.value+
							'&RefNumber='+this.form.RefNumber.value+
							'&InvoiceAmount='+<?php echo $InvoiceAmount ?>+
							'&InvoiceHeaderID='+<?php echo $InvoiceHeaderID ?>+
							'&Receipt=1'+
							'','content')"/>
		<!--					
		<input name="btnReceipt" type="button" Value="Save" class="ui button" onclick="loadmypage('receipt.php?'+							
							'&ReceiptAmount='+this.form.ReceiptAmount.value+'&receipt=1','content')"/> -->
	</div>
	</fieldset>							
</form>
</div>

<?php
    $search = json_encode(
    "
    <div id='search_box' class='ui attached segment' style='width: 100%; margin-top: 5px;'>
      <div class='ui grid'>
        <a class='ui right corner label' id='remove'>
          <i class='remove circle icon'></i>
        </a>
        <div class='six wide column'>
          <div id='search_input' class='ui icon mini input'>
            <input id='invoice_number' type='text' placeholder='Invoice Number' style='border-radius: 0;'>
            <i id='search_invoice' class='circular search link icon'></i>
          </div>
        </div>
        <div class='ten wide column'>
          <div id='result'>  </div>
        </div>
      </div>
    </div>
    "
  );
    $error = json_encode(
    "
    <div class='ui left pointing red basic label''>
      Could not find any invoice with that ID
    </div>
    "
  );
    $done = json_encode(
    "
    <div class='ui left pointing green basic label''>
      Done
    </div>
    "
  );
    print_r($_SERVER['DOCUMENT_ROOT']);

    ?>

    <script type="text/javascript">
    alert('ready')
      $(document).ready(function() {
        var done = <?php echo $done; ?>;
        var error = <?php echo $error; ?>;
        var searchForm = <?php echo $search; ?>;

        var url = '/Revenue/searchinvoice/{id}';
        var inv_url = '/Revenue/invoice/{hid}';
console.log(url)
        var total_due = 0;
        $('.due').each(function(i, el) {
          total_due += parseInt($(el).data('due').replace(/,/i, ''));
        })
        $( "#total_due" ).html( total_due.toFixed(2) );
        var total_receipted = 0;
        $('.receipted').each(function(i, el) {
          receipted = parseInt($(el).val()) || 0
          total_receipted += receipted;
        })
        $( "#total_receipted" ).html( total_receipted.toFixed(2) );

        console.log('ready!');
        $( "#add_invoice" ).click(function() {

          console.log( "Show form to add invoice" );
          $("#subform").append(searchForm);

          $( "#remove" ).click(function() {
            console.log('closing time');
            $( "#search_box" ).remove();
          })

          $( "#search_invoice" ).click(function() {
            if(! $( "#invoice_number" ).val() == '' ) {

              var path = url.slice(0, -4) + $( "#invoice_number" ).val();
              console.log("search invoice server side", path);
              $("#search_input").addClass("loading");

              $.get( path, function( data ) {
                $("#search_input").removeClass("loading");
                if(JSON.parse(data).status === 'error') {
                  $( "#result" ).html( error );
                } else {
                  $( "#result" ).html( done );

                  var inv_path = inv_url.slice(0, -9) + JSON.parse(data).invoice;

                  var tr = " \
                    <tr> \
                      <td> <a href='{inv_path}'> {inv_id} </a> </td> \
                      <td class='due'>KSh. {inv_bal} </td> \
                      <td > \
                        <div class='ui left corner labeled mini input' style='border-radius: 0;'> \
                          <div class='ui left corner label'> <i class='asterisk icon'  style='color: #d95c5c; font-size: 70%;'></i> </div> \
                          <input type='text' name='{inv_name}' placeholder='KSh. ' style='padding-left: 25px;'> \
                        </div> \
                      </td> \
                    </tr> \
                  ";

                  var supplant = function (str, o) {
                      return str.replace(/{([^{}]*)}/g,
                          function (a, b) {
                              var r = o[b];
                              return typeof r === 'string' || typeof r === 'number' ? r : a;
                          }
                      );
                  };

                  var opts = {
                    inv_path: inv_path,
                    inv_id: JSON.parse(data).invoice,
                    inv_bal: JSON.parse(data).balance.toFixed(2),
                    inv_name: ("invoice[" + JSON.parse(data).invoice + "]") };

                  $( "#tbody" ).append(supplant(tr, opts));
                }
              });

            }

          });

        });
      });
    </script>
</body>


    