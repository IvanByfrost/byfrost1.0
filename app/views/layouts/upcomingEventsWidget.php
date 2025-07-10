<?php
require_once 'app/models/eventModel.php';
$eventModel = new EventModel($dbConn);

// Obtener datos de eventos
$upcomingEvents = $eventModel->getUpcomingEvents(7);
$todayEvents = $eventModel->getTodayEvents();
$urgentEvents = $eventModel->getUrgentEvents(3);
$eventStats = $eventModel->getEventStatistics();
?>

<div class="upcoming-events-widget">
    <div class="widget-header">
        <h3><i class="fas fa-calendar-alt"></i> Eventos Próximos</h3>
        <div class="widget-actions">
            <button class="btn btn-sm btn-outline-primary" onclick="refreshEvents()">
                <i class="fas fa-sync-alt"></i>
            </button>
            <button class="btn btn-sm btn-outline-success" onclick="createEvent()">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>

    <!-- Métricas de eventos -->
    <div class="event-metrics">
        <div class="metric-card today">
            <div class="metric-icon">
                <i class="fas fa-calendar-day"></i>
            </div>
            <div class="metric-content">
                <h4><?php echo $eventStats['today_events'] ?? 0; ?></h4>
                <p>Hoy</p>
            </div>
        </div>

        <div class="metric-card upcoming">
            <div class="metric-icon">
                <i class="fas fa-calendar-week"></i>
            </div>
            <div class="metric-content">
                <h4><?php echo $eventStats['upcoming_events'] ?? 0; ?></h4>
                <p>Próximos 7 días</p>
            </div>
        </div>

        <div class="metric-card urgent">
            <div class="metric-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="metric-content">
                <h4><?php echo count($urgentEvents); ?></h4>
                <p>Urgentes (3 días)</p>
            </div>
        </div>
    </div>

    <!-- Eventos de hoy -->
    <?php if (!empty($todayEvents)): ?>
    <div class="events-section today-events">
        <h5><i class="fas fa-calendar-day text-primary"></i> Eventos de Hoy</h5>
        <div class="events-list">
            <?php foreach ($todayEvents as $event): ?>
            <div class="event-item today-event">
                <div class="event-time">
                    <i class="fas fa-clock"></i>
                    <span><?php echo date('H:i', strtotime($event['start_datetime'])); ?></span>
                </div>
                <div class="event-info">
                    <strong><?php echo htmlspecialchars($event['event_name']); ?></strong>
                    <span class="event-type"><?php echo htmlspecialchars($event['event_type']); ?></span>
                    <?php if ($event['description']): ?>
                        <small><i class="fas fa-info-circle"></i> <?php echo htmlspecialchars(substr($event['description'], 0, 50)); ?></small>
                    <?php endif; ?>
                </div>
                <div class="event-actions">
                    <button class="btn btn-sm btn-outline-primary" onclick="viewEvent(<?php echo $event['event_id']; ?>)">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Eventos próximos -->
    <div class="events-section upcoming-events">
        <h5><i class="fas fa-calendar-week text-info"></i> Próximos Eventos</h5>
        <div class="events-list">
            <?php if (empty($upcomingEvents)): ?>
                <div class="no-events">
                    <i class="fas fa-calendar-times"></i>
                    <span>No hay eventos próximos</span>
                </div>
            <?php else: ?>
                <?php foreach ($upcomingEvents as $event): ?>
                <?php 
                    $eventDate = new DateTime($event['start_datetime']);
                    $today = new DateTime();
                    $daysDiff = $today->diff($eventDate)->days;
                    $isUrgent = $daysDiff <= 3;
                ?>
                <div class="event-item <?php echo $isUrgent ? 'urgent-event' : ''; ?>">
                    <div class="event-date">
                        <div class="date-number"><?php echo $eventDate->format('d'); ?></div>
                        <div class="date-month"><?php echo $eventDate->format('M'); ?></div>
                        <div class="date-year"><?php echo $eventDate->format('Y'); ?></div>
                    </div>
                    <div class="event-info">
                        <strong><?php echo htmlspecialchars($event['event_name']); ?></strong>
                        <span class="event-type"><?php echo htmlspecialchars($event['event_type']); ?></span>
                        <div class="event-details">
                            <small><i class="fas fa-clock"></i> <?php echo $eventDate->format('H:i'); ?></small>
                            <?php if ($event['description']): ?>
                                <small><i class="fas fa-info-circle"></i> <?php echo htmlspecialchars(substr($event['description'], 0, 50)); ?></small>
                            <?php endif; ?>
                            <small class="days-until">
                                <?php if ($daysDiff == 0): ?>
                                    <span class="badge bg-primary">Hoy</span>
                                <?php elseif ($daysDiff == 1): ?>
                                    <span class="badge bg-info">Mañana</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">En <?php echo $daysDiff; ?> días</span>
                                <?php endif; ?>
                            </small>
                        </div>
                    </div>
                    <div class="event-actions">
                        <button class="btn btn-sm btn-outline-primary" onclick="viewEvent(<?php echo $event['event_id']; ?>)">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-warning" onclick="editEvent(<?php echo $event['event_id']; ?>)">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Acciones rápidas -->
    <div class="quick-actions">
        <button class="btn btn-primary" onclick="viewAllEvents()">
            <i class="fas fa-calendar"></i> Ver Todos los Eventos
        </button>
        <button class="btn btn-success" onclick="createEvent()">
            <i class="fas fa-plus"></i> Crear Evento
        </button>
        <button class="btn btn-info" onclick="exportEvents()">
            <i class="fas fa-download"></i> Exportar
        </button>
    </div>
