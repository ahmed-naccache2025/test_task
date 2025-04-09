<?php
class CoursesController {
    public function index() {
        header('Content-Type: application/json');
        try {
            $db = new PDO(
                'mysql:host=db;dbname=course_catalog',
                'test_user',
                'test_password'
            );
            
            $query = "SELECT * FROM course_list";
            $stmt = $db->query($query);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    public function show($id) {
        header('Content-Type: application/json');
        try {
            $db = new PDO(
                'mysql:host=db;dbname=course_catalog',
                'test_user',
                'test_password'
            );
            
            $stmt = $db->prepare("SELECT * FROM course_list WHERE course_id = ?");
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                echo json_encode($result);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Course not found']);
            }
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}