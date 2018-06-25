<?php

require_once '../../../common/mysqli/class/mysql_crud.php';
require_once '../../../common/constants.php';

class Customer{

	 public function getLinkedDevices($params){
	 	$db = new Database();
		$db->connect("../../../");
     	try
     	{
     		$customer_id = $params['customer_id'];

			$where_clause = "id=" .	"'$customer_id'";
			$db->select('customers','id', NULL, $where_clause, NULL); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
			$account_id_res = $db->getResult();	

			if (sizeof($account_id_res) > 0)
			{
				//check if device belongs to the account
				$account_id_array = $account_id_res[0];
				$account_id_int = $account_id_array["id"];
				$where_clause = "account_id=" .	"$account_id_int";
				
				$db->select('devices','id,device_id as device_name,status', NULL, $where_clause, NULL); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
				$dev_idz_res = $db->getResult();

				if (sizeof($dev_idz_res) > 0)
				{
					return  
	     			array (
	     				"error"=>false,
	     				"response"=>$dev_idz_res);
				}
				else
				{
					return 
	     			array (
	     				"error"=>true,
	     				"message"=>NO_DEVICES_LINKED_TO_ACCOUNT);
				}
				
			}
			else
			{
				return 
	     			array (
	     				"error"=>true,
	     				"message"=>ACCOUNT_ID_INVALID);

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

	  public function login($params){

		$db = new Database();
		$db->connect("../../../");
     	try
     	{

			echo('params');
			print_r($params);

			$customer_email = $params['customer_email'];
			$customer_password = $params['customer_password'];

			//$where_clause = "customer_email=" .	"'$customer_email'" . " AND " . "customer_password=" .	"'$customer_password'";
			$where_clause = "customer_email='narayan@gmail.com'";
			$db->select('customers','id', NULL, $where_clause, NULL); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
			$res = $db->getResult();
			print_r($res);

			if (sizeof($res) > 0) 
			{
				return  
		     	array (
		     		"error"=>false,
		     		"response"=>$res);
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

     public function signup($params){

		$db = new Database();
		$db->connect("../../../");
     	try
     	{

			//echo('params');
			//print_r($params);

			$account_id = $params['account_id'];
			$customer_name = $params['customer_name'];
			$customer_email = $db->escapeString($params['customer_email']);
			$customer_password = $params['customer_password'];

			//$date = date_create();
			$sign_up_timestamp = date('Y-m-d G:i:s');

			$where_clause = "account_id=" .	"'$account_id'";
			$db->select('accounts','id', NULL, $where_clause, NULL); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
			$res = $db->getResult();	
			

			if (sizeof($res) == 0)
			{
				return json_encode( 
	     			array (
	     				"error"=>true,
	     				"message"=>ACCOUNT_ID_INVALID));
			}
			else
			{
				$account_id_temp = $res[0]['id'];
				$where_clause = "customer_email
				=" .	"'$customer_email'";
				$db->select('customers','*',NULL,$where_clause,NULL); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
				$res = $db->getResult();
				//print_r ($res);

				if (sizeof($res) == 0)
				{
					//Plain style
					$sql="INSERT INTO customers (id, customer_name, customer_email, customer_password, sign_up_timestamp) VALUES
					($account_id_temp,'$customer_name','$customer_email','$customer_password','$sign_up_timestamp')";

					//echo "query - ".$sql;

					$db->sql($sql);
					$res = $db->getResult();
					//print_r ($res);
					if (sizeof($res) > 0) 
					{
						return  
		     			array (
		     			"error"=>false,
		     			"customer_id"=>$res,
		     			"message"=>CUSTOMER_CREATED);
					}
					else
					{
						return  
		     			array (
		     			"error"=>true,
		     			"message"=>CUSTOMER_NOT_CREATED);
					}
				}
				else
				{
	     			return  array (
	     				"error"=>true,
	     				"message"=>CUSTOMER_EXISTS);
				}
			}
			// $result = array("response" => $params);
	       //return  json_encode($result);
			
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