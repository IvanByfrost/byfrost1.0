<?php
class StudentStatsModel extends MainModel
{
    public function __construct($dbConn = null)
    {
        parent::__construct($dbConn);
    }

    /**
     * Obtiene estadísticas generales de estudiantes optimizado para Baldur.sql
     */
    public function getStudentStats()
    {
        $query = "
        SELECT 
            COUNT(*) AS total_students,
            SUM(CASE WHEN u.is_active = 1 THEN 1 ELSE 0 END) AS active_students,
            SUM(CASE WHEN u.is_active = 0 THEN 1 ELSE 0 END) AS inactive_students,
            ROUND(
                (SUM(CASE WHEN u.is_active = 1 THEN 1 ELSE 0 END) / COUNT(*)) * 100, 1
            ) AS active_percentage
        FROM users u
        JOIN user_roles ur ON u.user_id = ur.user_id
        WHERE ur.role_type = 'student'
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene estadísticas de estudiantes por género optimizado para Baldur.sql
     * Nota: Baldur.sql no tiene columna gender, se usa una aproximación basada en otros datos
     */
    public function getStudentStatsByGender()
    {
        $query = "
        SELECT 
            'No disponible' AS gender,
            COUNT(*) AS total,
            SUM(CASE WHEN u.is_active = 1 THEN 1 ELSE 0 END) AS active,
            SUM(CASE WHEN u.is_active = 0 THEN 1 ELSE 0 END) AS inactive
        FROM users u
        JOIN user_roles ur ON u.user_id = ur.user_id
        WHERE ur.role_type = 'student'
        GROUP BY 'No disponible'
        ORDER BY total DESC
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene estadísticas de estudiantes por edad optimizado para Baldur.sql
     */
    public function getStudentStatsByAge()
    {
        $query = "
        SELECT 
            CASE 
                WHEN TIMESTAMPDIFF(YEAR, u.date_of_birth, CURDATE()) < 12 THEN 'Niños (0-11)'
                WHEN TIMESTAMPDIFF(YEAR, u.date_of_birth, CURDATE()) < 18 THEN 'Adolescentes (12-17)'
                WHEN TIMESTAMPDIFF(YEAR, u.date_of_birth, CURDATE()) < 25 THEN 'Jóvenes (18-24)'
                ELSE 'Adultos (25+)'
            END AS age_group,
            COUNT(*) AS total,
            SUM(CASE WHEN u.is_active = 1 THEN 1 ELSE 0 END) AS active
        FROM users u
        JOIN user_roles ur ON u.user_id = ur.user_id
        WHERE ur.role_type = 'student' AND u.date_of_birth IS NOT NULL
        GROUP BY age_group
        ORDER BY total DESC
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene estudiantes recientemente registrados optimizado para Baldur.sql
     */
    public function getRecentStudents($limit = 10)
    {
        $query = "
        SELECT 
            CONCAT(u.first_name, ' ', u.last_name) AS student_name,
            u.email,
            u.created_at,
            u.is_active
        FROM users u
        JOIN user_roles ur ON u.user_id = ur.user_id
        WHERE ur.role_type = 'student'
        ORDER BY u.created_at DESC
        LIMIT :limit
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene estadísticas de crecimiento mensual optimizado para Baldur.sql
     */
    public function getMonthlyGrowth()
    {
        $query = "
        SELECT 
            DATE_FORMAT(u.created_at, '%Y-%m') AS month,
            COUNT(*) AS new_students,
            SUM(CASE WHEN u.is_active = 1 THEN 1 ELSE 0 END) AS active_new
        FROM users u
        JOIN user_roles ur ON u.user_id = ur.user_id
        WHERE ur.role_type = 'student' AND u.created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
        GROUP BY DATE_FORMAT(u.created_at, '%Y-%m')
        ORDER BY month DESC
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene estudiantes con mejor rendimiento optimizado para Baldur.sql
     */
    public function getTopPerformingStudents($limit = 5)
    {
        $query = "
        SELECT 
            CONCAT(u.first_name, ' ', u.last_name) AS student_name,
            COUNT(ss.score_id) AS total_activities,
            ROUND(AVG(ss.score), 2) AS average_score,
            MAX(ss.graded_at) AS last_activity
        FROM users u
        JOIN user_roles ur ON u.user_id = ur.user_id
        LEFT JOIN student_scores ss ON u.user_id = ss.student_user_id
        WHERE ur.role_type = 'student' AND u.is_active = 1
        GROUP BY u.user_id, u.first_name, u.last_name
        HAVING total_activities > 0
        ORDER BY average_score DESC, total_activities DESC
        LIMIT :limit
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene estudiantes que necesitan atención optimizado para Baldur.sql
     */
    public function getStudentsNeedingAttention($limit = 5)
    {
        $query = "
        SELECT 
            CONCAT(u.first_name, ' ', u.last_name) AS student_name,
            COUNT(ss.score_id) AS total_activities,
            ROUND(AVG(ss.score), 2) AS average_score,
            COUNT(a.attendance_id) AS attendance_count
        FROM users u
        JOIN user_roles ur ON u.user_id = ur.user_id
        LEFT JOIN student_scores ss ON u.user_id = ss.student_user_id
        LEFT JOIN attendance a ON u.user_id = a.student_user_id 
            AND a.attendance_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
        WHERE ur.role_type = 'student' AND u.is_active = 1
        GROUP BY u.user_id, u.first_name, u.last_name
        HAVING total_activities > 0 AND (average_score < 6 OR attendance_count < 5)
        ORDER BY average_score ASC, attendance_count ASC
        LIMIT :limit
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?> 