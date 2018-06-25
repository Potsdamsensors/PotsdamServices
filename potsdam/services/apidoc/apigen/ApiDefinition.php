<?php

class ApiDefinition
{
    /**
     * @ApiDescription(section="DeviceHandshake", description="Make an handshake with the server to authenticate the device and get an access token. This will be a one time call per device. For phase 2 we will be implementing an expiry during when the device will need to call this once a day.")
     * @ApiMethod(type="post")
     * @ApiRoute(name="/deviceHandshake")
     * @ApiHeaders(name="Content-Type", type="string", nullable=false, description="application/json")
     * @ApiParams(name="device_id", type="string", nullable=false, description="Device ID")
     * @ApiParams(name="account_id", type="string", nullable=false, description="Account ID")
     * @ApiReturnHeaders(sample="HTTP 200 OK")
     * @ApiBody(sample="{
        'device_id':'a001',
        'account_id':'dasdas'
        }")
     * @ApiReturn(type="object", sample="{
            'error': false,  'response': [
                {
                    'id': '1'
                }
            ], 'token':'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ0aW1lc3RhbXAiOjE1MjkxNzA3NDQsImN1c3RvbWVyX2VtYWlsIjoibmFyYXlhbkBnbWFpbC5jb20ifQ.W453vY_c7yf_RxFGjrRP1M_2aRThBAVnCuldPFxAGoY'
        }")
     */
    public function DeviceHandshake()
    {

    }

        /**
     * @ApiDescription(section="AddDeviceData", description="API method to get the sensor data from device. This API accepts data upto 10 channels named as chn_n where n < 0 and <= 10")
     * @ApiMethod(type="post")
     * @ApiRoute(name="/v1/app/addDeviceData")
     * @ApiHeaders(name="Content-Type", type="string", nullable=false, description="application/json")
     * @ApiHeaders(name="Authorization", type="string", nullable=false, description="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ0aW1lc3RhbXAiOjE1MjgwNTIzNTUsImRldmljZV9uYW1lIjoiYTAwMSJ9.zgFNIHQpnwWf4WU-IHC3h_1N2Mzf8EIT7BgoOpasHo0")
     * @ApiParams(name="device_id", type="string", nullable=false, description="Device ID")
     * @ApiParams(name="account_id", type="string", nullable=false, description="Account ID")

     * @ApiParams(name="sensor_type", type="string", nullable=false, description="Sensor type")
     * @ApiParams(name="timestamp", type="string", nullable=false, description="Timestamp")
     * @ApiParams(name="longitude", type="string", nullable=false, description="Longitude")
     * @ApiParams(name="latitude", type="string", nullable=false, description="Latitude")

     * @ApiParams(name="ch_1", type="float", nullable=false, description="Channel data")
     * @ApiParams(name="ch_2", type="float", nullable=false, description="Channel data")
     * @ApiParams(name="ch_10", type="float", nullable=false, description="Channel data")
     * @ApiReturnHeaders(sample="HTTP 200 OK")
     * @ApiBody(sample=" {
       'account_id':'abc123456789',
       'device_id':'a002',
       'sensor_type':'pots001',
       'timestamp':'1526281944',
       'longitude':'13.0836',
       'latitude':'80.2392',
       'ch_1':10.20,
       'ch_2':34.32,
       'ch_10':65.34
      }")
     * @ApiReturn(type="object", sample="{'error':false,'message':'sensor data from device has been added'}
")
     */
    public function AddDeviceData()
    {

    }

  
}
