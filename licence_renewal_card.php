<?php 
require 'DB_PARAMS/connect.php';
require_once('utilities.php');
require_once('GlobalFunctions.php');
require_once('county_details.php');
require_once('smsgateway.php');
// require("/PHPMailer/src/PHPMailer.php");
// require("/PHPMailer/src/SMTP.php");
// require("/PHPMailer/src/Exception.php");
// require_once("mPDF/mpdf.php");



if (!isset($_SESSION))
{
	session_start();
}
$msg ='';
$UserID = $_SESSION['UserID'];

if (isset($_REQUEST['msg']))
{
	$msg = $_REQUEST['msg'];	
}

$ApplicationID='';
$CustomerName='';
$CustomerID="";
$ServiceName ='';
$ServiceID='';
$Charges=0;
$Notes='';
$ServiceState="";
$CurrentStatus="";
$NextStatus="";
$Customer;
$SubCountyName;
$BusinessZoneID;
$WardName;
$CustomerType="";
$RegNo="";
$PostalAddress="";
$PostalCode="";
$Pin="";
$Vat="";
$Town="";
$Country="";
$Telephone1="";
$Mobile1="";
$Telephone2="";
$Mobile2="";
$Mobile1="";
$url="";
$Email="";
$ServiceHeaderType="";
$SubSystemID=1;
$ApplicationDate='';
$today=date("d/m/Y");
//$DateLine=date('d/m/Y',strtotime('2018-03-31'));
$DateLine=$cosmasRow['SBPDateline'];
$DateLine=date('d/m/Y',strtotime($DateLine));
$BusinessIsOld=0;
$ConservancyCost=0;
$PermitYear=date("Y");
$InvoiceNo=0;
$ServiceCost=0;
$LicenceNumber = "";
$SubmisionDate = "";
$LicenceIssueDate = "";
$LicenceExpiryDate = "";
$ServiceHeaderID= "";

if (isset($_REQUEST['ApplicationID'])) 
{
    $ApplicationID = $_REQUEST['ApplicationID']; 	


}

