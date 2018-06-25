<?php include "header.php"; ?>

<h2>Dummy data</h2>

	
<?php 

include "../common/mysqli/class/mysql_crud.php";

echo "<pre>";

$dir    = '../common/';
$files1 = scandir($dir);
print_r($files1);

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

?>