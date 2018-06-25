<?php

require_once '../../../common/mysqli/class/mysql_crud.php';
require_once '../../../common/constants.php';

class Device{

	public function checkIfDeviceExists($params){

		$db = new Database();
		$db->connect("../../../");
     	try
     	{
			$device_name = $params['device_id'];

			$where_clause = "device_id=" .	"'$device_name'";
			$db->select('devices','id', NULL, $where_clause, NULL); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
			$res = $db->getResult();

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
		     	"message"=>NO_DATA_FOUND);
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

	 public function getSensorTypes(){
	 	$db = new Database();
		$db->connect("../../../");
     	try
     	{
     		$db->select('sensors','*', NULL, NULL, NULL); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
			$sensor_res = $db->getResult();
			return array("error"=>false,
			"response" => $sensor_res);
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

	public function getDataBySensor($params){
	 	$db = new Database();
		$db->connect("../../../");
     	try
     	{
     		$sensor_id = $params['sensor_id'];

     		//
     		//select('CRUDClass','CRUDClass.id,CRUDClass.name,CRUDClassChild.name','CRUDClassChild ON CRUDClass.id = parentId','CRUDClass.name="Name 1"','id DESC');

     		/*$where_clause = "sensor_data.sensor_id=" .	"$sensor_id";
     		$db->select('data_packets','sensor_data.channel_1 as ch_1, sensor_data.channel_2 as ch_2, sensor_data.channel_3 as ch_3, sensor_data.channel_4 as ch_4, sensor_data.channel_5 as ch_5, sensor_data.channel_6 as ch_6, sensor_data.channel_7 as ch_7, sensor_data.channel_8 as ch_8, sensor_data.channel_9 as ch_9, sensor_data.channel_10 as ch_10, data_packets.time_stamp, data_packets.latitude, data_packets.longitude', 'data_packets ON sensor_data.id = data_packets.id', NULL, NULL); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
			$data_res = $db->getResult();*/

			$where_clause = "sensor_data.sensor_id=" .	"$sensor_id";
     		$db->select('data_packets','sensor_data.time_stamp, sensor_data.latitude, sensor_data.longitude, sensor_data.channel_1 as ch_1, sensor_data.channel_2 as ch_2, sensor_data.channel_3 as ch_3', 'sensor_data ON sensor_data.id = data_packets.id', $where_clause, 'data_packets.id DESC'); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
			$data_res = $db->getResult();

			if (sizeof($data_res) > 0) 
			{
				return  
		     	array (
		     		"error"=>false,
		     		"response"=>$data_res);
			}
			else
			{
				return  
		     	array (
		     	"error"=>true,
		     	"message"=>NO_DATA_FOUND);
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

     public function getDataByDevice($params){
	 	$db = new Database();
		$db->connect("../../../");
     	try
     	{
     		$device_id = $params['device_id'];

     		//
     		//select('CRUDClass','CRUDClass.id,CRUDClass.name,CRUDClassChild.name','CRUDClassChild ON CRUDClass.id = parentId','CRUDClass.name="Name 1"','id DESC');

     		$where_clause = "data_packets.device_id=" .	"$device_id";
     		$db->select('data_packets','sensor_data.time_stamp, sensor_data.latitude, sensor_data.longitude, sensor_data.channel_1 as ch_1, sensor_data.channel_2 as ch_2, sensor_data.channel_3 as ch_3', 'sensor_data ON sensor_data.id = data_packets.id', $where_clause, 'data_packets.id DESC'); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
			$data_res = $db->getResult();

			if (sizeof($data_res) > 0) 
			{
				return  
		     	array (
		     		"error"=>false,
		     		"response"=>$data_res);
			}
			else
			{
				return  
		     	array (
		     	"error"=>true,
		     	"message"=>NO_DATA_FOUND);
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

	 public function addDeviceData($params){
	 	$db = new Database();
		$db->connect("../../../");
     	try
     	{
     		$account_name = $params['account_id'];
     		$device_name = $params['device_id'];
			$sensor_name = $params['sensor_type'];
			$timestamp = $params['timestamp'];
			$longitude = $params['longitude'];
			$latitude = $params['latitude'];

			$channel_sql_query = '';
			$channel_sql_query_val = '';

			$ch_1 = $params['ch_1'];
			if(isset($params['ch_1']))
			{
				$ch_1 = $params['ch_1'];
				$channel_sql_query = $channel_sql_query . 'channel_1,';
				$channel_sql_query_val = $channel_sql_query_val . $ch_1 . ',';
			} 

			$ch_2 = $params['ch_2'];
			if(isset($params['ch_2']))
			{
				$ch_2 = $params['ch_2'];
				$channel_sql_query = $channel_sql_query . 'channel_2,';
				$channel_sql_query_val = $channel_sql_query_val . $ch_2 . ',';

			}

			$ch_3 = $params['ch_3'];
			if(isset($params['ch_3'])){
				$ch_3 = $params['ch_3'];
				$channel_sql_query = $channel_sql_query . 'channel_3,';
				$channel_sql_query_val = $channel_sql_query_val . $ch_3 . ',';
			}

		
			$ch_4 = '';
			if(isset($params['ch_4']))
			{
				$ch_4 = $params['ch_4'];
				$channel_sql_query = $channel_sql_query . 'channel_4,';
				$channel_sql_query_val = $channel_sql_query_val . $ch_4 . ',';
			}

			$ch_5 = '';
			if(isset($params['ch_5']))
			{
				$ch_5 = $params['ch_5'];
				$channel_sql_query = $channel_sql_query . 'channel_5,';
				$channel_sql_query_val = $channel_sql_query_val . $ch_5 . ',';
			}

			$ch_6 = '';
			if(isset($params['ch_6']))
			{
				$ch_6 = $params['ch_6'];
				$channel_sql_query = $channel_sql_query . 'channel_6,';
				$channel_sql_query_val = $channel_sql_query_val . $ch_6 . ',';
			}

			$ch_7 = '';
			if(isset($params['ch_7']))
			{
				$ch_7 = $params['ch_7'];
				$channel_sql_query = $channel_sql_query . 'channel_7,';
				$channel_sql_query_val = $channel_sql_query_val . $ch_7 . ',';

			}

			$ch_8 = '';
			if(isset($params['ch_8']))
			{
				$ch_8 = $params['ch_8'];
				$channel_sql_query = $channel_sql_query . 'channel_8,';
				$channel_sql_query_val = $channel_sql_query_val . $ch_8 . ',';
			}

			$ch_9 = '';
			if(isset($params['ch_9']))
			{
				$ch_9 = $params['ch_9'];
				$channel_sql_query = $channel_sql_query . 'channel_9,';
				$channel_sql_query_val = $channel_sql_query_val . $ch_9 . ',';
			}

			$ch_10 = '';
			if(isset($params['ch_10']))
			{
				$ch_10 = $params['ch_10'];
				$channel_sql_query = $channel_sql_query . 'channel_10,';
				$channel_sql_query_val = $channel_sql_query_val . $ch_10 . ',';
			}

			$channel_sql_query_trim = trim( $channel_sql_query, "," );
			$channel_sql_query_val_trim = trim( $channel_sql_query_val, "," );

			//echo $channel_sql_query_trim . "\n";
			//echo $channel_sql_query_val_trim;

			$where_clause = "account_id=" .	"'$account_name'";
			$db->select('accounts','id', NULL, $where_clause, NULL); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
			$account_id_res = $db->getResult();	

			if (sizeof($account_id_res) > 0)
			{
				//check if device belongs to the account
				$account_id_array = $account_id_res[0];
				$account_id_int = $account_id_array["id"];
				$where_clause = "account_id=" .	"$account_id_int" . " AND " . "device_id=" . "'$device_name'";
				
				$db->select('devices','id', NULL, $where_clause, NULL); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
				$dev_id_res = $db->getResult();

				if (sizeof($dev_id_res) > 0)
				{

					//check if device belongs to the account
					$device_id_array = $dev_id_res[0];
					$device_id_int = $device_id_array["id"];
					$timestamp_date = date("Y-m-d H:i:s", $timestamp)	;

					$where_clause = "sensor_name=" . "'$sensor_name'";
					$db->select('sensors','id', NULL, $where_clause, NULL); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
					$sensor_id_res = $db->getResult();	
					//print_r($sensor_id_res);
					if (sizeof($sensor_id_res) > 0)
					{
						//check if device belongs to the account
						$sensor_id_array = $sensor_id_res[0];
						$sensor_id_int = $sensor_id_array["id"];

						//Plain style
						$sql="INSERT INTO sensor_data (sensor_id," . $channel_sql_query_trim . ", time_stamp, latitude, longitude) VALUES
						($sensor_id_int," . $channel_sql_query_val_trim . ", '$timestamp_date', '$latitude', '$longitude')";

						//echo "query - ".$sql;

						$db->sql($sql);
						$res = $db->getResult();
						//print_r ($res);
						if (sizeof($res) > 0) 
						{
							$sensor_data_id = $res;
							

							//Plain style
							$sql="INSERT INTO data_packets (account_id, device_id, sensor_data_id) VALUES
							($account_id_int, $device_id_int, $sensor_data_id)";

							$db->sql($sql);
							$res = $db->getResult();

							//print_r($res);

							if (sizeof($res) > 0) 
							{
								return  
				     			array (
				     			"error"=>false,
				     			"message"=>DATA_ADDED);
							}
							else
							{
								return  
				     			array (
				     			"error"=>true,
				     			"message"=>DATA_NOT_ADDED);
							}

						}
						else
						{
							return  
			     			array (
			     			"error"=>true,
			     			"message"=>DATA_NOT_ADDED);
						}
					}
					else
					{
						return 
	     				array (
	     				"error"=>true,
	     				"message"=>SENSOR_NOT_FOUND);
					}

					
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
}