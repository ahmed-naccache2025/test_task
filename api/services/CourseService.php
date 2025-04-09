<?php
class CourseService {
    public static function getAllCourses($categoryId = null) {
        try {
            $courses = Course::getAll($categoryId);
            
            // Format the response to match Swagger spec
            return array_map(function($course) {
                return [
                    'id' => $course['id'],
                    'name' => $course['name'],
                    'description' => $course['description'],
                    'preview' => $course['preview'],
                    'main_category_name' => $course['main_category_name'],
                ];
            }, $courses);
        } catch (Exception $e) {
            error_log("CourseService Error: " . $e->getMessage());
            return false;
        }
    }

    public static function getCourseById($id) {
        try {
            $course = Course::getById($id);
            
            if (!$course) {
                return false;
            }
            
            // Format the response to match Swagger spec
            return [
                'id' => $course['id'],
                'name' => $course['name'],
                'description' => $course['description'],
                'preview' => $course['preview'],
                'main_category_name' => $course['main_category_name'],
            ];
        } catch (Exception $e) {
            error_log("CourseService Error: " . $e->getMessage());
            return false;
        }
    }
}