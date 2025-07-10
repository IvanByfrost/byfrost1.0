<?php
require_once ROOT . '/app/models/MainModel.php';

class AcademicHistoryModel extends MainModel {
    public function getAllByStudent($studentId) {
        $stmt = $this->dbConn->prepare("SELECT * FROM academic_history WHERE student_id = ?");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function create($studentId, $data) {
        $stmt = $this->dbConn->prepare("INSERT INTO academic_history (student_id, year, summary, gpa) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$studentId, $data['year'], $data['summary'], $data['gpa']]);
    }
    public function update($id, $data) {
        $stmt = $this->dbConn->prepare("UPDATE academic_history SET year=?, summary=?, gpa=? WHERE id=?");
        return $stmt->execute([$data['year'], $data['summary'], $data['gpa'], $id]);
    }
    public function delete($id) {
        $stmt = $this->dbConn->prepare("DELETE FROM academic_history WHERE id = ?");
        return $stmt->execute([$id]);
    }
} 