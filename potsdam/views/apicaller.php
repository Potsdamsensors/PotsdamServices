<?php

//$res = CallAPI ("POST", "http://codeneuron.com/rest/potsdam/services/staging/v1/app/addDeviceData", "{}");
for ($i=0; $i < 30 ; $i++) { 
   InsertSensorData ();
}


function InsertSensorData()
{
    $chn_temp = array("ch_4", "ch_5", "ch_3", "ch_7", "ch_9");
    $chn_rand_key = array_rand($chn_temp);
    //echo $chn_rand_key;
   $data =  array('account_id' => 'abc123456789', 'device_id' => 'a002', 'sensor_type' => 'pots001', 'timestamp' => '', 'longitude' => '', 
    'latitude' => '', 
    'ch_1' => 10.20,
    'ch_2' => 34.32,
    $chn_temp[$chn_rand_key] => 65.34);

    $int= mt_rand(1529193600,1529280000);
    $string = date("Y-m-d H:i:s",$int);
    //echo $string;

    $data ['timestamp'] = "" . $int . "";
    //echo $data ['timestamp'];

    $latlong = RandLatLong ();

    $data ['longitude'] = "" . $latlong['long'] . "";
    $data ['latitude'] = "" . $latlong['lat']. "";

    $data ['ch_1'] = Randfloat();
    $data ['ch_2'] = Randfloat();
    $data [$chn_temp[$chn_rand_key]] = Randfloat();

    echo "<pre>";

    $res = CallAPI ("POST", "http://localhost/rest/potsdam/services/staging/v1/app/addDeviceData", $data);
    print_r($res);
}

function RandLatLong ()
{
    $longitude = (float) 13.0827;
    $latitude = (float) 80.2707;
    $radius = rand(1,10); // in miles

    $lng_min = $longitude - $radius / abs(cos(deg2rad($latitude)) * 69);
    $lng_max = $longitude + $radius / abs(cos(deg2rad($latitude)) * 69);
    $lat_min = $latitude - ($radius / 69);
    $lat_max = $latitude + ($radius / 69);

    return array ('long' => $lng_min, 'lat' => $lat_min);
}

function Randfloat($st_num=-3.0,$end_num=11,$mul=1000000)
{
if ($st_num>$end_num) return false;
return mt_rand($st_num*$mul,$end_num*$mul)/$mul;
}

function CallAPI($method, $url, $data = false)
{
    $curl = curl_init();

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
            {
                print_r($data);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            }
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Optional Authentication:
    //curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    //curl_setopt($curl, CURLOPT_USERPWD, "username:password");
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
      'Authorization:   aBearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ0aW1lc3RhbXAiOjE1MjgwNTIzNTUsImRldmljZV9uYW1lIjoiYTAwMSJ9.zgFNIHQpnwWf4WU-IHC3h_1N2Mzf8EIT7BgoOpasHo0'
          ));
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);
    print_r($result);
    curl_close($curl);

    return $result;
}
?>