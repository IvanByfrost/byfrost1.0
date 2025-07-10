<?php
// Script para probar el EventModel con Baldur.sql
echo "<h1>🧪 Prueba del EventModel con Baldur.sql</h1>";

// Definir ROOT si no está definido
if (!defined('ROOT')) {
    define('ROOT', dirname(__DIR__));
}

// Cargar configuración
if (file_exists(ROOT . '/config.php')) {
    require_once ROOT . '/config.php';
    echo "<p style='color: green;'>✅ config.php cargado</p>";
} else {
    echo "<p style='color: red;'>❌ config.php no existe</p>";
    exit;
}

// Conectar a la base de datos
try {
    require_once ROOT . '/app/scripts/connection.php';
    $dbConn = getConnection();
    echo "<p style='color: green;'>✅ Conexión a BD exitosa</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error de conexión: " . $e->getMessage() . "</p>";
    exit;
}

// Verificar si la tabla school_events existe
try {
    $stmt = $dbConn->query("SHOW TABLES LIKE 'school_events'");
    $tableExists = $stmt->rowCount() > 0;
    
    if ($tableExists) {
        echo "<p style='color: green;'>✅ Tabla school_events existe</p>";
        
        // Mostrar estructura de la tabla
        $stmt = $dbConn->query("DESCRIBE school_events");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<p><strong>Estructura de school_events:</strong></p>";
        echo "<ul>";
        foreach ($columns as $column) {
            echo "<li>{$column['Field']} - {$column['Type']}</li>";
        }
        echo "</ul>";
        
    } else {
        echo "<p style='color: red;'>❌ Tabla school_events NO existe</p>";
        echo "<p>Necesitas ejecutar Baldur.sql primero</p>";
        exit;
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error verificando tabla: " . $e->getMessage() . "</p>";
    exit;
}

// Probar el modelo EventModel
echo "<h2>🧪 Prueba del EventModel</h2>";
try {
    require_once ROOT . '/app/models/eventModel.php';
    
    if (class_exists('EventModel')) {
        echo "<p style='color: green;'>✅ Clase EventModel existe</p>";
        
        $model = new EventModel($dbConn);
        
        // Probar método getUpcomingEvents
        echo "<p><strong>Probando getUpcomingEvents()...</strong></p>";
        
        try {
            $upcomingEvents = $model->getUpcomingEvents(7);
            echo "<p style='color: green;'>✅ getUpcomingEvents() ejecutado correctamente</p>";
            echo "<p>Eventos próximos: " . count($upcomingEvents) . "</p>";
            
            if (!empty($upcomingEvents)) {
                echo "<p><strong>Primer evento próximo:</strong></p>";
                echo "<pre>" . print_r($upcomingEvents[0], true) . "</pre>";
            }
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Error en getUpcomingEvents(): " . $e->getMessage() . "</p>";
            echo "<p><strong>Stack trace:</strong></p>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        }
        
        // Probar método getTodayEvents
        echo "<p><strong>Probando getTodayEvents()...</strong></p>";
        
        try {
            $todayEvents = $model->getTodayEvents();
            echo "<p style='color: green;'>✅ getTodayEvents() ejecutado correctamente</p>";
            echo "<p>Eventos de hoy: " . count($todayEvents) . "</p>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Error en getTodayEvents(): " . $e->getMessage() . "</p>";
        }
        
        // Probar método getEventStatistics
        echo "<p><strong>Probando getEventStatistics()...</strong></p>";
        
        try {
            $stats = $model->getEventStatistics();
            echo "<p style='color: green;'>✅ getEventStatistics() ejecutado correctamente</p>";
            echo "<pre>" . print_r($stats, true) . "</pre>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Error en getEventStatistics(): " . $e->getMessage() . "</p>";
        }
        
        // Probar método getUrgentEvents
        echo "<p><strong>Probando getUrgentEvents()...</strong></p>";
        
        try {
            $urgentEvents = $model->getUrgentEvents(3);
            echo "<p style='color: green;'>✅ getUrgentEvents() ejecutado correctamente</p>";
            echo "<p>Eventos urgentes: " . count($urgentEvents) . "</p>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Error en getUrgentEvents(): " . $e->getMessage() . "</p>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ Clase EventModel NO existe</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error general en EventModel: " . $e->getMessage() . "</p>";
    echo "<p><strong>Stack trace:</strong></p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h2>🔗 Prueba del Dashboard del Director</h2>";
echo "<p>Ahora puedes probar el dashboard del director:</p>";
echo "<ul>";
echo "<li><a href='/?view=directorDashboard' target='_blank'>Dashboard Director</a></li>";
echo "</ul>";

echo "<h2>💡 Estado</h2>";
echo "<p>Si todas las pruebas pasan (✅), el widget de eventos próximos debería funcionar sin errores.</p>";
echo "<p>Si hay errores (❌), necesitamos revisar más a fondo.</p>";
?> 