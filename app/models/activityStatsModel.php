<?php
class ActivityStatsModel extends MainModel
{
    public function __construct($dbConn = null)
    {
        parent::__construct($dbConn);
    }

    /**
     * Obtiene estadísticas de actividades por mes optimizado para Baldur.sql
     */
    public function getActivitiesByMonth()
    {
        $query = "
        SELECT 
            MONTH(ss.graded_at) AS mes,
            COUNT(*) AS total_activities,
            COUNT(DISTINCT ss.student_user_id) AS unique_students,
            ROUND(AVG(ss.score), 2) AS average_score
        FROM student_scores ss
        WHERE YEAR(ss.graded_at) = YEAR(CURDATE())
        GROUP BY MONTH(ss.graded_at)
        ORDER BY mes
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene estadísticas de calificaciones por mes optimizado para Baldur.sql
     */
    public function getGradesByMonth()
    {
        $query = "
        SELECT 
            MONTH(ss.graded_at) AS mes,
            COUNT(*) AS total_grades,
            ROUND(AVG(ss.score), 2) AS average_score,
            MIN(ss.score) AS min_score,
            MAX(ss.score) AS max_score
        FROM student_scores ss
        WHERE YEAR(ss.graded_at) = YEAR(CURDATE())
        GROUP BY MONTH(ss.graded_at)
        ORDER BY mes
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene estadísticas de asistencia por mes optimizado para Baldur.sql
     */
    public function getAttendanceByMonth()
    {
        $query = "
        SELECT 
            MONTH(a.attendance_date) AS mes,
            COUNT(DISTINCT a.student_user_id) AS present_students,
            (SELECT COUNT(*) FROM users u JOIN user_roles ur ON u.user_id = ur.user_id WHERE ur.role_type = 'student' AND u.is_active = 1) AS total_students,
            ROUND(
                COUNT(DISTINCT a.student_user_id) / (SELECT COUNT(*) FROM users u JOIN user_roles ur ON u.user_id = ur.user_id WHERE ur.role_type = 'student' AND u.is_active = 1) * 100, 1
            ) AS attendance_percentage
        FROM attendance a
        WHERE YEAR(a.attendance_date) = YEAR(CURDATE()) AND a.status = 'present'
        GROUP BY MONTH(a.attendance_date)
        ORDER BY mes
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene estadísticas por materia optimizado para Baldur.sql
     */
    public function getStatsBySubject()
    {
        $query = "
        SELECT 
            s.subject_name,
            COUNT(ss.score_id) AS total_activities,
            ROUND(AVG(ss.score), 2) AS average_score,
            COUNT(DISTINCT ss.student_user_id) AS unique_students
        FROM subjects s
        LEFT JOIN professor_subjects ps ON s.subject_id = ps.subject_id
        LEFT JOIN activities a ON ps.professor_subject_id = a.professor_subject_id
        LEFT JOIN student_scores ss ON a.activity_id = ss.activity_id
        GROUP BY s.subject_id, s.subject_name
        ORDER BY total_activities DESC
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene estadísticas de rendimiento por estudiante optimizado para Baldur.sql
     */
    public function getStudentPerformance()
    {
        $query = "
        SELECT 
            u.first_name,
            COUNT(ss.score_id) AS total_activities,
            ROUND(AVG(ss.score), 2) AS average_score,
            MIN(ss.score) AS min_score,
            MAX(ss.score) AS max_score
        FROM users u
        JOIN user_roles ur ON u.user_id = ur.user_id
        LEFT JOIN student_scores ss ON u.user_id = ss.student_user_id
        WHERE ur.role_type = 'student' AND u.is_active = 1
        GROUP BY u.user_id, u.first_name
        HAVING total_activities > 0
        ORDER BY average_score DESC
        LIMIT 10
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene datos para gráfico de actividades por mes
     */
    public function getChartData()
    {
        $activities = $this->getActivitiesByMonth();
        $grades = $this->getGradesByMonth();
        $attendance = $this->getAttendanceByMonth();

        $months = [];
        $activityData = [];
        $gradeData = [];
        $attendanceData = [];

        // Crear array con todos los meses del año
        for ($i = 1; $i <= 12; $i++) {
            $months[] = date("F", mktime(0, 0, 0, $i, 10));
            $activityData[$i] = 0;
            $gradeData[$i] = 0;
            $attendanceData[$i] = 0;
        }

        // Llenar datos de actividades
        foreach ($activities as $activity) {
            $activityData[$activity['mes']] = $activity['total_activities'];
        }

        // Llenar datos de calificaciones promedio
        foreach ($grades as $grade) {
            $gradeData[$grade['mes']] = $grade['average_score'];
        }

        // Llenar datos de asistencia
        foreach ($attendance as $att) {
            $attendanceData[$att['mes']] = $att['attendance_percentage'];
        }

        return [
            'months' => $months,
            'activities' => array_values($activityData),
            'grades' => array_values($gradeData),
            'attendance' => array_values($attendanceData)
        ];
    }
}
?> 