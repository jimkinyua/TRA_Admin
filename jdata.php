<?php
	require_once 'DB_PARAMS/connect.php';
	require_once 'utilities.php';

	$channel=array();

	$sql = "select sc.SubCountyName,sum(tt.amount)Amount from SubCounty sc
	join Wards wd on wd.SubCountyID=sc.SubCountyID
	join Markets mk on mk.WardID=wd.WardID
	join TestTable tt on tt.MArketID=mk.MarketID
	
	group by sc.SubCountyName";
			
	$result = sqlsrv_query($db, $sql);	
	while ($row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC)) 
	{
		extract($row);	
		$channel[] = array(	
					$SubCountyName,
					$Amount
		);

		
	} 

	$rss = (object) array('jData'=>$channel);
	$json = json_encode($rss);
	echo $json;

?>