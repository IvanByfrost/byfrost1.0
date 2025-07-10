<?php
class EventModel extends MainModel
{
    public function __construct($dbConn = null)
    {
        parent::__construct($dbConn);
    }

    /**
     * Obtiene eventos próximos
     */
    public function getUpcomingEvents($days = 7)
    {
        $query = "
        SELECT 
            event_id,
            event_type,
            event_name,
            description,
            start_datetime,
            end_datetime,
            created_by_user_id
        FROM school_events
        WHERE start_datetime BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL :days DAY)
            AND event_id IS NOT NULL
        ORDER BY start_datetime ASC
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(':days', $days, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene eventos del día actual
     */
    public function getTodayEvents()
    {
        $query = "
        SELECT 
            event_id,
            event_type,
            event_name,
            description,
            start_datetime,
            end_datetime,
            created_by_user_id
        FROM school_events
        WHERE DATE(start_datetime) = CURDATE()
        ORDER BY start_datetime ASC
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene eventos por tipo
     */
    public function getEventsByType($type, $limit = 10)
    {
        $query = "
        SELECT 
            event_id,
            event_type,
            event_name,
            description,
            start_datetime,
            end_datetime,
            created_by_user_id
        FROM school_events
        WHERE event_type = :type
            AND start_datetime >= NOW()
        ORDER BY start_datetime ASC
        LIMIT :limit
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene estadísticas de eventos
     */
    public function getEventStatistics()
    {
        $query = "
        SELECT 
            COUNT(*) AS total_events,
            COUNT(CASE WHEN DATE(start_datetime) = CURDATE() THEN 1 END) AS today_events,
            COUNT(CASE WHEN start_datetime BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY) THEN 1 END) AS upcoming_events,
            COUNT(CASE WHEN start_datetime < NOW() THEN 1 END) AS past_events,
            COUNT(DISTINCT event_type) AS event_types
        FROM school_events
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene eventos por mes
     */
    public function getEventsByMonth($year = null, $month = null)
    {
        if (!$year) $year = date('Y');
        if (!$month) $month = date('m');
        
        $query = "
        SELECT 
            event_id,
            event_type,
            event_name,
            description,
            start_datetime,
            end_datetime,
            created_by_user_id,
            DAY(start_datetime) AS day
        FROM school_events
        WHERE YEAR(start_datetime) = :year
            AND MONTH(start_datetime) = :month
        ORDER BY start_datetime ASC
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        $stmt->bindParam(':month', $month, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene tipos de eventos únicos
     */
    public function getEventTypes()
    {
        $query = "
        SELECT DISTINCT event_type, COUNT(*) AS count
        FROM school_events
        GROUP BY event_type
        ORDER BY count DESC
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene eventos urgentes (próximos 3 días)
     */
    public function getUrgentEvents($days = 3)
    {
        $query = "
        SELECT 
            event_id,
            event_type,
            event_name,
            description,
            start_datetime,
            end_datetime,
            created_by_user_id,
            DATEDIFF(start_datetime, NOW()) AS days_until
        FROM school_events
        WHERE start_datetime BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL :days DAY)
        ORDER BY start_datetime ASC
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(':days', $days, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Crea un nuevo evento
     */
    public function createEvent($data)
    {
        $query = "
        INSERT INTO school_events (
            school_id, event_name, description, 
            start_datetime, end_datetime, event_type, 
            created_by_user_id
        ) VALUES (
            :school_id, :event_name, :description,
            :start_datetime, :end_datetime, :event_type,
            :created_by_user_id
        )
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(':school_id', $data['school_id'], PDO::PARAM_INT);
        $stmt->bindParam(':event_name', $data['event_name'], PDO::PARAM_STR);
        $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
        $stmt->bindParam(':start_datetime', $data['start_datetime'], PDO::PARAM_STR);
        $stmt->bindParam(':end_datetime', $data['end_datetime'], PDO::PARAM_STR);
        $stmt->bindParam(':event_type', $data['event_type'], PDO::PARAM_STR);
        $stmt->bindParam(':created_by_user_id', $data['created_by_user_id'], PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    /**
     * Actualiza un evento existente
     */
    public function updateEvent($eventId, $data)
    {
        $query = "
        UPDATE school_events SET
            event_type = :event_type,
            event_name = :event_name,
            description = :description,
            start_datetime = :start_datetime,
            end_datetime = :end_datetime
        WHERE event_id = :event_id
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);
        $stmt->bindParam(':event_type', $data['event_type'], PDO::PARAM_STR);
        $stmt->bindParam(':event_name', $data['event_name'], PDO::PARAM_STR);
        $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR);
        $stmt->bindParam(':start_datetime', $data['start_datetime'], PDO::PARAM_STR);
        $stmt->bindParam(':end_datetime', $data['end_datetime'], PDO::PARAM_STR);
        
        return $stmt->execute();
    }

    /**
     * Elimina un evento
     */
    public function deleteEvent($eventId)
    {
        $query = "DELETE FROM school_events WHERE event_id = :event_id";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    /**
     * Obtiene un evento específico
     */
    public function getEventById($eventId)
    {
        $query = "
        SELECT 
            event_id,
            school_id,
            event_name,
            description,
            start_datetime,
            end_datetime,
            event_type,
            created_by_user_id
        FROM school_events
        WHERE event_id = :event_id
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(':event_id', $eventId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene eventos por escuela
     */
    public function getEventsBySchool($schoolId, $limit = 10)
    {
        $query = "
        SELECT 
            event_id,
            event_type,
            event_name,
            description,
            start_datetime,
            end_datetime,
            created_by_user_id
        FROM school_events
        WHERE school_id = :school_id
        ORDER BY start_datetime DESC
        LIMIT :limit
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(':school_id', $schoolId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?> 