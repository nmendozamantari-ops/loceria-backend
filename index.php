<?php

// CORS (permitir frontend en Vercel)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../core/Router.php';

$router = new Router();

// Rutas
require_once __DIR__ . '/../app/Routes/api.php';

// Ejecutar router
$router->run();
