<?php
// Script simple para verificar el estado del servidor
echo "<h1>🔍 Estado del Servidor Byfrost</h1>";

// 1. Verificar PHP
echo "<h2>✅ PHP Funcionando</h2>";
echo "<p>Versión de PHP: " . phpversion() . "</p>";
echo "<p>Directorio actual: " . __DIR__ . "</p>";

// 2. Verificar archivos críticos
echo "<h2>📁 Archivos Críticos</h2>";
$criticalFiles = [
    'index.php',
    'config.php',
    'app/controllers/RootController.php',
    'app/controllers/UserController.php',
    'app/views/root/dashboard.php',
    'app/views/user/assignRole.php'
];

foreach ($criticalFiles as $file) {
    if (file_exists($file)) {
        echo "<p style='color: green;'>✅ $file - Existe</p>";
    } else {
        echo "<p style='color: red;'>❌ $file - NO EXISTE</p>";
    }
}

// 3. Verificar configuración
echo "<h2>⚙️ Configuración</h2>";
if (file_exists('config.php')) {
    include_once 'config.php';
    if (defined('ROOT')) {
        echo "<p style='color: green;'>✅ ROOT definido: " . ROOT . "</p>";
    } else {
        echo "<p style='color: red;'>❌ ROOT no definido</p>";
    }
    
    if (defined('url')) {
        echo "<p style='color: green;'>✅ URL definido: " . url . "</p>";
    } else {
        echo "<p style='color: red;'>❌ URL no definido</p>";
    }
} else {
    echo "<p style='color: red;'>❌ config.php no encontrado</p>";
}

// 4. Verificar base de datos
echo "<h2>🗄️ Base de Datos</h2>";
if (file_exists('app/scripts/connection.php')) {
    try {
        include_once 'app/scripts/connection.php';
        echo "<p style='color: green;'>✅ Conexión a BD exitosa</p>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Error de BD: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='color: red;'>❌ connection.php no encontrado</p>";
}

// 5. Verificar rutas
echo "<h2>🛣️ Rutas de Prueba</h2>";
$testUrls = [
    'Página Principal' => 'http://localhost:8000/',
    'Login' => 'http://localhost:8000/?view=index&action=login',
    'Dashboard' => 'http://localhost:8000/?view=root&action=dashboard',
    'Asignar Roles' => 'http://localhost:8000/?view=user&action=assignRole'
];

foreach ($testUrls as $name => $url) {
    echo "<p><strong>$name:</strong> <a href='$url' target='_blank'>$url</a></p>";
}

// 6. Verificar permisos
echo "<h2>🔐 Permisos</h2>";
$directories = [
    'app/views',
    'app/controllers',
    'app/models',
    'app/resources'
];

foreach ($directories as $dir) {
    if (is_dir($dir)) {
        if (is_readable($dir)) {
            echo "<p style='color: green;'>✅ $dir - Legible</p>";
        } else {
            echo "<p style='color: red;'>❌ $dir - No legible</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ $dir - No existe</p>";
    }
}

echo "<h2>🚀 Próximos Pasos</h2>";
echo "<ol>";
echo "<li>Abre <a href='http://localhost:8000/' target='_blank'>http://localhost:8000/</a></li>";
echo "<li>Si no funciona, verifica que el servidor esté corriendo</li>";
echo "<li>Revisa la consola del navegador (F12) para errores</li>";
echo "<li>Prueba cada URL individualmente</li>";
echo "</ol>";

echo "<h2>📞 Información de Debug</h2>";
echo "<p><strong>Error Reporting:</strong> " . (error_reporting() ? 'Activado' : 'Desactivado') . "</p>";
echo "<p><strong>Display Errors:</strong> " . (ini_get('display_errors') ? 'Activado' : 'Desactivado') . "</p>";
echo "<p><strong>Log Errors:</strong> " . (ini_get('log_errors') ? 'Activado' : 'Desactivado') . "</p>";
?> 