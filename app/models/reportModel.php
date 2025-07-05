<?php
class ReportModel extends mainModel {
    public function getAllByStudent($studentId) {
        $stmt = $this->dbConn->prepare("SELECT * FROM reports WHERE student_id = ?");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function create($studentId, $data) {
        $stmt = $this->dbConn->prepare("INSERT INTO reports (student_id, title, description, date, file_path, grade) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$studentId, $data['title'], $data['description'], $data['date'], $data['file_path'], $data['grade']]);
    }
    public function update($id, $data) {
        $stmt = $this->dbConn->prepare("UPDATE reports SET title=?, description=?, date=?, file_path=?, grade=? WHERE id=?");
        return $stmt->execute([$data['title'], $data['description'], $data['date'], $data['file_path'], $data['grade'], $id]);
    }
    public function delete($id) {
        $stmt = $this->dbConn->prepare("DELETE FROM reports WHERE id = ?");
        return $stmt->execute([$id]);
    }
} 