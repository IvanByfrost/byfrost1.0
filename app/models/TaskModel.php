<?php
class TaskModel extends mainModel {
    public function getAllByStudent($studentId) {
        $stmt = $this->dbConn->prepare("SELECT a.* FROM activities a
            JOIN student_scores ss ON ss.activity_id = a.activity_id
            WHERE ss.student_user_id = ?");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getCountByStudent($studentId) {
        $stmt = $this->dbConn->prepare("SELECT COUNT(*) FROM student_scores WHERE student_user_id = ?");
        $stmt->execute([$studentId]);
        return $stmt->fetchColumn();
    }
    
    public function getPendingByStudent($studentId) {
        $stmt = $this->dbConn->prepare("SELECT a.* FROM activities a
            JOIN student_scores ss ON ss.activity_id = a.activity_id
            WHERE ss.student_user_id = ? AND ss.score IS NULL
            ORDER BY a.due_date ASC LIMIT 5");
        $stmt->execute([$studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function create($studentId, $data) {
        // Aquí deberías crear la actividad y luego la relación en student_scores
        // Este método requiere lógica adicional según tu flujo
        // Por ahora, solo ejemplo para activities
        $stmt = $this->dbConn->prepare("INSERT INTO activities (activity_name, due_date, description, created_by_user_id) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$data['title'], $data['due_date'], $data['description'], $data['created_by_user_id']]);
    }
    public function update($id, $data) {
        $stmt = $this->dbConn->prepare("UPDATE activities SET activity_name=?, description=?, due_date=? WHERE activity_id=?");
        return $stmt->execute([$data['title'], $data['description'], $data['due_date'], $id]);
    }
    public function delete($id) {
        $stmt = $this->dbConn->prepare("DELETE FROM activities WHERE activity_id = ?");
        return $stmt->execute([$id]);
    }
} 