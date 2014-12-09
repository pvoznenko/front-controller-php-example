<?php

define('ROOT', dirname(__DIR__));

require ROOT . '/vendor/autoload.php';
require ROOT . '/config/config.php';

$routes = require(ROOT . '/config/routes.php');
$responseVersion = 'HTTP/1.1';

// request data is filtered on demand in App\Request
$requestData = $_SERVER;
$requestData['POST'] = $_POST;
$requestData['GET'] = $_GET;

$serviceContainer = App\ServiceContainer::getInstance();

$dbConnection = new \PDO(DB_CONNECTION);
$redisClient = new \Predis\Client([
    'host' => REDIS_HOST,
    'port' => REDIS_PORT,
]);

App\Services\DB::initializeService($serviceContainer, $dbConnection);
App\Services\Cache::initializeService($serviceContainer, $redisClient);
App\Services\Curl::initializeService($serviceContainer);
App\Services\SpotifyAPI::initializeService($serviceContainer);

$request = new App\Request($requestData['REQUEST_URI'], $requestData['REQUEST_METHOD'], $requestData);
$response = new App\Response($responseVersion);
$router = new App\Router($routes);
$dispatcher = new App\Dispatcher;
$frontController = new App\FrontController($router, $dispatcher);
$frontController->run($request, $response);