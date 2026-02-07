<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config/Config.php';
require_once __DIR__ . '/config/Conexion.php';
require_once __DIR__ . '/controller/helpers.php';

$listaBlanca = [
    'auth',
    'dashboard',
    'entrenamiento',
    'ejercicio',
    'metrics',
    'perfil',
    'admin',
    'serie',
];

$controllerName = $_GET['controller'] ?? 'auth'; 
$action         = $_GET['action']     ?? 'login';  

$controllerName = strtolower(preg_replace('/[^a-z]/', '', $controllerName));
$action         = preg_replace('/[^a-zA-Z0-9_]/', '', $action);

if (!in_array($controllerName, $listaBlanca, true)) {
    http_response_code(404);
    echo 'Controlador no encontrado.';
    exit;
}

$controllerClass = ucfirst($controllerName) . 'Controller';      
$controllerFile  = __DIR__ . '/controller/' . $controllerClass . '.php';

if (!file_exists($controllerFile)) {
    http_response_code(404);
    echo 'Controlador no encontrado.';
    exit;
}

require_once $controllerFile;

$controller = new $controllerClass();

if (!method_exists($controller, $action)) {
    http_response_code(404);
    echo 'Acción no encontrada.';
    exit;
}

$controller->$action();
