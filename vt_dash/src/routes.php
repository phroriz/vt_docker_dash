<?php
use core\Router;
use src\middlewares\AuthAdmMiddleware;
use src\middlewares\AuthMiddleware;

$router = new Router();

// Rotas públicas
$router->get('/', 'PainelController@index' , [AuthMiddleware::class]);
$router->get('/auth/signin', 'AuthController@signin');
$router->post('/auth/signin/login', 'AuthController@login' );
$router->get('/auth/signin/login/first', 'AuthController@resetPass' );
$router->get('/auth/logoff', 'AuthController@logoff' );


$router->get('/painel', 'PainelController@home' , [AuthMiddleware::class]);

$router->get('/painel/dashboard/view/{hash}', 'DashboardController@view', [AuthMiddleware::class]);


$router->get('/painel/adm/groups', 'GruopController@list', [AuthAdmMiddleware::class]);
$router->get('/painel/adm/group/view/{hash}', 'GruopController@viewGroup', [AuthAdmMiddleware::class]);




$router->post('/private-api/1/user', 'GruopController@newUser', [AuthAdmMiddleware::class]);
$router->post('/private-api/1/auth/reset', 'AuthController@resetPassLogin');


$router->get('/private-api/1/group/id/{id}', 'GruopController@getGroupId', [AuthAdmMiddleware::class]);
$router->delete('/private-api/1/group/user', 'GruopController@removeUserGroup', [AuthAdmMiddleware::class]);
$router->put('/private-api/1/group/update', 'GruopController@updateGroup', [AuthAdmMiddleware::class]);
$router->post('/private-api/1/group', 'GruopController@createGroup', [AuthAdmMiddleware::class]);


$router->post('/private-api/1/dashboard', 'GruopController@newDashboard', [AuthAdmMiddleware::class]);
$router->get('/private-api/1/dashboard/hash/{hash}', 'GruopController@getDashboardHash', [AuthAdmMiddleware::class]);
$router->put('/private-api/1/dashboard/hash/{hash}', 'GruopController@updateDashboard', [AuthAdmMiddleware::class]);

$router->post('/private-api/1/comment', 'CommentController@new', [AuthMiddleware::class]);

$router->post('/private-api/1/nps', 'DashboardController@nps', [AuthMiddleware::class]);

$router->get('/private-api/1/email/send', 'EmailController@send', [AuthAdmMiddleware::class]);