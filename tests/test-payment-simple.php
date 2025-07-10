<?php
// Script simple para probar PaymentModel
echo "<h1>🧪 Prueba Simple del PaymentModel</h1>";

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

// Verificar tabla student_payments
try {
    $stmt = $dbConn->query("SHOW TABLES LIKE 'student_payments'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✅ Tabla student_payments existe</p>";
    } else {
        echo "<p style='color: red;'>❌ Tabla student_payments NO existe</p>";
        exit;
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error verificando tabla: " . $e->getMessage() . "</p>";
    exit;
}

// Probar PaymentModel
try {
    require_once 'app/models/paymentModel.php';
    $model = new PaymentModel($dbConn);
    echo "<p style='color: green;'>✅ PaymentModel creado correctamente</p>";
    
    // Probar getPaymentStatistics
    $paymentStats = $model->getPaymentStatistics();
    echo "<p style='color: green;'>✅ getPaymentStatistics() ejecutado: " . count($paymentStats) . " estadísticas</p>";
    echo "<pre>" . print_r($paymentStats, true) . "</pre>";
    
    // Probar getRevenueSummary
    $revenueSummary = $model->getRevenueSummary();
    echo "<p style='color: green;'>✅ getRevenueSummary() ejecutado</p>";
    echo "<pre>" . print_r($revenueSummary, true) . "</pre>";
    
    // Probar getPaymentConceptStatistics
    $paymentConcepts = $model->getPaymentConceptStatistics();
    echo "<p style='color: green;'>✅ getPaymentConceptStatistics() ejecutado: " . count($paymentConcepts) . " conceptos</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error en PaymentModel: " . $e->getMessage() . "</p>";
    echo "<p><strong>Stack trace:</strong></p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<h2>✅ Prueba completada</h2>";
echo "<p>Si ves ✅ en todos los puntos, el PaymentModel funciona correctamente.</p>";
echo "<p><a href='/?view=directorDashboard' target='_blank'>Probar Dashboard Director</a></p>";
?> 