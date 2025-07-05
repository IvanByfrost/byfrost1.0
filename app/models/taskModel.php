<?php
class TaskModel extends mainModel {
    public function getAllByStudent($studentId) {
        $stmt = $this->dbConn->prepare("SELECT * FROM tasks WHERE student_id = ?");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function create($studentId, $data) {
        $stmt = $this->dbConn->prepare("INSERT INTO tasks (student_id, title, description, due_date, status) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$studentId, $data['title'], $data['description'], $data['due_date'], $data['status']]);
    }
    public function update($id, $data) {
        $stmt = $this->dbConn->prepare("UPDATE tasks SET title=?, description=?, due_date=?, status=? WHERE id=?");
        return $stmt->execute([$data['title'], $data['description'], $data['due_date'], $data['status'], $id]);
    }
    public function delete($id) {
        $stmt = $this->dbConn->prepare("DELETE FROM tasks WHERE id = ?");
        return $stmt->execute([$id]);
    }
} 