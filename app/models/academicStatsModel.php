<?php
class AcademicStatsModel extends MainModel
{
    public function __construct($dbConn = null)
    {
        parent::__construct($dbConn);
    }

    /**
     * Obtiene promedios por período académico
     */
    public function getAveragesByTerm()
    {
        $query = "
        SELECT 
            at.term_name,
            at.term_id,
            ROUND(AVG(ss.score), 2) AS average_score,
            COUNT(ss.score_id) AS total_scores,
            COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) AS passing_scores,
            COUNT(CASE WHEN ss.score < 3.0 THEN 1 END) AS failing_scores,
            ROUND(
                (COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) / COUNT(ss.score_id)) * 100, 1
            ) AS pass_rate
        FROM student_scores ss
        JOIN academic_terms at ON ss.activity_id IN (
            SELECT activity_id FROM activities WHERE term_id = at.term_id
        )
        GROUP BY at.term_id, at.term_name
        ORDER BY at.term_id ASC
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene promedios por asignatura
     */
    public function getAveragesBySubject()
    {
        $query = "
        SELECT 
            s.subject_name,
            s.subject_id,
            ROUND(AVG(ss.score), 2) AS average_score,
            COUNT(ss.score_id) AS total_scores,
            COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) AS passing_scores,
            COUNT(CASE WHEN ss.score < 3.0 THEN 1 END) AS failing_scores,
            ROUND(
                (COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) / COUNT(ss.score_id)) * 100, 1
            ) AS pass_rate
        FROM student_scores ss
        JOIN activities a ON ss.activity_id = a.activity_id
        JOIN professor_subjects ps ON a.professor_subject_id = ps.professor_subject_id
        JOIN subjects s ON ps.subject_id = s.subject_id
        GROUP BY s.subject_id, s.subject_name
        ORDER BY average_score DESC
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene promedios por profesor
     */
    public function getAveragesByTeacher()
    {
        $query = "
        SELECT 
            u.first_name,
            u.last_name,
            u.user_id,
            ROUND(AVG(ss.score), 2) AS average_score,
            COUNT(ss.score_id) AS total_scores,
            COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) AS passing_scores,
            COUNT(CASE WHEN ss.score < 3.0 THEN 1 END) AS failing_scores,
            ROUND(
                (COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) / COUNT(ss.score_id)) * 100, 1
            ) AS pass_rate
        FROM student_scores ss
        JOIN activities a ON ss.activity_id = a.activity_id
        JOIN professor_subjects ps ON a.professor_subject_id = ps.professor_subject_id
        JOIN users u ON ps.professor_user_id = u.user_id
        GROUP BY u.user_id, u.first_name, u.last_name
        ORDER BY average_score DESC
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene estadísticas generales de calificaciones
     */
    public function getGeneralStats()
    {
        $query = "
        SELECT 
            COUNT(ss.score_id) AS total_scores,
            ROUND(AVG(ss.score), 2) AS global_average,
            MIN(ss.score) AS lowest_score,
            MAX(ss.score) AS highest_score,
            ROUND(STDDEV(ss.score), 2) AS standard_deviation,
            COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) AS passing_scores,
            COUNT(CASE WHEN ss.score < 3.0 THEN 1 END) AS failing_scores,
            ROUND(
                (COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) / COUNT(ss.score_id)) * 100, 1
            ) AS global_pass_rate,
            COUNT(DISTINCT ss.student_user_id) AS total_students,
            COUNT(DISTINCT a.activity_id) AS total_activities
        FROM student_scores ss
        JOIN activities a ON ss.activity_id = a.activity_id
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene tendencias de calificaciones por mes
     */
    public function getScoreTrends($months = 6)
    {
        $query = "
        SELECT 
            DATE_FORMAT(ss.graded_at, '%Y-%m') AS month,
            ROUND(AVG(ss.score), 2) AS average_score,
            COUNT(ss.score_id) AS total_scores,
            COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) AS passing_scores,
            COUNT(CASE WHEN ss.score < 3.0 THEN 1 END) AS failing_scores
        FROM student_scores ss
        WHERE ss.graded_at >= DATE_SUB(CURDATE(), INTERVAL :months MONTH)
        GROUP BY DATE_FORMAT(ss.graded_at, '%Y-%m')
        ORDER BY month ASC
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(':months', $months, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene distribución de calificaciones
     */
    public function getScoreDistribution()
    {
        $query = "
        SELECT 
            CASE 
                WHEN score >= 4.5 THEN 'Excelente (4.5-5.0)'
                WHEN score >= 3.5 THEN 'Bueno (3.5-4.4)'
                WHEN score >= 3.0 THEN 'Aceptable (3.0-3.4)'
                WHEN score >= 2.0 THEN 'Deficiente (2.0-2.9)'
                ELSE 'Insuficiente (0.0-1.9)'
            END AS score_range,
            COUNT(*) AS count,
            ROUND((COUNT(*) / (SELECT COUNT(*) FROM student_scores)) * 100, 1) AS percentage
        FROM student_scores
        GROUP BY 
            CASE 
                WHEN score >= 4.5 THEN 'Excelente (4.5-5.0)'
                WHEN score >= 3.5 THEN 'Bueno (3.5-4.4)'
                WHEN score >= 3.0 THEN 'Aceptable (3.0-3.4)'
                WHEN score >= 2.0 THEN 'Deficiente (2.0-2.9)'
                ELSE 'Insuficiente (0.0-1.9)'
            END
        ORDER BY 
            CASE score_range
                WHEN 'Excelente (4.5-5.0)' THEN 1
                WHEN 'Bueno (3.5-4.4)' THEN 2
                WHEN 'Aceptable (3.0-3.4)' THEN 3
                WHEN 'Deficiente (2.0-2.9)' THEN 4
                ELSE 5
            END
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene mejores estudiantes
     */
    public function getTopStudents($limit = 10)
    {
        $query = "
        SELECT 
            u.first_name,
            u.last_name,
            u.user_id,
            ROUND(AVG(ss.score), 2) AS average_score,
            COUNT(ss.score_id) AS total_scores
        FROM student_scores ss
        JOIN users u ON ss.student_user_id = u.user_id
        GROUP BY u.user_id, u.first_name, u.last_name
        HAVING COUNT(ss.score_id) >= 3
        ORDER BY average_score DESC
        LIMIT :limit
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene asignaturas con mejor rendimiento
     */
    public function getTopSubjects($limit = 10)
    {
        $query = "
        SELECT 
            s.subject_name,
            s.subject_id,
            ROUND(AVG(ss.score), 2) AS average_score,
            COUNT(ss.score_id) AS total_scores,
            COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) AS passing_scores,
            ROUND(
                (COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) / COUNT(ss.score_id)) * 100, 1
            ) AS pass_rate
        FROM student_scores ss
        JOIN activities a ON ss.activity_id = a.activity_id
        JOIN professor_subjects ps ON a.professor_subject_id = ps.professor_subject_id
        JOIN subjects s ON ps.subject_id = s.subject_id
        GROUP BY s.subject_id, s.subject_name
        HAVING COUNT(ss.score_id) >= 5
        ORDER BY average_score DESC
        LIMIT :limit
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene comparación entre períodos
     */
    public function getTermComparison()
    {
        $query = "
        SELECT 
            at.term_name,
            at.term_id,
            ROUND(AVG(ss.score), 2) AS average_score,
            COUNT(ss.score_id) AS total_scores,
            COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) AS passing_scores,
            COUNT(CASE WHEN ss.score < 3.0 THEN 1 END) AS failing_scores,
            ROUND(
                (COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) / COUNT(ss.score_id)) * 100, 1
            ) AS pass_rate,
            ROUND(STDDEV(ss.score), 2) AS standard_deviation
        FROM student_scores ss
        JOIN activities a ON ss.activity_id = a.activity_id
        JOIN academic_terms at ON a.term_id = at.term_id
        GROUP BY at.term_id, at.term_name
        ORDER BY at.term_id ASC
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene estadísticas por grupo de clase
     */
    public function getStatsByClassGroup()
    {
        $query = "
        SELECT 
            cg.group_name,
            cg.class_group_id,
            ROUND(AVG(ss.score), 2) AS average_score,
            COUNT(ss.score_id) AS total_scores,
            COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) AS passing_scores,
            COUNT(CASE WHEN ss.score < 3.0 THEN 1 END) AS failing_scores,
            ROUND(
                (COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) / COUNT(ss.score_id)) * 100, 1
            ) AS pass_rate
        FROM student_scores ss
        JOIN activities a ON ss.activity_id = a.activity_id
        JOIN class_groups cg ON a.class_group_id = cg.class_group_id
        GROUP BY cg.class_group_id, cg.group_name
        ORDER BY average_score DESC
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?> 