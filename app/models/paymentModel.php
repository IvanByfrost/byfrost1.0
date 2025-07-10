<?php
class PaymentModel extends MainModel
{
    public function __construct($dbConn = null)
    {
        parent::__construct($dbConn);
    }

    /**
     * Obtiene estadísticas generales de pagos
     */
    public function getPaymentStatistics()
    {
        $query = "
        SELECT 
            COUNT(*) AS total_payments,
            SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) AS completed_payments,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending_payments,
            SUM(CASE WHEN status = 'overdue' THEN 1 ELSE 0 END) AS overdue_payments,
            ROUND(
                (SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) / COUNT(*)) * 100, 1
            ) AS completion_rate
        FROM student_payments
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene pagos por mes
     */
    public function getPaymentsByMonth($year = null, $month = null)
    {
        if (!$year) $year = date('Y');
        if (!$month) $month = date('m');
        
        $query = "
        SELECT 
            DATE_FORMAT(payment_date, '%Y-%m-%d') AS payment_day,
            COUNT(*) AS total_payments,
            SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) AS completed,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending,
            SUM(CASE WHEN status = 'overdue' THEN 1 ELSE 0 END) AS overdue
        FROM student_payments
        WHERE YEAR(payment_date) = :year 
            AND MONTH(payment_date) = :month
        GROUP BY DATE_FORMAT(payment_date, '%Y-%m-%d')
        ORDER BY payment_day ASC
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        $stmt->bindParam(':month', $month, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene pagos atrasados
     */
    public function getOverduePayments($daysOverdue = 30)
    {
        $query = "
        SELECT 
            sp.payment_id,
            sp.student_user_id,
            u.first_name,
            u.last_name,
            sp.amount,
            sp.concept,
            sp.due_date,
            sp.status,
            DATEDIFF(CURDATE(), sp.due_date) AS days_overdue,
            sp.payment_date,
            sp.transaction_reference
        FROM student_payments sp
        JOIN users u ON sp.student_user_id = u.user_id
        WHERE sp.status IN ('pending', 'overdue')
            AND sp.due_date < CURDATE()
            AND DATEDIFF(CURDATE(), sp.due_date) >= :daysOverdue
        ORDER BY days_overdue DESC
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(':daysOverdue', $daysOverdue, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene pagos recientes
     */
    public function getRecentPayments($limit = 10)
    {
        $query = "
        SELECT 
            sp.payment_id,
            u.first_name,
            u.last_name,
            sp.amount,
            sp.concept,
            sp.payment_date,
            sp.status,
            sp.transaction_reference
        FROM student_payments sp
        JOIN users u ON sp.student_user_id = u.user_id
        WHERE sp.status = 'paid'
            AND sp.payment_date IS NOT NULL
        ORDER BY sp.payment_date DESC
        LIMIT :limit
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene estadísticas de pagos por concepto
     */
    public function getPaymentConceptStatistics()
    {
        $query = "
        SELECT 
            concept,
            COUNT(*) AS total_payments,
            SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) AS completed,
            ROUND(
                (SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) / COUNT(*)) * 100, 1
            ) AS success_rate
        FROM student_payments
        WHERE concept IS NOT NULL
        GROUP BY concept
        ORDER BY total_payments DESC
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene tendencias de pagos
     */
    public function getPaymentTrends($months = 6)
    {
        $query = "
        SELECT 
            DATE_FORMAT(payment_date, '%Y-%m') AS month,
            COUNT(*) AS total_payments,
            SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) AS completed,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending,
            SUM(CASE WHEN status = 'overdue' THEN 1 ELSE 0 END) AS overdue
        FROM student_payments
        WHERE payment_date >= DATE_SUB(CURDATE(), INTERVAL :months MONTH)
        GROUP BY DATE_FORMAT(payment_date, '%Y-%m')
        ORDER BY month DESC
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(':months', $months, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene resumen de ingresos
     */
    public function getRevenueSummary()
    {
        $query = "
        SELECT 
            SUM(amount) AS total_revenue,
            SUM(CASE WHEN status = 'paid' THEN amount ELSE 0 END) AS collected_revenue,
            SUM(CASE WHEN status IN ('pending', 'overdue') THEN amount ELSE 0 END) AS pending_revenue,
            ROUND(
                (SUM(CASE WHEN status = 'paid' THEN amount ELSE 0 END) / SUM(amount)) * 100, 1
            ) AS collection_rate
        FROM student_payments
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Actualiza el estado de un pago
     */
    public function updatePaymentStatus($paymentId, $status, $transactionReference = null)
    {
        $query = "
        UPDATE student_payments SET
            status = :status,
            payment_date = CASE WHEN :status = 'paid' THEN CURDATE() ELSE payment_date END,
            transaction_reference = :transaction_reference
        WHERE payment_id = :payment_id
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(':payment_id', $paymentId, PDO::PARAM_INT);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':transaction_reference', $transactionReference, PDO::PARAM_STR);
        
        return $stmt->execute();
    }

    /**
     * Crea un nuevo registro de pago
     */
    public function createPayment($data)
    {
        $query = "
        INSERT INTO student_payments (
            student_user_id, amount, concept, due_date, 
            payment_date, status, transaction_reference
        ) VALUES (
            :student_user_id, :amount, :concept, :due_date,
            :payment_date, :status, :transaction_reference
        )
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(':student_user_id', $data['student_user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':amount', $data['amount'], PDO::PARAM_STR);
        $stmt->bindParam(':concept', $data['concept'], PDO::PARAM_STR);
        $stmt->bindParam(':due_date', $data['due_date'], PDO::PARAM_STR);
        $stmt->bindParam(':payment_date', $data['payment_date'], PDO::PARAM_STR);
        $stmt->bindParam(':status', $data['status'], PDO::PARAM_STR);
        $stmt->bindParam(':transaction_reference', $data['transaction_reference'], PDO::PARAM_STR);
        
        return $stmt->execute();
    }

    /**
     * Obtiene pagos por estudiante
     */
    public function getPaymentsByStudent($studentUserId, $limit = 10)
    {
        $query = "
        SELECT 
            payment_id,
            amount,
            concept,
            due_date,
            payment_date,
            status,
            transaction_reference,
            created_at
        FROM student_payments
        WHERE student_user_id = :student_user_id
        ORDER BY created_at DESC
        LIMIT :limit
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(':student_user_id', $studentUserId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene un pago específico
     */
    public function getPaymentById($paymentId)
    {
        $query = "
        SELECT 
            sp.payment_id,
            sp.student_user_id,
            u.first_name,
            u.last_name,
            sp.amount,
            sp.concept,
            sp.due_date,
            sp.payment_date,
            sp.status,
            sp.transaction_reference,
            sp.created_at
        FROM student_payments sp
        JOIN users u ON sp.student_user_id = u.user_id
        WHERE sp.payment_id = :payment_id
        ";
        
        $stmt = $this->dbConn->prepare($query);
        $stmt->bindParam(':payment_id', $paymentId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?> 