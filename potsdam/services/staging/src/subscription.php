<?php

require_once '../../../common/mysqli/class/mysql_crud.php';
require_once '../../../common/constants.php';

class Subscription{

	 public function getSubscriptionPlans(){
	 	$db = new Database();
		$db->connect("../../../");
     	try
     	{
     		$db->select('subscription_plans','*', NULL, NULL, NULL); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
			$subscription_res = $db->getResult();
			return array("error"=>false,
			"response" => $subscription_res);
     	}
     	catch (Exception $ex)
     	{
     		return json_encode( 
     			array (
     				"error"=>true,
     				"message"=>$ex.getMessage()));
     	}
        $db->disconnect (); 
	 }

     public function subscribe($params){

		$db = new Database();
		$db->connect("../../../");
     	try
     	{
			$customer_id = $params['customer_id'];
			$device_id = $params['device_id'];
			$plan_id = $db->escapeString($params['plan_id']);

			//check if customer exists
			$where_clause = "id=" .	"'$customer_id'";
			$db->select('customers','id', NULL, $where_clause, NULL); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
			$account_id_res = $db->getResult();	
			

			if (sizeof($account_id_res) > 0)
			{
				//check if device belongs to the account
				$account_id_array = $account_id_res[0];
				$account_id_int = $account_id_array["id"];
				$where_clause = "account_id=" .	"$account_id_int" . " AND " . "id=" .	"$device_id";
				
				$db->select('devices','id,status', NULL, $where_clause, NULL); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
				$dev_id_res = $db->getResult();
				

				if (sizeof($dev_id_res) > 0)
				{
					$dev_id_array = $dev_id_res[0];
					$dev_id_int = $dev_id_array["id"];

					$where_clause = "device_id=" .	"$dev_id_int";
					$db->select('subscriptions','id', NULL, $where_clause, NULL); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
					$subscription_res = $db->getResult();	
					

					if (sizeof($subscription_res) > 0)
					{
						return  
				     			array (
				     			"error"=>true,
				     			"message"=>SUBSCRIBED_ALREADY);
					}
					else
					{

						$where_clause = "id=" .	"'$plan_id'";
						$db->select('subscription_plans','id', NULL, $where_clause, NULL); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
						$plan_id_res = $db->getResult();	
						//print_r($where_clause);

						if (sizeof($plan_id_res) > 0)
						{	
							$plan_id_array = $plan_id_res[0];
							$plan_id_int = $plan_id_array["id"];

							//Plain style
							$sql="INSERT INTO subscriptions (device_id, subscription_plan_id, status) VALUES
							($dev_id_int, $plan_id_int,1)";

							//echo "query - ".$sql;

							$db->sql($sql);
							$res = $db->getResult();
							//print_r ($res);
							if (sizeof($res) > 0) 
							{
								return  
				     			array (
				     			"error"=>false,
				     			"subscription_id"=>$res,
				     			"message"=>SUBSCRIBED);
							}
							else
							{
								return  
				     			array (
				     			"error"=>true,
				     			"message"=>SUBSCRIBE_FAILED);
							}
						}
						else
						{
							return  array (
		     				"error"=>true,
		     				"message"=>PLAN_DOES_NOT_EXIST);
						}

					}
				}
				else
				{
					return  array (
		     			"error"=>true,
		     			"message"=>DEVICE_NOT_LINKED);
				}
			}
			else
			{
				return  
		     			array (
		     			"error"=>true,
		     			"message"=>NO_SUCH_ACCOUNT);
				
			}

     	}
     	catch (Exception $ex)
     	{
     		return json_encode( 
     			array (
     				"error"=>true,
     				"message"=>$ex.getMessage()));
     	}
         $db->disconnect (); 
     }
		
}

?>