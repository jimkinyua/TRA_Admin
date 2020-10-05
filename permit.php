<?php

require_once('GlobalFunctions.php');
if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$UserID = $_SESSION['UserID'];

$ApplicationID=$_REQUEST['ApplicationID'];

$ApplicationID=1;
$PermitNo='';
$BusinessID="";
$CustomerID="";
$Validity="";
$Expiry="";
$ExpityDate="";
$CustomerName="";
$BusinessName="";
$ServiceName="";
$ServiceCost="";
$ServiceCost_Words="";
$PostalAdress="";
$PostalCode="";
$Vat="";
$PIN="";
$Town="";
$namee;

$namee='Josee'; 

//get the details for this application

$sql = "select distinct sh.ServiceHeaderID,sh.PermitNo,sh.ServiceID,sh.Validity,sh.ExpiryDate,
		ih.InvoiceHeaderID, ih.CustomerID,ih.InvoiceDate,ih.Paid,
		c.CustomerName,C.CustomerID,c.PostalAddress,c.PostalCode,c.VatNumber,c.PIN,Town,
		s.ServiceName,
		sum(il.Amount) Amount
		
		from InvoiceHeader ih
		inner join InvoiceLines il on il.InvoiceHeaderID=ih.InvoiceHeaderID
		inner join ServiceHeader sh on il.ServiceHeaderID=sh.ServiceHeaderID
		inner join Customer c on sh.CustomerID=c.CustomerID	
		inner join Services s on sh.ServiceID=s.ServiceID
		
		where sh.ServiceHeaderID=$ApplicationID 

		group by sh.ServiceHeaderID, ih.CustomerID,ih.InvoiceDate,c.CustomerName,s.ServiceName,ih.Paid,ih.InvoiceHeaderID,sh.ServiceHeaderID,C.CustomerID,
		sh.PermitNo,sh.ServiceID,sh.Validity,sh.ExpiryDate,c.PostalAddress,c.PostalCode,c.VatNumber,c.PIN,Town";
		
		$qry_result=sqlsrv_query($db,$sql);
		    
		if ($row = sqlsrv_fetch_array($qry_result,SQLSRV_FETCH_ASSOC))
		{
			$PermitNo=$row['PermitNo'];
			$BusinessID=$row['CustomerID'];
			$CustomerID=$row['CustomerID'];
			$Validity=$row['Validity'];
			$Expiry=$row['ExpiryDate'];
			$ExpityDate=$row['ExpityDate'];
			$CustomerName=$row['CustomerName'];
			$BusinessName=$row['CustomerName'];
			$ServiceName=$row['ServiceName'];
			$ServiceCost=$row['Amount'];
			$PostalAdress=$row['PostalAddress'];
			$PostalCode=$row['PostalCode'];
			$PIN=$row['VatNumber'];
			$Vat=$row['Vat'];
			$Town=$row['Town'];
			
			$ServiceCost_Words=convertNumber($ServiceCost);
		}
		
		//echo $sql;
$PermitNo=1;
?>

<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
		<tr>
			<td align="Center" colspan="2">
				<img src="images/CountyLogo_New.png" alt="County Logo">
			</td>
		</tr>
		<tr>
			<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
		</tr>
		<tr>
			<td width="50%" style="color:#0000BB;">
				Address: '.$CountyAddress.'<br />
				'.$CountyTown.'<br /> 
				Telephone: '.$CountyTelephone.'</td>
			<td width="50%" style="text-align: right;">Invoice No.<br /><span style="font-weight: bold; font-size: 12pt;">'.$InvoiceNo.'</span></td>
		</tr></table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo.png" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/><br/><br/>
		<div style="text-align: right">Date: '.date('jS F Y').'</div>
		
		<table width="100%" style="font-family: serif;" cellpadding="10">
		<tr>
			<td width="45%" style="border: 0.1mm solid #888888;">
				<span style="font-size: 7pt; color: #555555; font-family: sans;">TO:</span><br /><br />'.$CustomerName.'<br />'.$CustomerAddress.'<br />'.$CustomerCity.'<br />'.$CustomerMobile.'
			</td>
			<td width="10%">&nbsp;</td>
			<td width="45%"></td>
		</tr>
		</table>


		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td width="15%">REF. NO.</td>
		<td width="10%">QUANTITY</td>
		<td width="45%">DESCRIPTION</td>
		<td width="15%">UNIT PRICE</td>
		<td width="15%">AMOUNT</td>
		</tr>
		</thead>
		<tbody>
		'.
			while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
			{					
				$ServiceName = $rw['ServiceName'].$Remark;
				$ServiceAmount = $rw['Amount'];	
				$InvoiceLineID=$rw['InvoiceLineID'];
				.'				
					<tr>
					<td align="center">'.$InvoiceLineID.'</td>
					<td align="center">1</td>
					<td>'.$ServiceName.'</td>
					<td align="right">'.$ServiceAmount.'</td>
					<td align="right">'.$ServiceAmount.'</td>
					</tr>
				'.				
			}		
		.'
		<td class="blanktotal" colspan="3" rowspan="6"></td>
		<td class="totals">Subtotal:</td>
		<td class="totals">'.$ServiceAmount.'</td>
		</tr>
		<tr>
		<td class="totals"><b>TOTAL:</b></td>
		<td class="totals"><b>'.$ServiceAmount.'</b></td>
		</tr>
		<tr>
		<td class="totals"><b>Balance due:</b></td>
		<td class="totals"><b>'.$ServiceAmount.'</b></td>
		</tr>
		</tbody>
		</table>
		<div style="font-style: italic; font-size: 10;">
							Payment terms: payment due in 30 days<br>
							Payment by MPESA
							<ol>
							<li> Go to MPESA menu and select <b>Lipa na MPESA</b></li>
							<li> Enter <b>646464</b> as the paybill number and the invoice number as the account number</li>
							<li> Pay the amount and enter your MPESA pin number when printed</li>
							<li> Check your email for the permit if the amount paid by MPESA is successfull</li>
							</ol>
							
							
							<b>Payment by KCB</b>
							<ol>
								<li>Enter the Uasin Gishu cunty revenue account invoice number as the account number</li>
								<li>Check your mail for notofication and printing of the permit</li>
							</ol>
							Contact us on <b>0720646464</b> for any assistance
		</div>
		<br>
		<div style="text-align: center;">
			<img src="Images/Bar_Codes/'.$InvoiceNo.'.PNG">
		</div>
		</body>