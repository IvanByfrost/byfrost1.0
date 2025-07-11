<?php
require_once 'app/models/paymentModel.php';
$paymentModel = new PaymentModel($dbConn);

// Obtener datos de pagos
$paymentStats = $paymentModel->getPaymentStatistics();
$revenueSummary = $paymentModel->getRevenueSummary();
$overduePayments = $paymentModel->getOverduePayments(30);
$recentPayments = $paymentModel->getRecentPayments(5);
$paymentConcepts = $paymentModel->getPaymentConceptStatistics();
?>

<div class="payment-widget">
    <div class="widget-header">
        <h3><i class="fas fa-hand-holding-usd"></i> Gestión de Pagos</h3>
        <div class="widget-actions">
            <button class="btn btn-sm btn-outline-primary" onclick="refreshPayments()">
                <i class="fas fa-sync-alt"></i>
            </button>
            <button class="btn btn-sm btn-outline-success" onclick="createPayment()">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>

    <!-- Métricas principales -->
    <div class="payment-metrics">
        <div class="metric-card total">
            <div class="metric-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="metric-content">
                <h4><?php echo number_format($paymentStats['total_payments'] ?? 0); ?></h4>
                <p>Total Pagos</p>
            </div>
        </div>

        <div class="metric-card completed">
            <div class="metric-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="metric-content">
                <h4><?php echo number_format($paymentStats['completed_payments'] ?? 0); ?></h4>
                <p>Pagados</p>
                <small><?php echo $paymentStats['completion_rate'] ?? 0; ?>% del total</small>
            </div>
        </div>

        <div class="metric-card pending">
            <div class="metric-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="metric-content">
                <h4><?php echo number_format($paymentStats['pending_payments'] ?? 0); ?></h4>
                <p>Pendientes</p>
            </div>
        </div>

        <div class="metric-card overdue">
            <div class="metric-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="metric-content">
                <h4><?php echo number_format($paymentStats['overdue_payments'] ?? 0); ?></h4>
                <p>Atrasados</p>
            </div>
        </div>
    </div>

    <!-- Resumen de ingresos -->
    <div class="revenue-summary">
        <h5><i class="fas fa-chart-line"></i> Resumen de Ingresos</h5>
        <div class="revenue-cards">
            <div class="revenue-card total">
                <div class="revenue-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="revenue-content">
                    <h4>$<?php echo number_format($revenueSummary['total_revenue'] ?? 0, 2); ?></h4>
                    <p>Ingresos Totales</p>
                </div>
            </div>

            <div class="revenue-card collected">
                <div class="revenue-icon">
                    <i class="fas fa-hand-holding-usd"></i>
                </div>
                <div class="revenue-content">
                    <h4>$<?php echo number_format($revenueSummary['collected_revenue'] ?? 0, 2); ?></h4>
                    <p>Recaudado</p>
                    <small><?php echo $revenueSummary['collection_rate'] ?? 0; ?>% de cobranza</small>
                </div>
            </div>

            <div class="revenue-card pending">
                <div class="revenue-icon">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="revenue-content">
                    <h4>$<?php echo number_format($revenueSummary['pending_revenue'] ?? 0, 2); ?></h4>
                    <p>Por Cobrar</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagos atrasados -->
    <?php if (!empty($overduePayments)): ?>
    <div class="overdue-section">
        <h5><i class="fas fa-exclamation-triangle text-danger"></i> Pagos Atrasados</h5>
        <div class="overdue-list">
            <?php foreach ($overduePayments as $payment): ?>
            <div class="overdue-item">
                <div class="student-info">
                    <strong><?php echo htmlspecialchars($payment['first_name'] . ' ' . $payment['last_name']); ?></strong>
                    <span class="concept"><?php echo htmlspecialchars($payment['concept']); ?></span>
                </div>
                <div class="payment-details">
                    <span class="amount">$<?php echo number_format($payment['amount'], 2); ?></span>
                    <span class="days-overdue"><?php echo $payment['days_overdue']; ?> días atrasado</span>
                </div>
                <div class="payment-actions">
                    <button class="btn btn-sm btn-outline-primary" onclick="viewStudent(<?php echo $payment['student_user_id']; ?>)">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-success" onclick="markAsPaid(<?php echo $payment['payment_id']; ?>)">
                        <i class="fas fa-check"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-warning" onclick="sendReminder(<?php echo $payment['student_user_id']; ?>)">
                        <i class="fas fa-bell"></i>
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Pagos recientes -->
    <div class="recent-payments">
        <h5><i class="fas fa-history"></i> Pagos Recientes</h5>
        <div class="payments-list">
            <?php if (empty($recentPayments)): ?>
                <div class="no-payments">
                    <i class="fas fa-info-circle"></i>
                    <span>No hay pagos recientes</span>
                </div>
            <?php else: ?>
                <?php foreach ($recentPayments as $payment): ?>
                <div class="payment-item">
                    <div class="payment-info">
                        <strong><?php echo htmlspecialchars($payment['first_name'] . ' ' . $payment['last_name']); ?></strong>
                        <span class="concept"><?php echo htmlspecialchars($payment['concept']); ?></span>
                    </div>
                    <div class="payment-amount">
                        <span class="amount">$<?php echo number_format($payment['amount'], 2); ?></span>
                        <small><?php echo date('d/m/Y', strtotime($payment['payment_date'])); ?></small>
                    </div>
                    <div class="payment-status">
                        <span class="badge bg-success">Pagado</span>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Conceptos de pago -->
    <?php if (!empty($paymentConcepts)): ?>
    <div class="payment-concepts">
        <h5><i class="fas fa-tags"></i> Conceptos de Pago</h5>
        <div class="concepts-list">
            <?php foreach ($paymentConcepts as $concept): ?>
            <div class="concept-item">
                <div class="concept-info">
                    <strong><?php echo htmlspecialchars($concept['concept']); ?></strong>
                    <span><?php echo $concept['total_payments']; ?> pagos</span>
                </div>
                <div class="concept-stats">
                    <span class="success-rate"><?php echo $concept['success_rate']; ?>% éxito</span>
                    <span class="completed"><?php echo $concept['completed']; ?> completados</span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Acciones rápidas -->
    <div class="quick-actions">
        <button class="btn btn-primary" onclick="viewAllPayments()">
            <i class="fas fa-list"></i> Ver Todos los Pagos
        </button>
        <button class="btn btn-success" onclick="createPayment()">
            <i class="fas fa-plus"></i> Crear Pago
        </button>
        <button class="btn btn-warning" onclick="exportPayments()">
            <i class="fas fa-download"></i> Exportar Reporte
        </button>
        <button class="btn btn-info" onclick="viewPaymentTrends()">
            <i class="fas fa-chart-line"></i> Ver Tendencias
        </button>
    </div>
