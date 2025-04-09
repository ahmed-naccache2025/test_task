<?php

$request = $_SERVER['REQUEST_URI'];

switch ($request) {
    case '/categories':
        require __DIR__ . '/routes/categories.php';
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
        break;
}
