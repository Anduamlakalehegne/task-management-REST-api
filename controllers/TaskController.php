<?php

require_once __DIR__ . '/../models/Task.php';

class TaskController {
    private $taskModel;

    public function __construct() {
        $this->taskModel = new Task();
    }

    public function index() {
        $status = $_GET['status'] ?? null;
        $tasks = $this->taskModel->getAll($status);
        
        $this->sendResponse(200, [
            'success' => true,
            'data' => $tasks
        ]);
    }

    public function show($id) {
        $task = $this->taskModel->getById($id);
        
        if (!$task) {
            $this->sendResponse(404, [
                'success' => false,
                'message' => 'Task not found'
            ]);
            return;
        }
        
        $this->sendResponse(200, [
            'success' => true,
            'data' => $task
        ]);
    }

    public function store() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            $this->sendResponse(400, [
                'success' => false,
                'message' => 'Invalid JSON input'
            ]);
            return;
        }
        
        $errors = $this->taskModel->validate($input);
        
        if (!empty($errors)) {
            $this->sendResponse(400, [
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errors
            ]);
            return;
        }
        
        $id = $this->taskModel->create($input);
        $task = $this->taskModel->getById($id);
        
        $this->sendResponse(201, [
            'success' => true,
            'message' => 'Task created successfully',
            'data' => $task
        ]);
    }

    public function update($id) {
        $task = $this->taskModel->getById($id);
        
        if (!$task) {
            $this->sendResponse(404, [
                'success' => false,
                'message' => 'Task not found'
            ]);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            $this->sendResponse(400, [
                'success' => false,
                'message' => 'Invalid JSON input'
            ]);
            return;
        }
        
        $errors = $this->taskModel->validate($input);
        
        if (!empty($errors)) {
            $this->sendResponse(400, [
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errors
            ]);
            return;
        }
        
        $this->taskModel->update($id, $input);
        $updatedTask = $this->taskModel->getById($id);
        
        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Task updated successfully',
            'data' => $updatedTask
        ]);
    }

    public function delete($id) {
        $task = $this->taskModel->getById($id);
        
        if (!$task) {
            $this->sendResponse(404, [
                'success' => false,
                'message' => 'Task not found'
            ]);
            return;
        }
        
        $this->taskModel->delete($id);
        
        $this->sendResponse(200, [
            'success' => true,
            'message' => 'Task deleted successfully'
        ]);
    }

    private function sendResponse($statusCode, $data) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_PRETTY_PRINT);
        exit;
    }
} 