$today=date('Y-m-d H:i:s');
$FirstDec=date(date('Y')."-12-01 00:00:00");
if($today>$FirstDec){
	$PermitYear=date("Y")+1;
}


    if (isset($_REQUEST['save']) && $_REQUEST['NextStatus']!='')
    {
        // echo '<pre>';
        // print_r($_REQUEST);
        // exit;

        
        $ApplicationID=$_REQUEST['ApplicationID'];
        $ServiceHeaderID=$_REQUEST['ServiceHeaderID'];
        $CustomerID=$_REQUEST['CustomerID'];
        $CurrentStatus=$_REQUEST['CurrentStatus'];
        $NextStatus= $_REQUEST['NextStatus'];
        $Notes=empty($_REQUEST['Notes'])?$_REQUEST['Notes']:'tEST';
        $NextStatusID=$NextStatus;
        $InvoiceNo=$_REQUEST['InvoiceNo'];
        
        if($NextStatus == 5){ //Reject
            /* Begin the transaction. */
            if ( sqlsrv_begin_transaction( $db ) === false ) {
                die( print_r( sqlsrv_errors(), true ));
            }
            $UpdateLicenceRenewalSQL="update LicenceRenewals set 
            LicenceRenewalStatusId=2,
            Renewed = 0,
            Notes ='$Notes'
            where ServiceHeaderId=$ServiceHeaderID AND Renewed=0";
            // exit($UpdateLicenceRenewalSQL);
            $UpdateLicenceRenewalResult = sqlsrv_query($db, $UpdateLicenceRenewalSQL);

            /* If  query is successful, commit the transaction. */
            /* Otherwise, rollback the transaction. */
            if($UpdateLicenceRenewalResult ) {
                sqlsrv_commit( $db );
                echo "Transaction committed.<br />";
                // Header ("Location:renewal_applications_list.php");

            } else {
                sqlsrv_rollback( $db );
                echo "Transaction rolled back.<br />";
                DisplayErrors();
                // Header ("Location:renewal_applications_list.php");

            }
        }else{ //Renew Licence

                /* Begin the transaction. */
            if ( sqlsrv_begin_transaction( $db ) === false ) {
                die( print_r( sqlsrv_errors(), true ));
			}
			// exit($ServiceHeaderID);
			//Get the ServiceId 
			$GetServiceIDSQL = "select ServiceID
									from ServiceHeader  WHERE ServiceHeaderId = $ServiceHeaderID";
			// exit($GetServiceIDSQL);

			$GetServiceIDSQLresult = sqlsrv_query($db, $GetServiceIDSQL);

			while ($row = sqlsrv_fetch_array( $GetServiceIDSQLresult, SQLSRV_FETCH_ASSOC))
			{							
				$ServiceID=$row["ServiceID"];												
			}	


            $LicenceRenewalDate=date('Y-m-d H:i:s');
            $UpdateLicenceRenewalSQL="update LicenceRenewals set 
            LicenceRenewalDate='$LicenceRenewalDate',
            Renewed = 1,
            Notes ='$Notes'
            where ServiceHeaderId=$ServiceHeaderID AND Renewed=0";
            // exit($UpdateLicenceRenewalSQL);
            $UpdateLicenceRenewalResult = sqlsrv_query($db, $UpdateLicenceRenewalSQL);

            //Increase Expirery Date to December Next Year
            $day = 31; $month =12; $year = date("Y")+1;
            $d=mktime(00, 00, 00, $month,$day, $year);
            $ExpireDate = date("Y-m-d H:i:s", $d);
            $UpdateServiceHeaderSql="update ServiceHeader set ExpiryDate='$ExpireDate' 
            where ServiceHeaderID=$ServiceHeaderID";
           $UpdateServiceHeaderResult = sqlsrv_query($db, $UpdateServiceHeaderSql);


			//Generate an Invoice Now
			
			//LiceneceRenewalInvoice Header
            $InvoiceAmount=$_REQUEST['RenewalFee'];
            $InvoiceDate= date("Y-m-d H:i:s");
            $CustomerId = $_REQUEST['CustomerID'];
            $InvoiceNo = 'RENEWAL/'.date('Y').'/'.rand(1 , 999999);
			$UserID = $_SESSION['UserID'];
			
			//Get Banks 
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
            $InsertIntoLicenceRenewalInvoiceHeaderSQL="insert into LiceneRenewaInvoiceHeader
             (InvoiceDate,ServiceHeaderID,InvoiceNo,CustomerID, LicenceRenewalid, CreatedBy) 
			Values('$InvoiceDate','$ServiceHeaderID','$InvoiceNo',
			'$CustomerId','$ApplicationID','$UserID') SELECT SCOPE_IDENTITY() AS ID";
			
            // exit($InsertIntoLicenceRenewalInvoiceHeaderSQL);
            $InsertIntoLicenceRenewalInvoiceHeaderResult = sqlsrv_query($db, $InsertIntoLicenceRenewalInvoiceHeaderSQL);
            
            //Get InvoiceHeaderNo
			$InvoiceHeader=lastid($InsertIntoLicenceRenewalInvoiceHeaderResult);
			
			//Get Penalty Charges if Any
			$GetLicenceRenewalPenaltiesSQL="select * 
			from Penalties WHERE Penalties.ServiceHeaderID = $ServiceHeaderID";
			$GetLicenceRenewalPenaltiesSQLResult = sqlsrv_query($db, $GetLicenceRenewalPenaltiesSQL);
			
			if(sqlsrv_has_rows($GetLicenceRenewalPenaltiesSQLResult)){
				//Insert Them into the Invoice Lines
				while ($data= sqlsrv_fetch_array( $GetLicenceRenewalPenaltiesSQLResult, SQLSRV_FETCH_ASSOC))
				{			
					// echo '<pre>';
					// print_r($data);
					// exit;

					$PenaltyAmount=$data["Amount"];
					// $ServiceID=$row['ServiceID'];
					$TotalPenaltyAmount+=$PenaltyAmount;
					$Description = $data['Description'];

					$InsertPenaltyIntoLicenceRenewalnvoiceLinesSQL="insert into LicenceRenewalnvoiceLines (InvoiceHeaderID,
					ServiceId,
					Description,
					Amount,CreatedDate,CreatedBy) 
					Values($InvoiceHeader,$ServiceID,'$Description',$TotalPenaltyAmount,'$InvoiceDate',$UserID)";

					// exit($InsertPenaltyIntoLicenceRenewalnvoiceLinesSQL);
					$InsertPenaltyIntoLicenceRenewalnvoiceLinesSQLresult = sqlsrv_query($db, $InsertPenaltyIntoLicenceRenewalnvoiceLinesSQL);
					
					if(!$InsertPenaltyIntoLicenceRenewalnvoiceLinesSQLresult){
						sqlsrv_rollback( $db );
						echo "Transaction rolled back.<br />";
					}
				} 
			}

			//Get Service Charges Using the SeriviceId
			$GetServiceChargeSQL="select s.ServiceID,s.ServiceName, Amount 
					from ServiceCharges sc
					join services s on sc.ServiceID=s.serviceid                                 
					join FinancialYear fy on sc.FinancialYearId=fy.FinancialYearID                                      
					and fy.isCurrentYear=1
					and sc.serviceid=$ServiceID";
			// exit($GetServiceChargeSQL);
			$GetServiceChargeSQLResult = sqlsrv_query($db, $GetServiceChargeSQL);

			if(sqlsrv_has_rows($GetServiceChargeSQLResult)){
				while ($row7= sqlsrv_fetch_array( $GetServiceChargeSQLResult, SQLSRV_FETCH_ASSOC))
				{									
					$ServiceAmount=$row7["Amount"];
					// $ServiceID=$row['ServiceID'];
					$InvoiceAmount+=$ServiceAmount;
					$Description = $row7['ServiceName'];

					$InsertIntoLicenceRenewalnvoiceLinesSQL="insert into LicenceRenewalnvoiceLines (InvoiceHeaderID,
					ServiceId,
					Description,
					Amount,CreatedDate,CreatedBy,ServiceHeaderID) 
					Values($InvoiceHeader,
							$ServiceID,
							'$Description',
							$InvoiceAmount,
							'$InvoiceDate',
							$UserID,
							$ServiceHeaderID
						)";
					
					// exit('UR9838E');

					// exit($InsertIntoLicenceRenewalnvoiceLinesSQL);
					$InsertIntoLicenceRenewalnvoiceLinesresult = sqlsrv_query($db, $InsertIntoLicenceRenewalnvoiceLinesSQL);
										
				} 
				//If Everything Went Well, Commit Transactions and Send The Client an Email with the Invoice
				if( $UpdateServiceHeaderResult && $UpdateLicenceRenewalResult && $InsertIntoLicenceRenewalnvoiceLinesresult && $InsertIntoLicenceRenewalInvoiceHeaderResult ) {
					sqlsrv_commit( $db );

					$GetInvoiceLinesSQL="SELECT  [LiceneceRenewalInvoiceLineID]
					,[InvoiceHeaderID]
					,[Description]
					,[Amount]
					FROM [TRANEW].[dbo].[LicenceRenewalnvoiceLines] WHERE InvoiceHeaderID=$InvoiceHeader";

					// EXIT($GetInvoiceLinesSQL);
					$tblTotals=0;
					$GetInvoiceLinesResult=sqlsrv_query($db, $GetInvoiceLinesSQL);
					while($rw=sqlsrv_fetch_array($GetInvoiceLinesResult,SQLSRV_FETCH_ASSOC))
					{					
						$Description = $rw['Description'].'<br>'.$Remark;
						$ServiceAmount = $rw['Amount'];	
						$InvoiceLineID=$rw['LiceneceRenewalInvoiceLineID'];
						$InvoiceHeaderID=$rw['InvoiceHeaderID'];
						$InvoiceNo=$InvoiceHeader;
						$tblTotals+=$ServiceAmount;
						$tablestr.='<tr>
						<td align="center">'.$InvoiceLineID.'</td>
						<td align="center">1</td>
						<td>'.$Description.'</td>
						<td align="right">'.number_format($ServiceAmount,2).'</td>
						<td align="right">'.number_format($ServiceAmount,2).'</td>
						</tr>'; 
					}

					$SerialNo=$InvoiceHeader;
					// createBarCode($InvoiceHeader);
					$mpdf=new mPDF('win-1252','A4','','',20,15,48,25,10,10);
					$mpdf->useOnlyCoreFonts = true;    // false is default
					$mpdf->SetProtection(array('print'));
					$mpdf->SetTitle($CustomerName." - Invoice");
					$mpdf->SetAuthor('TRA');
					$mpdf->SetWatermarkText('Tourism Regulatory Authority');
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
						<td align="Center" colspan="2">
							<img src="images/logo1.png" alt="TRA Logo">
						</td>
					</tr>
					<tr>
						<td align="Center" colspan="2" style="font-size:5mm">
							<b>Licence Renewal Invoice</b>
						</td>
					</tr>
						
					<tr>
						<td width="50%" style="color:#0000BB;">
							Address: 30 <br />
							<br /> 
							Telephone: 0710 467 646</td>
						<td width="50%" style="text-align: right;">			
						Invoice No.<br/><span style="font-weight: bold; font-size: 10pt;">'.$SerialNo.'</span>
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
					<br/><br/><br/><br/>
					<div style="text-align: right">Invoice Date: '.date_format(date_create($CreatedDate),"d/m/Y").'</div>
					
					<table width="100%" style="font-family: serif;" cellpadding="10">
					<tr>
						<td width="45%" style="border: 0.1mm solid #888888;">
							<span style="font-size: 7pt; color: #555555; font-family: sans;">TO:</span><br /><br />'.$CustomerName.'<br /> Postal Address: Meru <br />Nairobi<br />O710 467 646
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
						<td class="blanktotal" colspan="2" rowspan="6"></td>
						<td class="totals" align="left">'.$Description.'</td>
						<td class="totals">Subtotal:</td>
						<td class="totals">'.number_format($tblTotals,2).'</td>
					</tr>
					<tr>
						<td class="blanktotal" rowspan="6"></td>
						<td class="totals"><b>TOTAL:</b></td>
						<td class="totals"><b>'.number_format($tblTotals,2).'</b></td>
					</tr>
					<tr>
					
					<td class="totals"><b>Balance due:</b></td>
					<td class="totals"><b>'.number_format($tblTotals,2).'</b></td>
					</tr>
					</tbody>
					</table>
					Created By <strong>'.$CreatedBy.'</strong><br>
					<div style="font-style: italic; font-size: 10;">
						Payment terms: payment due in 30 days<br>
						Payment by MPESA
						<ol>
						<li> Go to MPESA menu and select <b>Lipa na MPESA</b></li>
						<li> Enter <b>522522</b> as the paybill number and the Invoice Number as the account number</li>
						<li> Pay the amount and enter your MPESA pin number when printed</li>
						</ol>							
						<b>Payment by Bank</b>
						<ol>
							<li>Enter the TRA revenue account invoice number as the account number</li>
						</ol>
											
						Contact us on <b>0720646464</b> for any assistance
					</div>
					<br>
					<div style="text-align: center;">
						<img src="images/Bar_Codes/'.$InvoiceNo.'.PNG">
					</div>
				</body>
			</html>
					';
						/* 		echo $html;
					exit; */
					$mpdf->WriteHTML($html);
					// $mpdf->Output();
					// exit; 
					
					$mpdf->Output('pdfdocs/invoices/'.$SerialNo.'.pdf','F'); 
					
					//send email
					$my_file = $SerialNo.'.pdf';
					$file_path = "pdfdocs/invoices/";
					$my_name ='TRA'; //$CountyName;
					$toEmail ='jimkinyua25@gmail.com';// $Email;
					$fromEmail ='passdevelopment00@gmail.com';// $CountyEmail;
					$my_subject = "Service Application Invoice";
					$my_message="Kindly receive the invoice for your applied Service";
					//$my_mail = 'cngeno11@gmail.com';
					$result=php_mailer($toEmail,$fromEmail,$CountyName,$my_subject,$my_message,$my_file,$file_path,"Invoice");
					createPermit($db, $ServiceHeaderID,$cosmasRow);
					echo "Licence Renewed and Invoice Sent to the Customer via Email<br />";
					// exit;
					// Header ("Location:renewal_applications_list.php");

				} else {
					/* Otherwise, rollback the transaction. */
					sqlsrv_rollback( $db );
					echo "Transaction rolled back.<br />";
					// die( print_r( sqlsrv_errors(), true ));
					DisplayErrors();
					// Header ("Location:renewal_applications_list.php");

				}

			}else{
				echo'The Service is set not to have Renewal charges, hence cannot be invoiced';

			}


        }
              
    }

	$s_sql="select c.*, renewal.*, bt.CustomerTypeName, sh.ServiceStatusID,sh.ServiceHeaderID,s.ServiceName,sh.ServiceID,sh.CreatedDate,sh.SubSystemID,S.ServiceCategoryID
	from Customer c 
	left join ServiceHeader sh on sh.CustomerID=c.CustomerID
	join services s on sh.ServiceID=s.ServiceID
	left join LicenceRenewals renewal on sh.ServiceID=renewal.ServiceId 
	left join CustomerType bt on bt.CustomerTypeID=c.BusinessTypeID 
	where renewal.LicenceId=$ApplicationID AND renewal.Renewed=0";

    
    // echo '<pre>';
    // print_r($s_sql);
    // exit;

	$s_result=sqlsrv_query($db,$s_sql);


	// echo $s_sql;exit;

	if ($s_result)
	{
		
		
		while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC)){	
		
			// echo '<pre>';
			// print_r($row);
			// exit;	
			$CustomerType=$row['CustomerTypeName'];
			$CustomerID=$row['CustomerID'];
			$CustomerName=$row['CustomerName'];
			$ServiceID=$row['ServiceID'];
			$ServiceName=$row['ServiceName'];
			$CurrentStatus=$row['ServiceStatusID'];
			$ServiceCategoryID=$row['ServiceCategoryID'];
			$RegNo=$row['RegistrationNumber'];
			$PostalAddress=$row['PostalAddress'];
			$PostalCode=$row['PostalCode'];
			$ServiceHeaderTypeID=$row['ServiceHeaderType'];
			$ServiceHeaderID=$row['ServiceHeaderID'];
			$Pin=$row['PIN'];
			$Vat=$row['VATNumber'];
			$Town=$row['Town'];
			$Country=$row['CountyID'];
			$Telephone1=$row['Telephone1'];
			$Mobile1=$row['Mobile1'];
			$Telephone2=$row['Telephone2'];
			$Mobile2=$row['Mobile2'];
			$Mobile1=$row['Mobile1'];
			$url=$row['Url'];
			$Email=$row['Email'];
			$SubCountyName=$row['SubCountyName'];
			$WardName=$row['WardName'];
			$BusinessZone=$row['ZoneName'];
			$SubSystemID=$row['SubSystemID'];
			$ApplicationDate=$row['CreatedDate'];//date('d/m/Y',strtotime($date));
			$ApplicationDate=date('d/m/Y',strtotime($ApplicationDate));
			$LicenceNumber = $row['LicenceNo'];
			$SubmisionDate = $row['SubmissionDate'];
			$LicenceIssueDate = $row['IssueDate'];
			$LicenceExpiryDate =$row['ExpiryDate'];
			$ServiceFee = $row['RenewalFee'];
			$ServiceHeaderID = $row['ServiceHeaderID'];
			
		}
	}


