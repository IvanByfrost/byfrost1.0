<?php
require_once 'app/scripts/connection.php';

class AcademicAveragesModel {
    private $conn;
    
    public function __construct() {
        $this->conn = getConnection();
    }
    
    /**
     * Obtiene promedios por período académico optimizado para Baldur.sql
     */
    public function getTermAverages() {
        $query = "
        SELECT 
            act.term_id,
            act.term_name AS academic_term_name, 
            ROUND(AVG(ss.score), 2) AS promedio,
            COUNT(ss.score_id) AS total_calificaciones,
            MIN(ss.score) AS nota_minima,
            MAX(ss.score) AS nota_maxima,
            COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) AS aprobados,
            COUNT(CASE WHEN ss.score < 3.0 THEN 1 END) AS reprobados,
            ROUND(
                (COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) / COUNT(ss.score_id)) * 100, 1
            ) AS tasa_aprobacion,
            ROUND(STDDEV(ss.score), 2) AS desviacion_estandar
        FROM student_scores ss
        JOIN activities a ON ss.activity_id = a.activity_id
        JOIN academic_terms act ON a.term_id = act.term_id
        WHERE ss.score IS NOT NULL
        GROUP BY act.term_id, act.term_name
        ORDER BY act.term_id ASC
        ";
        
        $result = mysqli_query($this->conn, $query);
        
        if (!$result) {
            throw new Exception("Error en consulta: " . mysqli_error($this->conn));
        }
        
        $averages = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $averages[] = $row;
        }
        
        return $averages;
    }
    
    /**
     * Obtiene promedios por asignatura
     */
    public function getSubjectAverages() {
        $query = "
        SELECT 
            s.subject_id,
            s.subject_name,
            ROUND(AVG(ss.score), 2) AS promedio,
            COUNT(ss.score_id) AS total_calificaciones,
            MIN(ss.score) AS nota_minima,
            MAX(ss.score) AS nota_maxima,
            COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) AS aprobados,
            COUNT(CASE WHEN ss.score < 3.0 THEN 1 END) AS reprobados,
            ROUND(
                (COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) / COUNT(ss.score_id)) * 100, 1
            ) AS tasa_aprobacion
        FROM student_scores ss
        JOIN activities a ON ss.activity_id = a.activity_id
        JOIN subjects s ON a.professor_subject_id = s.subject_id
        WHERE ss.score IS NOT NULL
        GROUP BY s.subject_id, s.subject_name
        ORDER BY promedio DESC
        ";
        
        $result = mysqli_query($this->conn, $query);
        
        if (!$result) {
            throw new Exception("Error en consulta: " . mysqli_error($this->conn));
        }
        
        $subjects = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $subjects[] = $row;
        }
        
        return $subjects;
    }
    
    /**
     * Obtiene estadísticas generales de calificaciones
     */
    public function getGeneralStats() {
        $query = "
        SELECT 
            COUNT(ss.score_id) AS total_calificaciones,
            ROUND(AVG(ss.score), 2) AS promedio_general,
            MIN(ss.score) AS nota_minima_general,
            MAX(ss.score) AS nota_maxima_general,
            COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) AS total_aprobados,
            COUNT(CASE WHEN ss.score < 3.0 THEN 1 END) AS total_reprobados,
            ROUND(
                (COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) / COUNT(ss.score_id)) * 100, 1
            ) AS tasa_aprobacion_general,
            COUNT(DISTINCT ss.student_user_id) AS total_estudiantes,
            COUNT(DISTINCT a.activity_id) AS total_actividades
        FROM student_scores ss
        JOIN activities a ON ss.activity_id = a.activity_id
        WHERE ss.score IS NOT NULL
        ";
        
        $result = mysqli_query($this->conn, $query);
        
        if (!$result) {
            throw new Exception("Error en consulta: " . mysqli_error($this->conn));
        }
        
        return mysqli_fetch_assoc($result);
    }
    
    /**
     * Obtiene promedios por profesor
     */
    public function getProfessorAverages() {
        $query = "
        SELECT 
            p.user_id AS professor_id,
            CONCAT(p.first_name, ' ', p.last_name) AS professor_name,
            s.subject_name,
            ROUND(AVG(ss.score), 2) AS promedio,
            COUNT(ss.score_id) AS total_calificaciones,
            COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) AS aprobados,
            COUNT(CASE WHEN ss.score < 3.0 THEN 1 END) AS reprobados,
            ROUND(
                (COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) / COUNT(ss.score_id)) * 100, 1
            ) AS tasa_aprobacion
        FROM student_scores ss
        JOIN activities a ON ss.activity_id = a.activity_id
        JOIN professor_subjects ps ON a.professor_subject_id = ps.professor_subject_id
        JOIN users p ON ps.professor_user_id = p.user_id
        JOIN subjects s ON ps.subject_id = s.subject_id
        WHERE ss.score IS NOT NULL
        GROUP BY p.user_id, p.first_name, p.last_name, s.subject_name
        ORDER BY promedio DESC
        ";
        
        $result = mysqli_query($this->conn, $query);
        
        if (!$result) {
            throw new Exception("Error en consulta: " . mysqli_error($this->conn));
        }
        
        $professors = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $professors[] = $row;
        }
        
        return $professors;
    }
    
    /**
     * Obtiene tendencias de promedios por mes
     */
    public function getMonthlyTrends() {
        $query = "
        SELECT 
            DATE_FORMAT(ss.graded_at, '%Y-%m') AS mes,
            ROUND(AVG(ss.score), 2) AS promedio_mensual,
            COUNT(ss.score_id) AS total_calificaciones,
            COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) AS aprobados,
            COUNT(CASE WHEN ss.score < 3.0 THEN 1 END) AS reprobados
        FROM student_scores ss
        WHERE ss.score IS NOT NULL
        GROUP BY DATE_FORMAT(ss.graded_at, '%Y-%m')
        ORDER BY mes DESC
        LIMIT 12
        ";
        
        $result = mysqli_query($this->conn, $query);
        
        if (!$result) {
            throw new Exception("Error en consulta: " . mysqli_error($this->conn));
        }
        
        $trends = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $trends[] = $row;
        }
        
        return $trends;
    }
    
    /**
     * Obtiene los mejores estudiantes por período
     */
    public function getTopStudents($termId = null) {
        $whereClause = $termId ? "AND act.term_id = $termId" : "";
        
        $query = "
        SELECT 
            u.user_id AS student_id,
            CONCAT(u.first_name, ' ', u.last_name) AS student_name,
            u.email,
            act.term_name AS academic_term_name,
            ROUND(AVG(ss.score), 2) AS promedio,
            COUNT(ss.score_id) AS total_calificaciones,
            COUNT(DISTINCT s.subject_id) AS materias_count
        FROM users u
        JOIN user_roles ur ON u.user_id = ur.user_id
        JOIN student_scores ss ON u.user_id = ss.student_user_id
        JOIN activities a ON ss.activity_id = a.activity_id
        JOIN subjects s ON a.professor_subject_id = s.subject_id
        JOIN academic_terms act ON a.term_id = act.term_id
        WHERE ur.role_type = 'student' 
            AND ur.is_active = 1 
            AND u.is_active = 1
            AND ss.score IS NOT NULL
            $whereClause
        GROUP BY u.user_id, u.first_name, u.last_name, u.email, act.term_id, act.term_name
        HAVING COUNT(ss.score_id) >= 3
        ORDER BY promedio DESC
        LIMIT 10
        ";
        
        $result = mysqli_query($this->conn, $query);
        
        if (!$result) {
            throw new Exception("Error en consulta: " . mysqli_error($this->conn));
        }
        
        $students = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $students[] = $row;
        }
        
        return $students;
    }
    
    /**
     * Cierra la conexión
     */
    public function __destruct() {
        if ($this->conn) {
            mysqli_close($this->conn);
        }
    }
}
?> 