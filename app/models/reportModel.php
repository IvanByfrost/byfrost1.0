<?php
class ReportModel extends mainModel {
    public function getAllByStudent($studentId) {
        $stmt = $this->db->prepare("SELECT * FROM reports WHERE student_id = ?");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM reports WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function create($studentId, $data) {
        $stmt = $this->db->prepare("INSERT INTO reports (student_id, title, description, date, file_path, grade) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$studentId, $data['title'], $data['description'], $data['date'], $data['file_path'], $data['grade']]);
    }
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE reports SET title=?, description=?, date=?, file_path=?, grade=? WHERE id=?");
        return $stmt->execute([$data['title'], $data['description'], $data['date'], $data['file_path'], $data['grade'], $id]);
    }
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM reports WHERE id = ?");
        return $stmt->execute([$id]);
    }
} 