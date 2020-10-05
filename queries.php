<?php 
require 'DB_PARAMS/connect.php';
require_once('utilities.php');

$subject=$_REQUEST['subject'];
$key=$_REQUEST['key'];
$channel=Array();

$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );

if($subject=='plot')
{
    $sql="select * from fnLastPlotRecord($key)";
    $result=sqlsrv_query($db, $sql,$params,$options);

    $rows=sqlsrv_num_rows($result);
    if($result){

        if($rows==0)
        {
            $channel[] = 
                array(
                    "name" => "Success",
                    "message" => "No Records Found",
                    "code" => "01",
                    "status" => 200,
                    
                );
        }else
        {
            while ($row=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {

                $channel[]=Array(
                    "Total Balance"=>number_format($row['Balance'],2),
                    "Penalty"=>number_format($row["Penaltybalance"],2),
                    "Grount Rent"=>number_format($row["GroundRentBalance"],2),
                    "Other Charges"=>number_format($row["OtherChergesBalance"],2),
                );
            }
        }
    }else{
        $channel[] = 
        array(
            "name" => "Failed",
            "message" => "No Result",
            "code" => "01",
            "status" => 404,
            
        );
    }

    $json=json_encode($channel);

    echo $json;

}else if($subject=='business'){
    $sql="set dateformat dmy 
        select distinct top 100  p.PermitNo,p.IssueDate,p.ExpiryDate,c.CustomerName,              
        (select top 1 value from fnFormData(sh.ServiceHeaderID) where FormColumnID=5) BusinessActivity,il.Amount PermitCost,
        (select distinct Value from fnFormData (p.serviceheaderid) where formcolumnid=12242) PlotNo,
        c.PIN
        from ServiceHeader sh 
        join Permits p on p.ServiceHeaderID=sh.ServiceHeaderID
        join invoicelines il on il.ServiceHeaderID=sh.ServiceHeaderID
        join Services s on il.ServiceID=s.ServiceID and il.ServiceID=sh.ServiceID 
        join Customer c on sh.CustomerID=c.CustomerID

        where p.PermitNo='$key'";

    $result=sqlsrv_query($db, $sql,$params,$options);

    $rows=sqlsrv_num_rows($result);
    if($result){

        if($rows==0)
        {
            $channel[] = 
                array(
                    "name" => "Success",
                    "message" => "No Records Found",
                    "code" => "01",
                    "status" => 200,
                    
                );
        }else
        {
            $channel[]=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);


        }
    }else{
        $channel[] = 
        array(
            "name" => "Failed",
            "message" => "No Result",
            "code" => "01",
            "status" => 404,
            
        );
    }

    $json=json_encode($channel);

    echo $json;


}else if($subject=='market')
{
    $sql="SELECT mk.MarketID,mk.MarketName,sum(il.Amount) Amount
        FROM UserDevices ud
        join (select convert(date,CreateDate)CreateDate,sum(Amount)Amount,CreatedBy 
        from InvoiceLines where PosReceiptID is not null group by CreatedBy,convert(date,CreateDate)) il on il.CreatedBy=ud.DeviceUserID
        join Markets mk on ud.MarketID=mk.MarketID
        where ud.DeviceUserStatusID=1 and mk.MarketID='$key'
        group by mk.MarketID,mk.MarketName

        order by mk.MarketName";

    $result=sqlsrv_query($db, $sql,$params,$options);

    $rows=sqlsrv_num_rows($result);
    if($result){

        if($rows==0)
        {
            $channel[] = 
                array(
                    "name" => "Success",
                    "message" => "No Records Found",
                    "code" => "01",
                    "status" => 200,
                    
                );
        }else
        {
            $channel[]=sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);


        }
    }else{
        $channel[] = 
        array(
            "name" => "Failed",
            "message" => "No Result",
            "code" => "01",
            "status" => 404,
            
        );
    }

    $json=json_encode($channel);

    echo $json; 
}

?>