//get the serviceCost

	if ($ServiceHeaderTypeID==1)
	{
		$BSql="select l.RatesPayable from LandApplication la join land l on la.PlotNo=l.PlotNo and la.LRN=l.LRN where la.ServiceHeaderID=$ApplicationID";
		$rsult=sqlsrv_query($db,$BSql);
		//echo $BSql;
		if ($rsu=sqlsrv_fetch_array($rsult,SQLSRV_FETCH_ASSOC))
		{
			$ServiceCost=$rsu['RatesPayable'];							
		}else
		{
			$ServiceCost=0;
		}	
	}else
	{

		//get the subsystem
		$sql="select fn.Value, ss.SubSystemName from fnFormData($ServiceHeaderID) fn 
				join SubSystems ss on fn.Value=ss.SubSystemID
				where formcolumnid=12237";
		$res=sqlsrv_query($db,$sql);
		while($row=sqlsrv_fetch_array($res,SQLSRV_FETCH_ASSOC))
		{
			$SubSystemID=$row['Value'];
			$SubSystemName=$row['SubSystemName'];
		}

		//get the ward

		$sql="select fn.Value, w.WardName from fnFormData($ServiceHeaderID) fn 
			join Wards w on fn.Value=w.WardID
			where fn.formcolumnid=11204
			";
		$res=sqlsrv_query($db,$sql);
		while($row=sqlsrv_fetch_array($res,SQLSRV_FETCH_ASSOC))
		{
			$WardName=$row['WardName'];
		}	

		$ServiceCost=getServiceCost($db,$ServiceID,$SubSystemID,$ServiceHeaderID);
		
	}

