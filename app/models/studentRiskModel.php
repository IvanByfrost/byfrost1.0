<?php
require_once __DIR__ . '/mainModel.php';

class StudentRiskModel extends MainModel
{
    private $userIdColumn = 'user_id';
    private $studentUserIdColumn = 'student_user_id';
    
    public function __construct($dbConn = null)
    {
        if ($dbConn) {
            $this->dbConn = $dbConn;
        } else {
            parent::__construct();
        }
        $this->detectColumnNames();
    }
    
    /**
     * Detecta automáticamente los nombres de columnas en la base de datos
     */
    private function detectColumnNames()
    {
        try {
            // Verificar estructura de tabla users
            $stmt = $this->dbConn->query("DESCRIBE users");
            $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Debug: mostrar columnas encontradas
            error_log("Columnas en tabla users: " . implode(", ", $columns));
            
            // En Baldur.sql, la columna se llama 'user_id'
            if (in_array('user_id', $columns)) {
                $this->userIdColumn = 'user_id';
                error_log("Detectada columna 'user_id' en tabla users (Baldur.sql)");
            } elseif (in_array('id', $columns)) {
                $this->userIdColumn = 'id';
                error_log("Detectada columna 'id' en tabla users");
            } else {
                // Intentar con la primera columna que parezca ser un ID
                foreach ($columns as $column) {
                    if (strpos($column, 'id') !== false && strpos($column, 'user') !== false) {
                        $this->userIdColumn = $column;
                        error_log("Detectada columna '$column' en tabla users");
                        break;
                    }
                }
            }
            
            // Verificar estructura de tabla student_scores
            $stmt = $this->dbConn->query("DESCRIBE student_scores");
            $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Debug: mostrar columnas encontradas
            error_log("Columnas en tabla student_scores: " . implode(", ", $columns));
            
            // En Baldur.sql, la columna se llama 'student_user_id'
            if (in_array('student_user_id', $columns)) {
                $this->studentUserIdColumn = 'student_user_id';
                error_log("Detectada columna 'student_user_id' en tabla student_scores (Baldur.sql)");
            } elseif (in_array('student_id', $columns)) {
                $this->studentUserIdColumn = 'student_id';
                error_log("Detectada columna 'student_id' en tabla student_scores");
            } else {
                // Intentar con la primera columna que parezca ser un ID de estudiante
                foreach ($columns as $column) {
                    if (strpos($column, 'student') !== false && strpos($column, 'id') !== false) {
                        $this->studentUserIdColumn = $column;
                        error_log("Detectada columna '$column' en tabla student_scores");
                        break;
                    }
                }
            }
            
            // Debug: mostrar valores finales
            error_log("Valores finales - userIdColumn: {$this->userIdColumn}, studentUserIdColumn: {$this->studentUserIdColumn}");
            
        } catch (Exception $e) {
            // Si hay error, usar valores por defecto de Baldur.sql
            error_log("Error en detectColumnNames: " . $e->getMessage());
            $this->userIdColumn = 'user_id';
            $this->studentUserIdColumn = 'student_user_id';
            error_log("Usando valores por defecto de Baldur.sql - userIdColumn: {$this->userIdColumn}, studentUserIdColumn: {$this->studentUserIdColumn}");
        }
    }

