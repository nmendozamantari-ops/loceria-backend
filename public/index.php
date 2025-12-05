<?php
// public/index.php

// Lista de dominios permitidos (agrega más si es necesario)
$allowed_origins = [
    "https://loceria-melchorita.vercel.app",
    "https://loceria-melchorita-m60sa0cwg-nicos-projects-47970120.vercel.app"
];

// Detectar el origen real que hace la petición
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
}

// CORS básico
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept");
header("Access-Control-Allow-Credentials: true");

// Manejo del preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Cargar la clase Router
require_once __DIR__ . '/core/Router.php';

// Crear instancia del router ANTES de cargar las rutas
$router = new Router();

// Cargar las rutas
require_once __DIR__ . '/app/Routes/api.php';

// Ejecutar router
$router->run();
