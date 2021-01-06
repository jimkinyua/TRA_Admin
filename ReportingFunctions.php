<?php
ini_set('memory_limit','5000M');
	require 'DB_PARAMS/connect.php';
	require_once('utilities.php');
	require_once('PHPMailer-master/class.phpmailer.php');
	//include("PHPMailer/class.smtp.php");

	//require_once("dompdf/dompdf_config.inc.php");
	require_once("mPDF/mpdf.php");
	$msg="";
	
	//require("phpToPDF.php"); 

	 if (!isset($_SESSION))
	{
		session_start();
	}
	$msg ='';
	$UserID = $_SESSION['UserID'];
	$UserFullNames= $_SESSION['UserFullNames']; 
	function createReport($db,$cosmasRow,$rptName)
	{
		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];

		$tablestr = '';
		$ReportTitle="COLLECTIONS TO DATE";
		$sql="	SELECT   s.ServiceName,isnull(s.ServiceCode,'')ServiceCode,sg.ServiceGroupName,  sum(r.Amount)Amount
				FROM            dbo.ReceiptLines AS r INNER JOIN
				dbo.InvoiceHeader AS ih ON r.InvoiceHeaderID = ih.InvoiceHeaderID INNER JOIN
					(SELECT DISTINCT InvoiceHeaderID, ServiceHeaderID
					FROM            dbo.InvoiceLines) AS il ON il.InvoiceHeaderID = ih.InvoiceHeaderID INNER JOIN
				dbo.ServiceHeader AS sh ON il.ServiceHeaderID = sh.ServiceHeaderID INNER JOIN
				dbo.Services AS s ON sh.ServiceID = s.ServiceID INNER JOIN
				dbo.ServiceCategory AS sc ON s.ServiceCategoryID = sc.ServiceCategoryID INNER JOIN
				dbo.ServiceGroup AS sg ON sc.ServiceGroupID = sg.ServiceGroupID

				where convert(date,r.CreatedDate)=convert(date,getdate())
				group by s.ServiceName,sg.ServiceGroupName,s.ServiceCode";
		
				$tblTotals=0;
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{					
					$ServiceName = $rw['ServiceName'];					
					$ServiceCode=$rw['ServiceCode'];
					$ServiceGroupName=$rw['ServiceGroupName'];
					$Amount = $rw['Amount'];					
					$tblTotals+=$Amount;
					$tablestr.='<tr>
					<td align="left">'.$ServiceName.'</td>
					<td align="left">'.$ServiceCode.'</td>
					<td align="left">'.$ServiceGroupName.'</td>
					<td align="left">'.number_format($Amount,2).'</td>
					</tr>'; 
				}

		
		$mpdf=new mPDF('win-1252','A4','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("Tourism Regulatory Authority");
		$mpdf->SetAuthor("Tourism Regulatory Authority");
		$mpdf->SetWatermarkText("Tourism Regulatory Authority");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
					<img src="images/CountyLogo_New.png" alt="County Logo">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo_2.png" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/><br/><br/><br/><br/><br/><br/>
		
		



		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td width="35%">Service Name</td>
		<td width="15%">Service Code</td>
		<td width="35%">Service Group</td>
		<td width="15%">AMOUNT</td>
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		
		<tr>
		<td class="blanktotal" colspan="2" rowspan="6"></td>
		<td class="totals">Totals:</td>
		<td class="totals">'.number_format($tblTotals,2).'</td>
		</tr>

		</tbody>
		</table>
		</body>
		</html>
		';

		$mpdf->WriteHTML($html);
		
 		//$mpdf->Output();
		//exit; 
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}
	function mpesa_parking($db,$cosmasRow,$rptName,$fromDate,$toDate)
	{

		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];
		
		

		$tablestr = '';
		$ReportTitle="PARKING MPESA TRANSACTIONS<br> Between". $fromDate." and " .$toDate;

		$sql="set dateformat dmy
				select mpesa_code,left(mpesa_acc,3)+' '+SUBSTRING(mpesa_acc,4,3)+' '+right(mpesa_acc,1) [Reg Number],Mpesa_Acc,convert(money,mpesa_amt) Amount,mpesa_sender,
				convert(date,tstamp)[date],mpesa_msisdn PhoneNo,
				tstamp mpesa_trx_date,len(replace(mpesa_acc,' ','')) LENN
				from mpesa 
				where len(replace(mpesa_acc,' ','')) in (6,7) 
				and mpesa_acc  LIKE '%[^0-9]%'
				and mpesa_acc not LIKE '%/%'
				AND cast(tstamp as date)>=convert(date,'$fromDate') and cast(tstamp as date)<=convert(date,'$toDate') and len(replace(mpesa_acc,' ',''))=7";

		//echo $SQL; exit;
				$tblTotals=0;
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{					
					$mpesa_sender = $rw['mpesa_sender'];
					$mpesa_date=$rw['mpesa_trx_date'];
					$mpesa_phoneno=$rw['PhoneNo'];
					$mpesa_amt=$rw['Amount'];
					$mpesa_code=$rw['mpesa_code'];
					$mpesa_acc = $rw['Mpesa_Acc'];	
					$reg_no=$rw['Reg Number'];				
					$tblTotals+=$mpesa_amt;
					$tablestr.='<tr>
					<td align="left">'.$mpesa_sender.'</td>
					<td align="left">'.$mpesa_date.'</td>
					<td align="left">'.$mpesa_phoneno.'</td>
					<td align="left">'.$mpesa_code.'</td>
					<td align="left">'.$reg_no.'</td>
					<td align="right">'.number_format($mpesa_amt,2).'</td>
					</tr>'; 
				}

		
		$mpdf=new mPDF('win-1252','A4','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("Tourism Regulatory Authority");
		$mpdf->SetAuthor("Tourism Regulatory Authority");
		$mpdf->SetWatermarkText("Tourism Regulatory Authority");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
					<img src="images/CountyLogo_New.png" alt="County Logo">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo_2.png" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/><br/><br/><br/><br/><br/><br/>
		
		



		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td width="25%">Mpesa Sender</td>
		<td width="15%">Transaction Date</td>
		<td width="15%">Phone No</td>
		<td width="15%">Mpesa Code</td>
		<td width="15%">Reg No</td>
		<td width="15%">AMOUNT</td>
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		
		<tr>
		<td class="blanktotal" colspan="4" rowspan="6"></td>
		<td class="totals">Totals:</td>
		<td class="totals">'.number_format($tblTotals,2).'</td>
		</tr>

		</tbody>
		</table>
		</body>
		</html>
		';

		$mpdf->WriteHTML($html);
		
 		//$mpdf->Output();
		//exit; 
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}

	function facilitation_establishment($db,$cosmasRow,$rptName,$fromDate,$toDate,$cName)
	{
		$tablestr = '';
		$ReportTitle="Application Details";
		$sql="select sh.ServiceHeaderID,sh.ServiceStatusID,c.*,s.ServiceName,convert(date,sh.SubmissionDate) as DateApplied,st.ServiceStatusName
		from ServiceHeader sh
		join Customer c on sh.CustomerID = c.CustomerID
		join Services s on sh.ServiceID = s.ServiceID
		join ServiceStatus st on sh.ServiceStatusID = st.ServiceStatusID
		join ServiceCategory sc on sh.ServiceCategoryId = sc.ServiceCategoryID
		join ServiceGroup sg on sc.ServiceGroupID = sg.ServiceGroupID
		where sc.ServiceGroupID = 12 and c.CustomerID = $cName";
		// exit($sql);
				$tblTotals=0;
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{					
					$ServiceHeaderID=$rw['ServiceHeaderID'];				
					$CustomerName=$rw['CustomerName'];
					$CustomerID=$rw['CustomerID'];
					$ServiceStatusID=$rw['ServiceStatusID'];
					$ServiceName = $rw['ServiceName'];
					$ServiceStatusName = $rw['ServiceStatusName'];
					$Mobile1 = $rw['Mobile1'];
					$Email = $rw['Email'];
					if($ServiceStatusID == 6 || $ServiceStatusID == 7 || $ServiceStatusID == 11 || $ServiceStatusID == 4){
						$ServiceStatusName = $ServiceStatusName;
					}else{
						$ServiceStatusName = 'Under Review';
					}
					$tablestr.='<tr>
					<td align="left">'.$CustomerName.'</td>
					<td align="right">'.$ServiceName.'</td>
					<td align="right">'.$ServiceStatusName.'</td>
					<td align="right">'.$Mobile1.'</td>
					<td align="right">'.$Email.'</td>
					</tr>'; 
				}
				
		// echo $tablestr;
		$d_sql = "select * from Directors d left join Countries c on d.CountryId = c.Id where d.CompanyID = $CustomerID";
		// exit($d_sql);
		$d_result = sqlsrv_query($db, $d_sql);
		while($omrow = sqlsrv_fetch_array($d_result, SQLSRV_FETCH_ASSOC)){
			$FirstName = $omrow['FirstName'];
			$LastName = $omrow['LastName'];
			$IDNO = $omrow['IDNO'];
			$PhoneNumber = $omrow['PhoneNumber'];
			$Nationality = $omrow['Nationality'];
			$tablestr1.='<tr>
					<td align="left">'.$FirstName.'&nbsp;'.$LastName.'</td>
					<td align="right">'.$IDNO.'</td>
					<td align="right">'.$PhoneNumber.'</td>
					<td align="right">'.$Nationality.'</td>
					</tr>'; 
		}

		$a_sql = "select * from Attachments a 
		join Documents d on a.DocumentID = d.DocumentID 
		where a.ApplicationNo = $ServiceHeaderID";
		// exit($d_sql);
		$a_result = sqlsrv_query($db, $a_sql);
		while($omrow = sqlsrv_fetch_array($a_result, SQLSRV_FETCH_ASSOC)){
			$DocumentName = $omrow['DocumentName'];
			
			$tablestr2.='<tr>
					<td align="left">'.$DocumentName.'</td>
					</tr>'; 
		}

		$mpdf=new mPDF('win-1252','A4','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("TRA");
		$mpdf->SetAuthor("TRA");
		$mpdf->SetWatermarkText("Tourism Regulatory Authority");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
					<img src="images/logo.png" alt="TRA Logo">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo_2.png" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/>
		<style>
		table {
		  font-family: arial, sans-serif;
		  border-collapse: collapse;
		  width: 100%;
		}

		td, th {
		  border: 1px solid #dddddd;
		  text-align: left;
		  padding: 8px;
		}

		tr:nth-child(even) {
		  background-color: #dddddd;
		}
		table, th, td {
		  border: 1px solid black;
		  border-collapse: collapse;
		}
		</style>
		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td width="30%">Applicant Name</td>
		<td width="20%">Service Applied</td>
		<td width="15%">Application Status</td>
		<td width="15%">Phone Number</td>
		<td width="30%">Email Address</td>
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		
		

		</tbody>
		</table>

		<table width="100%">
		<tr>
			<td align="Center" colspan="2" style="font-size:3mm">
				<b>Directors</b>
			</td>
		</tr>		
				
	</table>
	
	</htmlpageheader>

	<htmlpagefooter name="myfooter">
	<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
	powered by      <img src="images/attain_logo_2.png" alt="County Logo">
	</div>
	</htmlpagefooter>

	<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
	<sethtmlpagefooter name="myfooter" value="on" />
	mpdf-->
	<br/><br/>
	<style>
	table {
	  font-family: arial, sans-serif;
	  border-collapse: collapse;
	  width: 100%;
	}

	td, th {
	  border: 1px solid #dddddd;
	  text-align: left;
	  padding: 8px;
	}

	tr:nth-child(even) {
	  background-color: #dddddd;
	}
	table, th, td {
	  border: 1px solid black;
	  border-collapse: collapse;
	}
	</style>
	<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
	<thead>
	<tr>
	<td width="40%">Director Name</td>
	<td width="20%">ID/Passport</td>
	<td width="20%">Phone Number</td>
	<td width="20%">Nationality</td>
	</tr>
	</thead>
	<tbody>
	
	<!-- ITEMS HERE -->'.
	
	
	$tablestr1.
									
	'<!-- END ITEMS HERE -->
	
	

	</tbody>
	</table>

	<table width="100%">
		<tr>
			<td align="Center" colspan="2" style="font-size:3mm">
				<b>Uploaded Docs</b>
			</td>
		</tr>		
				
	</table>
	
	</htmlpageheader>

	<htmlpagefooter name="myfooter">
	<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
	powered by      <img src="images/attain_logo_2.png" alt="County Logo">
	</div>
	</htmlpagefooter>

	<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
	<sethtmlpagefooter name="myfooter" value="on" />
	mpdf-->
	<br/><br/>
	<style>
	table {
	  font-family: arial, sans-serif;
	  border-collapse: collapse;
	  width: 100%;
	}

	td, th {
	  border: 1px solid #dddddd;
	  text-align: left;
	  padding: 8px;
	}

	tr:nth-child(even) {
	  background-color: #dddddd;
	}
	table, th, td {
	  border: 1px solid black;
	  border-collapse: collapse;
	}
	</style>
	<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
	<thead>
	<tr>
	<td width="40%">Document Name</td>
	
	</tr>
	</thead>
	<tbody>
	
	<!-- ITEMS HERE -->'.
	
	
	$tablestr2.
									
	'<!-- END ITEMS HERE -->
	
	

	</tbody>
	</table>

		</body>
		</html>
		';
		$mpdf->WriteHTML($html);
		
 		//$mpdf->Output();
		//exit;
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}

	function facilitation_applications($db,$cosmasRow,$rptName,$fromDate,$toDate)
	{

		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];
		
		

		$tablestr = '';
		$ReportTitle="Trade and Facilitation Applications<br> Between". $fromDate." and " .$toDate;

			$sql="select sh.ServiceHeaderID,c.CustomerName,s.ServiceName,convert(date,sh.SubmissionDate) as DateApplied,st.ServiceStatusName
			from ServiceHeader sh
			join Customer c on sh.CustomerID = c.CustomerID
			join Services s on sh.ServiceID = s.ServiceID
			join ServiceStatus st on sh.ServiceStatusID = st.ServiceStatusID
			join ServiceCategory sc on sh.ServiceCategoryId = sc.ServiceCategoryID
			join ServiceGroup sg on sc.ServiceGroupID = sg.ServiceGroupID
			where sc.ServiceGroupID = 12 and convert(date,sh.SubmissionDate)>='$fromDate' and convert(date,sh.SubmissionDate)<='$toDate'";

		// echo $sql; exit;
				// $tblTotals=0;
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{					
					$ServiceHeaderID = $rw['ServiceHeaderID'];
					$CustomerName=$rw['CustomerName'];
					$ServiceName=$rw['ServiceName'];
					$DateApplied=$rw['DateApplied'];
					$ServiceStatusName=$rw['ServiceStatusName'];
					
					$tablestr.='<tr>
					<td align="left">'.$ServiceHeaderID.'</td>
					<td align="left">'.$CustomerName.'</td>
					<td align="left">'.$ServiceName.'</td>
					<td align="left">'.$DateApplied.'</td>
					<td align="left">'.$ServiceStatusName.'</td>
					
					</tr>'; 
				}

		
		$mpdf=new mPDF('win-1252','A4','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("Tourism Regulatory Authority");
		$mpdf->SetAuthor("Tourism Regulatory Authority");
		$mpdf->SetWatermarkText("Tourism Regulatory Authority");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
				<img src="images/logo.png" alt="TRA Logo">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo_2.png" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/><br/><br/><br/><br/><br/><br/>
		
		



		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td width="5%">Application No.</td>
		<td width="25%">Customer Name</td>
		<td width="25%">Service Applied</td>
		<td width="10%">Date Applied</td>
		<td width="15%">Application Status</td>
		
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		
	

		</tbody>
		</table>
		</body>
		</html>
		';

		$mpdf->WriteHTML($html);
		
 		//$mpdf->Output();
		//exit; 
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}
	function facilitation_approved($db,$cosmasRow,$rptName,$fromDate,$toDate)
	{

		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];
		
		

		$tablestr = '';
		$ReportTitle="Trade and Facilitation Approved Applications<br> Between". $fromDate." and " .$toDate;

			$sql="select sh.ServiceHeaderID,c.CustomerName,s.ServiceName,convert(date,sh.SubmissionDate) as DateApplied,st.ServiceStatusName
			from ServiceHeader sh
			join Customer c on sh.CustomerID = c.CustomerID
			join Services s on sh.ServiceID = s.ServiceID
			join ServiceStatus st on sh.ServiceStatusID = st.ServiceStatusID
			join ServiceCategory sc on sh.ServiceCategoryId = sc.ServiceCategoryID
			join ServiceGroup sg on sc.ServiceGroupID = sg.ServiceGroupID
			where sc.ServiceGroupID = 12 and sh.ServiceStatusID = 4 and convert(date,sh.SubmissionDate)>='$fromDate' and convert(date,sh.SubmissionDate)<='$toDate'";

		// echo $sql; exit;
				// $tblTotals=0;
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{					
					$ServiceHeaderID = $rw['ServiceHeaderID'];
					$CustomerName=$rw['CustomerName'];
					$ServiceName=$rw['ServiceName'];
					$DateApplied=$rw['DateApplied'];
					$ServiceStatusName=$rw['ServiceStatusName'];
					
					$tablestr.='<tr>
					<td align="left">'.$ServiceHeaderID.'</td>
					<td align="left">'.$CustomerName.'</td>
					<td align="left">'.$ServiceName.'</td>
					<td align="left">'.$DateApplied.'</td>
					<td align="left">'.$ServiceStatusName.'</td>
					
					</tr>'; 
				}

		
		$mpdf=new mPDF('win-1252','A4','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("Tourism Regulatory Authority");
		$mpdf->SetAuthor("Tourism Regulatory Authority");
		$mpdf->SetWatermarkText("Tourism Regulatory Authority");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
				<img src="images/logo.png" alt="TRA Logo">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo_2.png" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/><br/><br/><br/><br/><br/><br/>
		
		



		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td width="5%">Application No.</td>
		<td width="25%">Customer Name</td>
		<td width="25%">Service Applied</td>
		<td width="10%">Date Applied</td>
		<td width="15%">Application Status</td>
		
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		
	

		</tbody>
		</table>
		</body>
		</html>
		';

		$mpdf->WriteHTML($html);
		
 		//$mpdf->Output();
		//exit; 
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}
	function classification_applications($db,$cosmasRow,$rptName,$fromDate,$toDate)
	{

		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];
		
		

		$tablestr = '';
		$ReportTitle="Classification and Grading Pending Applications<br> Between ". $fromDate." and " .$toDate;

			$sql="select sh.ServiceHeaderID,c.CustomerName,s.ServiceName,convert(date,sh.SubmissionDate) as DateApplied,st.ServiceStatusName
			from ServiceHeader sh
			join Customer c on sh.CustomerID = c.CustomerID
			join Services s on sh.ServiceID = s.ServiceID
			join ServiceStatus st on sh.ServiceStatusID = st.ServiceStatusID
			join ServiceCategory sc on sh.ServiceCategoryId = sc.ServiceCategoryID
			join ServiceGroup sg on sc.ServiceGroupID = sg.ServiceGroupID
			where sc.ServiceGroupID = 11 and convert(date,sh.SubmissionDate)>='$fromDate' and convert(date,sh.SubmissionDate)<='$toDate'";

		// echo $sql; exit;
				// $tblTotals=0;
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{					
					$ServiceHeaderID = $rw['ServiceHeaderID'];
					$CustomerName=$rw['CustomerName'];
					$ServiceName=$rw['ServiceName'];
					$DateApplied=$rw['DateApplied'];
					$ServiceStatusName=$rw['ServiceStatusName'];
					if($ServiceStatusID == 6 || $ServiceStatusID == 7 || $ServiceStatusID == 11 || $ServiceStatusID == 4){
						$ServiceStatusName = $ServiceStatusName;
					}else{
						$ServiceStatusName = 'Under Review';
					}
					$tablestr.='<tr>
					<td align="left">'.$ServiceHeaderID.'</td>
					<td align="left">'.$CustomerName.'</td>
					<td align="left">'.$ServiceName.'</td>
					<td align="left">'.$DateApplied.'</td>
					<td align="left">'.$ServiceStatusName.'</td>
					
					</tr>'; 
				}

		
		$mpdf=new mPDF('win-1252','A4','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("Tourism Regulatory Authority");
		$mpdf->SetAuthor("Tourism Regulatory Authority");
		$mpdf->SetWatermarkText("Tourism Regulatory Authority");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
				<img src="images/logo.png" alt="TRA Logo">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo_2.png" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/><br/><br/><br/><br/><br/><br/>
		
		



		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td width="5%">Application No.</td>
		<td width="25%">Customer Name</td>
		<td width="25%">Service Applied</td>
		<td width="10%">Date Applied</td>
		<td width="15%">Application Status</td>
		
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		
	

		</tbody>
		</table>
		</body>
		</html>
		';

		$mpdf->WriteHTML($html);
		
 		//$mpdf->Output();
		//exit; 
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}
	function classification_applicant($db,$cosmasRow,$rptName,$fromDate,$toDate,$cName)
	{
		$tablestr = '';
		$ReportTitle="Application Details";
		$sql="select sh.ServiceHeaderID,sh.ServiceStatusID,c.*,s.ServiceName,convert(date,sh.SubmissionDate) as DateApplied,st.ServiceStatusName
		from ServiceHeader sh
		join Customer c on sh.CustomerID = c.CustomerID
		join Services s on sh.ServiceID = s.ServiceID
		join ServiceStatus st on sh.ServiceStatusID = st.ServiceStatusID
		join ServiceCategory sc on sh.ServiceCategoryId = sc.ServiceCategoryID
		join ServiceGroup sg on sc.ServiceGroupID = sg.ServiceGroupID
		where sc.ServiceGroupID = 11 and c.CustomerID = $cName";
		// exit($sql);
				$tblTotals=0;
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{					
					$ServiceHeaderID=$rw['ServiceHeaderID'];				
					$CustomerName=$rw['CustomerName'];
					$CustomerID=$rw['CustomerID'];
					$ServiceStatusID=$rw['ServiceStatusID'];
					$ServiceName = $rw['ServiceName'];
					$ServiceStatusName = $rw['ServiceStatusName'];
					$Mobile1 = $rw['Mobile1'];
					$Email = $rw['Email'];
					if($ServiceStatusID == 6 || $ServiceStatusID == 7 || $ServiceStatusID == 11 || $ServiceStatusID == 4){
						$ServiceStatusName = $ServiceStatusName;
					}else{
						$ServiceStatusName = 'Under Review';
					}
					$tablestr.='<tr>
					<td align="left">'.$CustomerName.'</td>
					<td align="right">'.$ServiceName.'</td>
					<td align="right">'.$ServiceStatusName.'</td>
					<td align="right">'.$Mobile1.'</td>
					<td align="right">'.$Email.'</td>
					</tr>'; 
				}
				
		// echo $tablestr;
		$d_sql = "select * from Directors d left join Countries c on d.CountryId = c.Id where d.CompanyID = $CustomerID";
		// exit($d_sql);
		$d_result = sqlsrv_query($db, $d_sql);
		while($omrow = sqlsrv_fetch_array($d_result, SQLSRV_FETCH_ASSOC)){
			$FirstName = $omrow['FirstName'];
			$LastName = $omrow['LastName'];
			$IDNO = $omrow['IDNO'];
			$PhoneNumber = $omrow['PhoneNumber'];
			$Nationality = $omrow['Nationality'];
			$tablestr1.='<tr>
					<td align="left">'.$FirstName.'&nbsp;'.$LastName.'</td>
					<td align="right">'.$IDNO.'</td>
					<td align="right">'.$PhoneNumber.'</td>
					<td align="right">'.$Nationality.'</td>
					</tr>'; 
		}

		$a_sql = "select * from Attachments a 
		join Documents d on a.DocumentID = d.DocumentID 
		where a.ApplicationNo = $ServiceHeaderID";
		// exit($d_sql);
		$a_result = sqlsrv_query($db, $a_sql);
		while($omrow = sqlsrv_fetch_array($a_result, SQLSRV_FETCH_ASSOC)){
			$DocumentName = $omrow['DocumentName'];
			
			$tablestr2.='<tr>
					<td align="left">'.$DocumentName.'</td>
					</tr>'; 
		}

		$mpdf=new mPDF('win-1252','A4','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("TRA");
		$mpdf->SetAuthor("TRA");
		$mpdf->SetWatermarkText("Tourism Regulatory Authority");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
					<img src="images/logo.png" alt="TRA Logo">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo_2.png" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/>
		<style>
		table {
		  font-family: arial, sans-serif;
		  border-collapse: collapse;
		  width: 100%;
		}

		td, th {
		  border: 1px solid #dddddd;
		  text-align: left;
		  padding: 8px;
		}

		tr:nth-child(even) {
		  background-color: #dddddd;
		}
		table, th, td {
		  border: 1px solid black;
		  border-collapse: collapse;
		}
		</style>
		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td width="30%">Applicant Name</td>
		<td width="20%">Service Applied</td>
		<td width="15%">Application Status</td>
		<td width="15%">Phone Number</td>
		<td width="30%">Email Address</td>
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		
		

		</tbody>
		</table>

		<table width="100%">
		<tr>
			<td align="Center" colspan="2" style="font-size:3mm">
				<b>Directors</b>
			</td>
		</tr>		
				
	</table>
	
	</htmlpageheader>

	<htmlpagefooter name="myfooter">
	<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
	powered by      <img src="images/attain_logo_2.png" alt="County Logo">
	</div>
	</htmlpagefooter>

	<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
	<sethtmlpagefooter name="myfooter" value="on" />
	mpdf-->
	<br/><br/>
	<style>
	table {
	  font-family: arial, sans-serif;
	  border-collapse: collapse;
	  width: 100%;
	}

	td, th {
	  border: 1px solid #dddddd;
	  text-align: left;
	  padding: 8px;
	}

	tr:nth-child(even) {
	  background-color: #dddddd;
	}
	table, th, td {
	  border: 1px solid black;
	  border-collapse: collapse;
	}
	</style>
	<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
	<thead>
	<tr>
	<td width="40%">Director Name</td>
	<td width="20%">ID/Passport</td>
	<td width="20%">Phone Number</td>
	<td width="20%">Nationality</td>
	</tr>
	</thead>
	<tbody>
	
	<!-- ITEMS HERE -->'.
	
	
	$tablestr1.
									
	'<!-- END ITEMS HERE -->
	
	

	</tbody>
	</table>

	<table width="100%">
		<tr>
			<td align="Center" colspan="2" style="font-size:3mm">
				<b>Uploaded Docs</b>
			</td>
		</tr>		
				
	</table>
	
	</htmlpageheader>

	<htmlpagefooter name="myfooter">
	<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
	powered by      <img src="images/attain_logo_2.png" alt="County Logo">
	</div>
	</htmlpagefooter>

	<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
	<sethtmlpagefooter name="myfooter" value="on" />
	mpdf-->
	<br/><br/>
	<style>
	table {
	  font-family: arial, sans-serif;
	  border-collapse: collapse;
	  width: 100%;
	}

	td, th {
	  border: 1px solid #dddddd;
	  text-align: left;
	  padding: 8px;
	}

	tr:nth-child(even) {
	  background-color: #dddddd;
	}
	table, th, td {
	  border: 1px solid black;
	  border-collapse: collapse;
	}
	</style>
	<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
	<thead>
	<tr>
	<td width="40%">Document Name</td>
	
	</tr>
	</thead>
	<tbody>
	
	<!-- ITEMS HERE -->'.
	
	
	$tablestr2.
									
	'<!-- END ITEMS HERE -->
	
	

	</tbody>
	</table>

		</body>
		</html>
		';
		$mpdf->WriteHTML($html);
		
 		//$mpdf->Output();
		//exit;
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}

	function licence_applicant($db,$cosmasRow,$rptName,$fromDate,$toDate,$cName)
	{
		$tablestr = '';
		$ReportTitle="Application Details";
		$sql="select sh.ServiceHeaderID,sh.ServiceStatusID,c.*,s.ServiceName,convert(date,sh.SubmissionDate) as DateApplied,st.ServiceStatusName
		from ServiceHeader sh
		join Customer c on sh.CustomerID = c.CustomerID
		join Services s on sh.ServiceID = s.ServiceID
		join ServiceStatus st on sh.ServiceStatusID = st.ServiceStatusID
		join ServiceCategory sc on sh.ServiceCategoryId = sc.ServiceCategoryID
		join ServiceGroup sg on sc.ServiceGroupID = sg.ServiceGroupID
		where c.CustomerID = $cName";
		// exit($sql);
				$tblTotals=0;
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{					
					$ServiceHeaderID=$rw['ServiceHeaderID'];				
					$CustomerName=$rw['CustomerName'];
					$CustomerID=$rw['CustomerID'];
					$ServiceStatusID=$rw['ServiceStatusID'];
					$ServiceName = $rw['ServiceName'];
					$ServiceStatusName = $rw['ServiceStatusName'];
					$Mobile1 = $rw['Mobile1'];
					$Email = $rw['Email'];
					if($ServiceStatusID == 6 || $ServiceStatusID == 7 || $ServiceStatusID == 11 || $ServiceStatusID == 4){
						$ServiceStatusName = $ServiceStatusName;
					}else{
						$ServiceStatusName = 'Under Review';
					}
					$tablestr.='<tr>
					<td align="left">'.$CustomerName.'</td>
					<td align="right">'.$ServiceName.'</td>
					<td align="right">'.$ServiceStatusName.'</td>
					<td align="right">'.$Mobile1.'</td>
					<td align="right">'.$Email.'</td>
					</tr>'; 
				}
				
		// echo $tablestr;
		$d_sql = "select * from Directors d left join Countries c on d.CountryId = c.Id where d.CompanyID = $CustomerID";
		// exit($d_sql);
		$d_result = sqlsrv_query($db, $d_sql);
		while($omrow = sqlsrv_fetch_array($d_result, SQLSRV_FETCH_ASSOC)){
			$FirstName = $omrow['FirstName'];
			$LastName = $omrow['LastName'];
			$IDNO = $omrow['IDNO'];
			$PhoneNumber = $omrow['PhoneNumber'];
			$Nationality = $omrow['Nationality'];
			$tablestr1.='<tr>
					<td align="left">'.$FirstName.'&nbsp;'.$LastName.'</td>
					<td align="right">'.$IDNO.'</td>
					<td align="right">'.$PhoneNumber.'</td>
					<td align="right">'.$Nationality.'</td>
					</tr>'; 
		}

		$a_sql = "select * from Attachments a 
		join Documents d on a.DocumentID = d.DocumentID 
		where a.ApplicationNo = $ServiceHeaderID";
		// exit($d_sql);
		$a_result = sqlsrv_query($db, $a_sql);
		while($omrow = sqlsrv_fetch_array($a_result, SQLSRV_FETCH_ASSOC)){
			$DocumentName = $omrow['DocumentName'];
			
			$tablestr2.='<tr>
					<td align="left">'.$DocumentName.'</td>
					</tr>'; 
		}

		$mpdf=new mPDF('win-1252','A4','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("TRA");
		$mpdf->SetAuthor("TRA");
		$mpdf->SetWatermarkText("Tourism Regulatory Authority");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
					<img src="images/logo.png" alt="TRA Logo">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo_2.png" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/>
		<style>
		table {
		  font-family: arial, sans-serif;
		  border-collapse: collapse;
		  width: 100%;
		}

		td, th {
		  border: 1px solid #dddddd;
		  text-align: left;
		  padding: 8px;
		}

		tr:nth-child(even) {
		  background-color: #dddddd;
		}
		table, th, td {
		  border: 1px solid black;
		  border-collapse: collapse;
		}
		</style>
		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td width="30%">Applicant Name</td>
		<td width="20%">Service Applied</td>
		<td width="15%">Application Status</td>
		<td width="15%">Phone Number</td>
		<td width="30%">Email Address</td>
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		
		

		</tbody>
		</table>

		<table width="100%">
		<tr>
			<td align="Center" colspan="2" style="font-size:3mm">
				<b>Directors</b>
			</td>
		</tr>		
				
	</table>
	
	</htmlpageheader>

	<htmlpagefooter name="myfooter">
	<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
	powered by      <img src="images/attain_logo_2.png" alt="County Logo">
	</div>
	</htmlpagefooter>

	<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
	<sethtmlpagefooter name="myfooter" value="on" />
	mpdf-->
	<br/><br/>
	<style>
	table {
	  font-family: arial, sans-serif;
	  border-collapse: collapse;
	  width: 100%;
	}

	td, th {
	  border: 1px solid #dddddd;
	  text-align: left;
	  padding: 8px;
	}

	tr:nth-child(even) {
	  background-color: #dddddd;
	}
	table, th, td {
	  border: 1px solid black;
	  border-collapse: collapse;
	}
	</style>
	<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
	<thead>
	<tr>
	<td width="40%">Director Name</td>
	<td width="20%">ID/Passport</td>
	<td width="20%">Phone Number</td>
	<td width="20%">Nationality</td>
	</tr>
	</thead>
	<tbody>
	
	<!-- ITEMS HERE -->'.
	
	
	$tablestr1.
									
	'<!-- END ITEMS HERE -->
	
	

	</tbody>
	</table>

	<table width="100%">
		<tr>
			<td align="Center" colspan="2" style="font-size:3mm">
				<b>Uploaded Docs</b>
			</td>
		</tr>		
				
	</table>
	
	</htmlpageheader>

	<htmlpagefooter name="myfooter">
	<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
	powered by      <img src="images/attain_logo_2.png" alt="County Logo">
	</div>
	</htmlpagefooter>

	<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
	<sethtmlpagefooter name="myfooter" value="on" />
	mpdf-->
	<br/><br/>
	<style>
	table {
	  font-family: arial, sans-serif;
	  border-collapse: collapse;
	  width: 100%;
	}

	td, th {
	  border: 1px solid #dddddd;
	  text-align: left;
	  padding: 8px;
	}

	tr:nth-child(even) {
	  background-color: #dddddd;
	}
	table, th, td {
	  border: 1px solid black;
	  border-collapse: collapse;
	}
	</style>
	<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
	<thead>
	<tr>
	<td width="40%">Document Name</td>
	
	</tr>
	</thead>
	<tbody>
	
	<!-- ITEMS HERE -->'.
	
	
	$tablestr2.
									
	'<!-- END ITEMS HERE -->
	
	

	</tbody>
	</table>

		</body>
		</html>
		';
		$mpdf->WriteHTML($html);
		
 		//$mpdf->Output();
		//exit;
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}


	function licence_applications($db,$cosmasRow,$rptName,$fromDate,$toDate)
	{

		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];
		
		

		$tablestr = '';
		$ReportTitle="Licence Applications<br> Between <br>". $fromDate." and " .$toDate;

			$sql="select sh.ServiceHeaderID,c.CustomerName,s.ServiceName,convert(date,sh.SubmissionDate) as DateApplied,st.ServiceStatusName
			from ServiceHeader sh
			join Customer c on sh.CustomerID = c.CustomerID
			join Services s on sh.ServiceID = s.ServiceID
			join ServiceStatus st on sh.ServiceStatusID = st.ServiceStatusID
			join ServiceCategory sc on sh.ServiceCategoryId = sc.ServiceCategoryID
			join ServiceGroup sg on sc.ServiceGroupID = sg.ServiceGroupID
			where convert(date,sh.SubmissionDate)>='$fromDate' and convert(date,sh.SubmissionDate)<='$toDate' and sh.ServiceStatusID !=4 and (sc.ServiceGroupID != 12 and sc.ServiceGroupID != 11)";

		// echo $sql; exit;
				// $tblTotals=0;
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{					
					$ServiceHeaderID = $rw['ServiceHeaderID'];
					$CustomerName=$rw['CustomerName'];
					$ServiceName=$rw['ServiceName'];
					$DateApplied=$rw['DateApplied'];
					$ServiceStatusName=$rw['ServiceStatusName'];
					
					$tablestr.='<tr>
					<td align="left">'.$ServiceHeaderID.'</td>
					<td align="left">'.$CustomerName.'</td>
					<td align="left">'.$ServiceName.'</td>
					<td align="left">'.$DateApplied.'</td>
					<td align="left">'.$ServiceStatusName.'</td>
					
					</tr>'; 
				}

		
		$mpdf=new mPDF('win-1252','A4','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("Tourism Regulatory Authority");
		$mpdf->SetAuthor("Tourism Regulatory Authority");
		$mpdf->SetWatermarkText("Tourism Regulatory Authority");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
				<img src="images/logo.png" alt="TRA Logo">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo_2.png" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/><br/><br/><br/><br/><br/><br/>
		
		



		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td width="5%">Application No.</td>
		<td width="25%">Customer Name</td>
		<td width="25%">Service Applied</td>
		<td width="10%">Date Applied</td>
		<td width="15%">Application Status</td>
		
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		
	

		</tbody>
		</table>
		</body>
		</html>
		';

		$mpdf->WriteHTML($html);
		
 		//$mpdf->Output();
		//exit; 
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}

	function licenced($db,$cosmasRow,$rptName,$fromDate,$toDate)
	{

		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];
		
		

		$tablestr = '';
		$ReportTitle="Licenced Applications<br> Between <br>". $fromDate." and " .$toDate;

			$sql="select sh.ServiceHeaderID,c.CustomerName,s.ServiceName,convert(date,sh.SubmissionDate) as DateApplied,st.ServiceStatusName
			from ServiceHeader sh
			join Customer c on sh.CustomerID = c.CustomerID
			join Services s on sh.ServiceID = s.ServiceID
			join ServiceStatus st on sh.ServiceStatusID = st.ServiceStatusID
			join ServiceCategory sc on sh.ServiceCategoryId = sc.ServiceCategoryID
			join ServiceGroup sg on sc.ServiceGroupID = sg.ServiceGroupID
			where convert(date,sh.SubmissionDate)>='$fromDate' and convert(date,sh.SubmissionDate)<='$toDate' and sh.ServiceStatusID =4 and (sc.ServiceGroupID != 12 and sc.ServiceGroupID != 11)";

		// echo $sql; exit;
				// $tblTotals=0;
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{					
					$ServiceHeaderID = $rw['ServiceHeaderID'];
					$CustomerName=$rw['CustomerName'];
					$ServiceName=$rw['ServiceName'];
					$DateApplied=$rw['DateApplied'];
					$ServiceStatusName=$rw['ServiceStatusName'];
					
					$tablestr.='<tr>
					<td align="left">'.$ServiceHeaderID.'</td>
					<td align="left">'.$CustomerName.'</td>
					<td align="left">'.$ServiceName.'</td>
					<td align="left">'.$DateApplied.'</td>
					<td align="left">'.$ServiceStatusName.'</td>
					
					</tr>'; 
				}

		
		$mpdf=new mPDF('win-1252','A4','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("Tourism Regulatory Authority");
		$mpdf->SetAuthor("Tourism Regulatory Authority");
		$mpdf->SetWatermarkText("Tourism Regulatory Authority");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
				<img src="images/logo.png" alt="TRA Logo">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo_2.png" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/><br/><br/><br/><br/><br/><br/>
		
		



		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td width="5%">Application No.</td>
		<td width="25%">Customer Name</td>
		<td width="25%">Service Applied</td>
		<td width="10%">Date Applied</td>
		<td width="15%">Application Status</td>
		
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		
	

		</tbody>
		</table>
		</body>
		</html>
		';

		$mpdf->WriteHTML($html);
		
 		//$mpdf->Output();
		//exit; 
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}

	function all_licenced($db,$cosmasRow,$rptName,$fromDate,$toDate)
	{

		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];
		
		

		$tablestr = '';
		$ReportTitle="All Licenced Establishments";

			$sql="select sh.ServiceHeaderID,c.CustomerName,s.ServiceName,convert(date,sh.SubmissionDate) as DateApplied,st.ServiceStatusName
			from ServiceHeader sh
			join Customer c on sh.CustomerID = c.CustomerID
			join Services s on sh.ServiceID = s.ServiceID
			join ServiceStatus st on sh.ServiceStatusID = st.ServiceStatusID
			join ServiceCategory sc on sh.ServiceCategoryId = sc.ServiceCategoryID
			join ServiceGroup sg on sc.ServiceGroupID = sg.ServiceGroupID
			where sh.ServiceStatusID =4 and (sc.ServiceGroupID != 12 and sc.ServiceGroupID != 11)";

		// echo $sql; exit;
				// $tblTotals=0;
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{					
					$ServiceHeaderID = $rw['ServiceHeaderID'];
					$CustomerName=$rw['CustomerName'];
					$ServiceName=$rw['ServiceName'];
					$DateApplied=$rw['DateApplied'];
					$ServiceStatusName=$rw['ServiceStatusName'];
					
					$tablestr.='<tr>
					<td align="left">'.$ServiceHeaderID.'</td>
					<td align="left">'.$CustomerName.'</td>
					<td align="left">'.$ServiceName.'</td>
					<td align="left">'.$DateApplied.'</td>
					<td align="left">'.$ServiceStatusName.'</td>
					
					</tr>'; 
				}

		
		$mpdf=new mPDF('win-1252','A4','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("Tourism Regulatory Authority");
		$mpdf->SetAuthor("Tourism Regulatory Authority");
		$mpdf->SetWatermarkText("Tourism Regulatory Authority");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
				<img src="images/logo.png" alt="TRA Logo">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo_2.png" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/><br/><br/><br/><br/><br/><br/>
		
		



		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td width="5%">Application No.</td>
		<td width="25%">Customer Name</td>
		<td width="25%">Service Applied</td>
		<td width="10%">Date Applied</td>
		<td width="15%">Application Status</td>
		
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		
	

		</tbody>
		</table>
		</body>
		</html>
		';

		$mpdf->WriteHTML($html);
		
 		//$mpdf->Output();
		//exit; 
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}
	

	function mpesaTransactions($db,$cosmasRow,$rptName,$fromDate,$toDate)
	{

		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];
		
		

		$tablestr = '';
		$ReportTitle="MPESA TRANSACTIONS <br> Between". $fromDate." and " .$toDate;
		$sql="set dateformat dmy
				select mpesa_code,mpesa_acc,mpesa_amt,mpesa_sender,tstamp,mpesa_msisdn PhoneNo 
				from mpesa 
				where cast(tstamp as date)>=convert(date,'$fromDate') and cast(tstamp as date)<=convert(date,'$toDate') ";

				$tblTotals=0;
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{					
					$mpesa_sender = $rw['mpesa_sender'];
					$mpesa_date=$rw['tstamp'];
					$mpesa_phoneno=$rw['PhoneNo'];
					$mpesa_amt=$rw['mpesa_amt'];
					$mpesa_code=$rw['mpesa_code'];
					$mpesa_acc = $rw['mpesa_acc'];					
					$tblTotals+=$mpesa_amt;
					$tablestr.='<tr>
					<td align="left">'.$mpesa_sender.'</td>
					<td align="left">'.$mpesa_date.'</td>
					<td align="left">'.$mpesa_phoneno.'</td>
					<td align="left">'.$mpesa_code.'</td>
					<td align="left">'.$mpesa_acc.'</td>
					<td align="right">'.number_format($mpesa_amt,2).'</td>
					</tr>'; 
				}

		
		$mpdf=new mPDF('win-1252','A4','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("Tourism Regulatory Authority");
		$mpdf->SetAuthor("Tourism Regulatory Authority");
		$mpdf->SetWatermarkText("Tourism Regulatory Authority");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
					<img src="images/CountyLogo_New.png" alt="County Logo">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo_2.png" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/><br/><br/><br/><br/><br/><br/>
		
		



		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td width="25%">Mpesa Sender</td>
		<td width="15%">Transaction Date</td>
		<td width="15%">Phone No</td>
		<td width="15%">Mpesa Code</td>
		<td width="15%">Mpesa Account</td>
		<td width="15%">AMOUNT</td>
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		
		<tr>
		<td class="blanktotal" colspan="4" rowspan="6"></td>
		<td class="totals">Totals:</td>
		<td class="totals">'.number_format($tblTotals,2).'</td>
		</tr>

		</tbody>
		</table>
		</body>
		</html>
		';

		$mpdf->WriteHTML($html);
		
 		//$mpdf->Output();
		//exit; 
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}
	function posTransactions($db,$cosmasRow,$rptName,$fromDate,$toDate,$AgentID)
	{
		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];
		
		$subTotal=0;
		$subTotalRow='';
		$GroupBy='';
		$GroupName1='';
		

		$tablestr = '';
		$ReportTitle="POS COLLECTIONS BETWEEN $fromDate TO $toDate";
		$sql="set dateformat dmy select il.createdby AgentID,ag.FirstName+' '+ag.MiddleName+' '+ag.LastName AgentNames,mk.MarketName,isnull(s.ServiceID,0) ServiceID,isnull(s.ServiceName,'Void')ServiceName, isnull(sum(il.Amount),0) Amount 
				from InvoiceLines il
				join Agents ag on il.CreatedBy=ag.AgentID
				join Markets mk on il.MarketID=mk.MarketID
				join ServiceHeader sh on il.ServiceHeaderID=sh.ServiceHeaderID and il.ServiceID=sh.ServiceID
				left join services s on sh.ServiceID=s.ServiceID
				where PosReceiptID<>'' and convert(date,left(posreceiptid,6))='$fromDate' and convert(date,left(posreceiptid,6))<='$toDate' 
				group by il.CreatedBy,ag.FirstName+' '+ag.MiddleName+' '+ag.LastName,mk.MarketName,s.ServiceName,s.ServiceID
				order by s.ServiceID,mk.MarketName,sum(il.Amount)";
				//echo $sql;
				$tblTotals=0;
				$result=sqlsrv_query($db, $sql);
				$ServiceID='';
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{					
					$AgentID = $rw['AgentID'];
					$ServiceID=$rw['ServiceID'];					
					$AgentNames=$rw['AgentNames'];
					$MarketName=$rw['MarketName'];
					$ServiceName=$rw['ServiceName'];
					$Amount = $rw['Amount'];					
					$tblTotals+=$Amount;
					
					/*$subTotal+=$Amount;
					//echo $GroupBy1;
					 if ($GroupBy!=$ServiceID and $GroupBy!='')
					{
						$subTotal-=$Amount;
						$subTotalRow.='<tr>
						<td align="left" colspan="2">'.$GroupBy.'</td>	
						<td align="left" class="totals">'.number_format($subTotal,2).'</td>						
						<td align="right"></td>
					</tr>';
						$subTotal=$Amount;
					} */
					
					$tablestr.='<tr>
					<td align="left">'.$AgentID.'</td>
					<td align="left">'.$AgentNames.'</td>
					<td align="left">'.$MarketName.'</td>
					<td align="left">'.$ServiceName.'</td>
					<td align="right">'.number_format($Amount,2).'</td>
					</tr>'; 
					
					/* if (!$subTotalRow==''){
						$subTotalRow.=$tablestr;
						$tablestr=$subTotalRow;
					} 
					$GroupBy='3';
					$GroupName1='g';*/
					/* echo ','.$k;
					$k+=1; */
					//exit;
			
					//$GroupBy=$ServiceID;
					//$GroupName1=$ServiceName;					
					
				}

		
		$mpdf=new mPDF('win-1252','A4','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("Tourism Regulatory Authority");
		$mpdf->SetAuthor("Tourism Regulatory Authority");
		$mpdf->SetWatermarkText("Tourism Regulatory Authority");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
					<img src="images/CountyLogo_New.png" alt="County Logo">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo_2.png" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/><br/><br/><br/><br/><br/><br/>
		
		



		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td width="15%">Agent ID</td>
		<td width="25%">Agent Name</td>
		<td width="20%">Market</td>
		<td width="25%">Service name</td>
		<td width="15%">AMOUNT</td>
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		
		<tr>
		<td class="blanktotal" colspan="2" rowspan="6"></td>
		<td class="totals">Totals:</td>
		<td class="totals">'.number_format($tblTotals,2).'</td>
		</tr>

		</tbody>
		</table>
		</body>
		</html>
		';

		$mpdf->WriteHTML($html);
		
 		// $mpdf->Output();
		//exit; 
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}
	function rentBalances($db,$cosmasRow,$rptName)
	{

		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];
		
		

		$tablestr = '';
		$ReportTitle="RENT BALANCES";
		$sql="SELECt top 500 h.HouseNumber,es.EstateName,tn.MonthlyRent,tn.Balance,tn.CurrentTenant [Tenant],
			(select top 1 iif([Description]='Monthly Rent',DocumentNo,[Description])LastBillNumber from HouseReceipts 
			where EstateID=h.EstateID and HouseNumber=h.HouseNumber and ([Description]='Monthly Rent' or [Description] like 'Bill%')
			order by DateReceived desc) LastBillNumber
				FROM Houses h 
				join Tenancy tn on tn.UHN=h.UHN	
				join Estates es on h.EstateID=es.EstateID	
				
				
				order by  (select top 1 iif([Description]='Monthly Rent',DocumentNo,[Description])LastBillNumber from HouseReceipts 
			where EstateID=h.EstateID and HouseNumber=h.HouseNumber and ([Description]='Monthly Rent' or [Description] like 'Bill%')
			order by DateReceived desc) desc
				,h.EstateID,h.HouseNumber";
		
				$tblTotals=0;
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{					
					$EstateName = $rw['EstateName'];					
					$HouseNumber=$rw['HouseNumber'];
					$MonthlyRent=$rw['MonthlyRent'];
					$Balance = $rw['Balance'];		
					$Tenant=$rw['Tenant'];
					$LastBillNumber = $rw['LastBillNumber'];						
					$tblTotals+=$Balance;
					$tablestr.='<tr>
					<td align="left">'.$EstateName.'</td>
					<td align="left">'.$HouseNumber.'</td>
					<td align="left">'.$Tenant.'</td>
					<td align="right">'.$MonthlyRent.'</td>
					<td align="left">'.$LastBillNumber.'</td>					
					<td align="right">'.number_format($Balance,2).'</td>
					</tr>'; 
				}
				
		//echo $tablestr;

		$mpdf=new mPDF('win-1252','A4','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("Tourism Regulatory Authority");
		$mpdf->SetAuthor("Tourism Regulatory Authority");
		$mpdf->SetWatermarkText("Tourism Regulatory Authority");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
					<img src="images/CountyLogo_New.png" alt="County Logo">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo_2.png" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/><br/><br/><br/><br/><br/><br/>
		
		



		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td width="20%">Estate</td>
		<td width="15%">House Number</td>
		<td width="20%">Tenant</td>
		<td width="15%">Month Rent</td>
		<td width="15%">Last Bill</td>
		<td width="15%">Balance</td>
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		
		<tr>
		<td class="blanktotal" colspan="4" rowspan="6"></td>
		<td class="totals">Totals:</td>
		<td class="totals">'.number_format($tblTotals,2).'</td>
		</tr>

		</tbody>
		</table>
		</body>
		</html>
		';
		$mpdf->WriteHTML($html);
		
 		//$mpdf->Output();
		//exit;
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}
	function ReceiptsToday($db,$cosmasRow,$rptName)
	{

		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];		

		$tablestr = '';
		$subTotalRow='';
		$ReportTitle='RECEIPTS TODAY';
		$sql="select distinct * from 
			(select   convert(date,r.ReceiptDate)ReceiptDate,rm.ReceiptMethodName,r.ReferenceNumber,
			c.CustomerName,s.ServiceName,isnull(b.BankName,rm.ReceiptMethodName) BankName,r.Amount
			from Receipts r 
			left join ReceiptMethod rm on r.ReceiptMethodID=rm.ReceiptMethodID
			left join banks b on r.BankID=b.BankID
			join ReceiptLines rl on rl.ReceiptID=r.ReceiptID
			left join InvoiceHeader ih on rl.InvoiceHeaderID=ih.InvoiceHeaderID
			join Customer c on ih.CustomerID=c.CustomerID
			left join InvoiceLines il on il.InvoiceHeaderID=ih.InvoiceHeaderID and rl.InvoiceHeaderID=il.InvoiceHeaderID
			join ServiceHeader sh on il.ServiceHeaderID=sh.ServiceHeaderID
			join services s on sh.ServiceID=s.ServiceID and il.ServiceID=sh.ServiceID

			where convert(date,r.ReceiptDate)=convert(date,getdate())
			) k
			order by k.Bankname, k.ReceiptDate";
		//$sql="select 1 ReceiptDate,2 ReceiptMethodName,3 ReferenceNumber,4 CustomerName,5 BankName,6 Amount";
			
				$tblTotals=0;
				$subTotal=0;
				$PrevRMethod='';
				$ReceiptMethod='';
				$pBankName='';
				$result=sqlsrv_query($db, $sql);
				
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{	
					$pBankName='';						
					$ptblTotals+=0;
					
					
					$ReceiptDate = $rw['ReceiptDate'];					
					$ReceiptMethod=$rw['ReceiptMethodName'];
					$ReferenceNumber=$rw['ReferenceNumber'];
					$CustomerName=$rw['CustomerName'];
					$ServiceName=$rw['ServiceName'];
					$Amount = $rw['Amount'];		
					$BankName=$rw['BankName'];						
					$tblTotals+=$Amount;
					$subTotal+=$Amount;
					if ($PrevRMethod!=$BankName and $PrevRMethod!=''){
						$subTotal-=$Amount;
						$subTotalRow.='<tr>
						<td align="left" colspan="5">'.$pBankName.'</td>	
						<td align="left" class="totals">'.number_format($subTotal,2).'</td>						
						<td align="right"></td>
					</tr>';
						$subTotal=$Amount;
					}
					
					$tablestr.='<tr>
					<td align="left">'.$ReceiptDate.'</td>
					<td align="left">'.$ReceiptMethod.'</td>
					<td align="left">'.$ReferenceNumber.'</td>
					<td align="right">'.$CustomerName.'</td>
					<td align="right">'.$ServiceName.'</td>
					<td align="left">'.$BankName.'</td>	
					<td align="left">'.$Amount.'</td>						
					<td align="right">'.number_format($tblTotals,2).'</td>
					</tr>'; 
					
					
					if (!$subTotalRow==''){
						$subTotalRow.=$tablestr;
						$tablestr=$subTotalRow;
					}
			
					$pBankName=$BankName;						
					$ptblTotals+=$tblTotals;					
					$PrevRMethod=$BankName;
				}
				
		$mpdf=new mPDF('win-1252','A4-L','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("Tourism Regulatory Authority");
		$mpdf->SetAuthor("Tourism Regulatory Authority");
		$mpdf->SetWatermarkText("Tourism Regulatory Authority");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
					<img src="images/CountyLogo_New.png" alt="County Logo">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo_2.png" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/><br/><br/><br/><br/><br/><br/>
		
		



		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td width="10%">Receipt Date</td>
		<td width="10%">Receipt Method</td>
		<td width="10%">Reference Number</td>
		<td width="15%">Customer Name</td>
		<td width="15%">Service Name</td>
		<td width="15%">Bank Name</td>
		<td width="10%">Amount</td>
		<td width="15%">Totals</td>
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		
		<tr>
		<td class="blanktotal" colspan="4" rowspan="6"></td>
		<td class="totals">Totals:</td>
		<td class="totals">'.number_format($tblTotals,2).'</td>
		<td class="totals"></td>
		</tr>

		</tbody>
		</table>
		</body>
		</html>
		';
	
		$mpdf->WriteHTML($html);
		
 		//$mpdf->Output();
		//exit;
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}
	function NewEstablishment($db,$cosmasRow,$rptName,$fromDate,$toDate)
	{

		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];
		
		

		$tablestr = '';
		$ReportTitle="Registered Establishments <br>Between<br>$fromDate and $toDate";
		$sql="set dateformat dmy 
			select convert(date,CreatedDate) EstablishmentDate,UPPER(CustomerName)CustomerName,ContactPerson,
			Type,Mobile1,Email from Customer
			where convert(date,CreatedDate)>='$fromDate' and convert(date,CreatedDate)<='$toDate'
			order by CustomerID desc";
		// exit($sql);
				$tblTotals=0;
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{					
					$EstablishmentDate = $rw['EstablishmentDate'];					
					$CustomerName=$rw['CustomerName'];
					$ContactPerson = $rw['ContactPerson'];						
					$Type = $rw['Type'];
					$Mobile1 = $rw['Mobile1'];
					$Email = $rw['Email'];
					$tablestr.='<tr>
					<td align="left">'.$EstablishmentDate.'</td>
					<td align="left">'.$CustomerName.'</td>
					<td align="right">'.$ContactPerson.'</td>					
					<td align="right">'.$Type.'</td>
					<td align="right">'.$Mobile1.'</td>
					<td align="right">'.$Email.'</td>
					</tr>'; 
				}
				
		//echo $tablestr;

		$mpdf=new mPDF('win-1252','A4','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("Tourism Regulatory Authority");
		$mpdf->SetAuthor("Tourism Regulatory Authority");
		$mpdf->SetWatermarkText("Tourism Regulatory Authority");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
					<img src="images/logo.png" alt="TRA Logo">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo_2.png" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
		<style>
		table {
		  font-family: arial, sans-serif;
		  border-collapse: collapse;
		  width: 100%;
		}

		td, th {
		  border: 1px solid #dddddd;
		  text-align: left;
		  padding: 8px;
		}

		tr:nth-child(even) {
		  background-color: #dddddd;
		}
		table, th, td {
		  border: 1px solid black;
		  border-collapse: collapse;
		}
		</style>
		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td width="20%">Registration Date</td>
		<td width="40%">Establishment Name</td>
		<td width="20%">Contact Person</td>
		<td width="20%">Type</td>
		<td width="20%">Phone Number</td>
		<td width="20%">Email Address</td>
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		
		

		</tbody>
		</table>
		</body>
		</html>
		';
		$mpdf->WriteHTML($html);
		
 		//$mpdf->Output();
		//exit;
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}


function LicenceExpiryNotification($db,$cosmasRow,$rptName,$fromDate,$toDate)
	{

		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];
		
		

		$tablestr = '';
		$ReportTitle="Licence Expiry Notification";
		$sql="set dateformat dmy
			Select DISTINCT c.CustomerID,c.CustomerName,s.ServiceName,sc.CategoryName,sh.PermitNo,
				sh.IssuedDate,sh.ExpiryDate,sh.CreatedDate
				from ServiceHeader as sh join Customer as c
				on c.CustomerID = sh.CustomerID join Services as s on s.ServiceID = sh.ServiceID
				join ServiceCategory as sc on sc.ServiceCategoryID = sh.ServiceCategoryId 
				where convert(date,sh.CreatedDate)>='$fromDate' and convert(date,sh.CreatedDate)<='$toDate' and 
				sh.ServiceCategoryId != 2033
				order by CustomerID desc
				";
				// exit($sql);
		// where convert(date,CreatedDate)=convert(date,getdate())
				$tblTotals=0;
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{					
										
					$CustomerName=$rw['CustomerName'];
					$ServiceName = $rw['ServiceName'];						
					$PermitNo = $rw['PermitNo'];
					$IssuedDate = $rw['IssuedDate'];
					$ExpiryDate = $rw['ExpiryDate'];
					$tablestr.='<tr>
					
					<td align="left">'.$CustomerName.'</td>
					<td align="right">'.$ServiceName.'</td>					
					<td align="right">'.$PermitNo.'</td>
					<td align="right">'.$IssuedDate.'</td>
					<td align="right">'.$ExpiryDate.'</td>
					</tr>'; 
				}
				
		//echo $tablestr;

		$mpdf=new mPDF('win-1252','A4','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("Tourism Regulatory Authority");
		$mpdf->SetAuthor("Tourism Regulatory Authority");
		$mpdf->SetWatermarkText("Tourism Regulatory Authority");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
					<img src="images/logo.png" alt="TRA Logo">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo_2.png" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/><br/><br/><br/><br/>
		

				<style>
		table {
		  font-family: arial, sans-serif;
		  border-collapse: collapse;
		  width: 100%;
		}

		td, th {
		  border: 1px solid #dddddd;
		  text-align: left;
		  padding: 8px;
		}

		tr:nth-child(even) {
		  background-color: #dddddd;
		}
		table, th, td {
		  border: 1px solid black;
		  border-collapse: collapse;
		}
		</style>

		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td width="40%">Customer Name</td>
		<td width="60%">Licence Name</td>
		<td width="20%">Permit Number</td>
		<td width="20%">Issued Date</td>
		<td width="20%">Expiry Date</td>
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		
		

		</tbody>
		</table>
		</body>
		</html>
		';
		$mpdf->WriteHTML($html);
		
 		//$mpdf->Output();
		//exit;
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}

	function ReceiptsToday_Service($db,$cosmasRow,$rptName)
	{

		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];
		
		

		$tablestr = '';
		$ReportTitle="RECEIPTS TODAY PER SERVICE";
		$sql="	SELECT convert(date,r.CreatedDate)ReceiptDate,s.ServiceName,sum(r.Amount) Amount
				FROM dbo.ReceiptLines AS r INNER JOIN
				dbo.InvoiceHeader AS ih ON r.InvoiceHeaderID = ih.InvoiceHeaderID INNER JOIN
					(SELECT DISTINCT InvoiceHeaderID, ServiceHeaderID
					FROM            dbo.InvoiceLines) AS il ON il.InvoiceHeaderID = ih.InvoiceHeaderID INNER JOIN
				dbo.ServiceHeader AS sh ON il.ServiceHeaderID = sh.ServiceHeaderID INNER JOIN
				dbo.Services AS s ON sh.ServiceID = s.ServiceID INNER JOIN
				dbo.ServiceCategory AS sc ON s.ServiceCategoryID = sc.ServiceCategoryID INNER JOIN
				dbo.ServiceGroup AS sg ON sc.ServiceGroupID = sg.ServiceGroupID

				where convert(date,r.CreatedDate)=convert(date,getdate())
				group by s.ServiceName,convert(date,r.CreatedDate)";
		
				$tblTotals=0;
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{					
					$ReceiptDate = $rw['ReceiptDate'];					
					$ServiceName=$rw['ServiceName'];
					$Amount = $rw['Amount'];						
					$tblTotals+=$Amount;
					$tablestr.='<tr>
					<td align="left">'.$ReceiptDate.'</td>
					<td align="left">'.$ServiceName.'</td>
					<td align="right">'.number_format($Amount,2).'</td>					
					<td align="right">'.number_format($tblTotals,2).'</td>
					</tr>'; 
				}
				
		//echo $tablestr;

		$mpdf=new mPDF('win-1252','A4','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("Tourism Regulatory Authority");
		$mpdf->SetAuthor("Tourism Regulatory Authority");
		$mpdf->SetWatermarkText("Tourism Regulatory Authority");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
					<img src="images/CountyLogo_New.png" alt="County Logo">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo_2.png" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/><br/><br/><br/><br/><br/><br/>
		
		



		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td width="20%">Receipt Date</td>
		<td width="40%">Service Name</td>
		<td width="20%">Amount</td>
		<td width="20%">Totals</td>
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		
		<tr>
		<td class="blanktotal" rowspan="6"></td>
		<td class="totals">Totals:</td>
		<td class="totals">'.number_format($tblTotals,2).'</td>
		<td class="totals"></td>
		</tr>

		</tbody>
		</table>
		</body>
		</html>
		';
		$mpdf->WriteHTML($html);
		
 		//$mpdf->Output();
		//exit;
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}
	
	function PermitsList($db,$cosmasRow,$rptName,$fromDate,$toDate)
	{

		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];	
		

		$tablestr = '';
		$ReportTitle="Permits Issued <br>Between<br>$fromDate and $toDate";
		$sql="set dateformat dmy 
			select distinct p.PermitNo,p.ServiceHeaderID,convert(date,p.IssueDate) IssueDate,UPPER(c.CustomerName)CustomerName,
			convert(nvarchar(10),s.ServiceCode)+'-'+s.ServiceName ServiceName,il.Amount,ag.AgentID,
			ag.FirstName+' '+ag.MiddleName+' '+ag.LastName IssuedBy,
			(select top 1 value from fnFormData(sh.ServiceHeaderID) where FormColumnID=5) BusinessActivity,
			s.ServiceCode [Service Code],
			(select sc.SubCountyName from fnFormData(sh.ServiceHeaderID) fn 
					join SubCounty sc on fn.Value=sc.SubCountyID
					where fn.formcolumnid=11203)SubCounty,
			(select w.WardName from fnFormData(p.ServiceHeaderID) fn 
					join Wards w on fn.Value=w.WardID
					where fn.formcolumnid=11204) Ward,
			'Building: '+isnull((select distinct Value  from fnFormData (sh.ServiceHeaderID) where formcolumnid=13288),'')+
			', Floor: '+isnull((select distinct Value  from fnFormData (sh.ServiceHeaderID) where formcolumnid=13289),'')+
			', Room: '+isnull((select distinct Value  from fnFormData (sh.ServiceHeaderID) where formcolumnid=13290),'')+
			', Road: '+ isnull((select distinct Value  from fnFormData (sh.ServiceHeaderID) 
			where formcolumnid=123),'')PhysicalLocation 

			from Permits p
			join ServiceHeader sh on p.ServiceHeaderID=sh.ServiceHeaderID
			join customer c on sh.CustomerID=c.CustomerID
			join services s on sh.ServiceID=s.ServiceID
			join InvoiceLines il on il.ServiceHeaderID=sh.ServiceHeaderID and il.ServiceID=sh.ServiceID 
			join ReceiptLines r on r.InvoiceHeaderID=il.InvoiceHeaderID
			join Agents ag on p.CreatedBy=ag.AgentID
			where convert(date,p.IssueDate)>='$fromDate' and convert(date,p.IssueDate)<='$toDate'
			order by p.ServiceHeaderID desc";

			// echo $sql; exit;
				
				$tblTotals=0;
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{					
					$PermitNo = $rw['PermitNo'];					
					$IssueDate=$rw['IssueDate'];
					$CustomerName=$rw['CustomerName'];
					$ServiceName=$rw['ServiceName'];
					$IssuedBy=$rw['IssuedBy'];
					$Amount = $rw['Amount'];							
					$tblTotals+=$Amount;
					$tablestr.='<tr>
					<td align="left">'.$PermitNo.'</td>
					<td align="left">'.$IssueDate.'</td>
					<td align="left">'.$CustomerName.'</td>
					<td align="left">'.$ServiceName.'</td>
					<td align="left">'.$IssuedBy.'</td>					
					<td align="right">'.number_format($Amount,2).'</td>
					</tr>'; 
				}
				
		//echo $tablestr;

		$mpdf=new mPDF('win-1252','A4-L','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("TRA");
		$mpdf->SetAuthor("TRA");
		//$mpdf->SetWatermarkText("County Government Of Uasin Gishu");
		//$mpdf->showWatermarkText = true;
		//$mpdf->watermark_font = 'DejaVuSansCondensed';
		//$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
					
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by     Attain
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/><br/><br/><br/><br/><br/><br/>
		
		



		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td width="15%">PermitNo</td>
		<td width="15%">IssueDate</td>
		<td width="20%">CustomerName</td>
		<td width="20%">ServiceName</td>
		<td width="15%">Issued By</td>
		<td width="15%">Amount</td>
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		
		<tr>
		<td class="blanktotal" colspan="3" rowspan="6"></td>
		<td class="totals" colspan="2">Totals:</td>
		<td class="totals" >'.number_format($tblTotals,2).'</td>
		</tr>

		</tbody>
		</table>
		</body>
		</html>
		';
		$mpdf->WriteHTML($html);		
 		//$mpdf->Output();
		//exit;
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}


	function establishmentbranches($db,$cosmasRow,$rptName,$fromDate,$toDate,$cName)
	{
		$tablestr = '';
		$ReportTitle="Establishment Branches";
		$sql="select c.CustomerID,ca.CustomerAgentID,ca.AgentID,c.CustomerName,c.PhysicalAddress,c.Email,c.Mobile1 
			from Customer c join CustomerAgents ca on c.CustomerID = ca.CustomerID
			where ca.AgentID like '%$cName%'
			group by ca.AgentID,c.CustomerID,ca.CustomerAgentID,c.CustomerName,c.PhysicalAddress,c.Email,c.Mobile1
			order by c.CustomerID asc";
		// exit($sql);
				$tblTotals=0;
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{					
									
					$CustomerName=$rw['CustomerName'];
					$PhysicalAddress = $rw['PhysicalAddress'];
					$Mobile1 = $rw['Mobile1'];
					$Email = $rw['Email'];
					$tablestr.='<tr>
					<td align="left">'.$CustomerName.'</td>
					<td align="right">'.$PhysicalAddress.'</td>
					<td align="right">'.$Mobile1.'</td>
					<td align="right">'.$Email.'</td>
					</tr>'; 
				}
				
		// echo $tablestr;

		$mpdf=new mPDF('win-1252','A4','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("TRA");
		$mpdf->SetAuthor("TRA");
		$mpdf->SetWatermarkText("Tourism Regulatory Authority");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
					<img src="images/logo.png" alt="TRA Logo">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo_2.png" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/>
		<style>
		table {
		  font-family: arial, sans-serif;
		  border-collapse: collapse;
		  width: 100%;
		}

		td, th {
		  border: 1px solid #dddddd;
		  text-align: left;
		  padding: 8px;
		}

		tr:nth-child(even) {
		  background-color: #dddddd;
		}
		table, th, td {
		  border: 1px solid black;
		  border-collapse: collapse;
		}
		</style>
		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td width="40%">Establishment Name</td>
		<td width="20%">Branch</td>
		<td width="20%">Phone Number</td>
		<td width="20%">Email Address</td>
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		
		

		</tbody>
		</table>
		</body>
		</html>
		';
		$mpdf->WriteHTML($html);
		
 		//$mpdf->Output();
		//exit;
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}

	function graded($db,$cosmasRow,$rptName,$fromDate,$toDate)
	{
		$tablestr = '';
		$ReportTitle="Graded Establishments <br>Between $fromDate and $toDate";
		$sql="select c.CustomerName,c.CustomerID, c.Website, c.PhysicalAddress, c.Email, c.Mobile1, sh.ServiceID,
          s.ServiceName,sh.SubmissionDate 
          from ServiceHeader sh 
          join Inspections ins on sh.ServiceHeaderID = ins.ServiceHeaderID 
          join ChecklistResults cr on cr.InspectionID = ins.InspectionID 
          join Customer c on c.CustomerID = sh.CustomerID 
          join InspectionComments ic on ic.InspectionID = ins.InspectionID 
          join Services s on s.ServiceID = sh.ServiceID
          where convert(date,sh.SubmissionDate)>='$fromDate' and convert(date,sh.SubmissionDate)<='$toDate' and sh.ServiceCategoryID = 2033 and ServiceStatusID = 4 Group By c.CustomerName,c.CustomerID,
          c.Website,c.PhysicalAddress,c.Email,c.Mobile1,sh.ServiceID,s.ServiceName,sh.SubmissionDate";
		// exit($sql);
				$tblTotals=0;
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{					
									
					$CustomerName=$rw['CustomerName'];
					$Website = $rw['Website'];
					$PhysicalAddress = $rw['PhysicalAddress'];
					$ServiceName = $rw['ServiceName'];
					$CustomerID = $rw['CustomerID'];
					$ServiceID = $rw['ServiceID'];



                    $ratingsql = "select distinct top 1 ic.AverageScore,ins.InspectionID
                      from ServiceHeader sh 
                      join Inspections ins on sh.ServiceHeaderID = ins.ServiceHeaderID 
                      join ChecklistResults cr on cr.InspectionID = ins.InspectionID 
                      join Customer c on c.CustomerID = sh.CustomerID 
                      join InspectionComments ic on ic.InspectionID = ins.InspectionID 
                      left join Services s on s.ServiceID = sh.ServiceID
                      where sh.ServiceCategoryID = 2033 and ServiceStatusID = 4 and c.CustomerID = $CustomerID order by InspectionID desc";
                      // exit($ratingsql);
                      $rating_result = sqlsrv_query($db, $ratingsql);

                      while($ratingrow=sqlsrv_fetch_array($rating_result,SQLSRV_FETCH_ASSOC)){
                        $Rating = $ratingrow['AverageScore'];
                      }

                if($Rating == ''){
                  
                  $Rating = 'Missing Scores';
                  
                }else{

              $tr_sql = "select * from Rating where ServiceID = $ServiceID";
             // exit($tr_sql);
             $tr_result = sqlsrv_query($db, $tr_sql);

            while($omrow=sqlsrv_fetch_array($tr_result,SQLSRV_FETCH_ASSOC)){
              $trServiceID = $omrow['ServiceID'];
            }
                    if($ServiceID == $trServiceID){
              
             $r_sql = "select * from Rating where ServiceID=$trServiceID and MinRatingScore<=$Rating and MaxRatingScore>=$Rating";
             // exit($r_sql);
             $r_result = sqlsrv_query($db, $r_sql);

            while($omrow=sqlsrv_fetch_array($r_result,SQLSRV_FETCH_ASSOC)){
              $rServiceID = $omrow['ServiceID'];
              $MinRatingScore = $omrow['MinRatingScore'];
              $MaxRatingScore = $omrow['MaxRatingScore'];
              $RatingName = $omrow['RatingName'];

            }
            $omrow=sqlsrv_has_rows($r_result);
            if($omrow == false){
                  $Rating = 'Techinal Issue';      
            }else{
                                $StarRate1 = '1 Star'; 
                                $StarRate2 = '2 Star';
                                $StarRate3 = '3 Star';
                                $StarRate4 = '4 Star';
                                $StarRate5 = '5 Star';

                                if($StarRate1 == trim($RatingName)){
                                	$Rating = $RatingName;
                                }elseif($StarRate2 == trim($RatingName)){
                                	$Rating = $RatingName; 
                                }elseif($StarRate3 == trim($RatingName)){
                                	$Rating = $RatingName;
                                }elseif($StarRate4 == trim($RatingName)){
                                	$Rating = $RatingName;
                                }elseif($StarRate5 == trim($RatingName)){
                                	$Rating = $RatingName;
                                }
                               } 
                  }else{
                  } 
                  }  
                  $tablestr.='<tr>
					<td align="left">'.$CustomerName.'</td>
					<td align="right">'.$PhysicalAddress.'</td>
					<td align="right">'.$Website.'</td>
					<td align="right">'.$ServiceName.'</td>
					<td align="right">'.$Rating.'</td>
					</tr>'; 
				}
				
		// echo $tablestr;

		$mpdf=new mPDF('win-1252','A4','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("TRA");
		$mpdf->SetAuthor("TRA");
		$mpdf->SetWatermarkText("Tourism Regulatory Authority");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
					<img src="images/logo.png" alt="TRA Logo">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo_2.png" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/>
		
		<style>
		table {
		  font-family: arial, sans-serif;
		  border-collapse: collapse;
		  width: 100%;
		}

		td, th {
		  border: 1px solid #dddddd;
		  text-align: left;
		  padding: 8px;
		}

		tr:nth-child(even) {
		  background-color: #dddddd;
		}
		table, th, td {
		  border: 1px solid black;
		  border-collapse: collapse;
		}
		</style>
		<br><br><br><br><br>
		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td width="40%">Establishment Name</td>
		<td width="20%">Branch</td>
		<td width="20%">Website</td>
		<td width="20%">Classification</td>
		<td width="20%">Rating</td>
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		
		

		</tbody>
		</table>
		</body>
		</html>
		';
		$mpdf->WriteHTML($html);
		
 		//$mpdf->Output();
		//exit;
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}

	function all_graded($db,$cosmasRow,$rptName,$fromDate,$toDate)
	{
		$tablestr = '';
		$ReportTitle="Graded Establishments";
		$sql="select c.CustomerName,c.CustomerID, c.Website, c.PhysicalAddress, c.Email, c.Mobile1, sh.ServiceID,
          s.ServiceName,sh.SubmissionDate 
          from ServiceHeader sh 
          join Inspections ins on sh.ServiceHeaderID = ins.ServiceHeaderID 
          join ChecklistResults cr on cr.InspectionID = ins.InspectionID 
          join Customer c on c.CustomerID = sh.CustomerID 
          join InspectionComments ic on ic.InspectionID = ins.InspectionID 
          join Services s on s.ServiceID = sh.ServiceID
          where sh.ServiceCategoryID = 2033 and ServiceStatusID = 4 Group By c.CustomerName,c.CustomerID,
          c.Website,c.PhysicalAddress,c.Email,c.Mobile1,sh.ServiceID,s.ServiceName,sh.SubmissionDate";
		// exit($sql);
				$tblTotals=0;
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{					
									
					$CustomerName=$rw['CustomerName'];
					$Website = $rw['Website'];
					$PhysicalAddress = $rw['PhysicalAddress'];
					$ServiceName = $rw['ServiceName'];
					$CustomerID = $rw['CustomerID'];
					$ServiceID = $rw['ServiceID'];



                    $ratingsql = "select distinct top 1 ic.AverageScore,ins.InspectionID
                      from ServiceHeader sh 
                      join Inspections ins on sh.ServiceHeaderID = ins.ServiceHeaderID 
                      join ChecklistResults cr on cr.InspectionID = ins.InspectionID 
                      join Customer c on c.CustomerID = sh.CustomerID 
                      join InspectionComments ic on ic.InspectionID = ins.InspectionID 
                      left join Services s on s.ServiceID = sh.ServiceID
                      where sh.ServiceCategoryID = 2033 and ServiceStatusID = 4 and c.CustomerID = $CustomerID order by InspectionID desc";
                      // exit($ratingsql);
                      $rating_result = sqlsrv_query($db, $ratingsql);

                      while($ratingrow=sqlsrv_fetch_array($rating_result,SQLSRV_FETCH_ASSOC)){
                        $Rating = $ratingrow['AverageScore'];
                      }

                if($Rating == ''){
                  
                  $Rating = 'Missing Scores';
                  
                }else{

              $tr_sql = "select * from Rating where ServiceID = $ServiceID";
             // exit($tr_sql);
             $tr_result = sqlsrv_query($db, $tr_sql);

            while($omrow=sqlsrv_fetch_array($tr_result,SQLSRV_FETCH_ASSOC)){
              $trServiceID = $omrow['ServiceID'];
            }
                    if($ServiceID == $trServiceID){
              
             $r_sql = "select * from Rating where ServiceID=$trServiceID and MinRatingScore<=$Rating and MaxRatingScore>=$Rating";
             // exit($r_sql);
             $r_result = sqlsrv_query($db, $r_sql);

            while($omrow=sqlsrv_fetch_array($r_result,SQLSRV_FETCH_ASSOC)){
              $rServiceID = $omrow['ServiceID'];
              $MinRatingScore = $omrow['MinRatingScore'];
              $MaxRatingScore = $omrow['MaxRatingScore'];
              $RatingName = $omrow['RatingName'];

            }
            $omrow=sqlsrv_has_rows($r_result);
            if($omrow == false){
                  $Rating = 'Techinal Issue';      
            }else{
                                $StarRate1 = '1 Star'; 
                                $StarRate2 = '2 Star';
                                $StarRate3 = '3 Star';
                                $StarRate4 = '4 Star';
                                $StarRate5 = '5 Star';

                                if($StarRate1 == trim($RatingName)){
                                	$Rating = $RatingName;
                                }elseif($StarRate2 == trim($RatingName)){
                                	$Rating = $RatingName; 
                                }elseif($StarRate3 == trim($RatingName)){
                                	$Rating = $RatingName;
                                }elseif($StarRate4 == trim($RatingName)){
                                	$Rating = $RatingName;
                                }elseif($StarRate5 == trim($RatingName)){
                                	$Rating = $RatingName;
                                }
                               } 
                  }else{
                  } 
                  }  
                  $tablestr.='<tr>
					<td align="left">'.$CustomerName.'</td>
					<td align="right">'.$PhysicalAddress.'</td>
					<td align="right">'.$Website.'</td>
					<td align="right">'.$ServiceName.'</td>
					<td align="right">'.$Rating.'</td>
					</tr>'; 
				}
				
		// echo $tablestr;

		$mpdf=new mPDF('win-1252','A4','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("TRA");
		$mpdf->SetAuthor("TRA");
		$mpdf->SetWatermarkText("Tourism Regulatory Authority");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
					<img src="images/logo.png" alt="TRA Logo">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo_2.png" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/>
		
		<style>
		table {
		  font-family: arial, sans-serif;
		  border-collapse: collapse;
		  width: 100%;
		}

		td, th {
		  border: 1px solid #dddddd;
		  text-align: left;
		  padding: 8px;
		}

		tr:nth-child(even) {
		  background-color: #dddddd;
		}
		table, th, td {
		  border: 1px solid black;
		  border-collapse: collapse;
		}
		</style>
		
		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td width="40%">Establishment Name</td>
		<td width="20%">Branch</td>
		<td width="20%">Website</td>
		<td width="20%">Classification</td>
		<td width="20%">Rating</td>
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		
		

		</tbody>
		</table>
		</body>
		</html>
		';
		$mpdf->WriteHTML($html);
		
 		//$mpdf->Output();
		//exit;
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}












	function RevenueGenerated($db,$cosmasRow,$rptName,$fromDate,$toDate)
	{

		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];	
		

		$tablestr = '';

		$ReportTitle="Revenue Generated Between ".$fromDate." AND ".$toDate;

		$sql="set dateformat dmy select rs.RevenueStreamCode,rs.RevenueStreamName,sum(Amount) Total
			from ReceiptLines2 rl
			join services s on rl.ServiceID=s.ServiceID
			join RevenueStreams rs on s.RevenueStreamID=rs.RevenueStreamID
			where convert(date,rl.CreatedDate)>='$fromDate' 
			and convert(date,rl.CreatedDate)<'$toDate'
			group by rs.RevenueStreamName,rs.RevenueStreamCode";

			//echo $sql; exit;
				
				$tblTotals=0;
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{											
					$tblTotals+=(double)$rw['Total'];
					$tablestr.='<tr>
					<td align="left">'.$rw['RevenueStreamCode'].'</td>
					<td align="left">'.$rw['RevenueStreamName'].'</td>										
					<td align="right">'.number_format($rw['Total'],2).'</td>
					</tr>'; 
				}
				
		//echo $tablestr;

		$mpdf=new mPDF('win-1252','A4-L','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("TRA");
		$mpdf->SetAuthor("TRA.");
		//$mpdf->SetWatermarkText("County Government Of Uasin Gishu");
		//$mpdf->showWatermarkText = true;
		//$mpdf->watermark_font = 'DejaVuSansCondensed';
		//$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
					
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by     Attain
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/><br/><br/><br/><br/><br/><br/>
		

		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td >Code</td>
		<td >Revenuestream</td>		
		<td >Total</td>
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		
		<tr>
			<td class="blanktotal" rowspan="2"></td>
			<td class="totals" >Totals:</td>
			<td class="totals" >'.number_format($tblTotals,2).'</td>
		</tr>

		</tbody>
		</table>
		</body>
		</html>
		';
		$mpdf->WriteHTML($html);		
 		//$mpdf->Output();
		//exit;
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}
	function RevenuePerformance($db,$cosmasRow,$rptName,$fromDate,$toDate)
	{

		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];	
		

		$tablestr = '';

		$ReportTitle="REVENUE PERFOMANCE BETWEEN ".$fromDate." AND ".$toDate;

		$sql="set dateformat dmy exec spRevenuePerStream '$fromDate','$toDate'";

			//echo $sql; exit;
				
				$tblTotals=0;
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{											
					$tblTotals+=(double)$rw['Total'];
					$Variance_A=(double)$rw['Budget']-(double)$rw['Total'];
					$Variance_P=((float)$rw['Total']/(float)$rw['Budget'])*100;

					$tablestr.='<tr>
					<td align="left">'.$rw['RevenueStreamCode'].'</td>
					<td align="left">'.$rw['RevenueStreamName'].'</td>
					<td align="left">'.$rw['DepartmentName'].'</td>					
					<td align="right">'.number_format($rw['Total'],2).'</td>
					<td align="right">'.number_format($rw['Budget'],2).'</td>
					<td align="right">'.number_format($rw['Variance_P'],2).'</td>
					</tr>'; 
				}
				
		//echo $tablestr;

		$mpdf=new mPDF('win-1252','A4-L','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle($CountyName);
		$mpdf->SetAuthor($CountyName);
		//$mpdf->SetWatermarkText("County Government Of Uasin Gishu");
		//$mpdf->showWatermarkText = true;
		//$mpdf->watermark_font = 'DejaVuSansCondensed';
		//$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
					
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by     Attain
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/><br/><br/><br/><br/><br/><br/>
		

		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td >Code</td>
		<td >Stream Name</td>
		<td >Department</td>		
		<td >Total</td>
		<td >Budget</td>
		<td >Variance(%)</td>
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		
		<tr>
			<td class="blanktotal" colspan="3"></td>
			<td class="totals" >Totals:</td>
			<td class="totals" >'.number_format($tblTotals,2).'</td>
			<td class="totals" ></td>
		</tr>

		</tbody>
		</table>
		</body>
		</html>
		';
		$mpdf->WriteHTML($html);		
 		//$mpdf->Output();
		//exit;
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}
	function LandRatesStatusAsAt($db,$cosmasRow,$rptName,$fromDate,$toDate)
	{

		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];	
		

		$tablestr = '';

		$ReportTitle="REVENUE PERFOMANCE BETWEEN ".$fromDate." AND ".$toDate;

		$sql="set dateformat dmy exec spLandBalancesAsAt '$toDate'";

			//echo $sql; exit;
				
				$tblTotals=0;
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{											
					$tblTotals+=(double)$rw['TotalBalance'];
					
					$tablestr.='<tr>
					<td align="left">'.$rw['lrn'].'</td>
					<td align="left">'.$rw['plotno'].'</td>
					<td align="left">'.$rw['Owner'].'</td>					
					<td align="right">'.number_format($rw['RatesPayable'],2).'</td>
					<td align="right">'.number_format($rw['PrincipleBalance'],2).'</td>
					<td align="right">'.number_format($rw['PenaltyBalance'],2).'</td>
					<td align="right">'.number_format($rw['GroundRentBalance'],2).'</td>
					<td align="right">'.number_format($rw['OtherChargesBalance'],2).'</td>
					<td align="right">'.number_format($rw['TotalBalance'],2).'</td>
					</tr>'; 
				}
				
		//echo $tablestr;

		$mpdf=new mPDF('win-1252','A4-L','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle($CountyName);
		$mpdf->SetAuthor($CountyName);
		//$mpdf->SetWatermarkText("County Government Of Uasin Gishu");
		//$mpdf->showWatermarkText = true;
		//$mpdf->watermark_font = 'DejaVuSansCondensed';
		//$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
					
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by     Attain
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/><br/><br/><br/><br/><br/><br/>
		

		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td >LRN</td>
		<td >Plo No</td>
		<td >Owner</td>		
		<td >Rates Payable</td>
		<td >Principle Balance</td>
		<td >Penalty Balance</td>
		<td >Ground Rent Balance</td>
		<td >Other Charges Balance</td>
		<td >Total Balance</td>
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		
		<tr>
			<td class="blanktotal" colspan="7"></td>
			<td class="totals" >Totals:</td>
			<td class="totals" >'.number_format($tblTotals,2).'</td>
		</tr>

		</tbody>
		</table>
		</body>
		</html>
		';
		$mpdf->WriteHTML($html);		
 		//$mpdf->Output();
		//exit;
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}
	function RevenuePerStream_deposits($db,$cosmasRow,$rptName,$fromDate,$toDate,$BankID)
	{

		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];	
		$BankCondition='';

		if($BankID!==''){
			$BankCondition=" and b.BankID='$BankID'";
		}
		

		$tablestr = '';

		$ReportTitle="REVENUE PER STREAM (Deposited) BETWEEN ".$fromDate." AND ".$toDate;

		$sql="set dateformat dmy
			SELECT        rs.RevenueStreamID, rs.RevenueStreamName,convert(date,rC.CreatedDate) [Receipted Date],convert(date,rc.ReceiptDate) DepositDated, SUM(rl2.Amount) AS Total,
			 rs.RevenueStreamCode,rc.CreatedBy,rc.ReferenceNumber,rc.BankID,b.BankName,ag.FirstName+' '+ag.MiddleName+' '+ag.LastName UserName
			FROM            dbo.ReceiptLines AS r 
			JOIN RECEIPTS RC ON R.ReceiptID=RC.ReceiptID
			INNER JOIN dbo.InvoiceHeader AS ih ON r.InvoiceHeaderID = ih.InvoiceHeaderID 
			INNER JOIN (SELECT DISTINCT InvoiceHeaderID, ServiceHeaderID FROM InvoiceLines) AS il ON il.InvoiceHeaderID = ih.InvoiceHeaderID
			JOIN ReceiptLines2 rl2 on rl2.InvoiceheaderID=r.InvoiceHeaderID and rl2.ReceiptID=rc.ReceiptID  					
			INNER JOIN dbo.Services AS s ON rl2.ServiceID = s.ServiceID 					
			INNER JOIN dbo.RevenueStreams AS rs ON s.RevenueStreamID = rs.RevenueStreamID
			left join banks b on rc.BankID=b.BankID
			left join Agents ag on ag.AgentID=rc.CreatedBy
			where convert(date,rc.ReceiptDate)>='$fromDate' and convert(date,rc.ReceiptDate)<='$toDate' ".$BankCondition."					
			GROUP BY rs.RevenueStreamID, rs.RevenueStreamName, 
			rs.RevenueStreamCode,convert(date,rC.CreatedDate),convert(date,rC.ReceiptDate),rc.CreatedBy,rc.ReferenceNumber,rc.BankID,b.BankName,
			ag.FirstName+' '+ag.MiddleName+' '+ag.LastName
			order by  convert(date,rC.CreatedDate),rc.ReferenceNumber";

			echo $sql; exit;
				
				$tblTotals=0;
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{											
					$tblTotals+=(double)$rw['Total'];
					$tablestr.='<tr>
					td align="left">'.$rw['Receipted Date'].'</td>
					<td align="left">'.$rw['ReferenceNumber'].'</td>
					<td align="left">'.$rw['RevenueStreamName'].'</td>
					<td align="left">'.$rw['BankName'].'</td>					
					<td align="right">'.number_format($rw['Total'],2).'</td>
					</tr>'; 
				}
				
		//echo $tablestr;

		$mpdf=new mPDF('win-1252','A4-L','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("Invoice");
		$mpdf->SetAuthor("TRA.");
		//$mpdf->SetWatermarkText("County Government Of Uasin Gishu");
		//$mpdf->showWatermarkText = true;
		//$mpdf->watermark_font = 'DejaVuSansCondensed';
		//$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
					
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by     Attain
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/><br/><br/><br/><br/><br/><br/>
		

		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td >Deposit Date</td>
		<td >Reference Number</td>
		<td >Revenue Stream</td>		
		<td >Amount</td>
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		
		<tr>
			<td class="blanktotal" colspan="2" rowspan="2"></td>
			<td class="totals" >Totals:</td>
			<td class="totals" align="left">'.number_format($tblTotals,2).'</td>
		</tr>

		</tbody>
		</table>
		</body>
		</html>
		';
		$mpdf->WriteHTML($html);		
 		//$mpdf->Output();
		//exit;
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}
	function Permits_Summary($db,$cosmasRow,$rptName,$fromDate,$toDate)
	{

		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];	
		

		$tablestr = '';
		$ReportTitle="Revenue From Permits(Summary) <br>Between $fromDate to $toDate";
		
		$sql="set dateformat dmy 
				select month(p.IssueDate) Month,year(p.IssueDate) Year,sum(il.Amount) Total

				from Permits p
				join ServiceHeader sh on p.ServiceHeaderID=sh.ServiceHeaderID
				join customer c on sh.CustomerID=c.CustomerID
				join services s on sh.ServiceID=s.ServiceID
				join InvoiceLines il on il.ServiceHeaderID=sh.ServiceHeaderID and il.ServiceID=sh.ServiceID 
				join ReceiptLines r on r.InvoiceHeaderID=il.InvoiceHeaderID
				join Agents ag on p.CreatedBy=ag.AgentID
				where convert(date,p.IssueDate)>='$fromDate' and convert(date,p.IssueDate)<='$toDate'
				group by month(p.IssueDate),year(p.IssueDate)
				order by month(p.IssueDate)
				";
				//echo $sql;
				$tblTotals=0;
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{					
					$Month = MonthName($rw['Month']);					
					$Year=$rw['Year'];
					$Total=$rw['Total'];												
					$tblTotals+=$Total;
					$tablestr.='<tr>
					<td align="left">'.$Month.'</td>
					<td align="left">'.$Year.'</td>										
					<td align="right">'.number_format($Total,2).'</td>
					</tr>'; 
				}
				
		//echo $tablestr;

		$mpdf=new mPDF('win-1252','A4-L','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("TRA");
		$mpdf->SetAuthor("TRA.");
		$mpdf->SetWatermarkText("Tourism Regulatory Authority");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
					<img src="images/CountyLogo_New.png" alt="County Logo">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo_2.png" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/><br/><br/><br/><br/><br/><br/>
		
		



		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td width="30%">Month</td>
		<td width="30%">Year</td>
		<td width="30%">Total</td>		
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		
		<tr>
		<td class="blanktotal" rowspan="6"></td>
		<td class="totals">Totals:</td>
		<td class="totals">'.number_format($tblTotals,2).'</td>
		</tr>

		</tbody>
		</table>
		</body>
		</html>
		';
		$mpdf->WriteHTML($html);
		
 		//$mpdf->Output();
		//exit;
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 				
	}
	function RevenuePerStream($db,$cosmasRow,$rptName)
	{

		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];
		
		

		$tablestr = '';
		$ReportTitle="REVENUE PER STREAM";
		$sql="SELECT [ServiceGroupName],[Total]  FROM [COUNTYREVENUE].[dbo].[vwReceiptsPerStream]";
		
				$tblTotals=0;
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{					
					$ServiceGroupName = $rw['ServiceGroupName'];					
					$Amount=$rw['Total'];											
					$tblTotals+=$Amount;
					$tablestr.='<tr>
					<td align="left">'.$ServiceGroupName.'</td>									
					<td align="right">'.number_format($Amount,2).'</td>
					</tr>'; 
				}
				
		//echo $tablestr;

		$mpdf=new mPDF('win-1252','A4-L','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("Tourism Regulatory Authority");
		$mpdf->SetAuthor("Tourism Regulatory Authority");
		$mpdf->SetWatermarkText("Tourism Regulatory Authority");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
					<img src="images/logo.png" alt="County Logo">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo_2.png" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/><br/><br/><br/><br/><br/><br/>
		
		



		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td width="70%">Stream</td>
		<td width="30%">Amount</td>
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		
		<tr>
		<td class="blanktotal" rowspan="6"></td>
		
		<td class="totals">'.number_format($tblTotals,2).'</td>
		</tr>

		</tbody>
		</table>
		</body>
		</html>
		';
		$mpdf->WriteHTML($html);
		
 		//$mpdf->Output();
		//exit;
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}
	function PosCollectionPerAgent($db,$cosmasRow,$rptName)
	{

		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];
		
		

		$tablestr = '';
		$ReportTitle="POS COLLECTIONS PER AGENT";
		$sql="set dateformat dmy select il.PosReceiptID ReceiptNo,il.CreateDate,il.ServiceHeaderID,il.invoicelineId,il.amount,
			ag.AgentID,Ag.FirstName+' '+ag.MiddleName+' '+ag.LastName [Agent],
			mk.MarketName,s.ServiceName
			from InvoiceLines il 
			join Agents ag on il.CreatedBy=ag.AgentID
			join (select * from UserDevices where DeviceUserStatusID=1) ud on ud.DeviceUserID=ag.AgentID
			join Markets mk on ud.MarketID=mk.MarketID
			join ServiceHeader sh on il.ServiceHeaderID=sh.ServiceHeaderID
			join Services s on sh.ServiceID=s.ServiceID where il.PosReceiptID is not null and il.CreatedBy='30509' and month(il.CreateDate)=4 and day(il.CreateDate)=9
			order by sh.ServiceHeaderID desc";
		
				$tblTotals=0;
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{					
					$ReceiptNo = $rw['ReceiptNo'];	
					$Agent = $rw['Agent'];	
					$ServiceName = $rw['ServiceName'];	
					$Market = $rw['MarketName'];
					$Amount = $rw['amount'];															
					$tblTotals+=$Amount;
					$tablestr.='<tr>
					<td align="left">'.$ReceiptNo.'</td>
					<td align="left">'.$Agent.'</td>
					<td align="left">'.$ServiceName.'</td>
					<td align="left">'.$Market.'</td>					
					<td align="right">'.number_format($Amount,2).'</td>
					</tr>'; 
				}
				
		//echo $tablestr;

		$mpdf=new mPDF('win-1252','A4-L','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("Tourism Regulatory Authority");
		$mpdf->SetAuthor("Tourism Regulatory Authority");
		$mpdf->SetWatermarkText("Tourism Regulatory Authority");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
					<img src="images/CountyLogo_New.png" alt="County Logo">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo_2.png" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/><br/><br/><br/><br/><br/><br/>
		
		



		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td width="15%">Receipt No</td>
		<td width="20%">Agent</td>
		<td width="30%">ServiceName</td>
		<td width="20%">Market</td>
		<td width="15%">Amount</td>
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		
		<tr>
		<td class="blanktotal" colspan="4" rowspan="6"></td>		
		<td class="totals">'.number_format($tblTotals,2).'</td>
		</tr>

		</tbody>
		</table>
		</body>
		</html>
		';
		$mpdf->WriteHTML($html);
		
 		//$mpdf->Output();
		//exit;
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}
	function Receipts($db,$cosmasRow,$rptName,$fromDate,$toDate,$AgentID)
	{
		
		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];
		$BankID=0;
		$NewBankID=0;
		

		$tablestr = '';
		$ReportTitle="RECEIPTS";
		$sql="set dateformat dmy select r.ReferenceNumber,r.Amount,b.BankID,b.BankName,convert(date,r.ReceiptDate) DepositedDate,convert(date,r.CreatedDate) ReceiptedDate,ag.FirstName+' '+ag.MiddleName+' '+ag.LastName UserNames,ag.AgentID 
			from Receipts r
			join users u on r.CreatedBy=u.AgentID 
			join agents ag on ag.AgentID=u.agentid
			join Banks b on r.BankID=b.BankID
			where ag.AgentID=$AgentID and convert(date,r.CreatedDate)>='$fromDate' and convert(date,r.CreatedDate)<='$toDate'
			order by b.BankName";
			
			// echo $sql;
			// exit;
		
				$tblTotals=0;
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{
					

					if($NewBankID!==0 && $BankID!==$NewBankID){
						$tablestr.='<tr>
							<td align="left" colspan="3">SubTotal</td>					
							<td align="right">'.number_format($tblTotals,2).'</td>
						</tr>';
					}
					$NewBankID=$rw['BankID'];

					$ReceiptNo = $rw['ReferenceNumber'];	
					$DepositedDate = $rw['DepositedDate'];	
					$ReceiptedDate = $rw['ReceiptedDate'];	
					$Amount = $rw['Amount'];						
					$tblTotals+=$Amount;
					$tablestr.='<tr>
						<td align="left">'.$ReceiptNo.'</td>
						<td align="left">'.$DepositedDate.'</td>
						<td align="left">'.$ReceiptedDate.'</td>					
						<td align="right">'.number_format($Amount,2).'</td>
					</tr>'; 
				}



		$mpdf=new mPDF('win-1252','A4-L','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("Tourism Regulatory Authority");
		$mpdf->SetAuthor("Tourism Regulatory Authority.");
		$mpdf->SetWatermarkText("Tourism Regulatory Authority");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');
			


		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
					<img src="images/logo.png" alt="County Logo">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo_2.png" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/><br/><br/><br/><br/><br/><br/>
		
		



		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
			<td width="15%">Receipt No</td>
			<td width="15%">Deposited Date</td>
			<td width="15%">Receipt Date</td>
			<td width="10%">Amount</td>
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		
		<tr>
		<td class="blanktotal" colspan="3" rowspan="6"></td>		
		<td class="totals">'.number_format($tblTotals,2).'</td>
		</tr>

		</tbody>
		</table>
		</body>
		</html>
		';
		
		//echo $html;
		
		$mpdf->WriteHTML($html);
		
 		//$mpdf->Output();
		//exit;
						
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}
	function Receipts_Summary($db,$cosmasRow,$rptName,$fromDate,$toDate)
	{
		
		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];
		$UserNames='';
		$tablestr = '';

		$Revenuestreamname='';
		$userFilter='';


		$UserNames='';


		if($AgentID!=="All"){
			$userFilter="and rc.CreatedBy=$AgentID";

			$sql="SELECT FirstName+' '+MiddleName+' '+LastName [UserNames] 
				 FROM Agents where AgentID=$AgentID";
			$result=sqlsrv_query($db,$sql);
			while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
				$UserNames=$rw['UserNames'];
			}

			$ReportTitle="Receipts Posted 
			<BR><h4>Between ".$fromDate." and ".$toDate."</h4>
			By ".$UserNames."</h4>";

			
			
		}else{
			$ReportTitle="Receipts Receipted (Summary) 
			<BR><h4>Between ".$fromDate." and ".$toDate."</h4>";
		}

		$ReportTitle="Receipts Receipted (Summary) 
			<BR><h4>Between ".$fromDate." and ".$toDate."</h4>";

		$sql="set dateformat dmy 
		SELECT  upper(rc.ReferenceNumber)ReferenceNumber,
		isnull(misc.CustomerName,c.CustomerName)CustomerName,rc.BankID,Convert(date,rc.CreatedDate)CreatedDate,
		convert(date,rc.ReceiptDate)ReceiptDate,upper(b.BankName)BankName,b.AccountNumber ,
		isnull(Reversed.Amount,r.Amount) Amount,isnull(u.FirstName+' '+u.MiddleName+' '+u.LastName,'Info Account') UserName,
		upper(u.FirstName+' '+u.MiddleName+' '+u.LastName) CreatedBy
		FROM    dbo.Receipts rc        
		join dbo.ReceiptLines AS r on r.ReceiptID=rc.receiptid 
		INNER JOIN dbo.InvoiceHeader AS ih ON r.InvoiceHeaderID = ih.InvoiceHeaderID 
		INNER JOIN (SELECT DISTINCT InvoiceHeaderID, ServiceHeaderID FROM InvoiceLines) AS il ON il.InvoiceHeaderID = ih.InvoiceHeaderID 
		INNER JOIN ServiceHeader sh on il.ServiceHeaderID=sh.ServiceHeaderID
		inner join customer c on C.CustomerID=sh.CustomerID
		left join Miscellaneous misc on misc.ServiceHeaderID=sh.ServiceHeaderID
		JOIN ReceiptLines2 rl2 on rl2.InvoiceheaderID=r.InvoiceHeaderID and rl2.ReceiptID=rc.ReceiptID 					
		INNER JOIN dbo.Services AS s ON rl2.ServiceID = s.ServiceID					
		join Banks b on rc.BankID=b.BankID
		left join agents u on rc.CreatedBy=u.AgentID
		left join (select ReferenceNumber,sum(Amount) Amount from Receipts 
		where Status=2 group by ReferenceNumber) Reversed on Reversed.ReferenceNumber=rc.ReferenceNumber
		where Convert(date,rc.CreatedDate)>='$fromDate' and Convert(date,rc.CreatedDate)<='$toDate'					
		group by upper(rc.ReferenceNumber),isnull(misc.CustomerName,c.CustomerName),rc.BankID,Convert(date,rc.CreatedDate),convert(date,rc.ReceiptDate),upper(b.BankName),b.AccountNumber  ,isnull(u.FirstName+' '+u.MiddleName+' '+u.LastName,'Info Account') ,u.FirstName+' '+u.MiddleName+' '+u.LastName,isnull(Reversed.Amount,r.Amount)
		order by Convert(date,rc.CreatedDate),upper(rc.ReferenceNumber)";
				// echo $sql;
				//  exit;
				$Amount=0;
				$SubTotal=0;
				$GrandTotal=0;

				
				$BankID='';
				$PrevBankID='';
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{	
					$BankID=$rw['BankID'];
					$BankName=$rw['BankName'];
					$Amount = $rw['Amount'];					
					$ReferenceNumber=$rw['ReferenceNumber'];
					$AccountNumber=$rw['AccountNumber'];
					$DepositDate=date_create($rw['ReceiptDate']);
					$ReceiptDate=date_create($rw['CreatedDate']);
					$UserNames=$rw['UserNames'];
					$CustomerName=$rw['CustomerName'];
					$ServiceName=$rw['ServiceName'];
					$RevenueStream=$rw['RevenueStreamName'];
					$CreatedBy=$rw['CreatedBy'];
					
					
					if($BankID!==$PrevBankID || $PrevBankID=='')
					{
						if($PrevBankID!=='')
						{
							$tablestr.='<tr>
							<td align="Center" colspan="4" class="groupfootertitle"></td>
							<td  class="totals">'.number_format($SubTotal,2).'</td>
							<td class="totals"></td>
						</tr>';
							$SubTotal=0;
						}
						
						$tablestr.='<tr>
							<td align="Center" colspan="6" class="groupfootertitle">'.$BankName.'</td>

						</tr>';
					}
					
					$SubTotal+=$Amount;
					$GrandTotal+=$rw['Amount'];					
					
					$tablestr.='<tr>
						<td align="left">'.$ReferenceNumber.'</td>
						<td align="left">'.$CustomerName.'</td>							
						<td align="right">'.date_format($DepositDate,'d/m/Y').'</td>
						<td align="right">'.date_format($ReceiptDate,'d/m/Y').'</td>					
						<td align="right">'.number_format($Amount,2).'</td>
						<td align="right">'.$CreatedBy.'</td>								
					</tr>'; 					
					
					
					$PrevBankID=$BankID;
					$PrevBankName=$BankName;
				}
				
		

		$mpdf=new mPDF('win-1252','A4-L','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("Receipts Posted");
		$mpdf->SetAuthor("Attain ES");
		$mpdf->SetWatermarkText($CountyName);
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:5mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
					
				</td>
			</tr>
			<tr>
				
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo_2.png" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->

		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td >Ref Number</td>
		<td >Customer</td>		
		<td >Deposit Date</td>
		<td >Receipt Date</td>	
		<td >Amount</td>
		<td >Receipted By</td>
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		<tr>
			<td class="groupfootertitle" colspan="4"></td>			
			<td align="right" class="totals">'.number_format($GrandTotal,2).'</td>
			<td class="totals"></td>
		</tr>
		
		</tbody>
		</table>
		</body>
		</html>
		';
		$mpdf->WriteHTML($html);
		
 	// 	$mpdf->Output();
		// exit;
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}
	function Tenancy($db,$cosmasRow,$rptName,$EstateID)
	{

		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];	

		$tablestr = '';
		$ReportTitle="Tenancy Per Estate";
		$sql="SELECT es.EstateID,h.HouseNumber,es.EstateName,tn.MonthlyRent,tn.CurrentTenant [Tenant],vw.Balance
		FROM Houses h 
		join Tenancy tn on tn.UHN=h.UHN	
		join Estates es on h.EstateID=es.EstateID
		join vwhousepayments vw on vw.HouseNumber=h.HouseNumber
		where es.EstateID=$EstateID						 
		order by EstateID,HouseNumber";
		
				$tblTotals=0;
				$Paid2016=0;
				
				$EstateID=0;
				$PrevEstateID=0;
				$EstateName='';
				$PrevEstateName='';
				
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{				
					$EstateID=$rw['EstateID'];
					$EstateName=$rw['EstateName'];
					$HouseNumber = $rw['HouseNumber'];
					$Tenant = $rw['Tenant'];
					$MonthlyRent = $rw['MonthlyRent'];
					$Balance = $rw['Balance'];					
					$Amount=$rw['Balance'];	
					$paidTotals+=$Paid2016;					
					$tblTotals+=$Amount;
					
					if($EstateID!==$PrevEstateID and $PrevEstateID!==0)
					{				
						$tablestr.='<tr>
							<td class="groupfootertitle" colspan="2">'.$PrevEstateName.'</td>						
							<td align="right" class="totals">'.number_format($paidTotals,2).'</td>
							<td align="right" class="totals">'.number_format($tblTotals,2).'</td>
						</tr>';	

						$total_iAmount=0;
						$total_pAmount=0;
						$total_Balance=0;						
					}
					
					if($PrevEstateID==0 or $PrevEstateID!==$EstateID)
					{
						$tablestr.='<tr>
							<td align="center" colspan="4" class="groupfootertitle">'.$EstateName.'</td>						
						</tr>'; 
					}
					
					$tablestr.='<tr>
						<td align="left">'.$HouseNumber.'</td>									
						<td align="left">'.$Tenant.'</td>
						<td align="right">'.$MonthlyRent.'</td>		
						<td align="right">'.number_format($Balance,2).'</td>
					</tr>'; 
					
					$PrevEstateID=$EstateID;
					$PrevEstateName=$EstateName;
				}
				
		/* echo $tablestr;
		exit; */
		// echo $sql;
		// exit;

		$mpdf=new mPDF('win-1252','A4-L','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("Tourism Regulatory Authority");
		$mpdf->SetAuthor("Tourism Regulatory Authority.");
		$mpdf->SetWatermarkText("Tourism Regulatory Authority");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
					<img src="images/CountyLogo_New.png" alt="County Logo">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo_2.png" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/><br/><br/><br/><br/><br/><br/>
		
		



		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td width="20%">House Number</td>
		<td width="35%">Current Tenant</td>
		<td width="15%">Monthly Rent</td>
		<td width="15%">Balance</td>
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		
		<tr>
		
		<td class="blanktotal" colspan="2" rowspan="6"></td>
		<td class="totals">'.number_format($paidTotals,2).'</td>
		<td class="totals">'.number_format($tblTotals,2).'</td>
		</tr>

		</tbody>
		</table>
		</body>
		</html>
		';
		$mpdf->WriteHTML($html);
		
 		//$mpdf->Output();
		//exit;
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}
	function ReceiptsPerStream($db,$cosmasRow,$rptName,$fromDate,$toDate)
	{

		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];
		
		

		$tablestr = '';
		$ReportTitle="Receipts Per Stream";
		$sql="set dateformat dmy SELECT sg.ServiceGroupID,sg.ServiceGroupName, s.ServiceName, sum(il.Amount) Amount
					FROM            ServiceHeader sh 
									JOIN InvoiceLines il ON il.ServiceHeaderID = sh.ServiceHeaderID 
									JOIN Services s ON sh.ServiceID = s.ServiceID
									JOIN ServiceCategory sc on s.ServiceCategoryID=sc.ServiceCategoryID
									JOIN ServiceGroup sg on sc.ServiceGroupID=sg.ServiceGroupID
									where  convert(date,il.CreateDate)>='$fromDate' and convert(date,il.CreateDate)<='$toDate'
					GROUP BY sg.ServiceGroupName,sg.ServiceGroupID,  s.ServiceName
					UNION
					SELECT        sg.ServiceGroupID,sg.ServiceGroupName,s.ServiceName, sum(il.Amount) Amount
					FROM            ServiceHeader sh 
									JOIN	InvoiceLines il ON il.ServiceHeaderID = sh.ServiceHeaderID 
									JOIN    services s ON sh.ServiceID = s.ServiceID
									JOIN	ServiceCategory sc on s.ServiceCategoryID=sc.ServiceCategoryID
									JOIN	ServiceGroup sg on sc.ServiceGroupID=sg.ServiceGroupID 
									JOIN	Receipts r ON r.InvoiceHeaderID = il.InvoiceHeaderID
									where convert(date,il.CreateDate)>='$fromDate' and convert(date,il.CreateDate)<='$toDate'
					GROUP BY sg.ServiceGroupID,sg.ServiceGroupName, s.ServiceName";


		
				$tblTotals=0;
				$ServiceGroupID=0;
				$PrevServiceGroupID=0;
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{	
					
					$ServiceGroupName=$rw['ServiceGroupName'];
					$ServiceName = $rw['ServiceName'];
					$Amount = $rw['Amount'];															
					$tblTotals+=$Amount;
					
					if($ServiceGroupID==$PrevServiceGroupID)
					{
						$tablestr.='<tr>
							<td align="right">'.$ServiceGroupName.'</td>									
							<td align="left">'.$ServiceName.'</td>
							<td align="right">'.$Amount.'</td>						
						</tr>'; 
					}else
					{
						$tablestr.='<tr>
							<td class="groupfootertitle" colspan="2">'.$ServiceGroupName.'</td>																	
							<td align="right" class="totals">'.number_format($tblTotals,2).'</td>
						</tr>';						
					}
					$ServiceGroupID=$rw['ServiceGroupID'];
					$PrevServiceGroupID=$ServiceGroupID;
				}
				
		/* echo $tablestr;
		exit; */

		$mpdf=new mPDF('win-1252','A4-L','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("Tourism Regulatory Authority");
		$mpdf->SetAuthor("Tourism Regulatory Authority");
		$mpdf->SetWatermarkText("Tourism Regulatory Authority");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
					<img src="images/CountyLogo_New.png" alt="County Logo">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo_2.png" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/><br/><br/><br/><br/><br/><br/>
		
		



		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td width="40%">Service Group</td>
		<td width="40%">Service</td>
		<td width="20%">Amount</td>
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		
		<tr>		
		<td class="blanktotal" colspan="2" rowspan="6"></td>		
		<td class="totals">'.number_format($tblTotals,2).'</td>
		</tr>

		</tbody>
		</table>
		</body>
		</html>
		';
		$mpdf->WriteHTML($html);
		
 		//$mpdf->Output();
		//exit;
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}
	function HousingReceipts($db,$cosmasRow,$rptName,$EstateID,$fromDate,$toDate)
	{

		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];
		
		

		$tablestr = '';
		$ReportTitle="Housing Receipts";
		$sql="set dateformat dmy SELECT sg.ServiceGroupID,sg.ServiceGroupName, s.ServiceName, sum(il.Amount) Amount
					FROM            ServiceHeader sh 
									JOIN InvoiceLines il ON il.ServiceHeaderID = sh.ServiceHeaderID 
									JOIN Services s ON sh.ServiceID = s.ServiceID
									JOIN ServiceCategory sc on s.ServiceCategoryID=sc.ServiceCategoryID
									JOIN ServiceGroup sg on sc.ServiceGroupID=sg.ServiceGroupID
									where sg.ServiceGroupID='20' and convert(date,il.CreateDate)>='$fromDate' and convert(date,il.CreateDate)<='$toDate'
					GROUP BY sg.ServiceGroupName,sg.ServiceGroupID,  s.ServiceName
					UNION
					SELECT        sg.ServiceGroupID,sg.ServiceGroupName,s.ServiceName, sum(il.Amount) Amount
					FROM            ServiceHeader sh 
									JOIN	InvoiceLines il ON il.ServiceHeaderID = sh.ServiceHeaderID 
									JOIN    services s ON sh.ServiceID = s.ServiceID
									JOIN	ServiceCategory sc on s.ServiceCategoryID=sc.ServiceCategoryID
									JOIN	ServiceGroup sg on sc.ServiceGroupID=sg.ServiceGroupID 
									JOIN	Receipts r ON r.InvoiceHeaderID = il.InvoiceHeaderID
									where sg.ServiceGroupID='20' and convert(date,il.CreateDate)>='$fromDate' and convert(date,il.CreateDate)<='$toDate' and s.ServiceID=$EstateID
					GROUP BY sg.ServiceGroupID,sg.ServiceGroupName, s.ServiceName";
		//echo $sql; exit;
		
				$tblTotals=0;
				$ServiceGroupID=0;
				$PrevServiceGroupID=0;
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{	
					
					$ServiceGroupName=$rw['ServiceGroupName'];
					$ServiceName = $rw['ServiceName'];
					$Amount = $rw['Amount'];															
					$tblTotals+=$Amount;
					
					if($ServiceGroupID==$PrevServiceGroupID)
					{
						$tablestr.='<tr>
							<td align="right">'.$ServiceGroupName.'</td>									
							<td align="left">'.$ServiceName.'</td>
							<td align="right">'.$Amount.'</td>						
						</tr>'; 
					}else
					{
						$tablestr.='<tr>
							<td class="groupfootertitle" colspan="2">'.$ServiceGroupName.'</td>																	
							<td align="right" class="totals">'.number_format($tblTotals,2).'</td>
						</tr>';						
					}
					$ServiceGroupID=$rw['ServiceGroupID'];
					$PrevServiceGroupID=$ServiceGroupID;
				}
				
		/* echo $tablestr;
		exit; */

		$mpdf=new mPDF('win-1252','A4-L','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("Tourism Regulatory Authority");
		$mpdf->SetAuthor("Tourism Regulatory Authority");
		$mpdf->SetWatermarkText("Tourism Regulatory Authority");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
					<img src="images/CountyLogo_New.png" alt="County Logo">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo_2.png" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/><br/><br/><br/><br/><br/><br/>
		
		



		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td width="40%">Service</td>
		<td width="40%">Estate</td>
		<td width="20%">Amount</td>
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		
		<tr>		
		<td class="blanktotal" colspan="2" rowspan="6"></td>		
		<td class="totals">'.number_format($tblTotals,2).'</td>
		</tr>

		</tbody>
		</table>
		</body>
		</html>
		';
		$mpdf->WriteHTML($html);
		
 		//$mpdf->Output();
		//exit;
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}
	function GeneralInvoices($db,$cosmasRow,$rptName,$RevenueStreamID,$fromDate,$toDate)
	{

		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];
		
		

		$tablestr = '';
		$ReportTitle="General Invoices <BR><h4>Between ".$fromDate." and ".$toDate."</h4>";
		$sql="set dateformat dmy SELECT  sh.ServiceHeaderID, ih.InvoiceHeaderID, ih.CustomerID, convert(date,ih.InvoiceDate)InvoiceDate, isnull(misc.CustomerName,c.CustomerName) CustomerName, 
		s.ServiceName, s.ServiceID,il.Amount InvoicedAmount,isnull(rl.amount,0) PaidAmount,il.Amount-isnull(rl.amount,0) Balance,rs.RevenueStreamName                      
		FROM dbo.InvoiceHeader AS ih 
		JOIN (select invoiceheaderid,serviceheaderid,sum(Amount)Amount from dbo.InvoiceLines group by invoiceheaderid,serviceheaderid) AS il ON il.InvoiceHeaderID = ih.InvoiceHeaderID 
		JOIN dbo.ServiceHeader AS sh ON il.ServiceHeaderID = sh.ServiceHeaderID 
		JOIN dbo.Customer AS c ON sh.CustomerID = c.CustomerID 
		JOIN dbo.Services AS s ON sh.ServiceID = s.ServiceID
		left join Miscellaneous misc on misc.ServiceHeaderID=sh.ServiceHeaderID 
		left join (select InvoiceHeaderID,Sum(Amount)Amount from dbo.ReceiptLines group by invoiceheaderid)rl on rl.InvoiceHeaderID=il.InvoiceHeaderID
		left join RevenueStreams rs on s.RevenueStreamID=rs.RevenueStreamID
		where rs.RevenueStreamID=$RevenueStreamID and convert(date,ih.CreatedDate)>='$fromDate' and convert(date,ih.CreatedDate)<='$toDate'
		order by ih.InvoiceHeaderID";

		//echo $sql; exit;
				$total_iAmount=0;
				$total_pAmount=0;
				$total_Balance=0;
				
				$grand_iAmount=0;
				$grand_pAmount=0;
				$grand_Balance=0;
				
				$ServiceName='';
				$PrevServiceName='';
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{	
					$ServiceName=$rw['ServiceName'];
					if($ServiceName!==$PrevServiceName and $PrevServiceName!=='')
					{				
						$tablestr.='<tr>
							<td class="groupfootertitle" colspan="4">'.$PrevServiceName.'</td>																	
							<td align="right" class="totals">'.number_format($total_iAmount,2).'</td>
							<td align="right" class="totals">'.number_format($total_pAmount,2).'</td>
							<td align="right" class="totals">'.number_format($total_Balance,2).'</td>
						</tr>';	

						$total_iAmount=0;
						$total_pAmount=0;
						$total_Balance=0;						
					}
					
					$CustomerName=$rw['CustomerName'];
					$InvoiceDate=$rw['InvoiceDate'];					
					$InvoiceHeaderID = $rw['InvoiceHeaderID'];
					$iAmount = $rw['InvoicedAmount'];
					$pAmount = $rw['PaidAmount'];	
					$Balance = $rw['Balance'];

					$grand_iAmount+=$rw['InvoicedAmount'];
					$grand_pAmount+=$rw['PaidAmount'];
					$grand_Balance+=$rw['Balance'];
					
					$total_iAmount+=$iAmount;
					$total_pAmount+=$pAmount;
					$total_Balance+=$Balance;
					
					$tablestr.='<tr>
						<td align="left">'.$InvoiceHeaderID.'</td>									
						<td align="left">'.$InvoiceDate.'</td>
						<td align="left">'.$CustomerName.'</td>
						<td align="left">'.$ServiceName.'</td>	
						<td align="right">'.$iAmount.'</td>
						<td align="right">'.$pAmount.'</td>
						<td align="right">'.$Balance.'</td>							
					</tr>'; 					
					
					
					$PrevServiceName=$ServiceName;
				}
				
		/* echo $tablestr;
		exit; */

		$mpdf=new mPDF('win-1252','A4-L','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("Tourism Regulatory Authority");
		$mpdf->SetAuthor("Tourism Regulatory Authority");
		$mpdf->SetWatermarkText("Tourism Regulatory Authority");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
					<img src="images/CountyLogo_New.png" alt="County Logo">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo_2.png" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/><br/><br/><br/><br/><br/><br/>
		
		



		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
			<td>Invoice No</td>	
			<td>Invoice Date</td>		
			<td>Customer</td>
			<td>Service</td>
			<td>Amount Billed</td>
			<td>Amount Paid</td>
			<td>Balance</td>
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		<tr>
			<td class="groupfootertitle" colspan="4">'.$PrevServiceName.'</td>																	
			<td align="right" class="totals">'.number_format($total_iAmount,2).'</td>
			<td align="right" class="totals">'.number_format($total_pAmount,2).'</td>
			<td align="right" class="totals">'.number_format($total_Balance,2).'</td>
		</tr>
		<tr>		
			<td class="groupfootertitle" colspan="4" rowspan="6">Grand Totals</td>		
			<td class="totals">'.number_format($grand_iAmount,2).'</td>
			<td class="totals">'.number_format($grand_pAmount,2).'</td>
			<td class="totals">'.number_format($grand_Balance,2).'</td>
		</tr>

		</tbody>
		</table>
		</body>
		</html>
		';
		$mpdf->WriteHTML($html);
		
 		//$mpdf->Output();
		//exit;
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}
	function HousingInvoices($db,$cosmasRow,$rptName,$EstateID,$fromDate,$toDate)
	{
		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];
		
		

		$tablestr = '';
		$ReportTitle="Housing Invoices <BR><h4>Between ".$fromDate." and ".$toDate."</h4>";

		$sql="set dateformat dmy SELECT  sh.ServiceHeaderID, ih.InvoiceHeaderID, ih.CustomerID, convert(date,ih.InvoiceDate)InvoiceDate, c.CustomerName, 
		s.ServiceName, s.ServiceID,il.Amount InvoicedAmount,isnull(rl.amount,0) Paid,il.Amount-isnull(rl.amount,0) Balance,rs.RevenueStreamName,
		upper(SUBSTRING(il.Description,10,(CHARINDEX('E',il.Description,9)-10))) HouseNo                      
		FROM dbo.InvoiceHeader AS ih 
		JOIN (select invoiceheaderid,serviceheaderid,Description,sum(Amount)Amount from dbo.InvoiceLines group by invoiceheaderid,serviceheaderid,Description) AS il ON il.InvoiceHeaderID = ih.InvoiceHeaderID 
		JOIN dbo.ServiceHeader AS sh ON il.ServiceHeaderID = sh.ServiceHeaderID 
		JOIN dbo.Customer AS c ON sh.CustomerID = c.CustomerID 
		JOIN dbo.Services AS s ON sh.ServiceID = s.ServiceID 
		left join (select InvoiceHeaderID,Sum(Amount)Amount from dbo.ReceiptLines group by invoiceheaderid)rl on rl.InvoiceHeaderID=il.InvoiceHeaderID
		left join RevenueStreams rs on s.RevenueStreamID=rs.RevenueStreamID
		where s.ServiceID=$EstateID and convert(date,ih.CreatedDate)>='$fromDate' and convert(date,ih.CreatedDate)<='$toDate'
		order by ih.InvoiceHeaderID";

		


		echo $sql; exit;
				$total_iAmount=0;
				$total_pAmount=0;
				$total_Balance=0;
				
				$grand_iAmount=0;
				$grand_pAmount=0;
				$grand_Balance=0;
				
				$ServiceName='';
				$PrevServiceName='';
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{	
					$ServiceName=$rw['ServiceName'];
					if($ServiceName!==$PrevServiceName and $PrevServiceName!=='')
					{				
						$tablestr.='<tr>
							<td class="groupfootertitle" colspan="3">'.$PrevServiceName.'</td>																	
							<td align="right" class="totals">'.number_format($total_iAmount,2).'</td>
							<td align="right" class="totals">'.number_format($total_pAmount,2).'</td>
							<td align="right" class="totals">'.number_format($total_Balance,2).'</td>
						</tr>';	

						$total_iAmount=0;
						$total_pAmount=0;
						$total_Balance=0;						
					}
					
					$CustomerName=$rw['CustomerName'];
					$HouseNo=$rw['HouseNo'];					
					$InvoiceHeaderID = $rw['InvoiceHeaderID'];
					$iAmount = $rw['InvoicedAmount'];
					$pAmount = $rw['Paid'];	
					$Balance = $rw['Balance'];

					$grand_iAmount+=$rw['InvoicedAmount'];
					$grand_pAmount+=$rw['Paid'];
					$grand_Balance+=$rw['Balance'];
					
					$total_iAmount+=$iAmount;
					$total_pAmount+=$pAmount;
					$total_Balance+=$Balance;
					
					$tablestr.='<tr>
						<td align="left">'.$CustomerName.'</td>									
						<td align="left">'.$HouseNo.'</td>
						<td align="left">'.$InvoiceHeaderID.'</td>
						<td align="right">'.$iAmount.'</td>	
						<td align="right">'.$pAmount.'</td>
						<td align="right">'.$Balance.'</td>							
					</tr>'; 					
					
					
					$PrevServiceName=$ServiceName;
				}
				
		/* echo $tablestr;
		exit; */

		$mpdf=new mPDF('win-1252','A4-L','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("Tourism Regulatory Authority");
		$mpdf->SetAuthor("Tourism Regulatory Authority");
		$mpdf->SetWatermarkText("Tourism Regulatory Authority");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
					<img src="images/CountyLogo_New.png" alt="County Logo">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo_2.png" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/><br/><br/><br/><br/><br/><br/>
		
		



		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td width="25%">Customer</td>		
		<td width="15%">HouseNo</td>
		<td width="15%">Invoice No</td>
		<td width="15%">Invoice Amount</td>
		<td width="15%">Paid Amount</td>
		<td width="15%">Balance</td>
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		<tr>
			<td class="groupfootertitle" colspan="3">'.$PrevServiceName.'</td>																	
			<td align="right" class="totals">'.number_format($total_iAmount,2).'</td>
			<td align="right" class="totals">'.number_format($total_pAmount,2).'</td>
			<td align="right" class="totals">'.number_format($total_Balance,2).'</td>
		</tr>
		<tr>		
			<td class="groupfootertitle" colspan="3" rowspan="6">Grand Totals</td>		
			<td class="totals">'.number_format($grand_iAmount,2).'</td>
			<td class="totals">'.number_format($grand_pAmount,2).'</td>
			<td class="totals">'.number_format($grand_Balance,2).'</td>
		</tr>

		</tbody>
		</table>
		</body>
		</html>
		';
		$mpdf->WriteHTML($html);
		
 		//$mpdf->Output();
		//exit;
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}
	function HousingInvoicesSummary($db,$cosmasRow,$rptName,$fromDate,$toDate)
	{

		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];
		
		

		$tablestr = '';
		$ReportTitle="Rent Receipts (Summary) <BR><h4>Between ".$fromDate." and ".$toDate."</h4>";
		$sql="set dateformat dmy SELECT s.ServiceName, s.ServiceID, sg.ServiceGroupID, sg.ServiceGroupName,SUM(il.Amount) AS InvoicedAmount,sum(r.Amount) Paid, SUM(il.Amount)-sum(r.Amount) AS Balance 

				FROM dbo.InvoiceHeader AS ih 
				INNER JOIN dbo.InvoiceLines AS il ON il.InvoiceHeaderID = ih.InvoiceHeaderID 
				INNER JOIN	dbo.ServiceHeader AS sh ON il.ServiceHeaderID = sh.ServiceHeaderID 
				INNER JOIN	dbo.Customer AS c ON sh.CustomerID = c.CustomerID 
				INNER JOIN dbo.Services AS s ON sh.ServiceID = s.ServiceID 
				INNER JOIN dbo.ServiceCategory AS sc ON s.ServiceCategoryID = sc.ServiceCategoryID 
				INNER JOIN dbo.ServiceGroup AS sg ON sc.ServiceGroupID = sg.ServiceGroupID
				LEFT join vwReceipts r on r.InvoiceHeaderID=ih.InvoiceHeaderID

				where sg.ServiceGroupID=20 
				and convert(date,il.CreateDate)>='$fromDate' and convert(date,il.CreateDate)<='$toDate' 
				GROUP BY s.ServiceName,s.ServiceID, sg.ServiceGroupID, sg.ServiceGroupName				 
				order by s.ServiceName";
		
				$total_iAmount=0;
				$total_pAmount=0;
				$total_Balance=0;
				
				$grand_iAmount=0;
				$grand_pAmount=0;
				$grand_Balance=0;
				
				$ServiceName='';
				$PrevServiceName='';
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{	
					$ServiceName=$rw['ServiceName'];
					$iAmount = $rw['InvoicedAmount'];
					$pAmount = $rw['Paid'];	
					$Balance = $rw['Balance'];

					$grand_iAmount+=$rw['InvoicedAmount'];
					$grand_pAmount+=$rw['Paid'];
					$grand_Balance+=$rw['Balance'];
					
					$total_iAmount+=$iAmount;
					$total_pAmount+=$pAmount;
					$total_Balance+=$Balance;
					
					$tablestr.='<tr>
						<td align="left">'.$ServiceName.'</td>					
						<td align="right">'.$iAmount.'</td>	
						<td align="right">'.$pAmount.'</td>
						<td align="right">'.$Balance.'</td>							
					</tr>'; 					
					
					
					$PrevServiceName=$ServiceName;
				}
				
		/* echo $tablestr;
		exit; */

		$mpdf=new mPDF('win-1252','A4-L','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("Tourism Regulatory Authority");
		$mpdf->SetAuthor("Tourism Regulatory Authority");
		$mpdf->SetWatermarkText("Tourism Regulatory Authority");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:10mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
					<img src="images/CountyLogo_New.png" alt="County Logo">
				</td>
			</tr>
			<tr>
				<td colspan="2" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo_2.png" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/><br/><br/><br/><br/><br/><br/>
		
		



		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td width="30%">Estate</td>		
		<td width="20%">Invoice Amount</td>
		<td width="20%">Paid Amount</td>
		<td width="20%">Balance</td>
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		<tr>
			<td class="groupfootertitle"></td>																	
			<td align="right" class="totals">'.number_format($total_iAmount,2).'</td>
			<td align="right" class="totals">'.number_format($total_pAmount,2).'</td>
			<td align="right" class="totals">'.number_format($total_Balance,2).'</td>
		</tr>
		
		</tbody>
		</table>
		</body>
		</html>
		';
		$mpdf->WriteHTML($html);
		
 		//$mpdf->Output();
		//exit;
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}
	function Receipts_Receipted($db,$cosmasRow,$rptName,$fromDate,$toDate,$AgentID)
	{

		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];
		$UserNames='';
		$tablestr = '';

		$Revenuestreamname='';
		$userFilter='';


		$UserNames='';


		if($AgentID!=="All"){
			$userFilter="and rc.CreatedBy=$AgentID";

			$sql="SELECT FirstName+' '+MiddleName+' '+LastName [UserNames] 
				 FROM Agents where AgentID=$AgentID";
			$result=sqlsrv_query($db,$sql);
			while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
				$UserNames=$rw['UserNames'];
			}

			$ReportTitle="Receipts Posted 
			<BR><h4>Between ".$fromDate." and ".$toDate."</h4>
			By ".$UserNames."</h4>";

			
			
		}else{
			$ReportTitle="Receipts Posted 
			<BR><h4>Between ".$fromDate." and ".$toDate."</h4>";
		}

		$sql="set dateformat dmy 
		SELECT upper(rc.ReferenceNumber)ReferenceNumber,c.CustomerName,rc.BankID,
		Convert(date,rc.CreatedDate)CreatedDate, convert(date,rc.ReceiptDate)ReceiptDate,
		upper(b.BankName)BankName,b.AccountNumber ,rl2.Amount,S.ServiceName,RS.RevenueStreamName, 
		isnull(u.FirstName+' '+u.MiddleName+' '+u.LastName,'Info Account') UserNames,rc.CreatedBy 
		FROM dbo.Receipts rc join dbo.ReceiptLines AS r on r.ReceiptID=rc.receiptid 
		JOIN ReceiptLines2 rl2 on rl2.InvoiceheaderID=r.InvoiceHeaderID and rl2.ReceiptID=rc.ReceiptID 
		JOIN dbo.InvoiceHeader AS ih ON r.InvoiceHeaderID = ih.InvoiceHeaderID 
		JOIN (SELECT DISTINCT InvoiceHeaderID, ServiceHeaderID FROM InvoiceLines) AS il ON il.InvoiceHeaderID = ih.InvoiceHeaderID 
		JOIN ServiceHeader sh on il.ServiceHeaderID=sh.ServiceHeaderID inner join customer c on C.CustomerID=sh.CustomerID 
		JOIN dbo.Services AS s ON rl2.ServiceID = s.ServiceID 
		JOIN RevenueStreams RS ON S.RevenueStreamID=RS.RevenueStreamID
		JOIN Banks b on rc.BankID=b.BankID 
		left join agents u on rc.CreatedBy=u.AgentID 
		where Convert(date,rc.CreatedDate)>='$fromDate' and Convert(date,rc.CreatedDate)<='$toDate' 
		".$userFilter." 
		group by upper(rc.ReferenceNumber),c.CustomerName,rc.BankID,Convert(date,rc.CreatedDate), convert(date,rc.ReceiptDate),upper(b.BankName),b.AccountNumber ,rl2.Amount ,isnull(u.FirstName+' '+u.MiddleName+' '+u.LastName,'Info Account') ,rc.CreatedBy,
		S.ServiceName,RS.RevenueStreamName";
				// echo $sql;
				// exit;
				$Amount=0;
				$SubTotal=0;
				$GrandTotal=0;

				
				$BankID='';
				$PrevBankID='';
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{	
					$BankID=$rw['BankID'];
					$BankName=$rw['BankName'];
					$Amount = $rw['Amount'];					
					$ReferenceNumber=$rw['ReferenceNumber'];
					$AccountNumber=$rw['AccountNumber'];
					$DepositDate=date_create($rw['ReceiptDate']);
					$ReceiptDate=date_create($rw['CreatedDate']);
					$UserNames=$rw['UserNames'];
					$CustomerName=$rw['CustomerName'];
					$ServiceName=$rw['ServiceName'];
					$RevenueStream=$rw['RevenueStreamName'];
					
					
					if($BankID!==$PrevBankID || $PrevBankID=='')
					{
						if($PrevBankID!=='')
						{
							$tablestr.='<tr>
							<td align="Center" colspan="6" class="groupfootertitle"></td>
							<td  class="totals">'.number_format($SubTotal,2).'</td>
						</tr>';
							$SubTotal=0;
						}
						
						$tablestr.='<tr>
							<td align="Center" colspan="7" class="groupfootertitle">'.$BankName.'</td>
						</tr>';
					}
					
					$SubTotal+=$Amount;
					$GrandTotal+=$rw['Amount'];					
					
					$tablestr.='<tr>
						<td align="left">'.$ReferenceNumber.'</td>						
						<td align="right">'.date_format($DepositDate,'d/m/Y').'</td>
						<td align="right">'.date_format($ReceiptDate,'d/m/Y').'</td>
						<td align="left">'.$CustomerName.'</td>	
						<td align="left">'.$ServiceName.'</td>	
						<td align="left">'.$RevenueStream.'</td>	
						<td align="right">'.number_format($Amount,2).'</td>							
					</tr>'; 					
					
					
					$PrevBankID=$BankID;
					$PrevBankName=$BankName;
				}
				
		

		$mpdf=new mPDF('win-1252','A4-L','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("Receipts Posted");
		$mpdf->SetAuthor("Attain ES");
		$mpdf->SetWatermarkText($CountyName);
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:5mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
					
				</td>
			</tr>
			<tr>
				
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo_2.png" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->

		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td >Ref Number</td>		
		<td >Deposit Date</td>
		<td >Receipt Date</td>
		<td >Customer</td>
		<td >Service Name</td>
		<td >Revenue Stream</td>
		<td >Amount</td>
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		<tr>
			<td class="groupfootertitle" colspan="6"></td>			
			<td align="right" class="totals">'.number_format($GrandTotal,2).'</td>
		</tr>
		
		</tbody>
		</table>
		</body>
		</html>
		';
		$mpdf->WriteHTML($html);
		
 	// 	$mpdf->Output();
		// exit;
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 			
	}

	function Receipts_Deposited($db,$cosmasRow,$rptName,$fromDate,$toDate,$AgentID)
	{

		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];
		$UserNames='';
		$tablestr = '';

		$Revenuestreamname='';

		$userFilter='';
		$UserNames='';
		$ReportTitle='';

		if($AgentID!=="All"){
			
			//$userFilter="and rc.CreatedBy=$AgentID";

			$sql="SELECT FirstName+' '+MiddleName+' '+LastName [UserNames] 
				 FROM Agents where AgentID=$AgentID";
			$result=sqlsrv_query($db,$sql);
			while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
				$UserNames=$rw['UserNames'];
			}

			$ReportTitle="Receipts Deposited 
			<BR><h4>Between ".$fromDate." and ".$toDate."</h4>
			By ".$UserNames."</h4>";

			
			
		}else{
			$ReportTitle="Receipts Deposited 
			<BR><h4>Between ".$fromDate." and ".$toDate."</h4>";
		}

		$sql="set dateformat dmy 
		SELECT upper(rc.ReferenceNumber)ReferenceNumber,c.CustomerName,rc.BankID,
		Convert(date,rc.CreatedDate)CreatedDate, convert(date,rc.ReceiptDate)ReceiptDate,
		upper(b.BankName)BankName,b.AccountNumber ,rl2.Amount,S.ServiceName,RS.RevenueStreamName, 
		isnull(u.FirstName+' '+u.MiddleName+' '+u.LastName,'Info Account') UserNames,rc.CreatedBy 
		FROM dbo.Receipts rc join dbo.ReceiptLines AS r on r.ReceiptID=rc.receiptid 
		JOIN ReceiptLines2 rl2 on rl2.InvoiceheaderID=r.InvoiceHeaderID and rl2.ReceiptID=rc.ReceiptID 
		JOIN dbo.InvoiceHeader AS ih ON r.InvoiceHeaderID = ih.InvoiceHeaderID 
		JOIN (SELECT DISTINCT InvoiceHeaderID, ServiceHeaderID FROM InvoiceLines) AS il ON il.InvoiceHeaderID = ih.InvoiceHeaderID 
		JOIN ServiceHeader sh on il.ServiceHeaderID=sh.ServiceHeaderID inner join customer c on C.CustomerID=sh.CustomerID 
		JOIN dbo.Services AS s ON rl2.ServiceID = s.ServiceID 
		JOIN RevenueStreams RS ON S.RevenueStreamID=RS.RevenueStreamID
		JOIN Banks b on rc.BankID=b.BankID 
		left join agents u on rc.CreatedBy=u.AgentID 
		where Convert(date,rc.ReceiptDate)>='$fromDate' and Convert(date,rc.ReceiptDate)<='$toDate' 
		".$userFilter." 
		group by upper(rc.ReferenceNumber),c.CustomerName,rc.BankID,Convert(date,rc.CreatedDate), convert(date,rc.ReceiptDate),upper(b.BankName),b.AccountNumber ,rl2.Amount ,isnull(u.FirstName+' '+u.MiddleName+' '+u.LastName,'Info Account') ,rc.CreatedBy,
		S.ServiceName,RS.RevenueStreamName";
				//echo $sql;
				//exit;
				$Amount=0;
				$SubTotal=0;
				$GrandTotal=0;

				
				$BankID='';
				$PrevBankID='';
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{	
					$BankID=$rw['BankID'];
					$BankName=$rw['BankName'];
					$Amount = $rw['Amount'];					
					$ReferenceNumber=$rw['ReferenceNumber'];
					$AccountNumber=$rw['AccountNumber'];
					$DepositDate=date_create($rw['ReceiptDate']);
					$ReceiptDate=date_create($rw['CreatedDate']);
					$UserNames=$rw['UserNames'];
					$CustomerName=$rw['CustomerName'];
					$ServiceName=$rw['ServiceName'];
					$RevenueStream=$rw['RevenueStreamName'];
					
					
					if($BankID!==$PrevBankID || $PrevBankID=='')
					{
						if($PrevBankID!=='')
						{
							$tablestr.='<tr>
							<td align="Center" colspan="6" class="groupfootertitle"></td>
							<td  class="totals">'.number_format($SubTotal,2).'</td>
						</tr>';
							$SubTotal=0;
						}
						
						$tablestr.='<tr>
							<td align="Center" colspan="7" class="groupfootertitle">'.$BankName.'</td>
						</tr>';
					}
					
					$SubTotal+=$Amount;
					$GrandTotal+=$rw['Amount'];					
					
					$tablestr.='<tr>
						<td align="left">'.$ReferenceNumber.'</td>						
						<td align="right">'.date_format($DepositDate,'d/m/Y').'</td>
						<td align="right">'.date_format($ReceiptDate,'d/m/Y').'</td>
						<td align="left">'.$CustomerName.'</td>	
						<td align="left">'.$ServiceName.'</td>	
						<td align="left">'.$RevenueStream.'</td>	
						<td align="right">'.number_format($Amount,2).'</td>							
					</tr>'; 					
					
					
					$PrevBankID=$BankID;
					$PrevBankName=$BankName;
				}
				
		

		$mpdf=new mPDF('win-1252','A4-L','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("Receipts Posted");
		$mpdf->SetAuthor("Attain ES");
		$mpdf->SetWatermarkText($CountyName);
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');

		$html = '
		<html>
		<head>
			<link rel="stylesheet" type="text/css" href="css/my_css.css"/>		
		</head>
		<body>

		<!--mpdf
		<htmlpageheader name="myheader">
		<table width="100%">
			<tr>
				<td align="Center" colspan="2" style="font-size:5mm">
					<b>'.$ReportTitle.'</b>
				</td>
			</tr>		
			<tr>
				<td align="Center" colspan="2">
					
				</td>
			</tr>
			<tr>
				
			</tr>		
		</table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo_2.png" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->

		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td >Ref Number</td>		
		<td >Deposit Date</td>
		<td >Receipt Date</td>
		<td >Customer</td>
		<td >Service Name</td>
		<td >Revenue Stream</td>
		<td >Amount</td>
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		<tr>
			<td class="groupfootertitle" colspan="6"></td>			
			<td align="right" class="totals">'.number_format($GrandTotal,2).'</td>
		</tr>
		
		</tbody>
		</table>
		</body>
		</html>
		';
		$mpdf->WriteHTML($html);
		
 	// 	$mpdf->Output();
		// exit;
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 			
	}
	
	
	
?>