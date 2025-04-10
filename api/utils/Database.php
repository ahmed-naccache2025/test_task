<?php

class Database {
    public static function getConnection() {
        static $db = null;
        if ($db === null) {
            try {
                $db = new PDO("mysql:host=db;dbname=course_catalog;charset=utf8", "test_user", "test_password");
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode(['error' => "Database connection failed: " . $e->getMessage()]);
                exit;
            }
        }

        return $db;
    }
}
