<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_erros', 1);
error_reporting(E_ALL);
require 'vendor/autoload.php';
use App\Core\Middlewares;
use Dopesong\Slim\Error\Whoops as WhoopsError;

$middleware = new Middlewares;
$c = new \Slim\Container(); //Create Your container

//Override the default Not Found Handler
$c = [
    'notFoundHandler' => function ($c) {
        return new \App\Controllers\errorController();
    },
];

$app = new \Slim\App($c);
$container = $app->getContainer();

$container['phpErrorHandler'] = $container['errorHandler'] = function ($c) {
    return new WhoopsError($c->get('settings')['displayErrorDetails']);
};

$app->get('/', '\App\Controllers\homeController:index');
$app->get('/home', '\App\Controllers\homeController:index');
$app->get('/sobre', '\App\Controllers\sobreController:index');
$app->get('/register', '\App\Controllers\registerController:index');
$app->post('/user/inscrever', '\App\Controllers\inscreverController:index');

$app->get('/login', '\App\Controllers\loginController:index');
$app->post('/access', '\App\controllers\admin\adminController:store');
$app->group('/admin', function () use ($app) {
    $app->get('/painel', '\App\Controllers\admin\painelController:index');
    $app->get('/logout', '\App\Controllers\admin\adminController:destroy');
})->add($middleware->admin());

$app->get('/userLogin', '\App\Controllers\loginController:user');
$app->post('/userAccess', '\App\controllers\user\userController:store');
$app->group('/user', function () use ($app) {
    include('src/App/Rotas/paciente_route.php');
    include('src/App/Rotas/evolucao_route.php');   
    $app->get('/logout', '\App\Controllers\user\userController:destroy');
})->add($middleware->user());

$app->run();
