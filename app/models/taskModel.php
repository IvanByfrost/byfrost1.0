<?php
class TaskModel extends mainModel {
    public function getAllByStudent($studentId) {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE student_id = ?");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function create($studentId, $data) {
        $stmt = $this->db->prepare("INSERT INTO tasks (student_id, title, description, due_date, status) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$studentId, $data['title'], $data['description'], $data['due_date'], $data['status']]);
    }
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE tasks SET title=?, description=?, due_date=?, status=? WHERE id=?");
        return $stmt->execute([$data['title'], $data['description'], $data['due_date'], $data['status'], $id]);
    }
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = ?");
        return $stmt->execute([$id]);
    }
} 