<?php
define('ROOT', dirname(__DIR__));

require ROOT . '/app/ClassLoader.php';
require ROOT . '/config/config.php';

App\ClassLoader::register(ROOT);

$requestData = $_SERVER;
$requestData['POST'] = $_POST;
$requestData['GET'] = $_GET;

$serviceContainer = App\ServiceContainer::getInstance();

$dbConnection = new \PDO(DB_CONNECTION);

App\Services\DB::initializeService($serviceContainer, $dbConnection);
App\Services\Cache::initializeService($serviceContainer);
App\Services\Curl::initializeService($serviceContainer);
App\Services\SpotifyAPI::initializeService($serviceContainer);

$request = new App\Request($requestData['REQUEST_URI'], $requestData['REQUEST_METHOD'], $requestData);
$response = new App\Response('HTTP/1.1');
$router = new App\Router(require(ROOT . '/config/routes.php'));
$dispatcher = new App\Dispatcher;
$frontController = new App\FrontController($router, $dispatcher);
$frontController->run($request, $response);