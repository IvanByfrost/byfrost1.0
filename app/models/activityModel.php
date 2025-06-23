<?php
// Modelo para manejar a las actividades
require_once 'mainModel.php';

class ActivityModel extends MainModel
{
    // Constructor de la clase 
    public function __construct()
    {
        parent::__construct();
    }
    
    // Función para crear una actividad
    public function createActivity($data) {
        $query = "INSERT INTO activities (
            activity_name, 
            professor_subject_id, 
            activity_type_id, 
            class_group_id, 
            term_id, 
            max_score, 
            due_date, 
            description, 
            created_by_user_id
        ) VALUES (
            :activity_name, 
            :professor_subject_id, 
            :activity_type_id, 
            :class_group_id, 
            :term_id, 
            :max_score, 
            :due_date, 
            :description, 
            :created_by_user_id
        )";
        
        $stmt = $this->dbConn->prepare($query);
        return $stmt->execute([
            'activity_name' => $data['activity_name'],
            'professor_subject_id' => $data['professor_subject_id'],
            'activity_type_id' => $data['activity_type_id'],
            'class_group_id' => $data['class_group_id'],
            'term_id' => $data['term_id'],
            'max_score' => $data['max_score'],
            'due_date' => $data['due_date'],
            'description' => $data['description'],
            'created_by_user_id' => $data['created_by_user_id']
        ]);
    }
    
    // Función para consultar todas las actividades
    public function getActivities()
    {
        $query = "SELECT 
            a.activity_id,
            a.activity_name,
            a.max_score,
            a.due_date,
            a.description,
            at.type_name as activity_type,
            ps.professor_subject_id,
            u.first_name,
            u.last_name,
            cg.group_name,
            g.grade_name,
            s.school_name
        FROM activities a
        LEFT JOIN activity_types at ON a.activity_type_id = at.activity_type_id
        LEFT JOIN professor_subjects ps ON a.professor_subject_id = ps.professor_subject_id
        LEFT JOIN users u ON ps.professor_user_id = u.user_id
        LEFT JOIN class_groups cg ON a.class_group_id = cg.class_group_id
        LEFT JOIN grades g ON cg.grade_id = g.grade_id
        LEFT JOIN schools s ON g.school_id = s.school_id
        ORDER BY a.due_date DESC";
        
        $stmt = $this->dbConn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Función para consultar una actividad por ID
    public function getActivityById($activityId)
    {
        $query = "SELECT 
            a.*,
            at.type_name as activity_type,
            u.first_name,
            u.last_name,
            cg.group_name,
            g.grade_name,
            s.school_name
        FROM activities a
        LEFT JOIN activity_types at ON a.activity_type_id = at.activity_type_id
        LEFT JOIN professor_subjects ps ON a.professor_subject_id = ps.professor_subject_id
        LEFT JOIN users u ON ps.professor_user_id = u.user_id
        LEFT JOIN class_groups cg ON a.class_group_id = cg.class_group_id
        LEFT JOIN grades g ON cg.grade_id = g.grade_id
        LEFT JOIN schools s ON g.school_id = s.school_id
        WHERE a.activity_id = :activity_id";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute(['activity_id' => $activityId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Función para consultar actividades por grupo
    public function getActivitiesByGroup($classGroupId)
    {
        $query = "SELECT 
            a.*,
            at.type_name as activity_type,
            u.first_name,
            u.last_name
        FROM activities a
        LEFT JOIN activity_types at ON a.activity_type_id = at.activity_type_id
        LEFT JOIN professor_subjects ps ON a.professor_subject_id = ps.professor_subject_id
        LEFT JOIN users u ON ps.professor_user_id = u.user_id
        WHERE a.class_group_id = :class_group_id
        ORDER BY a.due_date ASC";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute(['class_group_id' => $classGroupId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Función para consultar actividades por profesor
    public function getActivitiesByProfessor($professorUserId)
    {
        $query = "SELECT 
            a.*,
            at.type_name as activity_type,
            cg.group_name,
            g.grade_name
        FROM activities a
        LEFT JOIN activity_types at ON a.activity_type_id = at.activity_type_id
        LEFT JOIN professor_subjects ps ON a.professor_subject_id = ps.professor_subject_id
        LEFT JOIN class_groups cg ON a.class_group_id = cg.class_group_id
        LEFT JOIN grades g ON cg.grade_id = g.grade_id
        WHERE ps.professor_user_id = :professor_user_id
        ORDER BY a.due_date DESC";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute(['professor_user_id' => $professorUserId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Función para actualizar una actividad
    public function updateActivity($activityId, $data) {
        $query = "UPDATE activities SET 
            activity_name = :activity_name,
            professor_subject_id = :professor_subject_id,
            activity_type_id = :activity_type_id,
            class_group_id = :class_group_id,
            term_id = :term_id,
            max_score = :max_score,
            due_date = :due_date,
            description = :description
        WHERE activity_id = :activity_id";
        
        $stmt = $this->dbConn->prepare($query);
        return $stmt->execute([
            'activity_id' => $activityId,
            'activity_name' => $data['activity_name'],
            'professor_subject_id' => $data['professor_subject_id'],
            'activity_type_id' => $data['activity_type_id'],
            'class_group_id' => $data['class_group_id'],
            'term_id' => $data['term_id'],
            'max_score' => $data['max_score'],
            'due_date' => $data['due_date'],
            'description' => $data['description']
        ]);
    }
    
    // Función para eliminar una actividad
    public function deleteActivity($activityId) {
        // Primero verificar si hay calificaciones asociadas
        $checkQuery = "SELECT COUNT(*) FROM student_scores WHERE activity_id = :activity_id";
        $checkStmt = $this->dbConn->prepare($checkQuery);
        $checkStmt->execute(['activity_id' => $activityId]);
        
        if ($checkStmt->fetchColumn() > 0) {
            throw new Exception("No se puede eliminar la actividad porque tiene calificaciones asociadas");
        }
        
        $query = "DELETE FROM activities WHERE activity_id = :activity_id";
        $stmt = $this->dbConn->prepare($query);
        return $stmt->execute(['activity_id' => $activityId]);
    }
    
    // Función para obtener tipos de actividad
    public function getActivityTypes()
    {
        $query = "SELECT * FROM activity_types ORDER BY type_name";
        $stmt = $this->dbConn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Función para obtener grupos de clase
    public function getClassGroups()
    {
        $query = "SELECT 
            cg.class_group_id,
            cg.group_name,
            g.grade_name,
            s.school_name
        FROM class_groups cg
        LEFT JOIN grades g ON cg.grade_id = g.grade_id
        LEFT JOIN schools s ON g.school_id = s.school_id
        ORDER BY g.grade_name, cg.group_name";
        
        $stmt = $this->dbConn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Función para obtener períodos académicos
    public function getAcademicTerms()
    {
        $query = "SELECT * FROM academic_terms ORDER BY start_date DESC";
        $stmt = $this->dbConn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Función para obtener materias de un profesor
    public function getProfessorSubjects($professorUserId)
    {
        $query = "SELECT 
            ps.professor_subject_id,
            s.subject_name,
            sch.school_name
        FROM professor_subjects ps
        LEFT JOIN subjects s ON ps.subject_id = s.subject_id
        LEFT JOIN schools sch ON ps.school_id = sch.school_id
        WHERE ps.professor_user_id = :professor_user_id AND ps.is_active = 1
        ORDER BY s.subject_name";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute(['professor_user_id' => $professorUserId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
