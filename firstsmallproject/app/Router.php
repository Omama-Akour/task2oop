<?php



use App\Router;

// Instantiate the router
$router = require_once 'Routes/routes.php';

// Resolve the route and handle the request
echo $router->resolve($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
