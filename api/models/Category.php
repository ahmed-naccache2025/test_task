<?php

class Category {
    public static function getAll() {
        $pdo = self::connect();
        $stmt = $pdo->query("SELECT * FROM categories");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private static function connect() {
        $host = 'db';
        $dbname = 'course_catalog';
        $user = 'test_user';
        $pass = 'test_password';

        try {
            return new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
        } catch (PDOException $e) {
            throw new Exception('Database connection failed: ' . $e->getMessage());
        }
    }
}
