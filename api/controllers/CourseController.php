<?php
require_once __DIR__ . '/../models/Course.php';
class CourseController
{
    public function getAllCourses()
    {
        header('Content-Type: application/json');
        try {
            $courses = Course::getAll();
            echo json_encode($courses);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getCourseById($id)
    {
        header('Content-Type: application/json');
        try {
            $course = Course::getById($id);
            if ($course) {
                echo json_encode($course);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Course not found']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function getCoursesByCategoryId($catId)
    {
        header('Content-Type: application/json');
        try {
            $courses = Course::getByCategoryId($catId);
            echo json_encode($courses);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}

