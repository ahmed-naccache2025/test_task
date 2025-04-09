<?php

require_once __DIR__ . '/../controllers/CategoryController.php';

$controller = new CategoryController();
$controller->getAllCategories();
