<?php
class WorkModel extends mainModel {
    public function getAllByStudent($studentId) {
        $stmt = $this->dbConn->prepare("SELECT a.* FROM activities a
            JOIN activity_types at ON a.activity_type_id = at.activity_type_id
            JOIN student_scores ss ON ss.activity_id = a.activity_id
            WHERE ss.student_user_id = ? AND at.type_name = 'work'");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getCountByStudent($studentId) {
        $stmt = $this->dbConn->prepare("SELECT COUNT(*) FROM activities a
            JOIN activity_types at ON a.activity_type_id = at.activity_type_id
            JOIN student_scores ss ON ss.activity_id = a.activity_id
            WHERE ss.student_user_id = ? AND at.type_name = 'work'");
        $stmt->execute([$studentId]);
        return $stmt->fetchColumn();
    }
    
    public function getRecentByStudent($studentId) {
        $stmt = $this->dbConn->prepare("SELECT a.* FROM activities a
            JOIN activity_types at ON a.activity_type_id = at.activity_type_id
            JOIN student_scores ss ON ss.activity_id = a.activity_id
            WHERE ss.student_user_id = ? AND at.type_name = 'work'
            ORDER BY a.due_date DESC LIMIT 5");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function create($studentId, $data) {
        // Aquí deberías crear la actividad y luego la relación en student_scores
        // Este método requiere lógica adicional según tu flujo
        $stmt = $this->dbConn->prepare("INSERT INTO activities (activity_name, due_date, description, activity_type_id, created_by_user_id) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$data['title'], $data['due_date'], $data['description'], $data['activity_type_id'], $data['created_by_user_id']]);
    }
    public function update($id, $data) {
        $stmt = $this->dbConn->prepare("UPDATE activities SET activity_name=?, description=?, due_date=?, activity_type_id=? WHERE activity_id=?");
        return $stmt->execute([$data['title'], $data['description'], $data['due_date'], $data['activity_type_id'], $id]);
    }
    public function delete($id) {
        $stmt = $this->dbConn->prepare("DELETE FROM activities WHERE activity_id = ?");
        return $stmt->execute([$id]);
    }
} 