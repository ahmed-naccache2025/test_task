<?php
class CategoryService {
    /**
     * Get all categories with course counts (including subcategories)
     */
    public static function getAllCategories() {
        try {
            $db = Database::getInstance();
            
            // Single query to get all categories with hierarchical relationships and course counts
            $query = "
                WITH RECURSIVE category_tree AS (
                    -- Base case: top-level categories
                    SELECT 
                        c.id, 
                        c.name, 
                        c.parent as parent_id,
                        c.created_at,
                        c.updated_at,
                        0 as level
                    FROM categories c
                    WHERE c.parent IS NULL
                    
                    UNION ALL
                    
                    -- Recursive case: child categories
                    SELECT 
                        c.id, 
                        c.name, 
                        c.parent as parent_id,
                        c.created_at,
                        c.updated_at,
                        ct.level + 1
                    FROM categories c
                    JOIN category_tree ct ON c.parent = ct.id
                    WHERE ct.level < 3 -- Enforce max depth of 4 (0-3)
                )
                
                SELECT 
                    ct.id,
                    ct.name,
                    ct.parent_id,
                    ct.created_at,
                    ct.updated_at,
                    COUNT(c.id) as count_of_courses
                FROM category_tree ct
                LEFT JOIN courses c ON c.category_id = ct.id
                GROUP BY ct.id, ct.name, ct.parent_id, ct.created_at, ct.updated_at
                ORDER BY ct.level, ct.name
            ";
            
            $stmt = $db->query($query);
            $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Build hierarchical structure
            return self::buildCategoryTree($categories);
        } catch (Exception $e) {
            error_log("CategoryService Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get single category by ID with course count
     */
    public static function getCategoryById($id) {
        try {
            $db = Database::getInstance();
            
            $query = "
                SELECT 
                    c.id,
                    c.name,
                    c.parent as parent_id,
                    c.created_at,
                    c.updated_at,
                    (
                        SELECT COUNT(*) 
                        FROM courses co 
                        WHERE co.category_id = c.id
                    ) as count_of_courses
                FROM categories c
                WHERE c.id = :id
            ";
            
            $stmt = $db->prepare($query);
            $stmt->execute([':id' => $id]);
            $category = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$category) {
                return false;
            }
            
            // Format according to Swagger spec
            return [
                'id' => $category['id'],
                'name' => $category['name'],
                'parent_id' => $category['parent_id'],
                'count_of_courses' => (int)$category['count_of_courses'],
                'created_at' => $category['created_at'],
                'updated_at' => $category['updated_at']
            ];
        } catch (Exception $e) {
            error_log("CategoryService Error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Build hierarchical category tree from flat array
     */
    private static function buildCategoryTree(array $categories, $parentId = null) {
        $branch = [];
        
        foreach ($categories as $category) {
            if ($category['parent_id'] === $parentId) {
                $children = self::buildCategoryTree($categories, $category['id']);
                if ($children) {
                    $category['children'] = $children;
                }
                $branch[] = [
                    'id' => $category['id'],
                    'name' => $category['name'],
                    'parent_id' => $category['parent_id'],
                    'count_of_courses' => (int)$category['count_of_courses'],
                    'created_at' => $category['created_at'],
                    'updated_at' => $category['updated_at'],
                    'children' => $children ?? []
                ];
            }
        }
        
        return $branch;
    }
}