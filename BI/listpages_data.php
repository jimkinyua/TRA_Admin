<?php
//.................................................
//. Date: 2014                                    .
//. Developer: Michael Omutimba Andanje           .
//. Gets User Data for all List pages			  .
//.................................................
require_once 'DB_PARAMS/connect.php';
require_once 'utilities.php';
$CurrentUser=$_SESSION['UserID'];
$exParam='';
$channel = array();
if (isset($_REQUEST['OptionValue'])) { $OptionValue = $_REQUEST['OptionValue']; }
if (isset($_REQUEST['exParam'])) { $exParam = $_REQUEST['exParam']; }

if ($OptionValue == 'users')
{
	$sql = "select * from Users";
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);
		$Date 	= date('d/m/Y',strtotime($CreatedDate));
		
		$PageID=2;
		$myRights=getrights($db,$CurrentUser,$PageID);
		if ($myRights)
		{
			$View=$myRights['View'];
			$Edit=$myRights['Edit'];
			$Add=$myRights['Add'];
			$Delete=$myRights['Delete'];
		}
		
		$ResetBtn='<a href="#" onClick="deleteConfirm2(\'Are you sure you wish to reset the password for '.$UserName.'\',\'users_list.php?reset=1&UserName='.$UserName.'&UserID='.$UserID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Reset Password</a>';
		
		if($myRights['Edit']==1){
			$EditBtn='<a href="#" onClick="loadpage(\'users.php?edit=1&UserID='.$UserID.'\',\'content\',\'\',\''.$myRights['Edit'].'\')">Edit</a>';
		}else{
			$EditBtn='';
		}
		if($myRights['Delete']==1){
			$DeleteBtn='<a href="#" onClick="deleteConfirm2(\'Are you sure you wish to delete this record\',\'users_list.php?delete=1&UserID='.$UserID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\',\''.$myRights['Delete'].'\')">Delete</a>';
		}else
		{
			$$DeleteBtn='';
		}
		$channel[] = array(
					$UserFullNames,
					$UserName,
					$Date,
					$ResetBtn,
					$EditBtn,					
					$DeleteBtn
		);
	}	
}
else if ($OptionValue == 'UserRoles')
{
	$sql = "select ur.UserID,ur.UserRoleID,u.FirstName+' '+u.MiddleName+' '+u.LastName UserFullNames,u.UserName,
			rc.RoleCenterName from UserRoles ur
			inner join Agents u on ur.UserID=u.AgentID 
			inner join RoleCenters rc on ur.RoleCenterID=rc.RoleCenterID";
	//echo $sql;
	$result = sqlsrv_query($db, $sql);
	while($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC))
	{
		extract($myrow);	
		$EditBtn = '<a href="#" onClick="loadpage(\'user_role.php?edit=1&UserRoleID='.$UserRoleID.'\',\'content\')">Edit</a>';			
		$DeleteBtn = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'user_roles_list.php?delete=1&UserRoleID='.$UserRoleID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Delete</a>';
		
		$channel[] = array(
					 $UserFullNames
					,$UserName
					,$RoleCenterName
					,$EditBtn
					,$DeleteBtn
					);
	}
}
else if ($OptionValue == 'Departments')
{
	$sql = "SELECT * FROM Departments";
	//echo $sql;
	$result = sqlsrv_query($db, $sql);
	while($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC))
	{
		extract($myrow);		
		$CreatedDate = date('Y-m-d',strtotime($CreatedDate));
		
		$EditBtn = '<a href="#" onClick="loadpage(\'departments_edit.php?edit=1&DepartmentID='.$DepartmentID.'&destform=1\',\'defaultpage\',\'progressbar\')">Edit</a>';
		$DeleteBtn   = '<a href="#" onClick="deleteConfirm(\'Are you sure you want to Delete?\',\'departments_list.php?delete=1&DepartmentID='.$DepartmentID.'\',\'defaultpage\',\'progressbar\')">Delete</a>';
		
		$channel[] = array(
					 $DepartmentName
					,$Description
					,$CreatedBy
					,$CreatedDate
					,$EditBtn
					,$DeleteBtn
					);
	}
}
else if ($OptionValue == 'ServiceGroups')
{
	$sql = "select ServiceGroupID,ServiceGroupName,Label from ServiceGroup";
			
	$result = sqlsrv_query($db, $sql);
	while($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC))
	{
		extract($myrow);		
		$CreatedDate = date('Y-m-d',strtotime($CreatedDate));
		
		
		$EditBtn = '<a href="#" onClick="loadpage(\'servicegroup.php?edit=1&ServiceGroupID='.$ServiceGroupID.'\',\'content\')">Edit</a>';
		$DeleteBtn   = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'servicegroup_list.php?delete=1&ServiceGroupID='.$ServiceGroupID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Delete</a>';
		
		$channel[] = array(
					 $ServiceGroupID
					,$ServiceGroupName
					,$EditBtn
					,$DeleteBtn
					);
	}
}
else if ($OptionValue == 'ServiceCategories')
{
	$sql = "SELECT sc.ServiceCategoryID,sc.ServiceCode,sc.CategoryName,sg.ServiceGroupName	 
			FROM ServiceCategory sc 
			inner join ServiceGroup sg on sc.ServiceGroupID=sg.ServiceGroupID
			left join Departments dp on sg.DepartmentID=dp.DepartmentID order by 1 desc";
			
	#sql="select 1,2,3,4,5,6";
	$result = sqlsrv_query($db, $sql);
	while($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC))
	{
		extract($myrow);		
		$CreatedDate = date('Y-m-d',strtotime($CreatedDate));
		
		
		$EditBtn = '<a href="#" onClick="loadpage(\'servicecategory.php?edit=1&ServiceCategoryID='.$ServiceCategoryID.'\',\'content\')">Edit</a>';
		$DeleteBtn   = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'servicecategory_list.php?delete=1&ServiceCategoryID='.$ServiceCategoryID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Delete</a>';
		$WorkFlow = '<a href="#" onClick="loadmypage(\'approval_steps_list.php?edit=1&ServiceCategoryID='.$ServiceCategoryID.'\',\'content\',\'loader\',\'listpages\',\'\',\'AprovalSteps\','.$ServiceCategoryID.')">WorkFlow</a>';
										  
		
		
		$channel[] = array(
					 $ServiceCode
					,$CategoryName
					,$ServiceGroupName
					,$EditBtn
					,$DeleteBtn
					,$WorkFlow
					);
	}
}

