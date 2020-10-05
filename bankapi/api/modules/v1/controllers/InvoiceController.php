<?php
 
namespace app\api\modules\v1\controllers;

use yii;

use yii\rest\ActiveController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
 
class InvoiceController extends ActiveController
{
 
    public $modelClass = 'app\models\Users';
	
	public function behaviors()
    {
        $behaviors = parent::behaviors();
 
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
        ];	
 
        return $behaviors;
    }
	
	public function actions()
	{
		$actions = parent::actions();

		// disable the "delete", "create", "update", "view", "index" actions
		unset($actions['delete'], $actions['create'], $actions['update'], $actions['view'], $actions['index']);

		// customize the data provider preparation with the "prepareDataProvider()" method
		$actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

		return $actions;
	}	
	
	public function actionVerification()
    {
		$channel = array();
		$connection = \Yii::$app->db; 
		$params = Yii::$app->request->post();
		
		\Yii::error($params, 'error');
		//$params1 = Yii::$app->request->get();
		//\Yii::error(implode(", ",$params), 'error');
		//\Yii::error(implode(", ",$params), 'error');
		//\Yii::error(implode(", ",$params1), 'error');
		
		if (isset($params['InvoiceNumber']))
		{
                $sql = "select distinct sh.ServiceHeaderID,ih.InvoiceHeaderID, ih.CustomerID,il.CreateDate,c.CustomerName,s.ServiceName,ih.Paid,sum(il.Amount) Amount
                            from InvoiceHeader ih
                            inner join InvoiceLines il on il.InvoiceHeaderID=ih.InvoiceHeaderID
                            inner join ServiceHeader sh on il.ServiceHeaderID=sh.ServiceHeaderID
                            inner join Customer c on sh.CustomerID=c.CustomerID 
                            inner join Services s on sh.ServiceID=s.ServiceID 
                            where il.InvoiceLineID not in (select InvoiceLineID from ConsolidateInvoice) and il.Amount>0 and ih.InvoiceHeaderID = :InvoiceNumber
                            group by sh.ServiceHeaderID, ih.CustomerID,il.CreateDate,c.CustomerName,s.ServiceName,ih.Paid,ih.InvoiceHeaderID,sh.ServiceHeaderID
                            order by il.CreateDate desc";
               if ($stmt = $connection->createCommand($sql)) 
	           {        
                    $stmt->bindValue(':InvoiceNumber', $params['InvoiceNumber']);
                    $result = $stmt->queryAll(); 
                    if (!empty($result))
                    {                        
                        $channel = array(
											"name" => "Successful",
											"message" => "Successful Transaction.",
											"code" => "00",
											"status" => 200,												
											"BankCode"=>'410005',
											"InvoiceNumber"=>$result[0]['InvoiceHeaderID'], 
											"AmountDue"=>$result[0]['Amount'],
											"CustomerName"=>$result[0]['CustomerName'],  
											"AllowPartial"=>'1'                            						
										);             
 

                    } else
                    {
                        $channel = array(				
									"name" => "Error",
									"message" => "Missing or Invalid Invoice Number.",
									"code" => "02",
									"status" => 200,
                                );                     
                    }                                               
               } else
               {
                   $channel = array(				
									"name" => "System Error",
									"message" => "Something went wrong, therefore we are not complete your request.",
									"code" => "03",
									"status" => 200,
                                ); 				                  
               }
		} else {
			$channel = array(				
								"name" => "Error",
								"message" => "Missing or Invalid Invoice Number.",
								"code" => "02",
								"status" => 200,
							); 
		}
		//$rss = (object) array('jData'=>$channel);
		//$rss = (object) array($channel);
		$json = json_encode($channel);
		echo $json;   
    }
	
    public function actionPayment()
    {
		$params = Yii::$app->request->post();
		$params1 = Yii::$app->request->get();
		\Yii::error(implode(", ",$params), 'error');
		\Yii::error(implode(", ",$params1), 'error');
		
        $connection = \Yii::$app->db; 
		$channel = array();
		if (!isset($params['InvoiceNumber']))
		{    
			$channel = array(				
								"name" => "Error",
								"message" => "Missing or Invalid Invoice Number.",
								"code" => "03",
								"status" => 200,
							); 			                
		} else if (!isset($params['TransactionNumber']))
		{    
			$channel = array(				
								"name" => "Error",
								"message" => "Missing Transaction Number.",
								"code" => "05",
								"status" => 200,
							); 				     
		} else if (!isset($params['AmountPaid']))
		{    
			$channel = array(				
								"name" => "Error",
								"message" => "Missing or Invalid Amount.",
								"code" => "02",
								"status" => 200,
							); 				               
		} else if (!isset($params['TransactionTime']))
		{  
			$channel = array(				
								"name" => "Error",
								"message" => "Missing or Invalid Transaction Time.",
								"code" => "04",
								"status" => 200,
							);
		}    
		if (empty($channel)) 
		{
			$sql = "SELECT * FROM Receipts WHERE ReferenceNumber = :TransactionNumber AND CreatedBy = 1";
			if ($stmt = $connection->createCommand($sql)) 
			{        
				$stmt->bindValue(':TransactionNumber', $params['TransactionNumber']);
				$result1 = $stmt->queryAll(); 
				if (count($result1)==0)
				{
					$sql = "INSERT INTO Receipts (ReceiptDate, ReceiptMethodID, ReferenceNumber, InvoiceHeaderID, Amount, ReceiptStatusID, CreatedBy)
                                            VALUES (:ReceiptDate, :ReceiptMethodID, :TransactionNumber, :InvoiceNumber, :AmountPaid, :ReceiptStatusID, :CreatedBy)";
					if ($stmt = $connection->createCommand($sql)) 
					{        
						$stmt->bindValue(':InvoiceNumber', $params['InvoiceNumber']); 
						$stmt->bindValue(':ReceiptDate', date('Y-m-d')); 
						$stmt->bindValue(':ReceiptMethodID', 3); 
						$stmt->bindValue(':TransactionNumber', $params['TransactionNumber']); 
						$stmt->bindValue(':AmountPaid', $params['AmountPaid']); 
						$stmt->bindValue(':ReceiptStatusID', 1); 
						$stmt->bindValue(':CreatedBy', 1);
                            
						$result = $stmt->execute(); 
						if ($result)
						{
						    $channel = array(				
									"name" => "Successful",
									"message" => "Successful Transaction.",
									"code" => "00",
									"status" => 200,
                                ); 										
										
						} else
						{
						    $channel = array(				
									"name" => "System Error",
									"message" => "Something went wrong, therefore we are not complete your request.",
									"code" => "07",
									"status" => 200,
                                );  
						}                    
					} else {
						       $channel = array(				
									"name" => "System Error",
									"message" => "Something went wrong, therefore we are not complete your request.",
									"code" => "07",
									"status" => 200,
                                );
					}
				} else
				{
					$channel[] = array(
                                        "Result"=>'06',
                                        "Message"=>'Payment transaction already exists'
                                        );                         
				}
			}  else {
				    $channel = array(				
									"name" => "System Error",
									"message" => "Something went wrong, therefore we are not complete your request.",
									"code" => "07",
									"status" => 200,
                                );
			}            
		}      
		//$rss = (object) array('jData'=>$channel);
		$json = json_encode($channel);
		echo $json; 
    }	
 
}