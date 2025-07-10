<?php
class AttendanceModel extends MainModel
{
    public function __construct($dbConn = null)
    {
        parent::__construct($dbConn);
    }

    /**
     * Obtiene estadísticas de asistencia del día actual optimizado para Baldur.sql
     */
    public function getTodayAttendanceStats()
    {
        $query = "
        SELECT 
            COALESCE(ROUND(
                (SELECT COUNT(DISTINCT a.student_user_id) 
                 FROM attendance a 
                 WHERE DATE(a.attendance_date) = CURDATE() AND a.status = 'present') / 
                NULLIF((SELECT COUNT(*) FROM users u 
                        JOIN user_roles ur ON u.user_id = ur.user_id 
                        WHERE ur.role_type = 'student' AND u.is_active = 1 AND ur.is_active = 1), 0), 2
            ), 0) AS attendance_today,
            
            COALESCE(ROUND(
                (SELECT COUNT(DISTINCT a.student_user_id) 
                 FROM attendance a 
                 WHERE MONTH(a.attendance_date) = MONTH(CURDATE()) 
                 AND YEAR(a.attendance_date) = YEAR(CURDATE()) 
                 AND a.status = 'present') / 
                NULLIF((SELECT COUNT(*) FROM users u 
                        JOIN user_roles ur ON u.user_id = ur.user_id 
                        WHERE ur.role_type = 'student' AND u.is_active = 1 AND ur.is_active = 1), 0), 2
            ), 0) AS attendance_month,
            
            (SELECT COUNT(*) FROM users u 
             JOIN user_roles ur ON u.user_id = ur.user_id 
             WHERE ur.role_type = 'student' AND u.is_active = 1 AND ur.is_active = 1) AS total_students,
            
            (SELECT COUNT(DISTINCT a.student_user_id) 
             FROM attendance a 
             WHERE DATE(a.attendance_date) = CURDATE() AND a.status = 'present') AS present_today,
            
            (SELECT COUNT(DISTINCT a.student_user_id) 
             FROM attendance a 
             WHERE MONTH(a.attendance_date) = MONTH(CURDATE()) 
             AND YEAR(a.attendance_date) = YEAR(CURDATE()) 
             AND a.status = 'present') AS present_month
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene estadísticas de asistencia por semana optimizado para Baldur.sql
     */
    public function getWeeklyAttendanceStats()
    {
        $query = "
        SELECT 
            DATE(a.attendance_date) as date,
            COUNT(DISTINCT a.student_user_id) as present_count,
            (SELECT COUNT(*) FROM users u 
             JOIN user_roles ur ON u.user_id = ur.user_id 
             WHERE ur.role_type = 'student' AND u.is_active = 1 AND ur.is_active = 1) as total_students,
            ROUND(
                COUNT(DISTINCT a.student_user_id) / 
                (SELECT COUNT(*) FROM users u 
                 JOIN user_roles ur ON u.user_id = ur.user_id 
                 WHERE ur.role_type = 'student' AND u.is_active = 1 AND ur.is_active = 1) * 100, 1
            ) as attendance_percentage
        FROM attendance a
        WHERE a.attendance_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
        AND a.status = 'present'
        GROUP BY DATE(a.attendance_date)
        ORDER BY date DESC
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene estadísticas de asistencia por materia optimizado para Baldur.sql
     */
    public function getAttendanceBySubject()
    {
        $query = "
        SELECT 
            s.subject_name,
            COUNT(DISTINCT a.student_user_id) as present_count,
            (SELECT COUNT(*) FROM users u 
             JOIN user_roles ur ON u.user_id = ur.user_id 
             WHERE ur.role_type = 'student' AND u.is_active = 1 AND ur.is_active = 1) as total_students,
            ROUND(
                COUNT(DISTINCT a.student_user_id) / 
                (SELECT COUNT(*) FROM users u 
                 JOIN user_roles ur ON u.user_id = ur.user_id 
                 WHERE ur.role_type = 'student' AND u.is_active = 1 AND ur.is_active = 1) * 100, 1
            ) as attendance_percentage
        FROM attendance a
        JOIN schedules sch ON a.schedule_id = sch.schedule_id
        JOIN professor_subjects ps ON sch.professor_subject_id = ps.professor_subject_id
        JOIN subjects s ON ps.subject_id = s.subject_id
        WHERE DATE(a.attendance_date) = CURDATE() AND a.status = 'present'
        GROUP BY s.subject_id, s.subject_name
        ORDER BY attendance_percentage DESC
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene los estudiantes con menor asistencia optimizado para Baldur.sql
     */
    public function getStudentsWithLowAttendance($limit = 10)
    {
        $query = "
        SELECT 
            CONCAT(u.first_name, ' ', u.last_name) as student_name,
            COUNT(DISTINCT a.student_user_id) as attendance_count,
            (SELECT COUNT(*) FROM users u2 
             JOIN user_roles ur2 ON u2.user_id = ur2.user_id 
             WHERE ur2.role_type = 'student' AND u2.is_active = 1 AND ur2.is_active = 1) as total_students,
            ROUND(
                COUNT(DISTINCT a.student_user_id) / 
                (SELECT COUNT(*) FROM users u2 
                 JOIN user_roles ur2 ON u2.user_id = ur2.user_id 
                 WHERE ur2.role_type = 'student' AND u2.is_active = 1 AND ur2.is_active = 1) * 100, 1
            ) as attendance_percentage
        FROM users u
        JOIN user_roles ur ON u.user_id = ur.user_id
        LEFT JOIN attendance a ON u.user_id = a.student_user_id 
            AND MONTH(a.attendance_date) = MONTH(CURDATE()) 
            AND YEAR(a.attendance_date) = YEAR(CURDATE())
            AND a.status = 'present'
        WHERE ur.role_type = 'student' AND u.is_active = 1 AND ur.is_active = 1
        GROUP BY u.user_id, u.first_name, u.last_name
        ORDER BY attendance_percentage ASC
        LIMIT :limit
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Registra la asistencia de un estudiante optimizado para Baldur.sql
     */
    public function recordAttendance($studentUserId, $scheduleId = null, $status = 'present')
    {
        $query = "INSERT INTO attendance (student_user_id, schedule_id, attendance_date, status, recorded_at) 
                  VALUES (:student_user_id, :schedule_id, CURDATE(), :status, NOW())";
        $stmt = $this->dbConn->prepare($query);
        return $stmt->execute([
            ':student_user_id' => $studentUserId,
            ':schedule_id' => $scheduleId,
            ':status' => $status
        ]);
    }

    /**
     * Verifica si un estudiante ya tiene asistencia registrada hoy
     */
    public function hasAttendanceToday($studentUserId)
    {
        $query = "SELECT COUNT(*) FROM attendance WHERE student_user_id = :student_user_id AND DATE(attendance_date) = CURDATE()";
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute([':student_user_id' => $studentUserId]);
        return $stmt->fetchColumn() > 0;
    }
}
?> 