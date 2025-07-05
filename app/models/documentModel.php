<?php
class DocumentModel extends mainModel {
    public function getAllByStudent($studentId) {
        $stmt = $this->db->prepare("SELECT * FROM documents WHERE student_id = ?");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM documents WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function create($studentId, $data) {
        $stmt = $this->db->prepare("INSERT INTO documents (student_id, file_name, file_path, uploaded_at, type) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$studentId, $data['file_name'], $data['file_path'], $data['uploaded_at'], $data['type']]);
    }
    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE documents SET file_name=?, file_path=?, uploaded_at=?, type=? WHERE id=?");
        return $stmt->execute([$data['file_name'], $data['file_path'], $data['uploaded_at'], $data['type'], $id]);
    }
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM documents WHERE id = ?");
        return $stmt->execute([$id]);
    }
} 