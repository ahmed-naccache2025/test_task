<?php

require_once __DIR__ . '/../models/Category.php';

class CategoryController {
    public function getAllCategories() {
        try {
            $categories = Category::getAll();
            header('Content-Type: application/json');
            echo json_encode($categories);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}