function getServiceCost($db,$ServiceID,$SubSystemID,$ServiceHeaderID){
	//echo $SubSystemID.'<BR>';
	$sql="select * from fnServiceCost($ServiceID,$SubSystemID)";
	$result=sqlsrv_query($db,$sql);
	if ($result)
	{
		while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
		{									
			$ServiceCost=$row['Amount'];
		}

		//Conservancy
		$sql1="select * from fnConservancyCost($ServiceCost,$SubSystemID)";
		//echo $sql1;
		$rs=sqlsrv_query($db,$sql1);
		if ($rs)
		{
			while($row=sqlsrv_fetch_array($rs,SQLSRV_FETCH_ASSOC))
			{									
				$ConservancyCost=$row["Amount"];										
			}	
		}	

		//penalty
		//echo 'The Business is '.$BusinessIsOld;
		if(strtotime($ApplicationDate)>strtotime($DateLine) and $BusinessIsOld==1)
			$penalty=.50*(double)$ServiceCost;
		else{
			$penalty=0;
		}
		//echo $ServiceCost;
		 /* echo $ServiceCost.'<BR>';
		echo $penalty;  */
		/*echo '<br>'.$ApplicationDate;*/
		$OtherCharge=0;
		//With other Charges?
	    $sql="select Amount 
	            from ServiceCharges sc
	            join services s on sc.ServiceID=s.serviceid                                 
	            join FinancialYear fy on sc.FinancialYearId=fy.FinancialYearID                                      
	            and fy.isCurrentYear=1
	            and sc.SubSystemId=$SubSystemID
	            and sc.serviceid=281";

									// echo $sql;

									// echo '<br><br>'
		
		$s_result = sqlsrv_query($db, $sql);
		while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
		{							
			$OtherCharge=$row["Amount"];												
		}

		//Application Charges
		$ApplicationCharge=0;
	    $sql="select sum(sc.Amount) Amount 
	            from ApplicationCharges sc 
	            join ServiceHeader sh on sh.serviceheaderid=sc.serviceheaderid 
	            join Services s1 on sc.ServiceID=s1.ServiceID 
	            where sh.ServiceHeaderID=$ServiceHeaderID";

	    //echo $sql;

	    $result=sqlsrv_query($db,$sql);
	    while($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC))
	    {
	        $ApplicationCharge=$row['Amount'];
	    }
	//echo $sql;
	//echo '<BR>'.$OtherCharge.'<BR>';
		$ServiceCost=$ServiceCost+$OtherCharge+$penalty+$ApplicationCharge+$ConservancyCost;
		return $ServiceCost;
	}
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
		$mpdf->SetWatermarkText("Tourism Regulatory Authority");
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
							<b>BUSINESS PERMIT</b>
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
		$my_name = 'Test'; //$CountyName;
		$my_mail = 'jimkinyua25@gmail.com'; //$Email;
		$my_replyto = 'jameskinyua190@gmail.com'; //$CountyEmail;
		$my_subject = "Service Permit";
		$my_message="Kindly receive the Permit for your approved Service";
		
		//mail_attachment($my_file, $my_path, $my_mail, $my_replyto, $my_name, $my_replyto, $my_subject, $my_message);
		//echo 'before'.'<br>';
		$result=php_mailer($my_mail,$my_replyto,$my_name,$my_subject,$my_message,$my_file,$my_path,"Licence");
		//echo 'after';
		return $result;
		
	}

//get the Arrears

