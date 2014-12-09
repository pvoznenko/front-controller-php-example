<?php
define('ROOT', dirname(__DIR__));

require ROOT . '/app/ClassLoader.php';
require ROOT . '/app/config/config.php';

app\ClassLoader::register(ROOT);

/**
 * TODO: fix XSS hole
 */
$requestData = $_SERVER;
$requestData['POST'] = $_POST;
$requestData['GET'] = $_GET;

$serviceContainer = app\ServiceContainer::getInstance();

app\services\DB::initializeService($serviceContainer, new \PDO('sqlite:' . DB_FILE_PATH));
app\services\Cache::initializeService($serviceContainer);
app\services\Curl::initializeService($serviceContainer);
app\services\SpotifyAPI::initializeService($serviceContainer);

$request = new app\Request($requestData['REQUEST_URI'], $requestData['REQUEST_METHOD'], $requestData);
$response = new app\Response('HTTP/1.1');
$router = new app\Router(require(ROOT . '/app/config/routes.php'));
$dispatcher = new app\Dispatcher;
$frontController = new app\FrontController($router, $dispatcher);
$frontController->run($request, $response);