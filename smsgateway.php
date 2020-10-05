
<?php

	function sendSms($MobileNo,$Message){

		//Initialize your SMS Client 
		$URL = "http://198.12.159.194/api/json/";//Service Endpoint 

		//Prepare your parameters to send SMS 
		$params=array(); 
		$params["username"]="ESCOM"; 
		$params["api_key"]=urlencode("933116FE3C2A83D7A6EF6CE8640122F0"); 
		$params["message"]=urlencode($Message); 
		$params["recipient"]=urlencode($MobileNo);//separate with commas to send several numbers 

		$URL=$URL."?send_bulk_message&username=".$params["username"]."&api_key=".$params["api_key"]."&message=".$params["message"]."&recipient=".$params["recipient"]; 

	  // USE PHP CURL 
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $URL); 
	    //return the transfer as a string 
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	    curl_setopt($ch, CURLOPT_VERBOSE, true);
	    // $output contains the output string 
	    $output = curl_exec($ch); 
	    // close curl resource to free up system resources 
	    curl_close($ch);  


		//echo   $output; 
	}
	