else if ($OptionValue == 'services')
{
	$sql = "Select Services.ServiceID,Services.ServiceName,Services.ServiceCode, CategoryName, ServiceGroupName From Services	
	JOIN ServiceCategory ON ServiceCategory.ServiceCategoryID = Services.ServiceCategoryID
	JOIN ServiceGroup ON ServiceGroup.ServiceGroupID = ServiceCategory.ServiceGroupID
	ORDER BY ServiceGroup.ServiceGroupName,ServiceCategory.CategoryName";
	$result = sqlsrv_query($db, $sql);
	while($myrow = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC))
	{
		extract($myrow);
		$historyString=urlencode("loadmypage('services_list.php?i=1','content','loader','listpages','','services')");
		$CreatedDate = date('Y-m-d',strtotime($CreatedDate));
		$ServicePBtn = '<a href="#" onClick="loadmypage(\'serviceplus_list.php?A_ServiceID='.$ServiceID.'\',\'content\',\'loader\',\'listpages\',\'\',\'ServicePlus\',\''.$ServiceID.'\')">FEES</a>';
		$EditBtn = '<a href="#" onClick="loadpage(\'services.php?edit=1&ServiceID='.$ServiceID.'\',\'content\')">Edit</a>';
		$DeleteBtn   = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'services_list.php?delete=1&ServiceID='.$ServiceID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Delete</a>';
		$ChargesBtn = '<a href="#" onClick="loadmypage(\'service_charges_list.php?ServiceID='.$ServiceID.'&ServiceName='.$ServiceName.'&histString='.$historyString.'\',\'content\',\'loader\',\'listpages\',\'\',\'service_charges\',\''.$ServiceID.'\')">Charges</a>';
		$actions='['.$ServicePBtn.'|'.$ChargesBtn.'|'.$EditBtn.']';
		
		$channel[] = array(
					 $ServiceID
					 ,$ServiceCode
					,$ServiceName
					,$ServiceGroupName
					,$actions
					);
	}
}
else if($OptionValue=='invoices-a')
{
	$sql = "set dateformat dmy select distinct sh.ServiceHeaderID,ih.InvoiceHeaderID, ih.CustomerID,ih.InvoiceDate [INV DATE],c.CustomerName,s.ServiceName +'('+il.[Description]+')' 		ServiceName,ih.Paid,sum(il.Amount) Amount
			from InvoiceHeader ih
			inner join InvoiceLines il on il.InvoiceHeaderID=ih.InvoiceHeaderID
			inner join ServiceHeader sh on il.ServiceHeaderID=sh.ServiceHeaderID
			inner join Customer c on sh.CustomerID=c.CustomerID	
			inner join Services s on sh.ServiceID=s.ServiceID 
			where il.InvoiceLineID not in (select InvoiceLineID from ConsolidateInvoice) and sh.serviceStatusID>4 
			group by sh.ServiceHeaderID, ih.CustomerID,ih.InvoiceDate,c.CustomerName,s.ServiceName,ih.Paid,ih.InvoiceHeaderID,sh.ServiceHeaderID,il.[Description]";
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{			
		extract($row);
		
		$CustomerName =  '<a href="#" onClick="loadmypage(\'invoice_lines.php?ApplicationID='.$ServiceHeaderID.'&ServiceName='.$ServiceName.'\',\'content\',\'loader\',\'listpages\',\'\',\'invoices_lines\','.$ServiceHeaderID.')">'.$CustomerName.'</a>';	
		$ViewBtn  = '<a href="view_pdf.php?report='.$InvoiceHeaderID.'&type=invoice" target="_blank">View</a>';
		$ResetBtn   = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want Reset?\',\'invoices_list.php?reset=1&ApplicationID='.$ServiceHeaderID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Reset</a>';
		
		$actions='['.$ViewBtn.'|'.$ResetBtn.']';
		
		$Date 	= date('d/m/Y',strtotime($CreatedDate));
		$channel[] = array(
					$InvoiceHeaderID,
					$ServiceHeaderID,
					$CustomerName,
					$ServiceName,
					$Amount,
					$Paid,
					$actions
		);
	}  	
}
else if($OptionValue=='invoices_lines')
{
	$sql = "select s.ServiceID,s.ServiceName,il.Amount 
			from InvoiceLines il
			join Services s on il.ServiceID =s.ServiceID
			where ServiceHeaderID=$exParam";
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{			
		extract($row);
		
		//$CustomerName =  '<a href="#" onClick="loadmypage(\'receipts.php?approve=1&InvoiceHeaderID='.$InvoiceHeaderID.'\',\'content\',\'loader\',\'listpages\',\'\',\'receipts\','.$InvoiceHeaderID.')">'.$CustomerName.'</a>';	
		///$ResetBtn   = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want Reset?\',\'invoices_list.php?reset=1&ApplicationID='.$ServiceHeaderID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Reset</a>';
		$channel[] = array(
					$ServiceID,
					$ServiceName,
					$Amount
		);
	}  	
}

else if($OptionValue=='invoices-b')
{
			
	$sql="set dateformat dmy select il.PosReceiptID ReceiptNo,il.CreateDate,il.ServiceHeaderID,il.invoicelineId,il.amount,
	ag.AgentID,Ag.FirstName+' '+ag.MiddleName+' '+ag.LastName [Agent],
	mk.MarketName,s.ServiceName

	from InvoiceLines il 
	join Agents ag on il.CreatedBy=ag.AgentID
	join (select * from UserDevices where DeviceUserStatusID=1) ud on ud.DeviceUserID=ag.AgentID
	join Markets mk on ud.MarketID=mk.MarketID
	join ServiceHeader sh on il.ServiceHeaderID=sh.ServiceHeaderID
	join Services s on sh.ServiceID=s.ServiceID where il.PosReceiptID is not null
	order by sh.ServiceHeaderID desc";
			
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{		
		
		
		extract($row);
		$amount=(double)$amount;
		$CustomerName =  '<a href="#" onClick="loadmypage(\'receipts.php?approve=1&InvoiceHeaderID='.$InvoiceHeaderID.'\',\'content\',\'loader\',\'receipts\')">'.$CustomerName.'</a>';	
		
		$Date 	= date('d/m/Y',strtotime($CreateDate));
		$channel[] = array(
					$Date,
					$ReceiptNo,					
					$amount,
					$ServiceName,
					$Agent,
					$MarketName
		);
	}  	
}

else if($OptionValue=='devices')
{
	$sql = "select d.DeviceID,d.DeviceSerialNo,d.Description,d.DeviceID,d.MacAddress,d.Status,dt.DeviceTypeName DeviceType from devices d
			inner join DeviceType dt on d.DeviceTypeID=dt.DeviceTypeID";

	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		
		
		
		extract($row);
		
		$EditBtn = '<a href="#" onClick="loadpage(\'device.php?edit=1&SerialNo='.$DeviceSerialNo.'\',\'content\')">Edit</a>';
		$DeleteBtn   = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'device_list.php?delete=1&DeviceID='.$DeviceID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Delete</a>';


		$channel[] = array(
					$DeviceSerialNo,
					$Description,
					$MacAddress,
					$DeviceType,
					$Status,
					$EditBtn,
					$DeleteBtn
		);
	}  	
}

else if($OptionValue=='user_devices')
{
	$sql = "select  ud.*,du.firstName+' '+du.MiddleName+' '+du.LastName DeviceUser,iu.firstName+' '+iu.MiddleName+' '+iu.LastName IssueingUser,dt.DeviceTypeName,m.MarketName,dus.DeviceUserStatusDescription [Status]  from UserDevices ud
			inner join Agents du on ud.deviceuserId=du.AgentID
			inner join Devices d on ud.deviceserialno=d.DeviceSerialNo
			inner join DeviceType dt on d.DeviceTypeID=dt.DeviceTypeID
			inner join Agents iu on ud.CreatedBy=iu.AgentID
			left join DeviceUserStatus dus on ud.DeviceUserStatusID=dus.DeviceUserStatusID
			left join Markets m on ud.MarketID=m.MarketID
			where dus.DeviceUserStatusID<>2";

	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{	
		extract($row);
		
		$EditBtn = '<a href="#" onClick="loadpage(\'user_devices.php?edit=1&UserDeviceID='.$UserDeviceID.'\',\'content\')">Edit</a>';
		$ReturnBtn   = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Return this device?\',\'user_devices_list.php?return=1&UserDeviceID='.$UserDeviceID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Return</a>';
		if($DeviceUserStatusID==3){
			$BlockBtn   = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Block this device?\',\'user_devices_list.php?unblock=1&UserDeviceID='.$UserDeviceID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Unblock</a>';
		}else
		{
			$BlockBtn   = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Block this device?\',\'user_devices_list.php?block=1&UserDeviceID='.$UserDeviceID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Block</a>';
		}
		
		$actions='['.$EditBtn.'|'.$ReturnBtn.'|'.$BlockBtn.']';
		
		$DateIssued = date('Y-m-d',strtotime($CreatedDate));
		
		$channel[] = array(
					$DeviceSerialNo,
					$DeviceTypeName,
					$DeviceUser,
					$Status,
					$DateIssued,
					$MarketName,
					$actions
		);
	}  	
}

