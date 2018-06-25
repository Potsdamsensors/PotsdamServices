<?php include "header.php"; ?>

<h2>Dummy data</h2>

	
<?php 

include "../common/mysqli/class/mysql_crud.php";

echo "<pre>";

$dir    = '../common/';
$files1 = scandir($dir);
print_r($files1);

//--- Add account and devices

$string = file_get_contents("../data/account_devices.json");
echo "json - ".$string;

$account_ids = array();
$device_ids = array();

$jsonIterator = new RecursiveIteratorIterator(
    new RecursiveArrayIterator(json_decode($string, TRUE)),
    RecursiveIteratorIterator::SELF_FIRST);

foreach ($jsonIterator as $key => $val) {
    if(is_array($val)) {
    	
        if ($key == 'devices'  && $key != '0')
        {
        	//echo "$key:\n";
        	$device_ids[] = $val;
        }
    } else {
        //echo "$key => $val\n";
        if ($key == 'account_id' && $key != '0')
        {
        	//echo "key - ".$key."\n";
        	$account_ids[] = array (
        		$key => $val,
        		"status" => 1
        	);
        }
    }
}

//print_r($account_ids);
//echo "-------:\n";
//print_r($device_ids);


$db = new Database();
$db->connect();

$i =0;
foreach ($account_ids as $account_id) {
	try
		{
			//$data = $db->escapeString("name5@email.com"); // Escape any input before insert
			$db->insert('accounts',$account_id);  // Table name, column names and respective values
			$res = $db->getResult();  
			print_r($res);
			
			foreach ($res as $key => $value) {
				//print_r($value);
				$insert_account_id = $value;
				if(is_int($value) == true)
				{
					foreach ($device_ids[$i] as $key => $value) {
						
						$device_account_map_data = array(
							"device_id" => $value,
							"account_id" => $insert_account_id,
							"status" => 1
						);

						//echo "device_account_map_data - ";
						//print_r($device_account_map_data);

						$db->insert('devices',$device_account_map_data);  // Table name, column names and respective values
						$res = $db->getResult();  
						//print_r($res);	
						// if not successful retry
						if (is_int($res[0]) == true) {
							echo 'success';
						}else{
							echo 'failed';
						}
					}
				
				}
				else
				{
					echo "wrong";
				}
			}

			
		}
		catch (Exception $ex)
		{
			printf($ex->getMessage());
		}
		$i++;
}

$db->disconnect ();

?>