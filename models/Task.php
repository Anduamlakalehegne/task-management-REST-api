<?php

require_once __DIR__ . '/../database/Database.php';

class Task {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll($status = null) {
        $sql = "SELECT * FROM tasks";
        $params = [];
        
        if ($status) {
            $sql .= " WHERE status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $sql = "SELECT * FROM tasks WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO tasks (title, description, status) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['title'],
            $data['description'] ?? '',
            $data['status'] ?? 'pending'
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $sql = "UPDATE tasks SET title = ?, description = ?, status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['title'],
            $data['description'] ?? '',
            $data['status'] ?? 'pending',
            $id
        ]);
    }

    public function delete($id) {
        $sql = "DELETE FROM tasks WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function validate($data) {
        $errors = [];
        
        if (empty($data['title'])) {
            $errors[] = 'Title is required';
        }
        
        if (isset($data['status']) && !in_array($data['status'], ['pending', 'in-progress', 'completed'])) {
            $errors[] = 'Status must be pending, in-progress, or completed';
        }
        
        return $errors;
    }
} 