<?php
use core\Router;
use src\middlewares\AuthMiddleware;

$router = new Router();

// Rotas públicas
$router->get('/auth/signin', 'AuthController@signin');
$router->post('/auth/signin/login', 'AuthController@login' );


$router->get('/painel', 'PainelController@home' , [AuthMiddleware::class]);

$router->get('/painel/dashboard/powerbi/view/{hash}', 'DashboardController@view', [AuthMiddleware::class]);

