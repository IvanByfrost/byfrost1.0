<?php
/**
 * Script de diagnóstico para errores del sistema de nómina
 */

// Configuración de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Diagnóstico de Errores - Sistema de Nómina</h1>\n";
echo "<hr>\n";

// 1. Verificar archivos requeridos
echo "<h2>1. Verificación de Archivos</h2>\n";
$requiredFiles = [
    'app/scripts/connection.php',
    'app/models/payrollModel.php',
    'app/controllers/payrollController.php',
    'app/views/payroll/dashboard.php',
    'app/views/payroll/employees.php',
    'app/views/payroll/createEmployee.php',
    'app/views/payroll/periods.php',
    'app/views/payroll/createPeriod.php',
    'app/library/SessionManager.php',
    'app/library/SecurityMiddleware.php'
];

foreach ($requiredFiles as $file) {
    if (file_exists($file)) {
        echo "✅ $file existe<br>\n";
    } else {
        echo "❌ $file NO existe<br>\n";
    }
}

// 2. Verificar sintaxis PHP
echo "<h2>2. Verificación de Sintaxis PHP</h2>\n";
$phpFiles = [
    'app/models/payrollModel.php',
    'app/controllers/payrollController.php'
];

foreach ($phpFiles as $file) {
    if (file_exists($file)) {
        $output = shell_exec("php -l $file 2>&1");
        if (strpos($output, 'No syntax errors') !== false) {
            echo "✅ $file - Sintaxis correcta<br>\n";
        } else {
            echo "❌ $file - Error de sintaxis:<br>\n";
            echo "<pre>" . htmlspecialchars($output) . "</pre>\n";
        }
    }
}

// 3. Verificar conexión a base de datos
echo "<h2>3. Verificación de Conexión a Base de Datos</h2>\n";
try {
    require_once 'app/scripts/connection.php';
    $conn = getConnection();
    echo "✅ Conexión a base de datos exitosa<br>\n";
    
    // Verificar si las tablas existen
    $tables = ['employees', 'payroll_concepts', 'payroll_periods', 'payroll_records'];
    foreach ($tables as $table) {
        try {
            $stmt = $conn->prepare("SHOW TABLES LIKE ?");
            $stmt->execute([$table]);
            if ($stmt->rowCount() > 0) {
                echo "✅ Tabla '$table' existe<br>\n";
            } else {
                echo "❌ Tabla '$table' NO existe<br>\n";
            }
        } catch (Exception $e) {
            echo "❌ Error verificando tabla '$table': " . $e->getMessage() . "<br>\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "<br>\n";
}

// 4. Verificar carga de clases
echo "<h2>4. Verificación de Carga de Clases</h2>\n";

// Probar PayrollModel
try {
    require_once 'app/models/payrollModel.php';
    $payrollModel = new PayrollModel();
    echo "✅ PayrollModel cargado correctamente<br>\n";
    
    // Probar métodos básicos
    try {
        $concepts = $payrollModel->getAllConcepts();
        echo "✅ Método getAllConcepts() funciona<br>\n";
    } catch (Exception $e) {
        echo "❌ Error en getAllConcepts(): " . $e->getMessage() . "<br>\n";
    }
    
    try {
        $employees = $payrollModel->getAllEmployees();
        echo "✅ Método getAllEmployees() funciona<br>\n";
    } catch (Exception $e) {
        echo "❌ Error en getAllEmployees(): " . $e->getMessage() . "<br>\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error cargando PayrollModel: " . $e->getMessage() . "<br>\n";
}

// Probar PayrollController
try {
    require_once 'app/controllers/payrollController.php';
    $payrollController = new PayrollController();
    echo "✅ PayrollController cargado correctamente<br>\n";
} catch (Exception $e) {
    echo "❌ Error cargando PayrollController: " . $e->getMessage() . "<br>\n";
}

// 5. Verificar dependencias
echo "<h2>5. Verificación de Dependencias</h2>\n";

// Verificar SessionManager
try {
    require_once 'app/library/SessionManager.php';
    $sessionManager = new SessionManager();
    echo "✅ SessionManager cargado correctamente<br>\n";
} catch (Exception $e) {
    echo "❌ Error cargando SessionManager: " . $e->getMessage() . "<br>\n";
}

// Verificar SecurityMiddleware
try {
    require_once 'app/library/SecurityMiddleware.php';
    $securityMiddleware = new SecurityMiddleware();
    echo "✅ SecurityMiddleware cargado correctamente<br>\n";
} catch (Exception $e) {
    echo "❌ Error cargando SecurityMiddleware: " . $e->getMessage() . "<br>\n";
}

// 6. Verificar rutas y URLs
echo "<h2>6. Verificación de Rutas</h2>\n";
$testUrls = [
    'index.php?controller=payroll&action=dashboard',
    'index.php?controller=payroll&action=employees',
    'index.php?controller=payroll&action=periods'
];

foreach ($testUrls as $url) {
    echo "🔗 URL de prueba: $url<br>\n";
}

// 7. Verificar permisos de archivos
echo "<h2>7. Verificación de Permisos</h2>\n";
$directories = [
    'app/views/payroll',
    'app/models',
    'app/controllers',
    'app/library'
];

foreach ($directories as $dir) {
    if (is_dir($dir)) {
        if (is_readable($dir)) {
            echo "✅ Directorio '$dir' es legible<br>\n";
        } else {
            echo "❌ Directorio '$dir' NO es legible<br>\n";
        }
    } else {
        echo "❌ Directorio '$dir' NO existe<br>\n";
    }
}

// 8. Verificar configuración del servidor
echo "<h2>8. Verificación del Servidor</h2>\n";
echo "PHP Version: " . phpversion() . "<br>\n";
echo "Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'No disponible') . "<br>\n";
echo "Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'No disponible') . "<br>\n";

// 9. Verificar extensiones PHP
echo "<h2>9. Verificación de Extensiones PHP</h2>\n";
$requiredExtensions = ['pdo', 'pdo_mysql', 'json', 'mbstring'];
foreach ($requiredExtensions as $ext) {
    if (extension_loaded($ext)) {
        echo "✅ Extensión '$ext' cargada<br>\n";
    } else {
        echo "❌ Extensión '$ext' NO cargada<br>\n";
    }
}

// 10. Resumen de errores encontrados
echo "<h2>10. Resumen de Diagnóstico</h2>\n";
echo "<div style='background-color: #f8f9fa; padding: 15px; border-radius: 5px;'>\n";
echo "<h4>Posibles Causas de Errores:</h4>\n";
echo "<ul>\n";
echo "<li>🔧 Tablas de base de datos no creadas</li>\n";
echo "<li>🔧 Errores de sintaxis en archivos PHP</li>\n";
echo "<li>🔧 Problemas de conexión a base de datos</li>\n";
echo "<li>🔧 Dependencias faltantes</li>\n";
echo "<li>🔧 Permisos de archivos incorrectos</li>\n";
echo "<li>🔧 Extensiones PHP faltantes</li>\n";
echo "</ul>\n";

echo "<h4>Soluciones Recomendadas:</h4>\n";
echo "<ul>\n";
echo "<li>📋 Ejecutar el script SQL para crear las tablas</li>\n";
echo "<li>📋 Verificar la configuración de la base de datos</li>\n";
echo "<li>📋 Revisar los logs de errores del servidor</li>\n";
echo "<li>📋 Verificar que todas las dependencias estén instaladas</li>\n";
echo "</ul>\n";
echo "</div>\n";

echo "<hr>\n";
echo "<p><em>Diagnóstico completado el " . date('Y-m-d H:i:s') . "</em></p>\n";
?> 