<?php
// public/index.php

// CORS – siempre lo primero
header("Access-Control-Allow-Origin: https://loceria-melchorita.vercel.app");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Cargar el router y las rutas
require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/app/Routes/api.php';

$router = new Router();
$router->run();