</div>

<style>
.upcoming-events-widget {
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
    color: #007bff;
}

.widget-actions {
    display: flex;
    gap: 8px;
}

.event-metrics {
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
    transition: transform 0.2s ease;
}

.metric-card:hover {
    transform: translateY(-2px);
}

.metric-card.today {
    border-left: 4px solid #007bff;
}

.metric-card.upcoming {
    border-left: 4px solid #17a2b8;
}

.metric-card.urgent {
    border-left: 4px solid #ffc107;
}

.metric-icon {
    margin-right: 12px;
    font-size: 1.5rem;
    color: #007bff;
}

.metric-content h4 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 700;
    color: #333;
}

.metric-content p {
    margin: 0;
    color: #666;
    font-size: 0.9rem;
}

.events-section {
    margin-bottom: 20px;
}

.events-section h5 {
    margin: 0 0 15px 0;
    color: #333;
    font-weight: 600;
    font-size: 1rem;
}

.events-section h5 i {
    margin-right: 8px;
}

.events-list {
    max-height: 400px;
    overflow-y: auto;
}

.event-item {
    display: flex;
    align-items: center;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 8px;
    background: #f8f9fa;
    border-left: 4px solid #007bff;
    transition: all 0.2s ease;
}

.event-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
}

.event-item.today-event {
    border-left-color: #007bff;
    background: rgba(0, 123, 255, 0.05);
}

.event-item.urgent-event {
    border-left-color: #ffc107;
    background: rgba(255, 193, 7, 0.05);
}

.event-date {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-right: 15px;
    min-width: 60px;
}

.date-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: #007bff;
    line-height: 1;
}

.date-month {
    font-size: 0.8rem;
    color: #666;
    text-transform: uppercase;
}

.date-year {
    font-size: 0.7rem;
    color: #999;
}

.event-time {
    display: flex;
    align-items: center;
    margin-right: 15px;
    min-width: 80px;
}

.event-time i {
    margin-right: 5px;
    color: #007bff;
}

.event-info {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.event-info strong {
    color: #333;
    font-weight: 600;
    margin-bottom: 2px;
}

.event-type {
    font-size: 0.85rem;
    color: #666;
    margin-bottom: 5px;
}

.event-details {
    display: flex;
    gap: 10px;
    align-items: center;
    flex-wrap: wrap;
}

.event-details small {
    color: #666;
    font-size: 0.8rem;
}

.event-details i {
    margin-right: 3px;
}

.event-actions {
    display: flex;
    gap: 5px;
}

.no-events {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 30px;
    color: #999;
    font-style: italic;
}

.no-events i {
    margin-right: 8px;
    font-size: 1.5rem;
}

.quick-actions {
    display: flex;
    gap: 10px;
    justify-content: center;
    padding-top: 15px;
    border-top: 1px solid #f0f0f0;
}

@media (max-width: 768px) {
    .event-metrics {
        grid-template-columns: 1fr;
    }
    
    .event-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .event-date, .event-time {
        margin-right: 0;
        margin-bottom: 10px;
    }
    
    .event-details {
        flex-direction: column;
        gap: 5px;
    }
    
    .quick-actions {
        flex-direction: column;
    }
}
</style>

<script>
// Función para refrescar eventos
function refreshEvents() {
    location.reload();
}

// Función para ver detalles del evento
function viewEvent(eventId) {
    if (eventId > 0) {
        loadView('event/view&id=' + eventId);
    }
}

// Función para editar evento
function editEvent(eventId) {
    if (eventId > 0) {
        loadView('event/edit&id=' + eventId);
    }
}

// Función para crear nuevo evento
function createEvent() {
    loadView('event/create');
}

// Función para ver todos los eventos
function viewAllEvents() {
    loadView('event/list');
}

// Función para exportar eventos
function exportEvents() {
    window.open('<?php echo url; ?>?view=event&action=export', '_blank');
}

// Auto-refresh cada 5 minutos
setTimeout(function() {
    refreshEvents();
}, 300000);
</script> 