<?php
/**
 * Verificación Completa del Sistema ByFrost
 * Ruta XAMPP: F:\xampp\
 */

echo "=== VERIFICACIÓN SISTEMA BYFROST ===\n\n";

// 1. Verificar configuración básica
echo "1. CONFIGURACIÓN BÁSICA:\n";
echo "   - Directorio actual: " . getcwd() . "\n";
echo "   - PHP version: " . phpversion() . "\n";
echo "   - XAMPP ubicado en: F:\\xampp\\\n\n";

// 2. Verificar archivos críticos
echo "2. ARCHIVOS CRÍTICOS:\n";

$critical_files = [
    'config.php' => 'Configuración principal',
    'index.php' => 'Punto de entrada',
    'app/controllers/MainController.php' => 'Controlador principal',
    'app/library/SessionManager.php' => 'Gestor de sesiones',
    'app/library/ErrorHandler.php' => 'Manejador de errores'
];

foreach ($critical_files as $file => $description) {
    if (file_exists($file)) {
        echo "   ✅ $file - $description\n";
    } else {
        echo "   ❌ $file - $description (FALTANTE)\n";
    }
}

// 3. Verificar controladores de dashboard
echo "\n3. CONTROLADORES DE DASHBOARD:\n";

$dashboard_controllers = [
    'app/controllers/DirectorController.php' => 'Director',
    'app/controllers/coordinatorController.php' => 'Coordinator',
    'app/controllers/teacherController.php' => 'Teacher',
    'app/controllers/studentController.php' => 'Student',
    'app/controllers/ParentDashboardController.php' => 'Parent',
    'app/controllers/rootDashboardController.php' => 'Root'
];

foreach ($dashboard_controllers as $controller => $name) {
    if (file_exists($controller)) {
        echo "   ✅ $name Dashboard - $controller\n";
    } else {
        echo "   ❌ $name Dashboard - $controller (FALTANTE)\n";
    }
}

// 4. Verificar vistas de dashboard
echo "\n4. VISTAS DE DASHBOARD:\n";

$dashboard_views = [
    'app/views/director/dashboard.php' => 'Director',
    'app/views/coordinator/dashboard.php' => 'Coordinator',
    'app/views/teacher/dashboard.php' => 'Teacher',
    'app/views/student/dashboard.php' => 'Student',
    'app/views/parent/dashboard.php' => 'Parent',
    'app/views/root/dashboard.php' => 'Root'
];

foreach ($dashboard_views as $view => $name) {
    if (file_exists($view)) {
        echo "   ✅ $name Vista - $view\n";
    } else {
        echo "   ❌ $name Vista - $view (FALTANTE)\n";
    }
}

// 5. Verificar JavaScript
echo "\n5. ARCHIVOS JAVASCRIPT:\n";

$js_files = [
    'app/resources/js/loadView.js' => 'Carga dinámica',
    'app/resources/js/directorDashboard.js' => 'Dashboard Director',
    'app/resources/js/dashboard.js' => 'Dashboard general'
];

foreach ($js_files as $js => $description) {
    if (file_exists($js)) {
        echo "   ✅ $description - $js\n";
    } else {
        echo "   ❌ $description - $js (FALTANTE)\n";
    }
}

// 6. Verificar configuración
echo "\n6. CONFIGURACIÓN:\n";
if (file_exists('config.php')) {
    include 'config.php';
    if (defined('url')) {
        echo "   ✅ URL configurada: " . url . "\n";
    } else {
        echo "   ❌ URL no configurada en config.php\n";
    }
} else {
    echo "   ❌ config.php no encontrado\n";
}

// 7. Verificar estructura de directorios
echo "\n7. ESTRUCTURA DE DIRECTORIOS:\n";

$directories = [
    'app/controllers' => 'Controladores',
    'app/models' => 'Modelos',
    'app/views' => 'Vistas',
    'app/resources/js' => 'JavaScript',
    'app/resources/css' => 'CSS',
    'app/library' => 'Librerías',
    'app/logs' => 'Logs'
];

foreach ($directories as $dir => $description) {
    if (is_dir($dir)) {
        echo "   ✅ $description - $dir\n";
    } else {
        echo "   ❌ $description - $dir (FALTANTE)\n";
    }
}

// 8. Resumen final
echo "\n=== RESUMEN FINAL ===\n";

$total_files = count($critical_files) + count($dashboard_controllers) + count($dashboard_views) + count($js_files);
$existing_files = 0;

foreach (array_merge($critical_files, $dashboard_controllers, $dashboard_views, $js_files) as $file => $desc) {
    if (file_exists($file)) {
        $existing_files++;
    }
}

$percentage = round(($existing_files / $total_files) * 100, 2);

echo "Archivos verificados: $total_files\n";
echo "Archivos existentes: $existing_files\n";
echo "Porcentaje de completitud: $percentage%\n\n";

if ($percentage >= 90) {
    echo "✅ SISTEMA COMPLETAMENTE FUNCIONAL\n";
} elseif ($percentage >= 70) {
    echo "⚠️ SISTEMA PARCIALMENTE FUNCIONAL\n";
} else {
    echo "❌ SISTEMA CON PROBLEMAS CRÍTICOS\n";
}

echo "\n=== URLS PARA PROBAR ===\n";
echo "http://localhost:8000/director/dashboard\n";
echo "http://localhost:8000/coordinator/dashboard\n";
echo "http://localhost:8000/teacher/dashboard\n";
echo "http://localhost:8000/student/dashboard\n";
echo "http://localhost:8000/parent/dashboard\n";
echo "http://localhost:8000/root/dashboard\n\n";

echo "=== COMANDO PARA EJECUTAR ===\n";
echo "F:\\xampp\\php\\php.exe verificar-sistema.php\n\n";

echo "✅ Verificación completada!\n";
?> 