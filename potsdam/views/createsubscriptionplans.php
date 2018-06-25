<?php include "header.php"; ?>

<h2>Dummy data</h2>

	
<?php 

include "../common/mysqli/class/mysql_crud.php";

echo "<pre>";


$string = file_get_contents("../data/subscriptions.json");
//echo "json - ".$string;

$data = array();
$plans = array();
$durations = array();

$jsonIterator = new RecursiveIteratorIterator(
    new RecursiveArrayIterator(json_decode($string, TRUE)),
    RecursiveIteratorIterator::SELF_FIRST);

foreach ($jsonIterator as $key => $val) {
    if(!is_array($val)) {

        if ($key == 'name' && $key != '0')
        {
        	$plans[] = $val;
        	
        }
        else if ($key == 'duration' && $key != '0')
        {
        	$durations[] = $val;
        }
	}
}

print_r($durations);

$db = new Database();
$db->connect();

$i =0;
foreach ($plans as $plan) {
	try
		{
				
				$where_clause = "name=" .	"'$plan'";
				$db->select('subscription_plans','*',NULL,$where_clause,NULL); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
				$res = $db->getResult();
				//print_r ($res);

				if (sizeof($res) == 0)
				{
					//$data = $db->escapeString("name5@email.com"); // Escape any input before insert
					$db->insert('subscription_plans',array(
						"name" => $plan,
						"duration" => $durations[$i]
					));  // Table name, column names and respective values
					$res = $db->getResult();  
					//print_r($res);
					if (sizeof($res) != 0) 
					{
						echo "plan added";
					
					}
					else
					{
						echo "plan could not be added";
					}
				}
				else{
					echo "plan already exists";
				}

		}
		catch (Exception $ex)
		{
			echo $ex->getMessage();
		}
		$i++;
}


$db->disconnect();


/*
$string = file_get_contents("../data/sensors.json");
//echo "json - ".$string;

$data = array();
$sensors = array();
$channels = array();

$jsonIterator = new RecursiveIteratorIterator(
    new RecursiveArrayIterator(json_decode($string, TRUE)),
    RecursiveIteratorIterator::SELF_FIRST);

foreach ($jsonIterator as $key => $val) {
    if(!is_array($val)) {

        if ($key == 'name' && $key != '0')
        {
        	$sensors[] = $val;
        	
        }
        else if ($key == 'channels' && $key != '0')
        {
        	$channels[] = $val;
        }
	}
}

print_r($channels);

$db = new Database();
$db->connect();

$i =0;
foreach ($sensors as $sensor) {
	try
		{
				
				$where_clause = "sensor_name=" .	"'$sensor'";
				$db->select('sensors','*',NULL,$where_clause,NULL); // Table name, Column Names, JOIN, WHERE conditions, ORDER BY conditions
				$res = $db->getResult();
				//print_r ($res);

				if (sizeof($res) == 0)
				{
					//$data = $db->escapeString("name5@email.com"); // Escape any input before insert
					$db->insert('sensors',array(
						"sensor_name" => $sensor,
						"channels" => $channels[$i]
					));  // Table name, column names and respective values
					$res = $db->getResult();  
					if (sizeof($res) != 0) 
					{
						echo "sensor added";
					
					}
					else
					{
						echo "sensor could not be added";
					}
				}
				else{
					echo "sensor already exists";
				}

		}
		catch (Exception $ex)
		{
			echo $ex->getMessage();
		}
		$i++;
}


$db->disconnect();
*/

/*

$dir    = '../common/';
$files1 = scandir($dir);
print_r($files1);

--- Add account and devices

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

die;

 */

if (isset($_POST['submit'])) {

	try {

		/*

		<form method="post">
		<label for="username">Username</label>
		<input type="text" name="username" id="username">
		<label for="password">Password</label>
		<input type="text" name="password" id="password">

		<input type="submit" name="submit" value="Submit">
		</form>

		<a href="index.php">Back to home</a>


		//$connection = new PDO($dsn, $username, $password, $options);

		$date = date_create();
		$date_str = $date->format('Y-m-d H:i:s');
		//echo date_timestamp_get($date);
		//echo $dsn;
		//die;

		$new_user = array (
			"username" => $_POST['username'],
			"password" => $_POST['password'],
			"date" => $date_str
		);

		include('../common/mysqli/class/mysql_crud.php');

		$db = new Database();
		$db->connect();
		//$data = $db->escapeString("name5@email.com"); // Escape any input before insert
		$db->insert('admin_users',$new_user);  // Table name, column names and respective values
		$res = $db->getResult();  
		print_r($res);

		
		$insert_part1 = implode(', ', array_keys($new_user));
		$insert_part2 = implode(', :', array_keys($new_user));

		//$values = array_map('array_pop', $new_user);
		$imploded = implode(',', $new_user);

		$sql = sprintf(
		"INSERT INTO %s (%s) values (%s)",
		"users",
		implode(", ", array_keys($new_user)),
		":" . implode(", :", array_keys($new_user))
		);

		$statement = $connection->prepare($sql);
		$statement->execute($new_user);
	
		echo "keys - ".$insert_part1;
		echo "<br>";
		echo "values - ".$insert_part2;
		echo "<br>";

		echo "imploded - ".$imploded;

		echo '</pre>';


		die;
		*/


	} catch(PDOException $error) {
		echo $error->getMessage();
	}
}

?>

<?php include "footer.php"; ?>