if (isset($_REQUEST['approve']))
{	
	$input=array_slice($_REQUEST,2,count($input)-1);	
	foreach ($input AS $id => $value)
	{	
		$newID=substr($id,3,strlen($id)-3);	
			
		$sql="if exists(select * from FormData where FormColumnID=$newID)
				Update FormData set Value='$value' where FormColumnID=$newID and ServiceHeaderID=$ApplicationID
			  else
				insert into FormData (FormColumnID,ServiceHeaderID,Value)
			    values($newID,$ApplicationID,'$value')";
				
		$result=sqlsrv_query($db,$sql);
		
		if(!$result)
		{
			DisplayErrors();
			continue;
		}		

	}	
}
if (isset($_REQUEST['change']))
{	
	$ApplicationID=$_REQUEST['ApplicationID'];
	$FromServiceID=$_REQUEST['FromServiceID'];
	$ToServiceID=$_REQUEST['ToServiceID'];
	$FromSubSystemID=$_REQUEST['FromSubSystemID'];
	$ToSubSystemID=$_REQUEST['ToSubSystemID'];
	$FromWardID=$_REQUEST['FromWardID'];
	$ToWardID=$_REQUEST['ToWardID'];
	$CurrentStatus=$_REQUEST['CurrentStatus'];
	$Notes=$_REQUEST['Notes'];

	
	
	if($FromServiceID==$ToServiceID && $FromSubSystemID==$ToSubSystemID && $FromWardID==$ToWardID){
		$msg="You have made not change in the application";
	}else if($CurrentStatus>4){
		$msg="The Application Cannot Be modified at this stage";
	}
	else
	{

		$sql="Update ServiceHeader Set ServiceID=$ToServiceID where ServiceHeaderID=$ApplicationID";
		$result=sqlsrv_query($db,$sql);
		if($result){

			$rst=SaveTransaction($db,$UserID," Changed Application number $InvoiceHeader from service $FromServiceID to $ToServiceID");

			$sql="Insert into ServiceHeaderChange(ServiceHeaderID,FromServiceID,ToServiceID,CreatedBy,Notes)
			Values ($ServiceHeaderID,$FromServiceID,$ToServiceID,$UserID,'$Notes')";
			
			$result1=sqlsrv_query($db,$sql);
			if($result1)
			{
				$msg ="Application Changed Successfully";
			}else{
				DisplayErrors();
			}
			
			if($FromServiceID!==$ToServiceID)
			{
				$sql="Update InvoiceLines Set ServiceID=$ToServiceID where ServiceHeaderID=$ApplicationID and ServiceHeaderID=$FromServiceID";
				
				$result2=sqlsrv_query($db,$sql);
				if($result2){
					$msg ="Application Changed Successfully";
				}else{
					DisplayErrors();
				}
			}

			if($FromSubSystemID!==$ToSubSystemID)
			{
				$sql="Update FormData Set value=$ToSubSystemID where ServiceHeaderID=$ApplicationID and FormColumnID=12237";
				$result2=sqlsrv_query($db,$sql);
				echo $sql;
				if($result2){
					$rst=SaveTransaction($db,$UserID," Changed Application number $InvoiceHeader from SubSystem $FromSubSystemID to $ToSubSystemID");
					$msg ="Application Changed Successfully";
				}else{
					DisplayErrors();
				}
			}
			
			if($FromWardID!==$ToWardID)
			{
				$sql="Update FormData Set value=$ToWardID where ServiceHeaderID=$ApplicationID and FormColumnID=11204";
				$result2=sqlsrv_query($db,$sql);
				if($result2){
					$rst=SaveTransaction($db,$UserID," Changed Application number $InvoiceHeader from Ward $FromWardID to $ToWardID");				
					$msg ="Application Changed Successfully";

				}else{
					DisplayErrors();
				}
			}
			
			$ServiceCost=getServiceCost($db,$ToServiceID,$ToSubSystemID);
		}	
	}
}





if (isset($_REQUEST['addofficer']))
{	
	$ApplicationID=$_REQUEST['ApplicationID'];
	$User_ID=$_REQUEST['User_ID'];
	
	if($CurrentStatus>4){
		$msg="The Application Cannot Be modified at this stage";
	}
	else
	{

			// $sql="Insert into InspectionOfficers(UserID, ServiceHeaderID)
			// Values ($User_ID, $ServiceHeaderID)";
			$sql="insert into Inspections(ServiceHeaderID,UserID,InspectionStatusID) values($ApplicationID,$User_ID,0)";

			// echo $sql;exit;
			
			$result1=sqlsrv_query($db,$sql);
			if($result1)
			{
				$msg ="The Inspection Officer Has Been Successfully Added";
			}else{
				DisplayErrors();
			}
	}
}


if (isset($_REQUEST['InspectionDate']))
{	
	$ApplicationID=$_REQUEST['ApplicationID'];
	$SetDate=$_REQUEST['SetDate'];
	
	if($CurrentStatus>4){
		$msg="The Application Cannot Be modified at this stage";
	}
	else
	{

			$sql="update ServiceHeader set SetDate = '$SetDate' where ServiceHeaderID =$ApplicationID";

			// echo $sql;
			
			$result1=sqlsrv_query($db,$sql);
			if($result1)
			{
				$msg ="The Inspection Date Has Been Set";
			}else{
				DisplayErrors();

			}
	}
}

// echo '<pre>';
// print_r($CustomerName);
// exit;


	 //$ServiceCost=$ServiceCost-OtherCharge;
  //echo $ServiceID.'<br>';
  // echo 'Subsustem '.$SubSystemID.'<BR>';
  // echo 'ServiceCost '.$ServiceCost.'<BR>';
  //  echo 'Conservancy '.$ConservancyCost.'<BR>';
  // echo 'Other Charges '.$OtherCharge.'<BR>'; 
  

?>
<script type="text/javascript">
	$("#addCharges").on('click', function(ev){
		var url = 'add_charge.php?ApplicationID=' 
			+ ev.target.dataset.appId + '&SubSystemID=' + ev.target.dataset.ssId+ '&ServiceID=' + ev.target.dataset.sId+ '&Renew=0'
		//console.log(url)
		$.get(url, function(res) {
			$.Dialog({
		        shadow: true,
		        overlay: false,
		        draggable: true,
		        icon: '<span class="icon-rocket"></span>',
		        title: 'Application Charges',
		        width: 500,
		        padding: 10,
		        content: res
		    });
		})
	    
	});
