<?php
class Course {
    public static function getAll($categoryId = null) {
        $db = Database::getInstance();
        $query = "SELECT 
                    c.id, 
                    c.title as name, 
                    c.description, 
                    c.image_preview as preview,
                    cat.name as main_category_name,
                    c.created_at,
                    c.updated_at
                  FROM course_list c
                  JOIN categories cat ON c.category_id = cat.id";
        
        if ($categoryId) {
            $query .= " WHERE c.category_id = :category_id";
            $stmt = $db->prepare($query);
            $stmt->execute([':category_id' => $categoryId]);
        } else {
            $stmt = $db->query($query);
        }
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT 
                                c.id, 
                                c.title as name, 
                                c.description, 
                                c.image_preview as preview,
                                cat.name as main_category_name,
                                c.created_at,
                                c.updated_at
                              FROM course_list c
                              JOIN categories cat ON c.category_id = cat.id
                              WHERE c.id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}