<?php
// Script para probar el PaymentModel con Baldur.sql
echo "<h1>🧪 Prueba del PaymentModel con Baldur.sql</h1>";

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

// Verificar si la tabla student_payments existe
try {
    $stmt = $dbConn->query("SHOW TABLES LIKE 'student_payments'");
    $tableExists = $stmt->rowCount() > 0;
    
    if ($tableExists) {
        echo "<p style='color: green;'>✅ Tabla student_payments existe</p>";
        
        // Mostrar estructura de la tabla
        $stmt = $dbConn->query("DESCRIBE student_payments");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<p><strong>Estructura de student_payments:</strong></p>";
        echo "<ul>";
        foreach ($columns as $column) {
            echo "<li>{$column['Field']} - {$column['Type']}</li>";
        }
        echo "</ul>";
        
    } else {
        echo "<p style='color: red;'>❌ Tabla student_payments NO existe</p>";
        echo "<p>Necesitas ejecutar Baldur.sql primero</p>";
        exit;
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error verificando tabla: " . $e->getMessage() . "</p>";
    exit;
}

// Probar el modelo PaymentModel
echo "<h2>🧪 Prueba del PaymentModel</h2>";
try {
    require_once ROOT . '/app/models/paymentModel.php';
    
    if (class_exists('PaymentModel')) {
        echo "<p style='color: green;'>✅ Clase PaymentModel existe</p>";
        
        $model = new PaymentModel($dbConn);
        
        // Probar método getPaymentStatistics
        echo "<p><strong>Probando getPaymentStatistics()...</strong></p>";
        
        try {
            $paymentStats = $model->getPaymentStatistics();
            echo "<p style='color: green;'>✅ getPaymentStatistics() ejecutado correctamente</p>";
            echo "<pre>" . print_r($paymentStats, true) . "</pre>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Error en getPaymentStatistics(): " . $e->getMessage() . "</p>";
            echo "<p><strong>Stack trace:</strong></p>";
            echo "<pre>" . $e->getTraceAsString() . "</pre>";
        }
        
        // Probar método getRevenueSummary
        echo "<p><strong>Probando getRevenueSummary()...</strong></p>";
        
        try {
            $revenueSummary = $model->getRevenueSummary();
            echo "<p style='color: green;'>✅ getRevenueSummary() ejecutado correctamente</p>";
            echo "<pre>" . print_r($revenueSummary, true) . "</pre>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Error en getRevenueSummary(): " . $e->getMessage() . "</p>";
        }
        
        // Probar método getOverduePayments
        echo "<p><strong>Probando getOverduePayments()...</strong></p>";
        
        try {
            $overduePayments = $model->getOverduePayments(30);
            echo "<p style='color: green;'>✅ getOverduePayments() ejecutado correctamente</p>";
            echo "<p>Pagos atrasados: " . count($overduePayments) . "</p>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Error en getOverduePayments(): " . $e->getMessage() . "</p>";
        }
        
        // Probar método getRecentPayments
        echo "<p><strong>Probando getRecentPayments()...</strong></p>";
        
        try {
            $recentPayments = $model->getRecentPayments(5);
            echo "<p style='color: green;'>✅ getRecentPayments() ejecutado correctamente</p>";
            echo "<p>Pagos recientes: " . count($recentPayments) . "</p>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Error en getRecentPayments(): " . $e->getMessage() . "</p>";
        }
        
        // Probar método getPaymentConceptStatistics
        echo "<p><strong>Probando getPaymentConceptStatistics()...</strong></p>";
        
        try {
            $paymentConcepts = $model->getPaymentConceptStatistics();
            echo "<p style='color: green;'>✅ getPaymentConceptStatistics() ejecutado correctamente</p>";
            echo "<p>Conceptos de pago: " . count($paymentConcepts) . "</p>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Error en getPaymentConceptStatistics(): " . $e->getMessage() . "</p>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ Clase PaymentModel NO existe</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error general en PaymentModel: " . $e->getMessage() . "</p>";
    echo "<p><strong>Stack trace:</strong></p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h2>🔗 Prueba del Dashboard del Director</h2>";
echo "<p>Ahora puedes probar el dashboard del director:</p>";
echo "<ul>";
echo "<li><a href='/?view=directorDashboard' target='_blank'>Dashboard Director</a></li>";
echo "</ul>";

echo "<h2>💡 Estado</h2>";
echo "<p>Si todas las pruebas pasan (✅), el widget de pagos debería funcionar sin errores.</p>";
echo "<p>Si hay errores (❌), necesitamos revisar más a fondo.</p>";
?> 