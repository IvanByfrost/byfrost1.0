<?php
class ReportModel extends mainModel {
    public function getAllByStudent($studentId) {
        $stmt = $this->dbConn->prepare("SELECT * FROM academic_reports WHERE student_user_id = ?");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getCountByStudent($studentId) {
        $stmt = $this->dbConn->prepare("SELECT COUNT(*) FROM academic_reports WHERE student_user_id = ?");
        $stmt->execute([$studentId]);
        return $stmt->fetchColumn();
    }
    
    public function create($studentId, $data) {
        $stmt = $this->dbConn->prepare("INSERT INTO academic_reports (student_user_id, period, summary, created_by_user_id) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$studentId, $data['period'], $data['summary'], $data['created_by_user_id']]);
    }
    public function update($reportId, $data) {
        $stmt = $this->dbConn->prepare("UPDATE academic_reports SET period=?, summary=? WHERE report_id=?");
        return $stmt->execute([$data['period'], $data['summary'], $reportId]);
    }
    public function delete($reportId) {
        $stmt = $this->dbConn->prepare("DELETE FROM academic_reports WHERE report_id = ?");
        return $stmt->execute([$reportId]);
    }
} 