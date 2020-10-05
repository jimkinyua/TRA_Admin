<?php                                                                        

//$data = array("InvoiceNumber" => '269639', "lines"=> array(array("LineID" => '1',"LineName" => "TEST1"), array("LineID" => '1',"LineName" => "TEST1")));

//receiving from hmis
$data = array("Email" => 'cngeno@gmail.com',"MobileNo"=>'0777777777', "Pid"=>'12321',"EncounterNo"=>'12',"FirstName"=>'Cosmas',"MiddleName"=>'Mike',"LastName"=>'Ngugi',"TransactionTime"=>'12/09/2016',"Amount"=>'900',"MflCode"=>'01',"Lines"=>array(
array("SeviceCode"=>'01',"ServiceName"=>"Service1","Amount"=>'200'),
array("SeviceCode"=>'02',"ServiceName"=>"Service2","Amount"=>'300'),
array("SeviceCode"=>'01',"ServiceName"=>"Service3","Amount"=>'400')));

//verification
//$data = array("InvoiceNumber" => '269639',"Amount"=>'200');

//Payment
//$data = array("InvoiceNumber" => '400657',"TransactionNumber"=>'NYERI18',"AmountPaid"=>'2000',"TransactionTime"=>'2016-09-22');

//$data = array("Pid" => '400651',"EncounterNo"=>'0202',"MflCode"=>'534333',"Amount"=>'2000',"TransactionTime"=>'2016-09-22');

 $data_string = json_encode($data);
 

//$ch = curl_init("http://attainsvr6:6325/v1/county/payment");  
//$ch = curl_init("http://attainsvr6:6325/v1/county/verification");
$ch = curl_init("http://attainsvr6:6325/v1/county/bill");
                                                                                                                                           
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                                                "Content-Type: application/json",
                                                "Authorization: Bearer 4p9mj82PTl1BWSya7bfpU_Nm8u07hkcB"
));                                                                                                                                           
$result = curl_exec($ch);
	if(curl_errno($ch))
		{
			echo 'Curl error: ' . curl_error($ch);
		} else {
			/* $ch2 = curl_init("https://www.google.com/");
			curl_e xec($ch2);*/
			echo $result;
		}

exit
?>