else if($OptionValue=='service_charges')
{
	$sql = "select sc.*, ss.SubSystemName,ct.ChargeTypeName,ls.LinkedServiceName,fy.FinancialYearName from ServiceCharges sc
			inner join SubSystems ss on sc.SubSystemId=ss.SubSystemID
			left join ChargeType ct on sc.ChargeTypeID=ct.ChargeTypeID
			left join LinkedService ls on sc.LinkedServiceID=ls.LinkedServiceID
			inner join FinancialYear fy on sc.FinancialYearID=fy.FinancialYearID
			where ServiceID=$exParam and fy.isCurrentYear=1 
			order by sc.ServiceID";

	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
				
		extract($row);
		
		$EditBtn ='';// '<a href="#" onClick="loadpage(\'service_charges.php?edit=1&ServiceID='.$exParam.'&SubSystemID='.$SubSystemId.'&FinancialYearID='.$FinancialYearId.'&ServiceAmount='.$Amount.'\',\'content\')">Edit</a>';
		$DeleteBtn   = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'service_charges_list.php?delete=1&ServiceID='.$exParam.'&SubSystemID='.$SubSystemID.'&FinancialYearID='.$FinancialYearID.'&LinkServiceID='.$LinkServiceID.'&ChargeTypeID='.$ChargeTypeID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Delete</a>';

		
		$DateIssued = date('Y-m-d',strtotime($CreatedDate));
		
		$channel[] = array(
					$FinancialYearName,
					$SubSystemName,
					$Amount,
					$EditBtn,
					$DeleteBtn					
		);
	}  	
}
else if($OptionValue=='requisitions')
{
	$sql = "select r.*,dp.DepartmentName,ras.Name Status,(select sum(amount) from requisitionlines 
			where RequisitionHeaderID=r.requisitionheaderid)Amount
			from requisitionheader r
			inner join Departments dp on r.departmentid=dp.DepartmentID
			inner join RequisitionApprovalStatus ras on r.ApprovalStatusID=ras.RequisitionApprovalStatusID";

	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
				
		extract($row);
		
		$EditBtn = '<a href="#" onClick="loadpage(\'service_charges.php?edit=1&RequisitionHeaderID='.$RequisitionHeaderID.'\',\'content\')">Edit</a>';
		$DeleteBtn   = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'requisition_list.php?delete=1&RequisitionHeaderID='.$RequisitionHeaderID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Delete</a>';
		$ApproveBtn = '<a href="#" onClick="loadpage(\'requisition_approval.php?edit=1&RequisitionHeaderID='.$RequisitionHeaderID.'\',\'content\')">Approve</a>';

		
		$DateIssued = date('Y-m-d',strtotime($CreatedDate));
		
		$channel[] = array(
					$DepartmentName,
					$RequisitionDate,
					$Notes,
					$Amount,
					$Status,
					$EditBtn,
					$DeleteBtn,
					$ApproveBtn					
		);
	}  	
}

else if($OptionValue=='receipts')
{
	if ($exParam=="")
	{
		$sql = "select distinct rec.ReferenceNumber ReceiptNo,rec.ReceiptID, rec.ReceiptDate,rl.InvoiceHeaderID,rl.Amount,c.CustomerName,rm.ReceiptMethodName,rec.ReceiptStatusID
			from Receipts rec
			join (select rel.ReceiptID, rel.InvoiceheaderID,sum(rel.amount)Amount from receiptlines rel group by rel.Invoiceheaderid,rel.ReceiptID)rl on rl.ReceiptID=rec.ReceiptID
			left join invoiceheader ih on rl.InvoiceHeaderID=ih.InvoiceHeaderID		
			left join Customer c on ih.CustomerID=c.CustomerID
			left join ReceiptMethod rm on rec.ReceiptMethodID=rm.ReceiptMethodID
			order by rec.ReceiptDate desc";		
	}else
	{
		$sql = "select distinct rec.ReferenceNumber ReceiptNo,rec.ReceiptID, rec.ReceiptDate,rl.InvoiceHeaderID,rl.Amount,c.CustomerName,rm.ReceiptMethodName,rec.ReceiptStatusID
			from Receipts rec
			join (select rel.ReceiptID, rel.InvoiceheaderID,sum(rel.amount)Amount from receiptlines rel group by rel.Invoiceheaderid,rel.ReceiptID)rl on rl.ReceiptID=rec.ReceiptID
			left join invoiceheader ih on rl.InvoiceHeaderID=ih.InvoiceHeaderID		
			left join Customer c on ih.CustomerID=c.CustomerID
			left join ReceiptMethod rm on rec.ReceiptMethodID=rm.ReceiptMethodID
			where rl.InvoiceHeaderID=$exParam
			order by rec.ReceiptDate desc";
	}
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{				
		extract($row);
		$VerifyBtn  = '<a href="#" onClick="deleteConfirm2(\'Verify this Receipt?\',\'receipts_list.php?verify=1&receiptno='.$ReceiptNo.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Verify</a>';
		$Effect  = '<a href="#" onClick="deleteConfirm2(\'Effect this Payment?\',\'receipts_list.php?effect=1&ReceiptID='.$ReceiptID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\',\'\')">Effect</a>';
		$DateIssued = date('Y-m-d',strtotime($CreatedDate));
		
		$actions='['.$Effect.'|'.$VerifyBtn.']';
		
		$channel[] = array(
					$ReceiptNo,
					$ReceiptDate,
					$InvoiceHeaderID,
					$Amount,
					$CustomerName,
					$ReceiptMethodName,
					$ReceiptStatusID,
					$actions
		);
	}  	
}
else if($OptionValue=='Mpesa')
{
	$sql = "select distinct tstamp [Date],mpesa_code,mpesa_acc,mpesa_amt,mpesa_sender from mpesa";	
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{				
		extract($row);
		$btnLink  = '<a href="#" onClick="loadpage(\'mpesa_to_invoice.php?link=1&mpesa_code='.$mpesa_code.'&mpesa_amt='.$mpesa_amt.'&mpesa_sender='.$mpesa_sender.'\',\'content\')">Link</a>';
		$DateIssued = date('Y-m-d',strtotime($CreatedDate));
		
		$channel[] = array(
					$Date,
					$mpesa_code,
					$mpesa_acc,
					$mpesa_amt,
					$mpesa_sender,
					$btnLink
		);
	}  	
}

