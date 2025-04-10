<?php
require_once __DIR__ . '/../utils/Database.php';
class Course {
    public static function getAll() {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT 
                            c.course_id, c.title, c.description, c.image_preview, c.category_id,
                            cat.name AS category_name
                            FROM courses c
                            JOIN categories cat ON c.category_id = cat.id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM courses WHERE course_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getByCategoryId($catId) {
        $db = Database::getConnection();
        $sql ="
        WITH RECURSIVE category_tree AS (
            SELECT id FROM categories WHERE id = ?
            UNION ALL
            SELECT c.id FROM categories c
            JOIN category_tree ct ON c.parent = ct.id
        )
        SELECT 
            c.course_id, c.title, c.description, c.image_preview, c.category_id,
            cat.name AS category_name
        FROM courses c
        JOIN categories cat ON c.category_id = cat.id
        WHERE c.category_id IN (SELECT id FROM category_tree)
    ";
        $stmt = $db->prepare($sql);
        $stmt->execute([$catId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
