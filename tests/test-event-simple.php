<?php
// Script simple para probar EventModel
echo "<h1>🧪 Prueba Simple del EventModel</h1>";

// Cargar configuración
require_once 'config.php';
echo "<p style='color: green;'>✅ config.php cargado</p>";

// Conectar a la base de datos
try {
    require_once 'app/scripts/connection.php';
    $dbConn = getConnection();
    echo "<p style='color: green;'>✅ Conexión a BD exitosa</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error de conexión: " . $e->getMessage() . "</p>";
    exit;
}

// Verificar tabla school_events
try {
    $stmt = $dbConn->query("SHOW TABLES LIKE 'school_events'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✅ Tabla school_events existe</p>";
    } else {
        echo "<p style='color: red;'>❌ Tabla school_events NO existe</p>";
        exit;
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error verificando tabla: " . $e->getMessage() . "</p>";
    exit;
}

// Probar EventModel
try {
    require_once 'app/models/eventModel.php';
    $model = new EventModel($dbConn);
    echo "<p style='color: green;'>✅ EventModel creado correctamente</p>";
    
    // Probar getUpcomingEvents
    $events = $model->getUpcomingEvents(7);
    echo "<p style='color: green;'>✅ getUpcomingEvents() ejecutado: " . count($events) . " eventos</p>";
    
    // Probar getEventStatistics
    $stats = $model->getEventStatistics();
    echo "<p style='color: green;'>✅ getEventStatistics() ejecutado</p>";
    echo "<pre>" . print_r($stats, true) . "</pre>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error en EventModel: " . $e->getMessage() . "</p>";
    echo "<p><strong>Stack trace:</strong></p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h2>✅ Prueba completada</h2>";
echo "<p>Si ves ✅ en todos los puntos, el EventModel funciona correctamente.</p>";
?> 