</div>

<style>
.payment-widget {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin-bottom: 20px;
}

.widget-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f0f0;
}

.widget-header h3 {
    margin: 0;
    color: #333;
    font-size: 1.2rem;
    font-weight: 600;
}

.widget-header h3 i {
    margin-right: 8px;
    color: #28a745;
}

.widget-actions {
    display: flex;
    gap: 8px;
}

.payment-metrics {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
    margin-bottom: 25px;
}

.metric-card {
    display: flex;
    align-items: center;
    padding: 15px;
    border-radius: 8px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-left: 4px solid #007bff;
    transition: all 0.3s ease;
}

.metric-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.metric-card.completed {
    border-left-color: #28a745;
}

.metric-card.pending {
    border-left-color: #ffc107;
}

.metric-card.overdue {
    border-left-color: #dc3545;
}

.metric-icon {
    margin-right: 15px;
    font-size: 1.5rem;
    color: #007bff;
}

.metric-card.completed .metric-icon {
    color: #28a745;
}

.metric-card.pending .metric-icon {
    color: #ffc107;
}

.metric-card.overdue .metric-icon {
    color: #dc3545;
}

.metric-content h4 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 700;
    color: #333;
}

.metric-content p {
    margin: 5px 0 0 0;
    color: #666;
    font-size: 0.9rem;
}

.metric-content small {
    color: #007bff;
    font-weight: 600;
}

.revenue-summary {
    margin-bottom: 25px;
}

.revenue-summary h5 {
    margin: 0 0 15px 0;
    color: #333;
    font-size: 1.1rem;
    font-weight: 600;
}

.revenue-summary h5 i {
    margin-right: 8px;
    color: #007bff;
}

.revenue-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.revenue-card {
    display: flex;
    align-items: center;
    padding: 15px;
    border-radius: 8px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-left: 4px solid #007bff;
    transition: all 0.3s ease;
}

.revenue-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.revenue-card.collected {
    border-left-color: #28a745;
}

