<?php
class ReportModel extends mainModel {
    public function getAllByStudent($studentId) {
        $stmt = $this->dbConn->prepare("SELECT * FROM reports WHERE student_id = ?");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getCountByStudent($studentId) {
        $stmt = $this->dbConn->prepare("SELECT COUNT(*) FROM reports WHERE student_id = ?");
        $stmt->execute([$studentId]);
        return $stmt->fetchColumn();
    }
    
    public function create($studentId, $data) {
        $stmt = $this->dbConn->prepare("INSERT INTO reports (student_id, title, description, report_date, type, file_path) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$studentId, $data['title'], $data['description'], $data['report_date'], $data['type'], $data['file_path']]);
    }
    public function update($id, $data) {
        $stmt = $this->dbConn->prepare("UPDATE reports SET title=?, description=?, report_date=?, type=?, file_path=? WHERE id=?");
        return $stmt->execute([$data['title'], $data['description'], $data['report_date'], $data['type'], $data['file_path'], $id]);
    }
    public function delete($id) {
        $stmt = $this->dbConn->prepare("DELETE FROM reports WHERE id = ?");
        return $stmt->execute([$id]);
    }
} 