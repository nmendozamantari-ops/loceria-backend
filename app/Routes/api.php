<?php
require_once __DIR__ . '/../../core/Router.php';
require_once __DIR__ . '/../Controllers/AuthController.php';
require_once __DIR__ . '/../Controllers/ProductoController.php';

$router = new Router();

// Autenticación
$router->post('/auth/register', 'AuthController@register');
$router->post('/auth/login', 'AuthController@login');
$router->post('/auth/validate', 'AuthController@validateToken');

// Productos
$router->get('/productos', 'ProductoController@getAll');
$router->post('/productos/get', 'ProductoController@getById');

// Perfil protegido
$router->get('/auth/me', 'AuthController@me', 'AuthMiddleware');
