<?php

use Slim\Container;

/** @var \Slim\App $app */
$container = $app->getContainer();

// Activating routes in a subfolder
$container['environment'] = function () {
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $_SERVER['SCRIPT_NAME'] = dirname(dirname($scriptName)) . '/' . basename($scriptName);
    return new Slim\Http\Environment($_SERVER);
};

use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

$container['logger'] = function (Container $container) {
    $settings = $container->get('settings');
    $logger = new Logger($settings['logger']['name']);
    
    $level = $settings['logger']['level'];
    if (!isset($level)) {
        $level = Logger::ERROR;
    }
    
    $logFile = $settings['logger']['file'];
    $handler = new RotatingFileHandler($logFile, 0, $level, true, 0775);
    $logger->pushHandler($handler);
    
    return $logger;
};

$container['notAllowedHandler'] = function ($container) {
    return function ($request, $response, $methods) use ($container) {
    	$error_msg = array (
    		"error" => true,
    		"message" => 'There seems to be no body in ' . implode(', ', $methods));
    	$jsonstr = json_encode($error_msg);
        return $container['response']
            ->withStatus(405)
            ->withHeader('Allow', implode(', ', $methods))
            ->withHeader('Content-type', 'application/json')
            //->withHeader ('There seems to be no body'); . implode(', ', $methods)
            ->write($jsonstr);

    };
};