</script>
<div class="example">
   <legend>Licence Renewal Approval</legend>
   <form>
      <fieldset>
          <table width="100%" border="0" cellspacing="0" cellpadding="3">
              <tr>
                <td colspan="2" align="center" style="color:#F00"><?php echo $msg; ?></td>
            </tr>
              <tr>
                 <td width="50%">
					<label>Customer Name</label>
					  <div class="input-control text" data-role="input-control">
						  <input name="customer" type="text" id="customer" value="<?php echo $CustomerName; ?>" disabled="disabled" placeholder="">
						  
					  </div>                 	
                  </td>
                  <td></td>                  
              </tr>
			  <tr>
                  <td width="50%">
                  <label>Application No</label>
					  <div class="input-control text" data-role="input-control">
						  <input name="ServiceHeaderID" type="text" id="ServiceHeaderID" value="<?php echo $ServiceHeaderID; ?>" disabled="disabled" placeholder="">						  
					  </div>				  
                  </td>
                  <td width="50%">				  
                  </td>   
              </tr>
              <!-- <tr>
                  <td width="50%">
                  <label>Ward</label>
					  <div class="input-control text" data-role="input-control">
						  <input name="Ward" type="text" id="Ward" value="<?php echo $WardName; ?>" disabled="disabled" placeholder="">						  
					  </div>				  
                  </td>
                  <td width="50%">				  
                  </td>   
              </tr> -->
              <tr>
                  <td width="50%">
                  <label>Region</label>
					  <div class="input-control text" data-role="input-control">
						  <input name="SubSystem" type="text" id="SubSystem" value="<?php echo $SubSystemName; ?>" disabled="disabled" placeholder="">						  
					  </div>				  
                  </td>
                  <td width="50%">				  
                  </td>   
              </tr>
              <tr>
                  <td width="50%">
                  <label>Licence Being Renewed</label>
					  <div class="input-control text" data-role="input-control">
						  <input name="servicename" type="text" id="servicename" value="<?php echo $ServiceName; ?>" disabled="disabled" placeholder="">
						  
					  </div>				  
                  </td>
                  <!-- <td width="50%"> -->
				<!-- <label>&nbsp;</label>				   -->
					<!--service_approval.php?ApplicationID='+app_id+'&app_type='+app_type+'&CurrentStatus='+current_status
					<input name="Button" type="button" onclick="loadmypage('service_form.php?save=1&ApplicationID=<?php echo $ApplicationID ?>','content','loader','','')" value="Change">-->
					<!-- <input name="Button" type="button" 
					onclick="loadmypage('application_change.php?ApplicationID=<?php echo $ApplicationID; ?>&CurrentStatus=<?php echo $CurrentStatus; ?>','content','loader','','')" value="Change"> -->
                  <!-- </td>    -->
              </tr>	


