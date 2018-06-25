<?php

// Slim middleware

use Slim\Middleware\JwtAuthentication;

$app->add(new JwtAuthentication([
    "path" => "/v1", /* or ["/api", "/admin"] */
    "secure" => true,
    "relaxed" => ["localhost", "codeneuron.com"],
    "secret" => "supersecretkeyyoushouldnotcommittogithub",
    "algorithm" => ["HS256"],
    "callback" => function ($request, $response, $arguments) use ($container) {
        $container["jwt"] = $arguments["decoded"];
    },
    "error" => function ($request, $response, $arguments) {
        $data["status"] = "error";
        $data["message"] = $arguments["message"];
        return $response
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }
]));