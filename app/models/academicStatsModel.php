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
            at.academic_term_name,
            at.academic_term_id,
            ROUND(AVG(ss.score), 2) AS average_score,
            COUNT(ss.score_id) AS total_scores,
            MIN(ss.score) AS min_score,
            MAX(ss.score) AS max_score,
            ROUND(STDDEV(ss.score), 2) AS standard_deviation,
            COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) AS passing_scores,
            COUNT(CASE WHEN ss.score < 3.0 THEN 1 END) AS failing_scores
        FROM subject_score ss
        JOIN academic_term at ON ss.academic_term_id = at.academic_term_id
        WHERE ss.is_active = 1
        GROUP BY at.academic_term_id, at.academic_term_name
        ORDER BY at.academic_term_id ASC
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
            MIN(ss.score) AS min_score,
            MAX(ss.score) AS max_score,
            COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) AS passing_scores,
            COUNT(CASE WHEN ss.score < 3.0 THEN 1 END) AS failing_scores,
            ROUND(
                (COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) / COUNT(ss.score_id)) * 100, 1
            ) AS pass_rate
        FROM subject_score ss
        JOIN subject s ON ss.subject_id = s.subject_id
        WHERE ss.is_active = 1
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
            t.teacher_name,
            t.teacher_id,
            ROUND(AVG(ss.score), 2) AS average_score,
            COUNT(ss.score_id) AS total_scores,
            COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) AS passing_scores,
            COUNT(CASE WHEN ss.score < 3.0 THEN 1 END) AS failing_scores,
            ROUND(
                (COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) / COUNT(ss.score_id)) * 100, 1
            ) AS pass_rate
        FROM subject_score ss
        JOIN teacher t ON ss.teacher_id = t.teacher_id
        WHERE ss.is_active = 1
        GROUP BY t.teacher_id, t.teacher_name
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
            COUNT(DISTINCT ss.student_id) AS total_students,
            COUNT(DISTINCT ss.subject_id) AS total_subjects
        FROM subject_score ss
        WHERE ss.is_active = 1
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
            DATE_FORMAT(ss.created_at, '%Y-%m') AS month,
            ROUND(AVG(ss.score), 2) AS average_score,
            COUNT(ss.score_id) AS total_scores,
            COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) AS passing_scores,
            COUNT(CASE WHEN ss.score < 3.0 THEN 1 END) AS failing_scores
        FROM subject_score ss
        WHERE ss.created_at >= DATE_SUB(CURDATE(), INTERVAL :months MONTH)
            AND ss.is_active = 1
        GROUP BY DATE_FORMAT(ss.created_at, '%Y-%m')
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
            ROUND((COUNT(*) / (SELECT COUNT(*) FROM subject_score WHERE is_active = 1)) * 100, 1) AS percentage
        FROM subject_score
        WHERE is_active = 1
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
     * Obtiene mejores estudiantes por período
     */
    public function getTopStudents($limit = 10)
    {
        $query = "
        SELECT 
            st.student_name,
            st.student_id,
            at.academic_term_name,
            ROUND(AVG(ss.score), 2) AS average_score,
            COUNT(ss.score_id) AS total_scores
        FROM subject_score ss
        JOIN student st ON ss.student_id = st.student_id
        JOIN academic_term at ON ss.academic_term_id = at.academic_term_id
        WHERE ss.is_active = 1
        GROUP BY st.student_id, st.student_name, at.academic_term_id, at.academic_term_name
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
        FROM subject_score ss
        JOIN subject s ON ss.subject_id = s.subject_id
        WHERE ss.is_active = 1
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
            at.academic_term_name,
            at.academic_term_id,
            ROUND(AVG(ss.score), 2) AS average_score,
            COUNT(ss.score_id) AS total_scores,
            COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) AS passing_scores,
            COUNT(CASE WHEN ss.score < 3.0 THEN 1 END) AS failing_scores,
            ROUND(
                (COUNT(CASE WHEN ss.score >= 3.0 THEN 1 END) / COUNT(ss.score_id)) * 100, 1
            ) AS pass_rate,
            ROUND(STDDEV(ss.score), 2) AS standard_deviation
        FROM subject_score ss
        JOIN academic_term at ON ss.academic_term_id = at.academic_term_id
        WHERE ss.is_active = 1
        GROUP BY at.academic_term_id, at.academic_term_name
        ORDER BY at.academic_term_id ASC
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?> 