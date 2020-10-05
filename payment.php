<body class="metro">
	<div class="example">
	  <form class="ui form"  enctype="multipart/form-data">
	  <!-- action="{{ route('portal.post.payment') }}" method="post"-->
		<h3 class="ui dividing header"> Confirm Bank Payment </h3>

		<div id="subform" class="ui basic padded segment">
		  <div id="test1">
		  </div>
		  <table class="ui selectable attached basic table">
			<thead>
			  <tr>
				<th class="five wide">Invoice</th>
				<th class="six wide">Amount Due</th>
				<th class="five wide">Amount Receipted</th>
			  </tr>
			</thead>
			<tbody id="tbody">
				<tr>
				  <td> <a href="{{route('application.invoice', [ 'id' => $invoice->id() ])}}">{{$invoice->id()}}</a> </td>
				  <td class='due' data-due="{{number_format($invoice->total(),2)}}">KSh. &nbsp; {{number_format($invoice->total(),2)}}</td>
				  <td  class="required">
					<div class="ui left corner labeled mini input" style="border-radius: 0;">
					  <div class="ui left corner label"> <i class="asterisk icon"  style="color: #d95c5c; font-size: 70%;"></i> </div>
					  <input class='receipted' type="text" name="invoice[{{$invoice->id()}}]" placeholder="KSh. " style="padding-left: 25px;">
					</div>
				  </td>
				</tr>
			</tbody>
			<tfoot id="tfoot">
			  <tr>

				<th>				
					<input type="button" class="ui basic button" id="demo" onclick="loadDoc()">
						<i class="plus outline icon"></i>Add Invoice
					</input>				  
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
		  <input type="text" name="date" placeholder="Date of Payment">
		</div>
		<div class="required field">
		  <label>Amount </label>
		  <input type="text" name="amount" placeholder="Amount Paid">
		</div>
		<div class="required required field">
		  <label>Payment Method</label>
		  <select class="ui fluid search dropdown" name="method">
			<option value="">Payment Method</option>
			<option value="1">Mpesa</option>
			<option value="3">Bank</option>
			<option value="4">LAIFOMS Receipt</option>
		  </select>
		</div>
		<div class="field">
		  <label>Issuing Bank</label>
		  <select class="ui fluid search dropdown" name="bank">
			<option value="">Bank</option>       
			  <option value="1" >bank1</option>       
		  </select>
		</div>
		<div class="field">
		  <label>Slip Number</label>
		  <input type="text" name="slip_number" placeholder="Slip Number">
		</div>
		<button class="ui button" type="submit">Submit</button>

	  </form>
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
    ?>

    
	</div>
</body>