    /**
     * Obtiene estudiantes con riesgo por notas bajas
     */
    public function getStudentsAtRiskByGrades($threshold = 3.0)
    {
        $query = "
        SELECT 
            u.{$this->userIdColumn} AS student_id,
            CONCAT(u.first_name, ' ', u.last_name) AS student_name,
            u.email,
            COALESCE(ROUND(AVG(ss.score), 2), 0) AS average_score,
            COUNT(ss.score_id) AS total_activities,
            MAX(ss.graded_at) AS last_activity
        FROM users u
        JOIN user_roles ur ON u.{$this->userIdColumn} = ur.user_id
        LEFT JOIN student_scores ss ON u.{$this->userIdColumn} = ss.{$this->studentUserIdColumn}
        WHERE ur.role_type = 'student' AND u.is_active = 1
        GROUP BY u.{$this->userIdColumn}, u.first_name, u.last_name, u.email
        HAVING average_score < :threshold AND total_activities > 0
        ORDER BY average_score ASC
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(':threshold', $threshold, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene estudiantes con riesgo por faltas excesivas
     */
    public function getStudentsAtRiskByAttendance($maxAbsences = 3, $monthsBack = 1)
    {
        $query = "
        SELECT 
            u.{$this->userIdColumn} AS student_id,
            CONCAT(u.first_name, ' ', u.last_name) AS student_name,
            u.email,
            COUNT(a.attendance_id) AS total_absences,
            COUNT(CASE WHEN a.status = 'absent' THEN 1 END) AS absences,
            COUNT(CASE WHEN a.status = 'late' THEN 1 END) AS tardies,
            MAX(a.attendance_date) AS last_attendance
        FROM users u
        JOIN user_roles ur ON u.{$this->userIdColumn} = ur.user_id
        LEFT JOIN attendance a ON u.{$this->userIdColumn} = a.{$this->studentUserIdColumn}
            AND a.attendance_date >= DATE_SUB(CURDATE(), INTERVAL :monthsBack MONTH)
        WHERE ur.role_type = 'student' AND u.is_active = 1
        GROUP BY u.{$this->userIdColumn}, u.first_name, u.last_name, u.email
        HAVING absences > :maxAbsences
        ORDER BY absences DESC
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(':maxAbsences', $maxAbsences, PDO::PARAM_INT);
        $stmt->bindParam(':monthsBack', $monthsBack, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene estudiantes con riesgo combinado (notas + faltas)
     */
    public function getStudentsAtCombinedRisk($gradeThreshold = 3.0, $maxAbsences = 3)
    {
        $query = "
        SELECT 
            u.{$this->userIdColumn} AS student_id,
            CONCAT(u.first_name, ' ', u.last_name) AS student_name,
            u.email,
            COALESCE(ROUND(AVG(ss.score), 2), 0) AS average_score,
            COUNT(ss.score_id) AS total_activities,
            COUNT(CASE WHEN a.status = 'absent' THEN 1 END) AS absences,
            CASE 
                WHEN COALESCE(ROUND(AVG(ss.score), 2), 0) < :gradeThreshold1 THEN 'Bajo Rendimiento'
                WHEN COUNT(CASE WHEN a.status = 'absent' THEN 1 END) > :maxAbsences1 THEN 'Baja Asistencia'
                ELSE 'Riesgo Combinado'
            END AS risk_type
        FROM users u
        JOIN user_roles ur ON u.{$this->userIdColumn} = ur.user_id
        LEFT JOIN student_scores ss ON u.{$this->userIdColumn} = ss.{$this->studentUserIdColumn}
        LEFT JOIN attendance a ON u.{$this->userIdColumn} = a.{$this->studentUserIdColumn}
            AND a.attendance_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
        WHERE ur.role_type = 'student' AND u.is_active = 1
        GROUP BY u.{$this->userIdColumn}, u.first_name, u.last_name, u.email
        HAVING (average_score < :gradeThreshold2 AND total_activities > 0) 
            OR absences > :maxAbsences2
        ORDER BY average_score ASC, absences DESC
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(':gradeThreshold1', $gradeThreshold, PDO::PARAM_STR);
        $stmt->bindParam(':maxAbsences1', $maxAbsences, PDO::PARAM_INT);
        $stmt->bindParam(':gradeThreshold2', $gradeThreshold, PDO::PARAM_STR);
        $stmt->bindParam(':maxAbsences2', $maxAbsences, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene estadísticas de riesgo general
     */
    public function getRiskStatistics()
    {
        $query = "
        SELECT 
            COUNT(DISTINCT student_stats.student_id) AS total_students,
            COUNT(DISTINCT CASE WHEN avg_score < 3.0 THEN student_stats.student_id END) AS low_grades_count,
            COUNT(DISTINCT CASE WHEN absences > 3 THEN student_stats.student_id END) AS high_absences_count,
            CASE 
                WHEN COUNT(DISTINCT student_stats.student_id) > 0 
                THEN ROUND(
                    (COUNT(DISTINCT CASE WHEN avg_score < 3.0 OR absences > 3 THEN student_stats.student_id END) / 
                    COUNT(DISTINCT student_stats.student_id)) * 100, 1
                )
                ELSE 0
            END AS risk_percentage
        FROM (
            SELECT 
                u.{$this->userIdColumn} AS student_id,
                COALESCE(ROUND(AVG(ss.score), 2), 0) AS avg_score,
                COALESCE(COUNT(CASE WHEN a.status = 'absent' THEN 1 END), 0) AS absences
            FROM users u
            JOIN user_roles ur ON u.{$this->userIdColumn} = ur.user_id
            LEFT JOIN student_scores ss ON u.{$this->userIdColumn} = ss.{$this->studentUserIdColumn}
            LEFT JOIN attendance a ON u.{$this->userIdColumn} = a.{$this->studentUserIdColumn}
                AND a.attendance_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
            WHERE ur.role_type = 'student' AND u.is_active = 1
            GROUP BY u.{$this->userIdColumn}
        ) AS student_stats
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    

    /**
     * Obtiene tendencias de riesgo por mes
     */
    public function getRiskTrends($months = 6)
    {
        $query = "
        SELECT 
            month,
            COALESCE(low_grades_count, 0) AS low_grades_count,
            COALESCE(high_absences_count, 0) AS high_absences_count
        FROM (
            SELECT 
                DATE_FORMAT(ss.graded_at, '%Y-%m') AS month,
                COUNT(DISTINCT CASE WHEN ss.score < 3.0 THEN u.{$this->userIdColumn} END) AS low_grades_count
            FROM users u
            JOIN user_roles ur ON u.{$this->userIdColumn} = ur.user_id
            LEFT JOIN student_scores ss ON u.{$this->userIdColumn} = ss.{$this->studentUserIdColumn}
            WHERE ur.role_type = 'student' AND u.is_active = 1 
                AND ss.graded_at >= DATE_SUB(CURDATE(), INTERVAL :months MONTH)
            GROUP BY DATE_FORMAT(ss.graded_at, '%Y-%m')
        ) grades
        LEFT JOIN (
            SELECT 
                DATE_FORMAT(a.attendance_date, '%Y-%m') AS month,
                COUNT(DISTINCT CASE WHEN a.status = 'absent' THEN u.{$this->userIdColumn} END) AS high_absences_count
            FROM users u
            JOIN user_roles ur ON u.{$this->userIdColumn} = ur.user_id
            LEFT JOIN attendance a ON u.{$this->userIdColumn} = a.{$this->studentUserIdColumn}
            WHERE ur.role_type = 'student' AND u.is_active = 1 
                AND a.attendance_date >= DATE_SUB(CURDATE(), INTERVAL :months MONTH)
            GROUP BY DATE_FORMAT(a.attendance_date, '%Y-%m')
        ) attendance ON grades.month = attendance.month
        ORDER BY month DESC
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(':months', $months, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene recomendaciones para estudiantes en riesgo
     */
    public function getRiskRecommendations($studentId)
    {
        $query = "
        SELECT 
            CONCAT(u.first_name, ' ', u.last_name) AS student_name,
            ROUND(AVG(ss.score), 2) AS average_score,
            COUNT(ss.score_id) AS total_activities,
            COUNT(CASE WHEN a.status = 'absent' THEN 1 END) AS absences,
            COUNT(CASE WHEN a.status = 'late' THEN 1 END) AS tardies,
            CASE 
                WHEN ROUND(AVG(ss.score), 2) < 3.0 THEN 'Necesita refuerzo académico'
                WHEN COUNT(CASE WHEN a.status = 'absent' THEN 1 END) > 3 THEN 'Necesita mejorar asistencia'
                ELSE 'Monitoreo regular'
            END AS recommendation
        FROM users u
        JOIN user_roles ur ON u.{$this->userIdColumn} = ur.user_id
        LEFT JOIN student_scores ss ON u.{$this->userIdColumn} = ss.{$this->studentUserIdColumn}
        LEFT JOIN attendance a ON u.{$this->userIdColumn} = a.{$this->studentUserIdColumn}
            AND a.attendance_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
        WHERE u.{$this->userIdColumn} = :studentId AND ur.role_type = 'student'
        GROUP BY u.{$this->userIdColumn}, u.first_name, u.last_name
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene información de debug sobre la estructura detectada
     */
    public function getDebugInfo()
    {
        return [
            'userIdColumn' => $this->userIdColumn,
            'studentUserIdColumn' => $this->studentUserIdColumn
        ];
    }
}
?> 