.revenue-card.pending {
    border-left-color: #ffc107;
}

.revenue-icon {
    margin-right: 15px;
    font-size: 1.5rem;
    color: #007bff;
}

.revenue-card.collected .revenue-icon {
    color: #28a745;
}

.revenue-card.pending .revenue-icon {
    color: #ffc107;
}

.revenue-content h4 {
    margin: 0;
    font-size: 1.3rem;
    font-weight: 700;
    color: #333;
}

.revenue-content p {
    margin: 5px 0 0 0;
    color: #666;
    font-size: 0.9rem;
}

.revenue-content small {
    color: #007bff;
    font-weight: 600;
}

.overdue-section, .recent-payments, .payment-concepts {
    margin-bottom: 20px;
}

.overdue-section h5, .recent-payments h5, .payment-concepts h5 {
    margin: 0 0 15px 0;
    color: #333;
    font-size: 1.1rem;
    font-weight: 600;
}

.overdue-section h5 i, .recent-payments h5 i, .payment-concepts h5 i {
    margin-right: 8px;
}

.overdue-list, .payments-list, .concepts-list {
    max-height: 300px;
    overflow-y: auto;
}

.overdue-item, .payment-item, .concept-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 15px;
    margin-bottom: 8px;
    border-radius: 6px;
    background: #f8f9fa;
    border-left: 4px solid #dc3545;
    transition: all 0.3s ease;
}

.overdue-item:hover, .payment-item:hover, .concept-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
}

.payment-item {
    border-left-color: #28a745;
}

.concept-item {
    border-left-color: #007bff;
}

.student-info, .payment-info, .concept-info {
    display: flex;
    flex-direction: column;
    flex: 1;
}

.student-info strong, .payment-info strong, .concept-info strong {
    color: #333;
    font-weight: 600;
}

.student-info .concept, .payment-info .concept, .concept-info span {
    font-size: 0.85rem;
    color: #666;
}

.payment-details, .payment-amount, .concept-stats {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    text-align: right;
}

.payment-details .amount, .payment-amount .amount {
    font-size: 1.1rem;
    font-weight: 700;
    color: #333;
}

.payment-details .days-overdue {
    font-size: 0.85rem;
    color: #dc3545;
    font-weight: 600;
}

.payment-amount small {
    font-size: 0.8rem;
    color: #666;
}

.payment-actions {
    display: flex;
    gap: 5px;
    margin-left: 15px;
}

.concept-stats .success-rate {
    font-size: 0.9rem;
    color: #28a745;
    font-weight: 600;
}

.concept-stats .completed {
    font-size: 0.8rem;
    color: #666;
}

.no-payments {
    text-align: center;
    padding: 30px;
    color: #666;
}

.no-payments i {
    font-size: 2rem;
    margin-bottom: 10px;
    display: block;
}

.quick-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #e9ecef;
}

.quick-actions .btn {
    flex: 1;
    min-width: 150px;
}

@media (max-width: 768px) {
    .payment-metrics {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .revenue-cards {
        grid-template-columns: 1fr;
    }
    
    .overdue-item, .payment-item, .concept-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .payment-details, .payment-amount, .concept-stats {
        align-items: flex-start;
        margin: 0;
    }
    
    .payment-actions {
        margin-left: 0;
        width: 100%;
        justify-content: center;
    }
    
    .quick-actions {
        flex-direction: column;
    }
    
    .quick-actions .btn {
        min-width: auto;
    }
}
</style>

<script>
function refreshPayments() {
    location.reload();
}

function createPayment() {
    // Implementar creación de pago
    alert('Función de crear pago en desarrollo');
}

function viewStudent(studentId) {
    // Implementar vista de estudiante
    alert('Vista de estudiante en desarrollo - ID: ' + studentId);
}

function markAsPaid(paymentId) {
    // Implementar marcar como pagado
    alert('Marcar como pagado en desarrollo - ID: ' + paymentId);
}

function sendReminder(studentId) {
    // Implementar envío de recordatorio
    alert('Envío de recordatorio en desarrollo - ID: ' + studentId);
}

function viewAllPayments() {
    // Implementar vista de todos los pagos
    alert('Vista de todos los pagos en desarrollo');
}

function exportPayments() {
    // Implementar exportación
    alert('Exportación de pagos en desarrollo');
}

function viewPaymentTrends() {
    // Implementar vista de tendencias
    alert('Vista de tendencias en desarrollo');
}
</script> 