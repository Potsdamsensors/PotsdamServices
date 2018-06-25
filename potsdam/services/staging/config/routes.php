<?php

include '../src/customer.php'; 
include '../src/subscription.php'; 
include '../src/device.php'; 
include 'settings.php';
include '../src/utils.php'; 
//include '../../vendor/terwey/slim-swagger/src/Middleware/SlimSwagger.php'; 

use Slim\Http\Request;
use Slim\Http\Response;
use \Firebase\JWT\JWT;

//$app->add(new SlimSwagger(array(), array('baseDir' => __DIR__.'/../src/')));

$app->get('/', function (Request $request, Response $response) {
    $response->getBody()->write("It works! This is the default welcome page.");

    return $response;
})->setName('root');

$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
});	

//Log to file functions
use Psr\Log\LoggerInterface;
use Slim\Container;

$app->get('/logger-test', function (Request $request, Response $response) {
    /** @var Container $this */
    /** @var LoggerInterface $logger */

    $logger = $this->get('logger');
    $logger->error('My error message!');

    $response->getBody()->write("Success");

    return $response;
});

//Potsdam Device APIS


$app->post("/deviceHandshake", function ($request, $response, $arguments) {
    $body = $request->getBody();
    $params = json_decode($body, true);

    $params_required = verifyRequiredParams (array ('device_id', 'account_id'), $params);
    
    //print_r($params_required);
    $response->withHeader('Content-Type', 'application/json');
    if ($params_required['error'] == true)
    {
         //echo 'error';
        return json_encode($params_required);
    }
    else
    {
        $device = new Device ();
        $result = $device->checkIfDeviceExists ($params);
        if ($result['error'] == false)
        {
            $date = date_create();
            $timestamp = date_timestamp_get($date);

            $settings = $this->get('settings'); // get settings array.
            $token = JWT::encode(['timestamp' => $timestamp, 'device_id' => $params['device_id'], 'account_id' => $params['account_id']], $settings['jwt']['secret'], "HS256");
            //echo $token;
            $result["token"] = $token;
            return json_encode($result);
        }
        else
        {
            return json_encode($result);
        }
    }
});

$app->post("/v1/app/addDeviceData", function ($request, $response, $arguments) {

    $body = $request->getBody();
    $params = json_decode($body, true);
    //print_r($data);

    //Use this if application/json
    //$allPostPutVars = $request->getParsedBody();
    //print_r($allPostPutVars);
    //die;

    //need to add logic to validate chn data
    $params_required = verifyRequiredParams (array ('account_id', 'device_id', 'sensor_type', 'timestamp', 'longitude', 'latitude'), $params);
    
    //print_r($params_required);
    $response->withHeader('Content-Type', 'application/json');
    if ($params_required['error'] == true)
    {
         //echo 'error';
        return json_encode($params_required);
    }
    else
    {
         //echo 'no error';
         $device = new Device ();
         $result = $device->addDeviceData ($params);
         return json_encode($result);
    }

});

//Potsdam Portal APIs

//-------------- user apis -----------
$app->post("/v1/app/customerSignUp", function ($request, $response, $arguments) {
    $body = $request->getBody();
    $params = json_decode($body, true);
    //print_r($data);

    //Use this if application/json
    //$allPostPutVars = $request->getParsedBody();
    //print_r($allPostPutVars);
    //die;

    $params_required = verifyRequiredParams (array ('customer_name', 'customer_email', 'customer_password', 'account_id'), $params);
    
    //print_r($params_required);
    $response->withHeader('Content-Type', 'application/json');
    if ($params_required['error'] == true)
    {
         //echo 'error';
        return json_encode($params_required);
    }
    else
    {
         //echo 'no error';
         $customer = new Customer ();
         $result = $customer->signup ($params);
         return json_encode($result);
    }

});

