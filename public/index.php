<?php
define('ROOT', dirname(__DIR__));

require ROOT . '/app/ClassLoader.php';
require ROOT . '/app/config/config.php';

app\ClassLoader::register(ROOT);

$requestData = $_SERVER;
$requestData['POST'] = $_POST;

$request = new app\Request($requestData['REQUEST_URI'], $requestData['REQUEST_METHOD'], $requestData);
$response = new app\Response('HTTP/1.1');
$router = new app\Router(require(ROOT . '/app/config/routes.php'));
$dispatcher = new app\Dispatcher;
$frontController = new app\FrontController($router, $dispatcher);
$frontController->run($request, $response);