<tr>

	<?php
	$sql="select SetDate from ServiceHeader where ServiceHeaderID = $ApplicationID";
	$s_result=sqlsrv_query($db,$sql);
		if ($s_result){
			?>
			
			<?php
			while($row=sqlsrv_fetch_array($s_result,SQLSRV_FETCH_ASSOC))
				{									
					$SetDate = $row['SetDate'];
				}
			}
			?>
                
                  <td width="50%">
				<label>&nbsp;</label>				  
					<!--service_approval.php?ApplicationID='+app_id+'&app_type='+app_type+'&CurrentStatus='+current_status
					<input name="Button" type="button" onclick="loadmypage('service_form.php?save=1&ApplicationID=<?php echo $ApplicationID ?>','content','loader','','')" value="Change">-->
					<input name="Button" type="button" 
					onclick="loadmypage('inspection_date.php?ApplicationID=<?php echo $ApplicationID; ?>&CurrentStatus=<?php echo $CurrentStatus; ?>','content','loader','','')" value="Renew Licence">
                  </td>   
              </tr>




			  <tr>
				   <td width="50%">
						<label>Service Cost (Ksh.)</label>
						  <div class="input-control text" data-role="input-control">
							  <input name="servicecost" type="text" id="servicecost" value="<?php echo $ServiceFee; ?>" disabled="disabled" placeholder="">
							  
						  </div>                  	
                  </td>
                  <td>
                  			<label>&nbsp</label>
						  <div class="input-control text" data-role="input-control">
							  <input id="addCharges" name="Button" type="button" 
								data-app-id="<?php echo $ApplicationID; ?>"
								data-ss-id="<?php echo $SubSystemID; ?>"
								data-s-id="<?php echo $ServiceID; ?>"
							 value="Add Charges">
							  
						  </div>
                  </td>
			  </tr>
				<tr>
					<td colspan="2">
						<HR>         
						<div class="tab-control" data-role="tab-clontrol">
						<div class="tab-control" data-role="tab-control">
							<ul class="tabs">
								<li class=""><a href="#_page_4">Applicant's Details</a></li>	
								<li class="active"><a href="#_page_1">Aplication Details</a></li>
								<li class=""><a href="#_page_3">Application Attachments</a></li>
								<!-- <li class=""><a href="#_page_2">Notes</a></li> -->
								<li class=""><a href="#_page_5">Inspection Officers</a></li>
							</ul>							
							<div class="frames">
								<div class="frame" id="_page_4" style="display: none;">
									<fieldset>
										<table width="50%" border="0" cellspacing="0" cellpadding="3">
											<tr>
												<td width="50%">
												   <label>Customer Name</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="CustomerName" type="text" id="CustomerName" value="<?php echo $CustomerName; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td>                 
												<td width="50%">
												   <label>Business Type</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="CustomerType" type="text" id="CustomerType" value="<?php echo $CustomerType; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td>                        
											<tr> 
											<tr>
												<td width="50%">
												   <label>Registration No</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="RegNo" type="text" id="RegNo" value="<?php echo $RegNo; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td> 
												<td width="50%">
												   <label>Pin</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="Pin" type="text" id="Pin" value="<?php echo $Pin; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td>                   
											</tr>
											<tr>
												<td width="50%">
												   <label>Postal Address</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="PostalAddress" type="text" id="PostalAddress" value="<?php echo $PostalAddress; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td> 
												<td width="50%">
												   <label>Postal Code</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="PostalCode" type="text" id="PostalCode" value="<?php echo $PostalCode; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td>                   
											</tr> 
											<tr>
												<td width="50%">
												   <label>Town</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="Town" type="text" id="Town" value="<?php echo $Town; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td> 
												<td width="50%">
												   <label>Country</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="Country" type="text" id="Country" value="<?php echo $Country; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td>                   
											</tr> 
											<tr>
												<td colspan="2">
												   <label>Physical Location</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="Location" type="text" id="Location" value="<?php echo $Town; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td>                    
											</tr>                                     
											 <tr>
												<td width="50%">
												   <label>Telephone 1</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="Telephone1" type="text" id="Telephone1" value="<?php echo $Telephone1; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td>
																		 
												<td width="50%">
												   <label>Telephone 2</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="Telephone2" type="text" id="Telephone2" value="<?php echo $Telephone2; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td>  
																 
											</tr>
											<tr>
												<td width="50%">
												   <label>Mobile 1</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="Mobile1" type="text" id="Mobile1" value="<?php echo $Mobile1; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td> 
												<td width="50%">
												   <label>Mobile 2</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="Mobile2" type="text" id="Mobile2" value="<?php echo $Mobile2; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td>                   
											</tr>
											<tr>
												<td width="50%">
												   <label>Email</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="Email" type="text" id="Email" value="<?php echo $Email; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td> 
												<td width="50%">
												   <label>Url</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="Url" type="text" id="Url" value="<?php echo $url; ?>" disabled="disabled" placeholder="">
													  <button class="btn-clear" tabindex="-1"></button>
												  </div>
												</td>                   
											</tr>                   
														   
										</table>            
									</fieldset>
								  </div>
								  <div class="frame" id="_page_2" style="display: block;">
									  <table class="hovered" cellpadding="3" cellspacing="1">
										<?php 
											$sql="SELECT SH.ServiceHeaderID, SS.ServiceStatusName, SAA.Notes, SAA.CreatedDate, U.FirstName+' '+U.MiddleName+' '+u.LastName UserFullNames
													FROM dbo.ServiceApprovalActions AS SAA INNER JOIN
													dbo.ServiceHeader AS SH ON SAA.ServiceHeaderID = SH.ServiceHeaderID INNER JOIN
													dbo.Agents AS U ON SAA.CreatedBy = U.AgentId INNER JOIN
													dbo.ServiceStatus AS SS ON SAA.ServiceStatusID = SS.ServiceStatusID
													where SH.ServiceHeaderID=$ApplicationID";

													$s_result=sqlsrv_query($db,$sql);
													
													if ($s_result)
													{
														while($row=sqlsrv_fetch_array($s_result,SQLSRV_FETCH_ASSOC))
														{									
															echo "<tr><td>".$row["ServiceStatusName"]."</td><td>".$row["Notes"]."</td><td>".$row["CreatedDate"]."</td><td>".$row["UserFullNames"]."</td></tr>";
														}
													}
										?>             	
									  </table>              
								  </div>
							  <div class="frame" id="_page_3" style="display: none;">
									<table class="hovered" cellpadding="3" cellspacing="1">
										<?php 
											$sql="select d.DocumentName,att.ID
													from Attachments att
													join Documents d on d.DocumentID=att.DocumentID
													 where att.ApplicationNo=$ApplicationID";

													$s_result=sqlsrv_query($db,$sql);
													
													if ($s_result){
														while($row=sqlsrv_fetch_array($s_result,SQLSRV_FETCH_ASSOC)){									
															echo "<tr>
																<td>
																<a href='documentdownload.php?id=".$row["ID"]."' target='_blank' >".$row["DocumentName"]." </a>
																</td>
															</tr>";
															}
													}
										?>             	
									  </table> 
								  </div>
								  <div class="frame" id="_page_1" style="display: none;">
									<table width="100%">
										<tr>
											<td >
												<label>Licence Number</label>  

												  <div class="input-control text" data-role="input-control">
													  <input name="SubCounty" type="text" id="SubCounty" value="<?php echo $LicenceNumber; ?>" disabled="disabled">													  
												  </div>
											</td>
											<td >
												<label>Submision Date</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="WardName" type="text" id="WardName" value="<?php echo $SubmisionDate; ?>" disabled="disabled">													  
												  </div>
											</td>
											<td >
												<label>Licence Issue Date</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="ZoneName" type="text" id="ZoneName" value="<?php echo $LicenceIssueDate; ?>" disabled="disabled">													  
												  </div>
											</td>	

                                            											<td >
												<label>Licence Expiry Date</label>
												  <div class="input-control text" data-role="input-control">
													  <input name="ZoneName" type="text" id="ZoneName" value="<?php echo $LicenceExpiryDate; ?>" disabled="disabled">													  
												  </div>
											</td>										
										</tr>
										
										<?php  
											if ($ServiceHeaderTypeID==1)
											{
												$sql=" select lrn,plotno,mplotno,titleno from landapplication where ServiceHeaderID='$ApplicationID'";
												$s_result=sqlsrv_query($db,$sql);
												if ($s_result){
													while($row=sqlsrv_fetch_array($s_result,SQLSRV_FETCH_ASSOC))
													{									
														echo "<tr><td>Lrn No: </td><td> ".$row["lrn"]."</td></tr>";
														echo "<tr><td>Plot No: </td><td> ".$row["plotno"]."</td></tr>";
														echo "<tr><td>Mother PlotNo: </td><td> ".$row["mplotno"]."</td></tr>";
														echo "<tr><td>Title No:</td><td> ".$row["titleno"]."</td></tr>"; 
														
														$lrn=$row["lrn"];
														$plotno=$row["plotno"];
														
														$BSql="select RatesPayable,PrincipalBalance from LAND where lrn='".$row["lrn"]."' and PlotNo='".$row["plotno"]."'";
														//echo ($BSql);
														$rsult=sqlsrv_query($db,$BSql);
														if ($rsu=sqlsrv_fetch_array($rsult,SQLSRV_FETCH_ASSOC))
														{
															$Balance=$rsu['PrincipalBalance'];
															$Rates=$rsu['RatesPayable'];
														}else
														{
															$balance=0;
														}														
													}													
													echo "<tr><td>Rates Payable:</td><td> ".$Rates."</td></tr>";
													echo "<tr><td>Outstanding Balance:</td><td> ".$Balance."</td></tr>";
													echo "<a href='statement.php?popupwindow&lrn=$lrn&plotno=$plotno' class='popupwindow' target='_blank'>Rates Statement</a>";
												}else
												{
													echo $sql;
												}												
												
											}else if ($ServiceHeaderTypeID==2)
											{
												$sql="select h.HouseNo,e.EstateID,e.EstateName from HouseApplication h join Estates e on h.EstateID=e.EstateID where h.serviceheaderid=$ApplicationID";
												$s_result=sqlsrv_query($db,$sql);
												if ($s_result){
													while($row=sqlsrv_fetch_array($s_result,SQLSRV_FETCH_ASSOC)){									
														echo "<tr><td>Estate Name: </td><td> ".$row["EstateName"]."</td></tr>";
														echo "<tr><td>House No: </td><td> ".$row["HouseNo"]."</td></tr>";														
														}
												}else
												{
													echo $sql;
												}												
											}else if ($ServiceHeaderTypeID==3)
											{
												$sql="select ha.FromDate,ha.ToDate,s.ServiceName from HireApplication ha 
														join ServiceHeader sh on ha.ServiceHeaderID=sh.ServiceHeaderID
														join Services s on sh.ServiceID=s.ServiceID where ha.ServiceHeaderID=$ApplicationID";
												$s_result=sqlsrv_query($db,$sql);
												if ($s_result){
													while($row=sqlsrv_fetch_array($s_result,SQLSRV_FETCH_ASSOC)){
														echo "<tr><td>Facilty: </td><td> ".$row["ServiceName"]."</td></tr>";
														echo "<tr><td>Start Date: </td><td> ".$row["FromDate"]."</td></tr>";
														echo "<tr><td>End Date: </td><td> ".$row["ToDate"]."</td></tr>";														
														}
												}else
												{
													echo $sql;
												}												
											}else
											{
												$sql="select FD.ServiceHeaderID,FC.FormColumnName,FD.Value,FD.FormDataID,fc.ColumnDataTypeID,fc.Notes from FormData fd 
											  inner join FormColumns fc on fd.FormColumnID=fc.FormColumnID
											  WHERE FD.ServiceHeaderID=$ApplicationID
											  ORDER BY FC.Priority";

													$s_result=sqlsrv_query($db,$sql);
													
													if ($s_result){
														while($row=sqlsrv_fetch_array($s_result,SQLSRV_FETCH_ASSOC)){
															$rValue=$row['Value'];
															$Notes=$row['Notes'];
															
															if($Notes!='')
															{
																$rsult='';
																$Notes=str_replace("'","''",$Notes);
																//echo $notes; exit;
																$sql="select * from [dbo].[fnColumnDetails] ('$Notes')";									
																$rsult=sqlsrv_query($db,$sql);
																if ($rsult)
																{
																	while($rw=sqlsrv_fetch_array($rsult,SQLSRV_FETCH_ASSOC))
																	{
																		$Table=$rw['table_name'];
																		$displayName=$rw['display_column'];
																		$ColumnName=$rw['column_name'];
																		
																		$myQry="select ".$displayName ." from ".$Table." where ".$ColumnName." =$rValue";
																		$DName=sqlsrv_query($db,$myQry);
																		if($DName)
																		{
																			
																			while($rww=sqlsrv_fetch_array($DName,SQLSRV_FETCH_ASSOC))
																			{
																				$rValue=$rww[$displayName];
																			}
																			
																		}else
																		{

																		}
																		// echo $rValue;
																		
																	}
																}else
																{

																}
															}
															
															
															$dataType=$row['ColumnDataTypeID'];									
																								
															echo "<tr><td>".$row["FormColumnName"]."</td><td>".$rValue."</td></tr>";
															}
													}
											}
										
										?>            	
									</table> 
								  </div>


								</div>

						  </div>

						</div>					
					</td>
				</tr>

				<tr>

					<td width="100%"><label>Notes</label>
					  <div class="input-control textarea" data-role="input-control">
						<textarea name="Notes" type="textarea> id="Notes" placeholder=""><?php //echo $Notes; ?></textarea>  
					  </div>
					</td>                  
					<td width="50%"></td>   
				</tr>	



            <tr>
              <td width="50%">
                <label>Action</label>
                <div class="input-control select" data-role="input-control">
                  <select name="NextStatus"  id="NextStatus">                    
                    <?php 
                         
						
						$s_sql="SELECT Id,StatusName  from LicenceRenewalStatus where Id in (4,5)";						

						
						$s_result = sqlsrv_query($db, $s_sql);
						if ($s_result) 
						{ //connection succesful 
						  while ($row = sqlsrv_fetch_array( $s_result, SQLSRV_FETCH_ASSOC))
						  {
							  $s_name = $row["StatusName"];							  
							  $s_id = $row["Id"];
                                    
						   ?>
						  <option value="<?php echo $s_id; ?>" <?php echo $selected; ?>><?php echo $s_name; ?></option>
						<?php 
						  }
						}
                          ?>
                  </select> 
                  <?php  //echo $s_sql;  ?>
                </div></td>
                <td width="50%"></td>   
            </tr>                       
            		
          </table> 
          
           <?php
 $numrows = 0;
 