$app->post("/customerLogin", function ($request, $response, $arguments) {
    $body = $request->getBody();
    $params = json_decode($body, true);

    $params_required = verifyRequiredParams (array ('customer_email', 'customer_password'), $params);

    //print_r($params_required);
    $response->withHeader('Content-Type', 'application/json');
    if ($params_required['error'] == true)
    {
         //echo 'error';
        return json_encode($params_required);
    }
    else
    {
        $customer = new Customer ();
        $result = $customer->login ($params);
        if ($result['error'] == false)
        {
            $date = date_create();
            $timestamp = date_timestamp_get($date);

            $settings = $this->get('settings'); // get settings array.
            $token = JWT::encode(['timestamp' => $timestamp, 'customer_email' => $params['customer_email']], $settings['jwt']['secret'], "HS256");
            //echo $token;
            $result["token"] = $token;
            return json_encode($result);
        }
        else
        {
            return json_encode($result);
        }
    }
});

$app->post("/v1/app/getLinkedDevices", function ($request, $response, $arguments) {
    $body = $request->getBody();
    $params = json_decode($body, true);
    //print_r($data);

    //Use this if application/json
    //$allPostPutVars = $request->getParsedBody();
    //print_r($allPostPutVars);
    //die;

    $params_required = verifyRequiredParams (array ('customer_id'), $params);
    
    //print_r($params_required);
    $response->withHeader('Content-Type', 'application/json');
    if ($params_required['error'] == true)
    {
         //echo 'error';
        return json_encode($params_required);
    }
    else
    {
         //echo 'no error';
         $customer = new Customer ();
         $result = $customer->getLinkedDevices ($params);
         return json_encode($result);
    }

});

//-------------- user apis [END] -----------

//-------------- subscription apis -----------

$app->post("/v1/app/deviceSubscription", function ($request, $response, $arguments) {
    $body = $request->getBody();
    $params = json_decode($body, true);
    //print_r($data);

    //Use this if application/json
    //$allPostPutVars = $request->getParsedBody();
    //print_r($allPostPutVars);
    //die;

    $params_required = verifyRequiredParams (array ('customer_id', 'device_id', 'plan_id'), $params);
    
    //print_r($params_required);
    $response->withHeader('Content-Type', 'application/json');
    if ($params_required['error'] == true)
    {
         //echo 'error';
        return json_encode($params_required);
    }
    else
    {
         //echo 'no error';
         $subsription = new Subscription ();
         $result = $subsription->subscribe ($params);
         return json_encode($result);
    }

});

$app->get('/v1/app/getSubscriptionPlans', function (Request $request, Response $response) {
    $response->withHeader('Content-Type', 'application/json');
   
    $subsription = new Subscription ();
    $result = $subsription->getSubscriptionPlans ();
    return json_encode($result);
});

//-------------- subscription apis [END]-----------

//-------------- device apis -----------

$app->get('/v1/app/getSensorTypes', function (Request $request, Response $response) {
    $response->withHeader('Content-Type', 'application/json');

    //print_r($this->get('jwt'));
   
    $device = new Device ();
    $result = $device->getSensorTypes ();
    return json_encode($result);
});


$app->post("/v1/app/getDataBySensor", function ($request, $response, $arguments) {

    $body = $request->getBody();
    $params = json_decode($body, true);
    //print_r($data);

    //Use this if application/json
    //$allPostPutVars = $request->getParsedBody();
    //print_r($allPostPutVars);
    //die;

    //need to add logic to validate chn data
    $params_required = verifyRequiredParams (array ('sensor_id'), $params);
    
    //print_r($params_required);
    $response->withHeader('Content-Type', 'application/json');
    if ($params_required['error'] == true)
    {
         //echo 'error';
        return json_encode($params_required);
    }
    else
    {
         //echo 'no error';
         $device = new Device ();
         $result = $device->getDataBySensor ($params);
         return json_encode($result);
    }

});

$app->post("/v1/app/getDataByDevice", function ($request, $response, $arguments) {

    $body = $request->getBody();
    $params = json_decode($body, true);
    //print_r($data);

    //Use this if application/json
    //$allPostPutVars = $request->getParsedBody();
    //print_r($allPostPutVars);
    //die;

    //need to add logic to validate chn data
    $params_required = verifyRequiredParams (array ('device_id'), $params);
    
    //print_r($params_required);
    $response->withHeader('Content-Type', 'application/json');
    if ($params_required['error'] == true)
    {
         //echo 'error';
        return json_encode($params_required);
    }
    else
    {
         //echo 'no error';
         $device = new Device ();
         $result = $device->getDataByDevice ($params);
         return json_encode($result);
    }

});

//-------------- device apis [END] -----------




