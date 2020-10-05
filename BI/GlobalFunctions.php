<?php
	require 'DB_PARAMS/connect.php';
	require_once('utilities.php');
	require_once('PHPMailer/class.phpmailer.php');
	include("PHPMailer/class.smtp.php");
	

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

	function createBarCode($No){

		// Including all required classes
		require('BarCode/class/BCGFont.php');
		require('BarCode/class/BCGColor.php');
		require('BarCode/class/BCGDrawing.php'); 

		// Including the barcode technology
		include('BarCode/class/BCGcode39.barcode.php'); 

		// Loading Font
		$font =& new BCGFont('BarCode/class/font/Arial.ttf', 18);

		// The arguments are R, G, B for color.
		$color_black =& new BCGColor(0, 0, 0);
		$color_white =& new BCGColor(255, 255, 255); 

		$code =& new BCGcode39();
		$code->setScale(2); // Resolution
		$code->setThickness(30); // Thickness
		$code->setForegroundColor($color_black); // Color of bars
		$code->setBackgroundColor($color_white); // Color of spaces
		$code->setFont($font); // Font (or 0)
		$code->parse($No); // Text


		/* Here is the list of the arguments
		1 - Filename (empty : display on screen)
		2 - Background color */
		$drawing =& new BCGDrawing('Images/Bar_Codes/'.$No.'.png', $color_white);
		$drawing->setBarcode($code);
		$drawing->draw();

		// Header that says it is an image (remove it if you save the barcode to a file)
		//header('Content-Type: image/png');

		// Draw (or save) the image into PNG format.
		$drawing->finish($drawing->IMG_FORMAT_PNG);
	}

	function uploadFiles($db,$mName)
	{
		$UploadDirectory	= 'C:/COSBACKUP/Dev/County/'; //Upload Directory, ends with slash & make sure folder exist
		$SuccessRedirect	= 'success.html'; //Redirect to a URL after success
	
		if (!@file_exists($UploadDirectory)) {
			//destination folder does not exist
			$msg="Make sure Upload directory exist!";
			return;
		}
		
		if($_POST)
		{	
			if(!isset($mName) || strlen($mName)<1)
			{
				//required variables are empty
				$msg="Title is empty!";
				return;
			}
			
			
			if($_FILES['mFile']['error'])
			{
				//File upload error encountered
				$msg=upload_errors($_FILES['mFile']['error']);
			}
		
			$FileName			= strtolower($_FILES['mFile']['name']); //uploaded file name
			$FileTitle			= mysql_real_escape_string($mName); // file title
			$ImageExt			= substr($FileName, strrpos($FileName, '.')); //file extension
			$FileType			= $FileType; //file type
			$FileSize			= $_FILES['mFile']["size"]; //file size
			$RandNumber   		= rand(0, 9999999999); //Random number to make each filename unique.
			$uploaded_date		= date("Y-m-d H:i:s");
			
			switch(strtolower($FileType))
			{
				//allowed file types
				case 'image/png': //png file
				case 'image/gif': //gif file 
				case 'image/jpeg': //jpeg file
				case 'application/pdf': //PDF file
				case 'application/msword': //ms word file
				case 'application/vnd.ms-excel': //ms excel file
				case 'application/x-zip-compressed': //zip file
				case 'text/plain': //text file
				case 'text/html': //html file
					break;
				default:
					die('Unsupported File!'); //output error
			}
		
		  
			//File Title will be used as new File name
			$NewFileName = preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), strtolower($FileTitle));
			$NewFileName = $NewFileName.'_'.$RandNumber.$ImageExt;
		   //Rename and save uploded file to destination folder.
		   if(move_uploaded_file($_FILES['mFile']["tmp_name"], $UploadDirectory . $NewFileName ))
		   {
			   $sql="INSERT INTO RequisitionFiles (FileName, FileTitle, FileSize) VALUES ('$NewFileName', '$FileTitle',$FileSize)";
			   $result=sqlsrv_query($db,$sql);
			   if ($result)
			   {
				}
				
				//header('Location: '.$SuccessRedirect); //redirect user after success
				
		   }else
		   {
			   
				$msg='error uploading File!';
		   }
		}
	
	//function outputs upload error messages, http://www.php.net/manual/en/features.file-upload.errors.php#90522
		function upload_errors($err_code) {
			switch ($err_code) { 
				case UPLOAD_ERR_INI_SIZE: 
					return 'The uploaded file exceeds the upload_max_filesize directive in php.ini'; 
				case UPLOAD_ERR_FORM_SIZE: 
					return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form'; 
				case UPLOAD_ERR_PARTIAL: 
					return 'The uploaded file was only partially uploaded'; 
				case UPLOAD_ERR_NO_FILE: 
					return 'No file was uploaded'; 
				case UPLOAD_ERR_NO_TMP_DIR: 
					return 'Missing a temporary folder'; 
				case UPLOAD_ERR_CANT_WRITE: 
					return 'Failed to write file to disk'; 
				case UPLOAD_ERR_EXTENSION: 
					return 'File upload stopped by extension'; 
				default: 
					$msg='Unknown upload error'; 
			} 
		}
	

	}
	function mail_attachment($filename, $path, $mailto, $from_mail, $from_name, $replyto, $subject, $message) 
	{
/*		echo 'While';
		 echo "$filename, $path, $mailto, $from_mail, $from_name, $replyto, $subject, $message";
		 exit;	*/
		$file = $path.$filename;
		$file_size = filesize($file);
		$handle = fopen($file, "r");
		$content = fread($handle, $file_size);
		fclose($handle);
		$content = chunk_split(base64_encode($content));
		$uid = md5(uniqid(time()));
		$name = basename($file);
		$header = "From: ".$from_name." <".$from_mail.">\r\n";
		$header .= "Reply-To: ".$replyto."\r\n";
		$header .= "MIME-Version: 1.0\r\n";
		$header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
		$header .= "This is a multi-part message in MIME format.\r\n";
		$header .= "--".$uid."\r\n";
		$header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
		$header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
		$header .= $message."\r\n\r\n";
		$header .= "--".$uid."\r\n";
		$header .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n"; // use different content types here
		$header .= "Content-Transfer-Encoding: base64\r\n";
		$header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
		$header .= $content."\r\n\r\n";
		$header .= "--".$uid."--";
		
		if ($mailto=''){
			$mailto='cngeno11@gmail.com';
		}
		
		if (mail($mailto, $subject, $message, $header)) {
			$msg= "SUCCESS: Invoice Sent to $mailto"; // or use booleans here
		} else {
			print_r(error_get_last());
			//$msg= "mail send ... ERROR!";
		}
		return $msg;
	}
	function send_email($to,$subject,$txt)
	{
		$headers = "From: ".$CountyEmail."\r\n"."CC: cngeno11@gmail.com";		
		if (mail($to,$subject,$txt,$headers))
		{
			$msg="SUCCESS: Credentials set to $to";	
		}else {
			$msg= "mail send ... ERROR!";
		}
	}
	
	function php_mailer($toEmail,$from,$fromName,$subject,$msg,$attachment,$file_path,$item)
	{
		$feedback=null;
		//echo $toEmail.'<br>'.$from.'<br>'.$subject.'<br>'.$msg.'<br>'.$attachment.'<br>'.$file_path;
		
		$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
		$mail->IsSMTP(); // telling the class to use SMTP
		try 
		{
			$mail->Host       = "197.156.135.155"; // SMTP server	
			$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
				
			$mail->SMTPAuth   = true;                  // enable SMTP authentication	
			$mail->Host       = "197.156.135.155"; // sets the SMTP server	
			$mail->Port       = 25;                    // set the SMTP port for the GMAIL server				
			$mail->Username   = "revenue@uasingishu.go.ke"; // SMTP account username	
			$mail->Password   = "Co2014";        // SMTP account password	
			
			
			$mail->AddReplyTo($toEmail, $fromName);	
			$mail->AddAddress($toEmail, $fromName);	
			$mail->SetFrom($from, $fromName);	
			$mail->AddReplyTo($from, $fromName);
			
			$mail->Subject = $subject;	
			$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
			
			//$mail->MsgHTML(file_get_contents('contents.html'));
			$mail->MsgHTML($msg);
			
			if($attachment!=''){			
				$mail->AddAttachment($file_path.$attachment); // attachment
			}
			
			$mail->Send();
			if ($mail!=false)
			{
				$feedback[0]="true";
				$feedback[1]=$item." sent Successfully to $toEmail";
				return $feedback;
				
				//return "Mail Sent Successfully to $toEmail";
			}else
			{
				$feedback[0]="false";
				$feedback[1]=errorMessage();
				return $feedback;
				//return "error";
				//return "Mail failed to send ";
			}
			
			
		
		} catch (phpmailerException $e) 
		{
			echo $e->errorMessage();
			//return $e->errorMessage(); //Pretty error messages from PHPMailer		
 				$feedback[0]="false";
				$feedback[1]=$e->errorMessage();
				return $feedback; 
			/*	//return "error two";
				return $e->errorMessage();*/
		} 	
	}
	
	function createInvoice($db,$ApplicationID,$cosmasRow,$Remark,$CustomerName)
	{
		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];
		
		$rsql="select sh.CustomerID,c.PostalAddress,c.PostalCode,c.Town,c.Mobile1,sh.ServiceID,c.Email,s.ServiceName  
			from ServiceHeader sh 
			join Customer c on sh.CustomerID=c.CustomerID
			join Services s on sh.ServiceID=s.ServiceID
			where sh.ServiceHeaderID=$ApplicationID";
			
		$rresult = sqlsrv_query($db, $rsql);	
		

		if ($rrow = sqlsrv_fetch_array( $rresult, SQLSRV_FETCH_ASSOC))
		{
			//$CustomerName = $rrow['CustomerName'];
			$ServiceName = $rrow['ServiceName'];
			$InvoiceLineID=$rrow['InvoiceLineID'];
			$Email=$rrow['Email'];
			$CustomerAddress=$rrow['PostalAddress'].' '.$rrow['PostalCode'];
			$CustomerCity=$rrow['Town'];
			$CustomerMobile=$rrow['Mobile1'];				
		}

		$tablestr = '';
		$bankrows='';
		$sql="select il.InvoiceLineID,il.InvoiceHeaderID,s.ServiceName, il.ServiceHeaderID,il.Amount,ih.InvoiceNo
				from InvoiceLines il
				inner join InvoiceHeader ih on il.InvoiceHeaderID=ih.InvoiceHeaderID
				inner join services s on il.ServiceID=s.ServiceID 				
				where il.ServiceHeaderID=$ApplicationID				
				order by il.InvoiceLineID";
		
				$tblTotals=0;
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{					
					$ServiceName = $rw['ServiceName'].'<br>'.$Remark;
					$ServiceAmount = $rw['Amount'];	
					$InvoiceLineID=$rw['InvoiceLineID'];
					$InvoiceHeaderID=$rw['InvoiceHeaderID'];
					$InvoiceNo=$rw['InvoiceNo'];
					$tblTotals+=$ServiceAmount;
					$tablestr.='<tr>
					<td align="center">'.$InvoiceLineID.'</td>
					<td align="center">1</td>
					<td>'.$ServiceName.'</td>
					<td align="right">'.number_format($ServiceAmount,2).'</td>
					<td align="right">'.number_format($ServiceAmount,2).'</td>
					</tr>'; 
				}
		$InvoiceNo=$InvoiceNo;
		$SerialNo=$InvoiceHeaderID;
		
		$sqlb="select BankName,AccountNumber from Banks";
		$bnkr=sqlsrv_query($db,$sqlb);
		while($bnks=sqlsrv_fetch_array($bnkr,SQLSRV_FETCH_ASSOC))
		{
			$bankrows.='<tr>
				<td>'.sentence_case($bnks['BankName']).'</td>
				<td>'.sentence_case($bnks['AccountNumber']).'</td>
				</tr>
			';
		}
		//echo $bankrows;
		//exit;
		$OtherCharge=0;
		//With other Charges?
		$sql="select sum (distinct sc.amount)Amount
		from ServiceCharges sc
		join ServicePlus sp on sp.service_add=sc.ServiceID
		join FinancialYear fy on sc.FinancialYearId=fy.FinancialYearID
		join ServiceHeader sh on sh.ServiceID=sp.ServiceID
		and sh.ServiceHeaderID=$ApplicationID
		and fy.isCurrentYear=1";
		$s_result = sqlsrv_query($db, $sql);
		while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
		{							
			$OtherCharge=$row["Amount"];												
		}

		$ServiceAmount=$ServiceAmount+$OtherCharge;		
		
		createBarCode($InvoiceNo);
		
		$mpdf=new mPDF('win-1252','A4','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("Acme Trading Co. - Invoice");
		$mpdf->SetAuthor("Acme Trading Co.");
		$mpdf->SetWatermarkText("County Government Of Uasin Gishu");
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
				<b>SERVICE APPLICATION INVOICE</b>
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
		<tr>
			<td width="50%" style="color:#0000BB;">
				Address: '.$CountyAddress.'<br />
				'.$CountyTown.'<br /> 
				Telephone: '.$CountyTelephone.'</td>
			<td width="50%" style="text-align: right;">
			Invoice No.<br/><span style="font-weight: bold; font-size: 12pt;">'.$InvoiceNo.'<br/></span>
			Serial No.<br/><span style="font-weight: bold; font-size: 12pt;">'.$SerialNo.'</span>
			</td>
		</tr></table>
		
		</htmlpageheader>

		<htmlpagefooter name="myfooter">
		<div style="border-top: 1px solid #000000; font-size: 9pt; text-align: center; padding-top: 3mm; ">
		powered by      <img src="images/attain_logo_2.jpg" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/><br/><br/><br/><br/><br/><br/>
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
		<td width="15%">QUANTITY</td>
		<td width="40%">DESCRIPTION</td>
		<td width="15%">UNIT PRICE</td>
		<td width="15%">AMOUNT</td>
		</tr>
		</thead>
		<tbody>
		
		<!-- ITEMS HERE -->'.
		
		
		$tablestr.
										
		'<!-- END ITEMS HERE -->
		
		<tr>
		<td class="blanktotal" colspan="3" rowspan="6"></td>
		<td class="totals">Subtotal:</td>
		<td class="totals">'.number_format($tblTotals,2).'</td>
		</tr>
		<tr>
		<td class="totals"><b>TOTAL:</b></td>
		<td class="totals"><b>'.number_format($tblTotals,2).'</b></td>
		</tr>
		<tr>
		<td class="totals"><b>Balance due:</b></td>
		<td class="totals"><b>'.number_format($tblTotals,2).'</b></td>
		</tr>
		</tbody>
		</table>
		<div style="font-style: italic; font-size: 10;">
							Payment terms: payment due in 30 days<br>
							Payment by MPESA
							<ol>
							<li> Go to MPESA menu and select <b>Lipa na MPESA</b></li>
							<li> Enter <b>646464</b> as the paybill number and the Invoice Serial Number as the account number</li>
							<li> Pay the amount and enter your MPESA pin number when printed</li>
							</ol>							
							<b>Payment by Bank</b>
							<ol>
								<li>Enter the Uasin Gishu cunty revenue account invoice number as the account number</li>
							</ol>
							<b>Bank Accounts</b>
							<table width="75%" style="font-family: serif; font-size: 11;">'
							.$bankrows.
							'</table><br>							
							Contact us on <b>0720646464</b> for any assistance
		</div>
		<br>
		<div style="text-align: center;">
			<img src="Images/Bar_Codes/'.$InvoiceNo.'.PNG">
		</div>
		</body>
		</html>
		';
/* 		echo $html;
		exit; */
		$mpdf->WriteHTML($html);
/*  		$mpdf->Output();
		exit;
		 */
		$mpdf->Output('pdfdocs/invoices/'.$SerialNo.'.pdf','F'); 
		
		//send email
		$my_file = $SerialNo.'.pdf';
		$file_path = "pdfdocs/invoices/";
		$my_name = $CountyName;
		$toEmail = $Email;
		$fromEmail = $CountyEmail;
		$my_subject = "Service Application Invoice";
		$my_message="Kindly receive the invoice for your applied Service";
		//$my_mail = 'cngeno11@gmail.com';
		$result=php_mailer($toEmail,$fromEmail,$CountyName,$my_subject,$my_message,$my_file,$file_path,"Invoice");
		
		return $result;			
	}
	
	function createPermit($db, $ApplicationID,$row)
		{
		
		$CustomerName = '';
		$ServiceName = '';
		$ServiceAmount = '';	
		$InvoiceHeaderID='';	
		$CountyName=$row['CountyName'];
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];	
		$CountyPostalCode=$row['PostalCode'];
		$PlotNo="";
		
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
		$PhysicalAddress="";
		$PostalCode="";
		$Vat="";
		$PIN="";
		$Town="";
	

		//get the details for this application

		$sql = "select distinct sh.ServiceHeaderID,p.PermitNo,sh.ServiceID,p.Validity,p.ExpiryDate,
			ih.InvoiceHeaderID, ih.CustomerID,ih.InvoiceDate,ih.Paid,
			c.CustomerName,c.Mobile1,c.BusinessID,c.BusinessRegistrationNumber,C.CustomerID,c.PostalAddress,c.PhysicalAddress,c.Telephone1,c.Telephone2,c.PostalCode,c.VatNumber,c.PIN,c.Town,c.Email,
			s.ServiceName,
			il.Amount,a.FirstName+' '+a.MiddleName+' '+a.LastName IssuedBy
			
			from InvoiceHeader ih
			join InvoiceLines il on il.InvoiceHeaderID=ih.InvoiceHeaderID
			join ServiceHeader sh on il.ServiceHeaderID=sh.ServiceHeaderID
			join Customer c on sh.CustomerID=c.CustomerID	
			join Services s on sh.ServiceID=s.ServiceID and  il.ServiceID=sh.ServiceID			
			join Permits p on p.ServiceHeaderID=sh.ServiceHeaderID
			left join Agents a on p.CreatedBy=a.AgentID
			where sh.ServiceHeaderID = $ApplicationID";
			

			$qry_result=sqlsrv_query($db,$sql);	
			  
			if (($rrow = sqlsrv_fetch_array($qry_result,SQLSRV_FETCH_ASSOC))==false)
			{
				DisplayErrors();
				die;
			}else
			{

				$BusinessRegNo=$rrow['BusinessRegistrationNumber'];
				$PermitNo=$rrow['PermitNo'];
				$BusinessID=$rrow['BusinessID'];
				$CustomerID=$rrow['CustomerID'];
				$Validity=$rrow['Validity'];
				$Expiry=$rrow['ExpiryDate'];
				$ExpiryDate=$rrow['ExpiryDate'];
				$CustomerName=$rrow['CustomerName'];
				$BusinessName=$rrow['CustomerName'];
				$ServiceName=$rrow['ServiceName'];
				$ServiceCost=$rrow['Amount'];
				$PostalAdress=$rrow['PostalAddress'];
				$Telephone1=$rrow['Telephone1'];
				$Telephone2=$rrow['Telephone2'];
				$CustomerEmail=$rrow['Email'];
				$PostalCode=$rrow['PostalCode'];
				$PIN=$rrow['PIN'];
				$Vat=$rrow['VatNumber'];
				$Town=$rrow['Town'];
				$IssuedBy=$rrow['IssuedBy'];
				$MobileNo=$rrow['Mobile1'];
				
				$ServiceCost_Words=convertNumber($ServiceCost);				
			}

		//$Validity='2016';
		$mdate=date_create($Expiry);
		$Expiry=date_format($mdate,"d/m/Y");
		$Validity=date_format($mdate,'Y');
		$PostalTown='';
		/*$Expiry='2015';	
		$PostalAdress=0;
		$PostalCode=0;
		$Vat=0;
		$PIN=0;
		$Town='';
		$Email='amail';*/
		
		$rsql="select sh.CustomerID,c.CustomerName,c.PostalAddress,c.PhysicalAddress,c.PostalCode,sh.ServiceID,s.ServiceName,s.ServiceCode, il.ServiceHeaderID,il.ServiceHeaderID,il.Amount,ih.InvoiceHeaderID,c.Email,fd.Value BDescription  
			from invoiceLines il 
			inner join InvoiceHeader ih on il.InvoiceHeaderID=ih.InvoiceHeaderID 
			inner join ServiceHeader sh on	il.ServiceHeaderID=sh.ServiceHeaderID 
			inner join Services s on sh.ServiceID=s.ServiceID and il.ServiceID=sh.ServiceID
			inner join Customer c on sh.CustomerID=c.CustomerID 
			join FormData fd on fd.ServiceHeaderID=sh.ServiceheaderID
			where fd.FormColumnID=5 and sh.ServiceHeaderID=$ApplicationID";
			
			$rresult = sqlsrv_query($db, $rsql);	
			

			if ($rrow = sqlsrv_fetch_array( $rresult, SQLSRV_FETCH_ASSOC))
			{
				$CustomerName = $rrow['CustomerName'];
				$ServiceName = $rrow['ServiceName'];
				$ServiceAmount = $rrow['Amount'];	
				$InvoiceHeaderID=$rrow['InvoiceHeaderID'];	
				$Email=$rrow['Email'];
				$BDescription=$rrow['BDescription'];
				$ServiceCode=$rrow['ServiceCode'];
				$PostalAddress=$rrow['PostalAddress'];
				$PostalTown=$rrow['Town'];
				$PostalCode=$rrow['PostalCode'];
				$PhysicalAddress=$rrow['PhysicalAddress'];
			}		
		
		$PlotNo="";
		//$sql="select Value PlotNo from fnFormData ($ApplicationID) where formcolumnid=12233";
		$sql="select
			(select distinct Value PlotNo from fnFormData ($ApplicationID) where formcolumnid=12242) PlotNo,
			(select distinct Value VatNo from fnFormData ($ApplicationID) where formcolumnid=12243)VatNo";
		
		$result=sqlsrv_query($db,$sql);
		while($rww=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
		{
			$PlotNo=$rww['PlotNo'];
			$Vat=$rww['VatNo'];
		}
		
		createBarCode($PermitNo);	

		$mpdf=new mPDF('win-1252','A4','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->debugfonts = true; 
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle($CountyName."- Invoice");
		$mpdf->SetAuthor($CountyName);
		$mpdf->SetWatermarkText("County Government Of Uasin Gishu");
		$mpdf->showWatermarkText = true;
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetDisplayMode('fullpage');
		
		$html='<html 
		  <head>
				<link rel="stylesheet" href="css/my_css.css" type="text/css"/>			
		  </head>			
		<body>
				<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse; border-top:thick; " cellpadding="1">
					<tr>
						<td align="Center" colspan="5" style="font-size:10mm">
							<b>SINGLE BUSINESS PERMIT</b>
						</td>
					</tr>
					<tr>
						<td align="Center" colspan="5">
							<img src="images/CountyLogo_New.png" alt="County Logo">
						</td>
					</tr>					
					<tr>
						<td style="border-right:0pt"></td>
						<td colspan="3" align="Center"><span style="font-weight: bold; font-size: 14pt;">'.$CountyName.'</span></td>
						<td><span style="font-weight: bold; font-size: 14pt;">'.$Validity.'</span></h3></td>
					</tr>
					<tr>
						<td colspan="5" align="Center"><span style="font-weight: bold; font-size: 14pt;">
						<br>
						GRANTS THIS BUSINESS PERMIT <BR>
								TO
						</span></td>
					</tr>
					<thead>
						<tr>							
							<td colspan="5"><B>'.$BusinessName.'</B></td>
						</tr>
						<tr>
							<td colspan="2">Certificate of Registration NO/ID No: <br>'.$BusinessRegNo.'</td>
							<td width=20%>Business ID No:'.$BusinessID.'</td>
							<td>PIN NO: '.$PIN.'</td>
							<td>VAT NO: '.$Vat.'</td>
						</tr>
					</thead>
						<tr>
							<td colspan="5" align="center">
									<br><p><strong>To engage in the Activity/Business/Profession or Occupation of:</strong></p><br><br>									
							</td>
						</tr>
					<thead>
						<tr>
							<td align="left" colspan="3"><strong>Business Activity Code & Description:</strong><br>('.$ServiceCode.') '.$ServiceName.'</td>
							<td align="right" colspan="2"><strong>Detailed Activity Description:</strong><br>'.$BDescription.'</td>
						</tr>
					</thead>	
					<tr>
						<td colspan="5" align="center">
							<br><p><strong>Having Paid a Single Business Permit Fee of:</strong></p><br><br>
						</td>					
					</tr>
					<tr>
						<td></td> 
						<td colspan="3"  align="center" style="background-color: #BEBABA; font-size:5mm">(Ksh.)<br>'.number_format($ServiceCost,2).'<br>('.$ServiceCost_Words.' only)</td>
						<td></td> 
					</tr>
					<thead>
						<tr>
							<td>P.O Box <br> '.$PostalAddress.'</td>
							<td>Postal Code <br> '.$PostalCode.'</td>
							<td>Postal Town <br> '.$PostalTown.'</td>
							<td>Business Physical Address<br> '.$PhysicalAddress.'</td>
							<td>Plot No <br> '.$PlotNo.'</td>
						</tr>
					
						<tr>
							<td><strong>Mobile No</strong> <br> '.$Telephone1.'</td>
							<td><strong>Telephone</strong> <br> '.$Telephone2.'</td>
							<td><strong>Fax</strong> <br> '.$Fax.'</td>
							<td colspan="2" align="left"><strong>Email Address</strong><br> '.$CustomerEmail.'</td>						
						</tr>
					</thead>
					<tr>
						<td colspan="2"><strong>Validity Period </strong>'.$Validity.'</td>
						<td></td>
						<td colspan="2" align="center"><strong>Expiry Date:</strong>'.$Expiry.'</td>
					</tr>
					<tr>
						<td colspan="2"><strong>Issued By:</strong><br>SILAS KERING LETING</td>	
						<td></td>						
						<td colspan="2"></td>
					</tr>
					<tr>
						<td colspan="2"><br><strong>For The Chief Officer<br>Finance And Economic Planning</strong></td>
						<td></td>
						<td colspan="2"><br><strong><br></td>
					</tr>
					<tr>
						<td colspan="5"><hr></td>
					</tr>
					<tr>						
						<td colspan="5" align="center"><img src="Images/Bar_Codes/'.$PermitNo.'.PNG"></td>
					</tr>					
					<thead>
						<tr>
							<td Colspan="5" style="text-align:justified;"> 
							<small><strong>Notice:</strong> Granting this permit does not exempt the business identified above from
									complying with the current regulations on Health and Safety as established by the Government of Kenya 
									and the '.$CountyName.'.</small>
							</td>
						</tr>
					</thead>
				</table>
				<I>Served by <B>'.$IssuedBy.'</B></I>
		</body>
		</html>';
		//echo $html; 
		$mpdf->WriteHTML($html);
		
		/* $mpdf->Output();
		exit; */
		
		$mpdf->Output('pdfdocs/sbps/'.$PermitNo.'.pdf','F');
		
		//send Email
		$my_file = $PermitNo.'.pdf';
		$my_path = "pdfdocs/sbps/";
		$my_name = $CountyName;
		$my_mail = $Email;
		$my_replyto = $CountyEmail;
		$my_subject = "Service Permit";
		$my_message="Kindly receive the Permit for your approved Service";
		
		//mail_attachment($my_file, $my_path, $my_mail, $my_replyto, $my_name, $my_replyto, $my_subject, $my_message);
		//echo 'before'.'<br>';
		$result=php_mailer($my_mail,$CountyEmail,$CountyName,$my_subject,$my_message,$my_file,$my_path,"Permit");
		//echo 'after';
		return $result;
		
	}
	function reCreatePermit($db,$ApplicationID,$row,$UserID)
	{
		//echo $ApplicationID.'<br>'.$row;
		//echo 'User ID: '.$UserID;
		if ($UserID==''){
			return "Restart the application to reset your session";
		}
		$validity=date('Y');
		$permitNo=randomNumber();//time();				
		$expiryDate="31/12/{$validity}";
		$sql="set dateformat dmy  if exists (select 1 from permits where ServiceHeaderID=$ApplicationID) begin delete from Permits where ServiceHeaderID=$ApplicationID end insert into Permits(permitNo,ServiceHeaderID,Validity,ExpiryDate,CreatedBy) values('$permitNo',$ApplicationID,'$validity','$expiryDate','$UserID')";

		$s_result1 = sqlsrv_query($db, $sql);
		if ($s_result1)
		{
			
			$feedBack=createPermit($db,$ApplicationID,$row);
			$msg=$feedBack[1];
			$mail=true;
			/* if($feedBack[0]=="true")
			{
				$mail=true;
			}else
			{
				$mail=false;
			} */
		}
		if($s_result1 && $mail==true)
		{						
			//sqlsrv_commit($db);
			$Sawa=true;
		}else
		{
			//sqlsrv_rollback($db);
			$Sawa=false;
		}
	}
	function mpesaToInvoice($db,$mpesa_acc,$mpesa_code,$mpesa_amt)
	{
		$total=0;
		$params = array();
		$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
		
		
		$ServiceHeaderID=0;

		$sql="Select Distinct ServiceHeaderID from InvoiceLines where InvoiceHeaderID='$mpesa_acc'";

		$result3=sqlsrv_query($db,$sql,$params,$options);
		if($result3)
		{
			
			$records=sqlsrv_num_rows($result3);
			if($records>0)
			{				
				while($row=sqlsrv_fetch_array($result3,SQLSRV_FETCH_ASSOC))
				{
					$ServiceHeaderID=$row['ServiceHeaderID'];
					
					
					$delqry="Delete from Receipts where ReferenceNumber='$mpesa_code'";
					$qresult=sqlsrv_query($db,$delqry);
					if($qresult){
						
					}else{
						DisplayErrors();
					}
				
					$query2 = "insert into  Receipts ([ReceiptDate],[ReceiptMethodID],[ReferenceNumber],[Amount],[ReceiptStatusID],CreatedBy) 
					VALUES(convert(date,getdate()),'1','$mpesa_code','$mpesa_amt','1','1') SELECT SCOPE_IDENTITY() AS ID";
			
					$result1 = sqlsrv_query($db, $query2);
					if ($result1)
					{
						
						$ReceiptID=lastid($result1);							
						
						$query4="Insert into ReceiptLines (ReceiptID,InvoiceHeaderID,Amount,CreatedBy)
							VALUES('$ReceiptID','$mpesa_acc','$mpesa_amt','1')";		
						$result2 = sqlsrv_query($db, $query4);
						if($result2)
						{												
								
						}else
						{
							DisplayErrors();
						}
						
						$delqry="update mpesa set mpesa_acc='$mpesa_acc' where mpesa_code='$mpesa_code'";
						$qresult=sqlsrv_query($db,$delqry);
						if($qresult){
							while ($rw=sqlsrv_fetch_array($delqry,SQLSRV_FETCH_ASSOC))
							{
								$total=$rw['amount'];
							}								
						}else{
							DisplayErrors();
						}
						
						if($splitamount>$total)
						{
							echo $delqry.'<br>splitamount: '. $splitamount.' Total: '.$total;
							$msg="The receipt is overallocated!";
							return $msg;
						}
					}else
					{						
						DisplayErrors();
					}

					if($result1 and $result2 and $result3)
					{
						//echo $query4;
						$msg1 = 'Payment Matched';
					} else 
					{
						DisplayErrors();
						$msg1 = 'Error in Receipting';
					}
											
				}
			}else
			{
				//DisplayErrors();
				$msg1="The reference number entered cannot be matched with any Invoice from the county";
			}
		}else
		{
			DisplayErrors();
			$msg1="Error in the Query";			
		}
		
		return $msg1;		
	}
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
		$mpdf->SetTitle("Acme Trading Co. - Invoice");
		$mpdf->SetAuthor("Acme Trading Co.");
		$mpdf->SetWatermarkText("County Government Of Uasin Gishu");
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
		powered by      <img src="images/attain_logo_2.jpg" alt="County Logo">
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
/* 		echo $html;
		exit; */
		$mpdf->WriteHTML($html);
		
/*  		$mpdf->Output();
		exit; */
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}
	function mpesaTransactions($db,$cosmasRow,$rptName)
	{

		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];
		
		

		$tablestr = '';
		$ReportTitle="MPESA TRANSACTIONS TODAY";
		$sql="set dateformat dmy
				select mpesa_code,mpesa_acc,mpesa_amt,mpesa_sender from mpesa 
				where cast(mpesa_trx_date as date)=convert(date,getdate()) ";
		
				$tblTotals=0;
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{					
					$mpesa_sender = $rw['mpesa_sender'];					
					$mpesa_amt=$rw['mpesa_amt'];
					$mpesa_code=$rw['mpesa_code'];
					$mpesa_acc = $rw['mpesa_acc'];					
					$tblTotals+=$mpesa_amt;
					$tablestr.='<tr>
					<td align="left">'.$mpesa_sender.'</td>
					<td align="left">'.$mpesa_code.'</td>
					<td align="left">'.$mpesa_acc.'</td>
					<td align="right">'.number_format($mpesa_amt,2).'</td>
					</tr>'; 
				}

		
		$mpdf=new mPDF('win-1252','A4','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("Acme Trading Co. - Invoice");
		$mpdf->SetAuthor("Acme Trading Co.");
		$mpdf->SetWatermarkText("County Government Of Uasin Gishu");
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
		powered by      <img src="images/attain_logo_2.jpg" alt="County Logo">
		</div>
		</htmlpagefooter>

		<sethtmlpageheader name="myheader" value="on" show-this-page="1" />
		<sethtmlpagefooter name="myfooter" value="on" />
		mpdf-->
		<br/><br/><br/><br/><br/><br/><br/><br/>
		
		



		<table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
		<thead>
		<tr>
		<td width="35%">Mpesa Sender</td>
		<td width="15%">Mpesa Code</td>
		<td width="35%">Mpesa Account</td>
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
/* 		echo $html;
		exit; */
		$mpdf->WriteHTML($html);
		
 		/* $mpdf->Output();
		exit; */
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}
	function posTransactionsToday($db,$cosmasRow,$rptName)
	{

		$row=$cosmasRow;	
		$CountyName=$row['CountyName'];		
		$CountyAddress=$row['PostalAddress'];
		$CountyTown=$row['Town'];
		$CountyTelephone=$row['Telephone1'];
		$CountyMobile=$row['Mobile1'];
		$CountyEmail=$row['Email'];
		
		

		$tablestr = '';
		$ReportTitle="POS TRANSACTIONS TODAY";
		$sql="select il.createdby AgentID,ag.FirstName+' '+ag.MiddleName+' '+ag.LastName AgentNames,mk.MarketName, isnull(sum(il.Amount),0) Amount 
				from InvoiceLines il
				join Agents ag on il.CreatedBy=ag.AgentID
				join Markets mk on il.MarketID=mk.MarketID
				where PosReceiptID<>''

				and convert(date,il.CreateDate)=convert(date,getdate())
				group by il.CreatedBy,ag.FirstName+' '+ag.MiddleName+' '+ag.LastName,mk.MarketName
				order by mk.MarketName,sum(il.Amount)";
		
				$tblTotals=0;
				$result=sqlsrv_query($db, $sql);
				while($rw=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
				{					
					$AgentID = $rw['AgentID'];					
					$AgentNames=$rw['AgentNames'];
					$MarketName=$rw['MarketName'];
					$Amount = $rw['Amount'];					
					$tblTotals+=$Amount;
					$tablestr.='<tr>
					<td align="left">'.$AgentID.'</td>
					<td align="left">'.$AgentNames.'</td>
					<td align="left">'.$MarketName.'</td>
					<td align="right">'.number_format($Amount,2).'</td>
					</tr>'; 
				}

		
		$mpdf=new mPDF('win-1252','A4','','',20,15,48,25,10,10);
		$mpdf->useOnlyCoreFonts = true;    // false is default
		$mpdf->SetProtection(array('print'));
		$mpdf->SetTitle("Acme Trading Co. - Invoice");
		$mpdf->SetAuthor("Acme Trading Co.");
		$mpdf->SetWatermarkText("County Government Of Uasin Gishu");
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
		powered by      <img src="images/attain_logo_2.jpg" alt="County Logo">
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
		<td width="35%">Agent Name</td>
		<td width="35%">Market</td>
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
/* 		echo $html;
		exit; */
		$mpdf->WriteHTML($html);
		
 		/* $mpdf->Output();
		exit; */
		
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
		$mpdf->SetTitle("Acme Trading Co. - Invoice");
		$mpdf->SetAuthor("Acme Trading Co.");
		$mpdf->SetWatermarkText("County Government Of Uasin Gishu");
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
		powered by      <img src="images/attain_logo_2.jpg" alt="County Logo">
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
/* 		echo $html;
		exit; */
		$mpdf->WriteHTML($html);
		
/*  		$mpdf->Output();
		exit; */
		
		$mpdf->Output('pdfdocs/reports/'.$rptName.'.pdf','F'); 
		
				
	}
?>