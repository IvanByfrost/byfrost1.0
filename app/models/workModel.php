<?php
class WorkModel extends mainModel {
    public function getAllByStudent($studentId) {
        $stmt = $this->dbConn->prepare("SELECT * FROM works WHERE student_id = ?");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getCountByStudent($studentId) {
        $stmt = $this->dbConn->prepare("SELECT COUNT(*) FROM works WHERE student_id = ?");
        $stmt->execute([$studentId]);
        return $stmt->fetchColumn();
    }
    
    public function getRecentByStudent($studentId) {
        $stmt = $this->dbConn->prepare("SELECT * FROM works WHERE student_id = ? ORDER BY created_at DESC LIMIT 5");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function create($studentId, $data) {
        $stmt = $this->dbConn->prepare("INSERT INTO works (student_id, title, description, due_date, status, file_path, grade) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$studentId, $data['title'], $data['description'], $data['due_date'], $data['status'], $data['file_path'], $data['grade']]);
    }
    public function update($id, $data) {
        $stmt = $this->dbConn->prepare("UPDATE works SET title=?, description=?, due_date=?, status=?, file_path=?, grade=? WHERE id=?");
        return $stmt->execute([$data['title'], $data['description'], $data['due_date'], $data['status'], $data['file_path'], $data['grade'], $id]);
    }
    public function delete($id) {
        $stmt = $this->dbConn->prepare("DELETE FROM works WHERE id = ?");
        return $stmt->execute([$id]);
    }
} 