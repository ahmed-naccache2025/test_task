<?php
require_once __DIR__ . '/../utils/Database.php';
class Category {
    public static function getAll() {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT id, name, parent FROM categories");
        $stmt->execute();
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $db->prepare("SELECT category_id, COUNT(*) as count FROM courses GROUP BY category_id");
        $stmt->execute();
        $counts = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); 

        $categoryMap = [];
        $tree = [];
        foreach ($categories as &$cat) {
            $cat['children'] = [];
            $cat['course_count'] = isset($counts[$cat['id']]) ? (int)$counts[$cat['id']] : 0;
            $categoryMap[$cat['id']] = &$cat;
        }
    
        foreach ($categories as &$cat) {
            if ($cat['parent'] && isset($categoryMap[$cat['parent']])) {
                $categoryMap[$cat['parent']]['children'][] = &$cat;
            } else {
                $tree[] = &$cat;
            }
        }

        function sumCourses(&$cat) {
            foreach ($cat['children'] as &$child) {
                $cat['course_count'] += sumCourses($child);
            }
            return $cat['course_count'];
        }
    
        foreach ($tree as &$top) {
            sumCourses($top);
        }
    
        return $categories;
    }
    public static function getById($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM categories WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
