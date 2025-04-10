<?php
ob_start(); 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
require_once __DIR__ . '/../controllers/CourseController.php';

$requestUri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
$controller = new CourseController();
if ($method === 'GET' && $requestUri === '/courses') {
    $controller->getAllCourses();
    return;
}
if ($method === 'GET' && preg_match('#^/courses/([^/]+)$#', $requestUri, $matches)) {
    $controller->getCourseById($matches[1]);
    return;
}
if ($method === 'GET' && preg_match('#^/courses/by-category/([a-f0-9\-]{36})$#', $requestUri, $matches)) {
    $controller->getCoursesByCategoryId($matches[1]);
    return;
}
