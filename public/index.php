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

// Cargar la clase Router
require_once __DIR__ . '/core/Router.php';

// Crear la instancia DEL ROUTER antes de cargar las rutas
$router = new Router();

// Cargar las rutas (aquí $router YA EXISTE)
require_once __DIR__ . '/app/Routes/api.php';

// Ejecutar el router
$router->run();
