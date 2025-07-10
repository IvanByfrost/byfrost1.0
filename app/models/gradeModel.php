<?php
class GradeModel extends MainModel
{
    public function __construct($dbConn = null)
    {
        parent::__construct($dbConn);
    }

    /**
     * Obtiene todas las calificaciones con información de estudiante y materia
     */
    public function getAllGrades()
    {
        $query = "SELECT 
                    s.student_name, 
                    sb.subject_name, 
                    sc.activity_name, 
                    sc.score, 
                    sc.score_date,
                    sc.grade_id,
                    s.student_id,
                    sb.subject_id
                  FROM student_scores sc
                  JOIN student s ON sc.student_id = s.student_id
                  JOIN subject sb ON sc.subject_id = sb.subject_id
                  ORDER BY sc.score_date DESC";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene calificaciones filtradas por estudiante
     */
    public function getGradesByStudent($studentId)
    {
        $query = "SELECT 
                    s.student_name, 
                    sb.subject_name, 
                    sc.activity_name, 
                    sc.score, 
                    sc.score_date,
                    sc.grade_id
                  FROM student_scores sc
                  JOIN student s ON sc.student_id = s.student_id
                  JOIN subject sb ON sc.subject_id = sb.subject_id
                  WHERE s.student_id = :student_id
                  ORDER BY sc.score_date DESC";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute([':student_id' => $studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene calificaciones filtradas por materia
     */
    public function getGradesBySubject($subjectId)
    {
        $query = "SELECT 
                    s.student_name, 
                    sb.subject_name, 
                    sc.activity_name, 
                    sc.score, 
                    sc.score_date,
                    sc.grade_id
                  FROM student_scores sc
                  JOIN student s ON sc.student_id = s.student_id
                  JOIN subject sb ON sc.subject_id = sb.subject_id
                  WHERE sb.subject_id = :subject_id
                  ORDER BY sc.score_date DESC";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute([':subject_id' => $subjectId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Agrega una nueva calificación
     */
    public function addGrade($data)
    {
        $query = "INSERT INTO student_scores (student_id, subject_id, activity_name, score, score_date) 
                  VALUES (:student_id, :subject_id, :activity_name, :score, :score_date)";
        
        $stmt = $this->dbConn->prepare($query);
        return $stmt->execute([
            ':student_id' => $data['student_id'],
            ':subject_id' => $data['subject_id'],
            ':activity_name' => $data['activity_name'],
            ':score' => $data['score'],
            ':score_date' => $data['score_date']
        ]);
    }

    /**
     * Actualiza una calificación existente
     */
    public function updateGrade($gradeId, $data)
    {
        $query = "UPDATE student_scores 
                  SET student_id = :student_id, 
                      subject_id = :subject_id, 
                      activity_name = :activity_name, 
                      score = :score, 
                      score_date = :score_date
                  WHERE grade_id = :grade_id";
        
        $stmt = $this->dbConn->prepare($query);
        return $stmt->execute([
            ':student_id' => $data['student_id'],
            ':subject_id' => $data['subject_id'],
            ':activity_name' => $data['activity_name'],
            ':score' => $data['score'],
            ':score_date' => $data['score_date'],
            ':grade_id' => $gradeId
        ]);
    }

    /**
     * Elimina una calificación
     */
    public function deleteGrade($gradeId)
    {
        $query = "DELETE FROM student_scores WHERE grade_id = :grade_id";
        $stmt = $this->dbConn->prepare($query);
        return $stmt->execute([':grade_id' => $gradeId]);
    }

    /**
     * Obtiene la información de una calificación específica
     */
    public function getGrade($gradeId)
    {
        $query = "SELECT 
                    sc.*, 
                    s.student_name, 
                    sb.subject_name
                  FROM student_scores sc
                  JOIN student s ON sc.student_id = s.student_id
                  JOIN subject sb ON sc.subject_id = sb.subject_id
                  WHERE sc.grade_id = :grade_id";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute([':grade_id' => $gradeId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene la lista de estudiantes para el formulario
     */
    public function getStudents()
    {
        $query = "SELECT student_id, student_name FROM student WHERE is_active = 1 ORDER BY student_name";
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene la lista de materias para el formulario
     */
    public function getSubjects()
    {
        $query = "SELECT subject_id, subject_name FROM subject WHERE is_active = 1 ORDER BY subject_name";
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?> 