else if($OptionValue=='applications')
{
	$sql = "SELECT sh.ServiceHeaderID AS ApplicationID,sh.ServiceStatusID,ss.ServiceStatusName, 
	s.ServiceName ,c.CustomerID, c.CustomerName, sh.SubmissionDate,s.ServiceID,f.ServiceHeaderType ApplicationType,s.ServiceCategoryID
	FROM dbo.ServiceHeader AS sh INNER JOIN 
	dbo.Services AS s ON sh.ServiceID = s.ServiceID INNER JOIN
	dbo.Customer AS c ON sh.CustomerID = c.CustomerID INNER JOIN 
	dbo.ServiceStatus ss ON sh.ServiceStatusID=ss.ServiceStatusID INNER JOIN
	DBO.ServiceCategory sc on s.ServiceCategoryID=sc.ServiceCategoryID INNER JOIN
	dbo.Forms f on sh.FormID=f.FormID
	where s.ServiceCategoryID!=1 and sh.ServiceStatusID NOT IN (0,7) and sh.ServiceStatusID in 
	(select ServiceStatusID from RoleCenterApproval where RoleCenterID=$exParam)
	and (sc.InvoiceStage<>sc.LastStage or sh.ServiceStatusID<>sc.LastStage) 
	and sh.ServiceID not in (select ServiceID from ServiceTrees)
	order by sh.SubmissionDate desc";

	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);
		$app_type=$ApplicationType;
		//$SubmissionDate= date('d/m/Y',strtotime($SubmissionDate));	
		//$CustomerName =  '<a href="#" onClick="loadpage(\'service_approval.php?ApplicationID='.$ApplicationID.'&CustomerName='.$CustomerName.'&CustomerID='.$CustomerID.'&ServiceID='.$ServiceID.'&ServiceName='.$ServiceName.'&CurrentStatus='.$ServiceStatusID.'&ServiceCategoryID='.$ServiceCategoryID.'\',\'content\')">'.$CustomerName.'</a>';
		$CustomerName =  '<a href="#" onClick="loadoptionalpage('.$ApplicationID.','.$app_type.','.$ServiceStatusID.',\'content\',\'loader\',\'listpages\',\'\',\''.$ApplicationID.'\')">'.$CustomerName.'</a>';
		$DeleteBtn   = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'clients_list.php?delete=1&ApplicationID='.$ApplicationID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Delete</a>';
		$channel[] = array(			
				$ApplicationID,
				$CustomerName,				
				$ServiceName,
				$SubmissionDate,
				$ServiceStatusName,
				$DeleteBtn
		);			
	}  	
	//print_r($channel);
}
else if($OptionValue=='subcounties')
{
	$sql = "select * from SubCounty order by 1";//and (sh.ServiceHeaderID=96 or sh.ServiceHeaderID=95)
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);		
		
		$SubCountyName =  '<a href="#" onClick="loadpage(\'subcounty.php?edit=1&SubCountyID='.$SubCountyID.'\',\'content\')">'.$SubCountyName.'</a>';
		$DeleteBtn   = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'subcounty_list.php?delete=1&SubCountyID='.$SubCountyID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Delete</a>';
	
		$channel[] = array(	
			$SubCountyID,		
			$SubCountyName,
			$DeleteBtn
		);
		
	}  	
}
else if($OptionValue=='Banks')
{
	$sql = "select * from Banks order by 1";//and (sh.ServiceHeaderID=96 or sh.ServiceHeaderID=95)
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);		
		
		$BankName =  '<a href="#" onClick="loadpage(\'bank.php?edit=1&BankID='.$BankID.'\',\'content\')">'.$BankName.'</a>';
		$DeleteBtn   = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'banks_list.php?delete=1&BankID='.$BankID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Delete</a>';
	
		$channel[] = array(	
			$BankName,		
			$AccountNumber,
			$Branch,
			$DeleteBtn
		);
		
	}  	
}
else if($OptionValue=='wards')
{
	$sql = "SELECT w.WardID,w.WardName,sb.SubCountyName
  			FROM Wards w join SubCounty sb on w.SubCountyID=sb.SubCountyID order by w.SubCountyID";//and (sh.ServiceHeaderID=96 or sh.ServiceHeaderID=95)
	//echo $sql;
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);
		$WardName =  '<a href="#" onClick="loadpage(\'ward.php?edit=1&WardID='.$WardID.'\',\'content\')">'.$WardName.'</a>';
		$DeleteBtn   = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'wards_list.php?delete=1&WardID='.$WardID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Delete</a>';
		$channel[] = array(			
					$WardID,
					$WardName,
					$SubCountyName,
					$DeleteBtn
		);
		
	}  	
}
else if($OptionValue=='zones')
{
	$sql = "SELECT bz.ZoneID, bz.ZoneName,w.wardname,sb.SubCountyName
			FROM BusinessZones bz
			join Wards w on bz.wardid=w.wardid
			join SubCounty sb on w.SubCountyID=w.SubCountyID";//and (sh.ServiceHeaderID=96 or sh.ServiceHeaderID=95)
	//echo $sql;
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);
				
		$ZoneName =  '<a href="#" onClick="loadpage(\'zones.php?edit=1&ZoneID='.$ZoneID.'\',\'content\')">'.$ZoneName.'</a>';
		
	
		$channel[] = array(			
					$ZoneName,
					$wardname,
					$SubCountyName,
					'',
					''
						
		);
		
	}  	
}
else if($OptionValue=='ServiceStatus')
{
	$sql = "select ServiceStatusID,ServiceStatusName,ServiceStatusDisplay from servicestatus";//and (sh.ServiceHeaderID=96 or sh.ServiceHeaderID=95)
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);	
		
		$EditBtn = '<a href="#" onClick="loadpage(\'service_status.php?edit=1&ServiceStatusID='.$ServiceStatusID.'\',\'content\')">Edit</a>';
		$DeleteBtn  = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'service_status_list.php?delete=1&ServiceStatusID='.$ServiceStatusID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Delete</a>';
		$actions='['.$EditBtn.'|'.$DeleteBtn.']';	
	
		$channel[] = array(			
					$ServiceStatusID,
					$ServiceStatusName,
					$ServiceStatusDisplay,
					$actions					
		);
		
	}  	
}
else if($OptionValue=='markets')
{
	$sql = "select m.*,w.WardName Ward from markets m inner join Wards w on m.WardID=w.WardID";//and (sh.ServiceHeaderID=96 or sh.ServiceHeaderID=95)
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);		
		
		$Services = '<a href="#"    onClick="loadpage(\'market_service_assignment.php?MarketID='.$MarketID.'&MarketName='.$MarketName.'\',\'content\')">Services</a>';
		$MarketName =  '<a href="#" onClick="loadpage(\'markets.php?edit=1&MarketID='.$MarketID.'\',\'content\')">'.$MarketName.'</a>';		
		$DeleteBtn   = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'market_list.php?delete=1&MarketID='.$MarketID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Delete</a>';
		
		$actions='['.$Services.'|'.$DeleteBtn.']';

	
		$channel[] = array(	
					$MarketID,		
					$MarketName,
					$Ward,
					$actions
		);
		
	}  	
}
else if ($OptionValue=='marketservices')
{
	$sql = "SELECT ms.MarketServiceID,mk.MarketID,mk.MarketName,s.ServiceID,s.ServiceName
	  FROM [COUNTYREVENUE].[dbo].[MarketServices] ms
	  inner join markets mk on ms.MarketID=mk.MarketID
	  inner join Services s on ms.ServiceID=s.ServiceID";
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);
				
		$EditBtn = '<a href="#" onClick="loadpage(\'marketservice.php?edit=1&MarketServiceID='.$MarketServiceID.'\',\'content\')">Edit</a>';
		$DeleteBtn   = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'marketservice_list.php?delete=1&MarketServiceID='.$MarketServiceID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Delete</a>';

	
		$channel[] = array(	
					$MarketName,		
					$ServiceName,
					$EditBtn,
					$DeleteBtn
		);
		
	} 
	
}
else if ($OptionValue=='Pages')
{
	$sql = "select p.*,mg.MenuGroupName from Pages p left join MenuGroups mg on p.MenuGroupID=mg.MenuGroupID";
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);
				
		$PageName = '<a href="#" onClick="loadpage(\'pages.php?edit=1&PageID='.$PageID.'\',\'content\')">'.$PageName.'</a>';
		$DeleteBtn   = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'pages_list.php?delete=1&PageID='.$PageID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Delete</a>';

	
		$channel[] = array(	
					$PageID,		
					$PageName,
					$MenuGroupName,
					$DeleteBtn
		);
		
	} 
	
}
else if ($OptionValue=='MenuGroups')
{
	$sql = "select * from MenuGroups";
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);
				
		$MenuGroupName = '<a href="#" onClick="loadpage(\'menu_group.php?edit=1&MenuGroupID='.$MenuGroupID.'\',\'content\')">'.$MenuGroupName.'</a>';
		$DeleteBtn   = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'menu_groups_list.php?delete=1&MenuGroupID='.$MenuGroupID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Delete</a>';

	
		$channel[] = array(	
					$MenuGroupID,		
					$MenuGroupName,
					$DeleteBtn
		);
		
	} 
	
}
else if ($OptionValue=='RoleCenters')
{
	$sql = "select * from RoleCenters";
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);
		$CenterName=$RoleCenterName;		
		$RoleCenterName = '<a href="#" onClick="loadpage(\'role_center.php?edit=1&RoleCenterID='.$RoleCenterID.'\',\'content\')">'.$RoleCenterName.'</a>';
		$DeleteBtn   = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'role_centers_list.php?delete=1&RoleCenterID='.$RoleCenterID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Delete</a>';
		$RoleCenter   = '<a href="#" onClick="loadmypage(\'role_center_roles.php?RoleCenterName='.$CenterName.'&RoleCenterID='.$RoleCenterID.'\',\'content\',\'loader\',\'listpages\',\'\',\'RoleCenterRoles\','.$RoleCenterID.')">ROLE CENTER</a>';
		$ServiceApprovals   = '<a href="#" onClick="loadpage(\'role_center_approval.php?RoleCenterName='.$CenterName.'&RoleCenterID='.$RoleCenterID.'\',\'content\')">Approvals</a>';
		
		$channel[] = array(		
					$RoleCenterName,
					$DeleteBtn,
					$RoleCenter,
					$ServiceApprovals
		);
		
	} 
	
}
else if ($OptionValue=='Roles')
{
	$sql = "select * from Roles";
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);
		$DeleteBtn   = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'userroles_list.php?delete=1&RoleCenterID='.$RoleCenterID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Delete</a>';	
		$channel[] = array(	
					$UserRoleID,		
					$UserID,
					$RoleCenterID,
					$DeleteBtn
		);
		
	} 
	
}
else if ($OptionValue=='ServiceTrees')
{
	$sql = "SELECT st.*,st2.Description Parent,s.ServiceName
			FROM [ServiceTrees] st
			left join Services s on st.ServiceID=s.ServiceID
			left join ServiceTrees st2 on st.ParentID=st2.ServiceTreeID order by st2.ParentID";
			
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);
		$fieldname='chkService';
		$IsItService='';
		if ($IsService == 1) {$IsItService = 'checked="checked"';}
		$EditBtn = '<a href="#" onClick="loadpage(\'service_tree.php?edit=1&ServiceTreeID='.$ServiceTreeID.'\',\'content\')">Edit</a>';
		$DeleteBtn   = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'service_trees_list.php?delete=1&ServiceTreeID='.$ServiceTreeID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Delete</a>';
		$IsService='<input id="'.$fieldname.'" name="'.$fieldname.'" type="checkbox" '. $IsItService.'/>';
		$actions='['.$EditBtn.'|'.$DeleteBtn.']';
	
		$channel[] = array(	
					$Description,		
					$Parent,
					$IsService,
					$ServiceName,
					$actions
		);
		
	} 
	
}
else if ($OptionValue=='RoleCenterRoles')
{
	$sql = "select pages.*, RoleID, [View], [Edit], [Add], [Delete] from roles
			RIGHT JOIN pages ON pages.PageID = roles.PageID 
			AND RoleCenterID = $exParam";
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);
		$DeleteBtn   = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'userroles_list.php?delete=1&RoleCenterID='.$RoleCenterID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Delete</a>';

		$fieldname1 = 'V_'.$PageID.'_'.$RoleID;
		$fieldname2 = 'E_'.$PageID.'_'.$RoleID;
		$fieldname3 = 'A_'.$PageID.'_'.$RoleID;
		$fieldname4 = 'D_'.$PageID.'_'.$RoleID;
		
		$link .= "+'&".$fieldname1."='+this.form.".$fieldname1.'.checked';
		$link .= "+'&".$fieldname2."='+this.form.".$fieldname2.'.checked';
		$link .= "+'&".$fieldname3."='+this.form.".$fieldname3.'.checked';
		$link .= "+'&".$fieldname3."='+this.form.".$fieldname4.'.checked';
		
		$Viewvalue = '';
		$Editvalue = '';
		$Addvalue = '';
		$Deletevalue = '';
		
		if ($View == 1) {$Viewvalue = 'checked="checked"';}
		if ($Edit == 1) {$Editvalue = 'checked="checked"';}
		if ($Delete == 1) {$Deletevalue = 'checked="checked"';}
		if ($Add == 1) {$Addvalue = 'checked="checked"';}
		
		$channel[] = array(	
					$PageName,
					'<input id="'.$fieldname1.'" name="'.$fieldname1.'" type="checkbox" '. $Viewvalue.'/>',
					'<input id="'.$fieldname2.'" name="'.$fieldname2.'" type="checkbox" '. $Editvalue.'/>',
					'<input id="'.$fieldname3.'" name="'.$fieldname3.'" type="checkbox" '. $Addvalue.'/>',
					'<input id="'.$fieldname4.'" name="'.$fieldname4.'" type="checkbox" '. $Deletevalue.'/>'
		);
		
	} 
	
}
else if ($OptionValue=='Forms')
{
	$sql = "select * from Forms";
			
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);
		$ColumnBtn ='<a href="#" onClick="loadmypage(\'form_columns_list.php?FormID='.$FormID.'&FormName='.$FormName.'\',\'content\',\'loader\',\'listpages\',\'\',\'FormColumns\','.$FormID.')">Columns</a>';
		$SectionBtn ='<a href="#" onClick="loadmypage(\'form_sections_list.php?FormID='.$FormID.'&FormName='.$FormName.'\',\'content\',\'loader\',\'listpages\',\'\',\'FormSections\','.$FormID.')">Sections</a>';
		$EditBtn = '<a href="#" onClick="loadpage(\'form.php?edit=1&FormID='.$FormID.'\',\'content\')">Edit</a>';
		$DeleteBtn  = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'forms_list.php?delete=1&FormID='.$FormID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Delete</a>';
		$actions='['.$ColumnBtn.'|'.$SectionBtn.'|'.$EditBtn.'|'.$DeleteBtn.']';
	
		$channel[] = array(	
					$FormID,		
					$FormName,
					$actions
		);
		
	} 
	
}
else if ($OptionValue=='FormSections')
{
	$sql = "select fs.*,f.FormName from FormSections fs inner join Forms f on fs.FormID=f.FormID where f.FormID=$exParam";
			
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);
		
		$EditBtn = '<a href="#" onClick="loadpage(\'form_sections.php?edit=1&FormSectionID='.$FormSectionID.'&FormName='.$FormName.'\',\'content\')">Edit</a>';
		$DeleteBtn  = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'form_sections_list.php?delete=1&FormSectionID='.$FormSectionID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Delete</a>';
		$actions='['.$EditBtn.'|'.$DeleteBtn.']';
	
		$channel[] = array(	
					$FormSectionName,
					$actions
		);
		
	}	
}
else if ($OptionValue=='FormColumns')
{
	$sql = "select fc.*,f.FormName,fs.FormSectionName,fc.ColumnSize,cdt.ColumnDataTypeName from FormColumns fc 
		inner join Forms f on fc.FormID=f.FormID
		left join ColumnDataType cdt on fc.ColumnDataTypeID=cdt.ColumnDataTypeID
		left join FormSections fs on fc.FormSectionID=fs.FormSectionID
		where f.FormID=$exParam";
			
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);
		
		$EditBtn = '<a href="#" onClick="loadpage(\'form_column.php?edit=1&FormColumnID='.$FormColumnID.'\',\'content\')">Edit</a>';
		$DeleteBtn  = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'form_columns_list.php?delete=1&FormColumnID='.$FormColumnID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Delete</a>';
		$actions='['.$EditBtn.'|'.$DeleteBtn.']';
	
		$channel[] = array(
					$FormColumnID,
					$FormColumnName,
					$FormSectionName,
					$ColumnDataTypeName,
					$ColumnSize,
					$actions
		);
		
	} 
	
}
else if ($OptionValue=='RoleCenterApprovals')
{
	$sql = "select rca.RoleCenterApprovalID,isnull(rca.RoleCenterID,'')RoleCenterID,iif(rca.ServiceStatusID is null,'0','1') Accesses, 
			ss.ServiceStatusName, ss.ServiceStatusID 
			from RoleCenterApproval rca
			right join ServiceStatus ss 
			on ss.ServiceStatusID=rca.ServiceStatusID AND RoleCenterID = $exParam
			order by ss.ServiceStatusID,rca.RoleCenterID ";
			//echo $sql;
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);
		//$DeleteBtn   = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'userroles_list.php?delete=1&RoleCenterID='.$RoleCenterID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Delete</a>';

		$fieldname1 = $ServiceStatusID;

		
		$link = $fieldname1."='+this.form.".$fieldname1.'.checked';

		
		$AccesValue = '';

		
		if ($Accesses == 1) {$AccesValue = 'checked="checked"';}

		
		$channel[] = array(			
					'<input id="'.$fieldname1.'" name="'.$fieldname1.'" type="checkbox" '. $AccesValue.'>'.$ServiceStatusName.'</input>'
		);
		
	} 
	
}
else if ($OptionValue=='AprovalSteps')
{
	$sql = "select sas.*,ss.ServiceStatusName,sc.CategoryName from ServiceApprovalSteps sas
	join ServiceStatus ss on sas.ServiceStatusID=ss.ServiceStatusID
	join ServiceCategory sc on sas.ServiceCategoryID=sc.ServiceCategoryID
	where sc.ServiceCategoryID=$exParam";
			//echo $sql;
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);
		
		$EditBtn = '<a href="#" onClick="loadpage(\'approval_step.php?edit=1&ServiceApprovalStepID='.$ServiceApprovalStepID.'\',\'content\')">Edit</a>';
		$DeleteBtn  = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'approval_steps_list.php?delete=1&ServiceApprovalStepID='.$ServiceApprovalStepID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Delete</a>';
		$actions='['.$EditBtn.'|'.$DeleteBtn.']';
	
		$channel[] = array(	
					$step,
					$ServiceStatusName,
					$actions
		);

		
	} 
	
}
else if ($OptionValue=='GLAccounts')
{
	$sql = "select * from GlAccounts";
			
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);
		
		$EditBtn = '<a href="#" onClick="loadpage(\'gl_accounts_setup.php?edit=1&GlAccountID='.$GlAccountID.'\',\'content\')">Edit</a>';
		$DeleteBtn  = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'gl_accounts_list.php?delete=1&GlAccountID='.$GlAccountID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Delete</a>';
		$actions='['.$EditBtn.'|'.$DeleteBtn.']';
	
		$channel[] = array(	
					$GlAccountNo,
					$GlAccountName,
					$actions
		);

		
	} 
	
}
else if ($OptionValue=='LAIFOMS_LAND')
{
		
	$sql = "SELECT p.PlotNumber LRN,p.BlockLRNumber PlotNo,cs.CustomerSupplierName [Owner],cs.LocationDescription Location,cs.Town
			FROM PROPERTY p 
			join CustomerSupplier cs on p.CustomerSupplierID=cs.CustomerSupplierID
			join LandApplication la on la.LRN=p.BlockLRNumber and la.PlotNo=p.PlotNumber 
			where la.ServiceHeaderID=$exParam

			order by p.BlockLRNumber,p.PlotNumber";
			
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);
		
		$EditBtn = '<a href="#" onClick="loadpage(\'gl_accounts_setup.php?edit=1&GlAccountID='.$GlAccountID.'\',\'content\')">Edit</a>';
		$DeleteBtn  = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'gl_accounts_list.php?delete=1&GlAccountID='.$GlAccountID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Delete</a>';
		$actions='['.$EditBtn.'|'.$DeleteBtn.']';
	
		$channel[] = array(	
					$Owner,
					$Town,
					$Location
		);		
	} 
	
}
else if ($OptionValue=='LAIFOMS_LAND_LIST')
{
	$upn='';
	$plotno='';
	$lrn='';
	$owner='';
	if (strlen($exParam)>0)
	{
		$sql = "select top 10 p.LRN,p.PlotNo,p.LaifomsOwner [Owner],p.Balance Balance from land p";
		$details=explode(':',$exParam);
		
		$str3=explode('=',$details[0]);
		$upn=$str3[1];
		
		$str3=explode('=',$details[1]);
		$plotno=$str3[1];		
		
		$str3=explode('=',$details[2]);
		$lrn=$str3[1]; 
		
		$str3=explode('=',$details[3]);
		$owner=$str3[1];		
		
		 if(!$upn==0)
		{
			$sql = "select p.laifomsUPN UPN,p.LRN,p.PlotNo,p.LaifomsOwner [Owner],RatesPayable,p.Balance Balance from land p where laifomsUPN='$upn'";
		}else if(!$owner=='')
		{
			$sql = "select p.laifomsUPN UPN,p.LRN,p.PlotNo,p.LaifomsOwner [Owner],RatesPayable,p.Balance Balance from land p where laifomsOwner like '$owner%'";
		}else
		{	if ($lrn!=='' and $plotno=='')
			{
				$sql = "select p.laifomsUPN UPN,p.LRN,p.PlotNo,p.LaifomsOwner [Owner],RatesPayable,p.Balance Balance from land p where lrn='$lrn'";				
			}else if($lrn=='' and $plotno!=='')
			{
				$sql = "select p.laifomsUPN UPN,p.LRN,p.PlotNo,p.LaifomsOwner [Owner],RatesPayable,p.Balance Balance from land p where plotno='$plotno'";
			}else if(($lrn!=='' and $plotno!==''))
			{
				$sql = "select  p.laifomsUPN UPN,p.LRN,p.PlotNo,p.LaifomsOwner [Owner],RatesPayable,p.Balance Balance from land p where plotno='$plotno' and lrn='$lrn'";
			}
			
		}  
	}else
	{
		$sql = "select top 1000  p.laifomsUPN UPN,p.LRN,p.PlotNo,p.LaifomsOwner [Owner],RatesPayable,p.Balance Balance from land p";
	}
	
	//$sql = "select  p.laifomsUPN UPN,p.LRN,p.PlotNo,p.LaifomsOwner [Owner],RatesPayable,p.Balance Balance from land p";
	
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);
		
		$EditBtn = '<a href="#" onClick="loadpage(\'gl_accounts_setup.php?edit=1&GlAccountID='.$GlAccountID.'\',\'content\')">Edit</a>';
		//$DeleteBtn  = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'gl_accounts_list.php?delete=1&GlAccountID='.$GlAccountID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Delete</a>';
		$StmtBtn  = '<a href="statement.php?lrn='.$LRN.'&plotno='.$PlotNo.'" target="_blank">Statement</a>';
		$actions='['.$StmtBtn.']';
	
		$channel[] = array(
					$UPN,
					$LRN,
					$PlotNo,
					$RatesPayable,
					$Balance,
					$Owner,
					$actions
					
		);		
	}
}
else if ($OptionValue=='NEW_LAND_LIST')
{
		
	$sql = "select l.LRN,l.PlotNo,c.CustomerName Owner,L.Balance+L.PenaltyBalance Balance from Land l
			join LandOwner lo on lo.UPN=l.UPN 
			join Customer c on lo.CustomerID=c.CustomerID ORDER BY l.LRN,l.PlotNo";
			
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);
		
		$EditBtn = '<a href="#" onClick="loadpage(\'gl_accounts_setup.php?edit=1&GlAccountID='.$GlAccountID.'\',\'content\')">Edit</a>';
		$DeleteBtn  = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'gl_accounts_list.php?delete=1&GlAccountID='.$GlAccountID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Delete</a>';
		$actions='['.$EditBtn.'|'.$DeleteBtn.']';
	
		$channel[] = array(	
					$LRN,
					$PlotNo,
					$Balance,
					$Owner
		);		
	}
}
else if ($OptionValue=='LAIFOMS_HOUSE')
{
		
	$sql = "SELECT h.HouseNumber,h.EstateID,tn.CurrentTenant [Tenant],tn.MonthlyRent,tn.Balance
			FROM Houses h 
			join Tenancy tn on tn.HouseNumber=h.HouseNumber and tn.EstateID=h.EstateID
			
			join HouseApplication ha on ha.HouseNumber=h.HouseNumber and ha.EstateID=h.EstateID
			where ha.ServiceHeaderID=$exParam

			order by h.EstateID,h.HouseNumber";
			
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);
		
		$EditBtn = '<a href="#" onClick="loadpage(\'gl_accounts_setup.php?edit=1&GlAccountID='.$GlAccountID.'\',\'content\')">Edit</a>';
		$DeleteBtn  = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'gl_accounts_list.php?delete=1&GlAccountID='.$GlAccountID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Delete</a>';
		$actions='['.$EditBtn.'|'.$DeleteBtn.']';
	
		$channel[] = array(	
					$MonthlyRent,
					$Balance,
					$Tenant					
		);		
	} 
	
}
else if ($OptionValue=='LAIFOMS_HOUSE_LIST')
{
	$HouseNumber='';
	$EstateID='';
	$CurrentTenant='';
	
	
	$sql = "SELECT top 50 h.HouseNumber,es.EstateName,tn.MonthlyRent,tn.Balance,tn.CurrentTenant [Tenant]
		FROM Houses h 
		join Tenancy tn on tn.UHN=h.UHN	
		join Estates es on h.EstateID=es.EstateID		

		order by h.EstateID,h.HouseNumber";

	if (strlen($exParam)>0)
	{
		$sql = "SELECT top 50 h.HouseNumber,es.EstateName,tn.MonthlyRent,tn.Balance,tn.CurrentTenant [Tenant]
				FROM Houses h 
				join Tenancy tn on tn.UHN=h.UHN	
				join Estates es on h.EstateID=es.EstateID		

				order by h.EstateID,h.HouseNumber";
				
		$details=explode(':',$exParam);
		
		$str3=explode('=',$details[0]);
		$EstateID=$str3[1];
		
		$str3=explode('=',$details[1]);
		$HouseNumber=$str3[1];		
		
		$str3=explode('=',$details[2]);
		$CurrentTenant=$str3[1]; 
				
		
		 if(!$CurrentTenant=='')
		{
			$sql = "SELECT es.EstateID,h.HouseNumber,es.EstateName,tn.MonthlyRent,tn.Balance,tn.CurrentTenant [Tenant]
				FROM Houses h 
				join Tenancy tn on tn.UHN=h.UHN	
				join Estates es on h.EstateID=es.EstateID where tn.CurrentTenant='%$CurrentTenant%'";
		}else
		{	if ($EstateID!=='' and $HouseNumber=='')
			{
				$sql = "SELECT es.EstateID,h.HouseNumber,es.EstateName,tn.MonthlyRent,tn.Balance,tn.CurrentTenant [Tenant]
				FROM Houses h 
				join Tenancy tn on tn.UHN=h.UHN	
				join Estates es on h.EstateID=es.EstateID where h.EstateID='$EstateID'";
				
			}else if($EstateID=='' and $HouseNumber!=='')
			{
				$sql = "SELECT es.EstateID,h.HouseNumber,es.EstateName,tn.MonthlyRent,tn.Balance,tn.CurrentTenant [Tenant]
				FROM Houses h 
				join Tenancy tn on tn.UHN=h.UHN	
				join Estates es on h.EstateID=es.EstateID where h.HouseNumber='$HouseNumber'";
			}else if(($EstateID!=='' and $HouseNumber!==''))
			{
				$sql = "SELECT es.EstateID,h.HouseNumber,es.EstateName,tn.MonthlyRent,tn.Balance,tn.CurrentTenant [Tenant]
				FROM Houses h 
				join Tenancy tn on tn.UHN=h.UHN	
				join Estates es on h.EstateID=es.EstateID where h.EstateID='$EstateID' and h.HouseNumber='$HouseNumber'";
			}			
		}  
	}else
	{
		$sql = "SELECT top 50 es.EstateID,h.HouseNumber,es.EstateName,tn.MonthlyRent,tn.Balance,tn.CurrentTenant [Tenant]
				FROM Houses h 
				join Tenancy tn on tn.UHN=h.UHN	
				join Estates es on h.EstateID=es.EstateID		

				order by h.EstateID,h.HouseNumber";
	}
		

			
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);
		
		$StmtBtn  = '<a href="housestatement.php?EstateID='.$EstateID.'&HouseNumber='.$HouseNumber.'" target="_blank">Statement</a>';
		$actions='['.$EditBtn.'|'.$DeleteBtn.']';
	
		$channel[] = array(	
					$EstateID,
					$EstateName,
					$HouseNumber,
					$Tenant,
					$MonthlyRent,
					$Balance,
					$StmtBtn
		);		
	} 	
}
else if ($OptionValue=='Permits')
{
		
	$sql = "select p.PermitNo,p.IssueDate,p.ExpiryDate,c.CustomerName,p.ServiceHeaderID 
			from ServiceHeader sh 
			join Permits p on p.ServiceHeaderID=sh.ServiceHeaderID
			join Customer c on sh.CustomerID=c.CustomerID";
			
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);
		$IssueDate=date_create($IssueDate);
		$IssueDate=date_format($IssueDate,"d/m/Y");
		$ResendBtn  = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Resend?\',\'permits_list.php?resend=1&ServiceHeaderID='.$ServiceHeaderID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Resend</a>';
		$RevokeBtn  = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Revoke the Permit?\',\'permits_list.php?revoke=1&permitno='.$PermitNo.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Revoke</a>';
		$actions='['.$ResendBtn.'|'.$RevokeBtn.']';
	
		$channel[] = array(	
					$PermitNo,
					$ServiceHeaderID,
					$IssueDate,
					$CustomerName,
					$ExpiryDate,
					$actions
		);		
	} 
	
}
else if ($OptionValue=='NEW_HOUSE_TENANTS')
{
		
	$sql = "select e.EstateName,h.HouseNo,c.CustomerName Tenant,t.RentBalance from Tenancy t 
			join Houses h on t.UHN=h.UHN
			join Customer c on t.CustomerID=c.CustomerID
			join Estates e on h.EstateID=e.EstateID	
			

			order by h.EstateID,h.HouseNo";
			
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);
		
		$EditBtn = '<a href="#" onClick="loadpage(\'gl_accounts_setup.php?edit=1&GlAccountID='.$GlAccountID.'\',\'content\')">Edit</a>';
		$DeleteBtn  = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'gl_accounts_list.php?delete=1&GlAccountID='.$GlAccountID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Delete</a>';
		$actions='['.$EditBtn.'|'.$DeleteBtn.']';
	
		$channel[] = array(	
					$EstateName,
					$HouseNo,
					$Tenant
		);		
	} 
	
}
else if ($OptionValue=='LAIFOMS_PERMIT')
{
		
	$sql = "select SBPNumber PermitNo,AmountPaid,DateIssued,BusinessName 
			from [LAIFOMS-M].dbo.IssuedSingleBusinessPermits 
			where CalenderYear='2016' 
			order by CalenderYear desc";
			
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);
		
		$EditBtn = '<a href="#" onClick="loadpage(\'gl_accounts_setup.php?edit=1&GlAccountID='.$GlAccountID.'\',\'content\')">Edit</a>';
		$DeleteBtn  = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'gl_accounts_list.php?delete=1&GlAccountID='.$GlAccountID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Delete</a>';
		$actions='['.$EditBtn.'|'.$DeleteBtn.']';
	
		$channel[] = array(	
					$PermitNo,
					$BusinessName,
					$DateIssued,
					$AmountPaid
		);		
	} 
	
}
else if ($OptionValue=='FinancialYear')
{
	$sql = "select * from financialyear";
			
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);
		
		$EditBtn = '<a href="#" onClick="loadpage(\'financialyear.php?edit=1&financialyearID='.$FinancialYearID.'\',\'content\')">Edit</a>';
		$DeleteBtn  = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'financialyear_list.php?delete=1&FinancialYearID='.$FinancialYearID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.','.$FinancialYearID.'\')">Delete</a>';
		$actions='['.$EditBtn.'|'.$DeleteBtn.']';
	
		$channel[] = array(	
					$FinancialYearName,
					$TargetCollection,
					$actions
		);

		
	} 
	
}
else if ($OptionValue=='Businesses')
{
	$sql = "select b.*,w.WardName from Businesses b inner join Wards w on b.WardID=w.WardID";
			
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);
		
		$EditBtn = '<a href="#" onClick="loadpage(\'business.php?edit=1&BusinessID='.$BusinessID.'\',\'content\')">Edit</a>';
		$DeleteBtn  = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'businesses_list.php?delete=1&BusinessID='.$BusinessID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Delete</a>';
		$actions='['.$EditBtn.'|'.$DeleteBtn.']';
	
		$channel[] = array(					
					$WardName,
					$BusinessName,					
					$BusinessActivity,
					$BusinessOwner,
					$IDNO,
					$PhoneNo,
					$SBP_NO,
					$actions	
		);

		
	} 
	
}
else if ($OptionValue=='TestTable')
{
/*	$sql = "select sc.SubCountyName,sum(tt.amount)Amount from SubCounty sc
	join Wards wd on wd.SubCountyID=sc.SubCountyID
	join Markets mk on mk.WardID=wd.WardID
	join TestTable tt on tt.MArketID=mk.MarketID
	
	group by sc.SubCountyName";*/
	
	$sql="set dateformat dmy exec spPeriodicCollection";
			
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);	
		$channel[] = array(	
					$date,
					(double)$amount
		);		
	} 
	
}
else if ($OptionValue=='Target')
{
	//$sql="SELECT sum(amount) Amount,1000000000 [Target] FROM [COUNTYREVENUE].[dbo].[vwTarget]";
	$sql="select sum(Total)Amount,10000000000 Target from vwreceiptsperstream";
			
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);	
		$channel[] = array(	
					(double)$Amount,
					(double)$Target
		);		
	} 	
}
else if ($OptionValue=='TodaysCollection')
{
	$sql="exec spTodaysCollection";
	//$sql="select sum(Total)Amount,1000000000 Target from vwreceiptsperstream";
			
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);	
		$channel[] = array(	
					(double)$Amount,
					(double)number_format($Target,2)
		);		
	} 	
}
else if ($OptionValue=='TodaysPosCollection')
{
	$sql="exec spPosCollectionToday";	
			
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);	
		$channel[] = array(	
					(double)$Amount,
					(double)$Target
		);		
	} 	
}
else if ($OptionValue=='TodaysCollection_f')
{
	$sql="exec spTodaysCollection_d";
			
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);	
		$channel[] = array(	
					$ServiceGroupName,
					(double)$Amount
		);		
	} 	
}
else if ($OptionValue=='ServiceRanking')
{
	$sql="select ServiceGroupName [Group],Total Amount from vwReceiptsPerStream order by Total desc";
			
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);	
		$channel[] = array(	
					$Group,
					(double)$Amount
		);		
	} 	
}
else if ($OptionValue=='Customers')
{
	$sql="select distinct c.CustomerID,c.CustomerName, count(sh.ServiceID) Services
			from ServiceHeader sh
			join Customer c on c.CustomerID=sh.CustomerID
			join InvoiceLines il on il.ServiceHeaderID=sh.ServiceHeaderID
			where il.InvoiceLineID not in (select InvoiceLineID from ConsolidateInvoice) and sh.ServiceStatusID=7
			group by c.CustomerID,c.CustomerName 
			order by count(sh.ServiceID) desc";
			
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);	
		$CustomerName = '<a href="#" onClick="loadmypage(\'customer_services.php?Customer='.$CustomerName.'\',\'content\',\'loader\',\'listpages\',\'\',\'CustomerServices\',\''.$CustomerID.'\')">'.$CustomerName.'</a>';
		$DeleteBtn  = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'customer_services_list.php?delete=1&CustomerID='.$CustomerID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Delete</a>';
		$channel[] = array(	
					$CustomerID,
					$CustomerName,
					$Services,
					$DeleteBtn
		);		
	} 	
}
else if ($OptionValue=='CustomerServices')
{
	$sql="select distinct sh.ServiceHeaderID,s.ServiceName from 
		services s
		join ServiceHeader sh on sh.ServiceID=s.ServiceID
		join InvoiceLines il on il.ServiceHeaderID=sh.ServiceHeaderID
		where il.InvoiceLineID not in (select InvoiceLineID from ConsolidateInvoice)
		and sh.CustomerID=$exParam and sh.ServiceStatusID=7";
			
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);		
		$channel[] = array(	
					$ServiceHeaderID,
					$ServiceName
		);		
	} 	
}
else if ($OptionValue=='BusinessTypes')
{
	$sql="select * from BusinessType";			
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);		
		
		$EditBtn = '<a href="#" onClick="loadpage(\'businesstype.php?edit=1&BusinessTypeID='.$BusinessTypeID.'\',\'content\')">Edit</a>';
		$DeleteBtn  = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'businesstypes_list.php?delete=1&BusinessTypeID='.$BusinessTypeID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\')">Delete</a>';
		$actions='['.$EditBtn.'|'.$DeleteBtn.']';
		
		$channel[] = array(	
					$BusinessTypeName,
					$Notes,
					$actions
		);		
	} 	
}
else if ($OptionValue=='ServicePlus')
{
	$sql="select sp.ServicePlusID,sp.ServiceID AppliedServiceID,s2.ServiceID,s2.ServiceName 
	from ServicePlus sp, services s, services s2
	where sp.ServiceID=s.ServiceID and sp.service_add=s2.ServiceID and sp.ServiceID=$exParam";			
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);		
		$DeleteBtn  = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'serviceplus_list.php?delete=1&ServicePlusID='.$ServicePlusID.'&A_ServiceID='.$AppliedServiceID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\',\''.$AppliedServiceID.'\')">Delete</a>';
		//'<a href="#" onClick="loadmypage(\'serviceplus_list.php?A_ServiceID='.$ServiceID.'\',\'content\',\'loader\',\'listpages\',\'\',\'ServicePlus\',\''.$ServiceID.'\')">FEES</a>';
		$actions='['.$DeleteBtn.']';
		
		$channel[] = array(	
					$ServiceID,
					$ServiceName,
					$actions
		);		
	} 	
}
else if ($OptionValue=='Miscellaneous')
{
	$sql="select sh.ServiceHeaderID,m.[Description] Description,M.CustomerName,M.Amount,Sh.CreatedDate from miscellaneous m 
			join ServiceHeader sh on m.ServiceHeaderID=sh.ServiceHeaderID";			
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);		
		$DeleteBtn  = '<a href="#" onClick="deleteConfirm2(\'Are you sure you want to Delete?\',\'miscellaneous_list.php?delete=1&ApplicationID='.$ServiceHeaderID.'\',\'content\',\'loader\',\'listpages\',\'\',\''.$OptionValue.'\',\''.$AppliedServiceID.'\')">Delete</a>';
		//'<a href="#" onClick="loadmypage(\'serviceplus_list.php?A_ServiceID='.$ServiceID.'\',\'content\',\'loader\',\'listpages\',\'\',\'ServicePlus\',\''.$ServiceID.'\')">FEES</a>';
		$actions='['.$DeleteBtn.']';
		
		$channel[] = array(	
					$ServiceHeaderID,
					$CustomerName,
					$Description,
					$Amount,
					$CreatedDate,
					$actions
		);		
	} 	
}
$channels = array($channel);
$rss = (object) array('aaData'=>$channel);
//print_r($rss);
$json = json_encode($rss);
echo $json;
?>