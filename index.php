<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET,POST,PUT,PATCH,DELETE");
header('Access-Control-Allow-Credentials: true');
//header('Content-Type: plain/text');
//header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Methods,Access-Control-Allow-Origin, Access-Control-Allow-Credentials, Authorization, X-Requested-With");

require __DIR__ . "/inc/bootstrap.php";

$router = new Router();

$router->get('/transactions', TransactionController::class . '::listAction');
$router->get('/accounts', AccountController::class . '::listAction');
$router->get('/categories', CategoryController::class . '::listAction');
$router->get('/auth', UserController::class . '::auth');
$router->get('/logout', UserController::class . '::logout');
$router->get('/chart-data', ChartController::class . '::getChartData');
$router->get('/chart-amount', ChartController::class . '::getAmountPerYear');
$router->post('/login', UserController::class . '::login');
$router->addNotFoundHandler(NotFoundPageController::class . '::notFound');

$router->run();