$r_sql = "Select COUNT(UserID) AS TotalRows FROM Inspections where ServiceHeaderID ='$ApplicationID'";
// echo $r_sql;
// exit();
$result = sqlsrv_query($db, $r_sql);
if ($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
{		
	$numrows = $myrow['TotalRows'];
}


$SetDate1 = 0;
$ServiceType = 0;
$d_sql = "select SetDate,ServiceID from ServiceHeader where ServiceHeaderID = '$ApplicationID'";

$dresult = sqlsrv_query($db, $d_sql);
if ($myrow = sqlsrv_fetch_array( $dresult, SQLSRV_FETCH_ASSOC)) 
{		
	$SetDate1 = $myrow['SetDate'];
	$ServiceType = $myrow['ServiceID'];
}
          ?>

          
        
          <?php 
     
           ?>
		  
           <input type="reset" value="Cancel" onClick="loadmypage('renewal_applications_list.php?i=1','content','loader','listpages','','applications','<?php echo $_SESSION['RoleCenter'] ?>')">


		  <input name="Button" type="button" onClick="
		    CurrStatus=this.form.CurrentStatus.value;

		  	loadpage('licence_renewal_card.php?save=1&ApplicationID=<?php echo $ApplicationID ?>&CustomerName=<?php echo $CustomerName ?>&CustomerID=<?php echo $CustomerID ?>&ServiceID=<?php echo $ServiceID ?>&RenewalFee=<?php echo $ServiceFee ?>&ServiceHeaderID=<?php echo $ServiceHeaderID ?>&ServiceName=<?php echo $ServiceName ?>&CurrentStatus=<?php echo $CurrentStatus ?>&NextStatus='+this.form.NextStatus.value+'&Notes='+this.form.Notes.value+'&ServiceCategoryID=<?php echo $ServiceCategoryID ?>','content')
		  

		    " value="Submit ">

		  <?php
		



		  ?>

          <span class="table_text">
          <input name="ApplicationID" type="hidden" id="ApplicationID" value="<?php echo $ApplicationID;?>" />
  <input name="edit" type="hidden" id="edit" value="<?php echo $edit;?>" />
  <input name="edit" type="hidden" id="CurrentStatus" value="<?php echo $CurrentStatus;?>" />
                  </span>
          <div style="margin-top: 20px">
  </div>


      </fieldset>
  </form>                  
