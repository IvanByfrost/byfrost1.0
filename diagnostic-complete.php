<?php
// Script de diagnóstico completo para ByFrost
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);

echo "<!DOCTYPE html>
<html>
<head>
    <title>🔍 Diagnóstico Completo - ByFrost</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #2c3e50; text-align: center; border-bottom: 3px solid #3498db; padding-bottom: 10px; }
        h2 { color: #34495e; border-left: 4px solid #3498db; padding-left: 15px; }
        h3 { color: #7f8c8d; }
        .success { color: #27ae60; font-weight: bold; }
        .error { color: #e74c3c; font-weight: bold; }
        .warning { color: #f39c12; font-weight: bold; }
        .info { color: #3498db; font-weight: bold; }
        .code { background: #ecf0f1; padding: 10px; border-radius: 4px; font-family: monospace; margin: 10px 0; }
        .section { margin: 20px 0; padding: 15px; border: 1px solid #bdc3c7; border-radius: 5px; }
        .url-test { margin: 5px 0; }
        .url-test a { color: #3498db; text-decoration: none; }
        .url-test a:hover { text-decoration: underline; }
        .solution { background: #e8f5e8; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error-details { background: #ffeaea; padding: 10px; border-radius: 4px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔍 Diagnóstico Completo - ByFrost</h1>";

// =============================================
// 1. INFORMACIÓN DEL SISTEMA
// =============================================
echo "<div class='section'>
    <h2>1. 📊 Información del Sistema</h2>
    <p><span class='info'>Sistema Operativo:</span> " . php_uname() . "</p>
    <p><span class='info'>Versión de PHP:</span> " . phpversion() . "</p>
    <p><span class='info'>Servidor Web:</span> " . ($_SERVER['SERVER_SOFTWARE'] ?? 'No disponible') . "</p>
    <p><span class='info'>Document Root:</span> " . ($_SERVER['DOCUMENT_ROOT'] ?? 'No disponible') . "</p>
    <p><span class='info'>Directorio Actual:</span> " . __DIR__ . "</p>
    <p><span class='info'>URL Actual:</span> " . ($_SERVER['REQUEST_URI'] ?? 'No disponible') . "</p>
</div>";

// =============================================
// 2. VERIFICACIÓN DE ARCHIVOS CRÍTICOS
// =============================================
echo "<div class='section'>
    <h2>2. 📁 Verificación de Archivos Críticos</h2>";

$criticalFiles = [
    'index.php' => 'Archivo principal de la aplicación',
    'config.php' => 'Archivo de configuración',
    'app/scripts/connection.php' => 'Conexión a base de datos',
    'app/scripts/routerView.php' => 'Sistema de rutas',
    '.htaccess' => 'Configuración de Apache',
    'app/scripts/Baldur.sql' => 'Script de base de datos'
];

$missingFiles = [];
foreach ($criticalFiles as $file => $description) {
    if (file_exists($file)) {
        echo "<p><span class='success'>✅ $description</span> ($file)</p>";
    } else {
        echo "<p><span class='error'>❌ $description</span> ($file) - <span class='error'>NO ENCONTRADO</span></p>";
        $missingFiles[] = $file;
    }
}

if (!empty($missingFiles)) {
    echo "<div class='solution'>
        <h3>💡 Solución para archivos faltantes:</h3>
        <p>Los siguientes archivos son críticos y deben existir:</p>
        <ul>";
    foreach ($missingFiles as $file) {
        echo "<li><code>$file</code></li>";
    }
    echo "</ul>
        <p>Verifica que todos los archivos del proyecto estén en su lugar correcto.</p>
    </div>";
}

echo "</div>";

// =============================================
// 3. VERIFICACIÓN DE EXTENSIONES PHP
// =============================================
echo "<div class='section'>
    <h2>3. 🔧 Verificación de Extensiones PHP</h2>";

$requiredExtensions = [
    'pdo' => 'Conexión a bases de datos',
    'pdo_mysql' => 'Driver MySQL para PDO',
    'json' => 'Manejo de JSON',
    'mbstring' => 'Manejo de caracteres multibyte'
];

foreach ($requiredExtensions as $ext => $description) {
    if (extension_loaded($ext)) {
        echo "<p><span class='success'>✅ $description</span> ($ext)</p>";
    } else {
        echo "<p><span class='error'>❌ $description</span> ($ext) - <span class='error'>NO CARGADA</span></p>";
    }
}

echo "</div>";

// =============================================
// 4. VERIFICACIÓN DE BASE DE DATOS
// =============================================
echo "<div class='section'>
    <h2>4. 🗄️ Verificación de Base de Datos</h2>";

$host = 'localhost';
$user = 'byfrost_app_user';
$pass = 'ByFrost2024!Secure#';
$dbName = 'baldur_db';

$dbStatus = 'unknown';
$dbError = '';

try {
    // Probar conexión sin especificar base de datos
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    echo "<p><span class='success'>✅ Conexión a MySQL exitosa</span></p>";
    
    // Verificar si la base de datos existe
    $stmt = $pdo->query("SHOW DATABASES LIKE 'baldur_db'");
    if ($stmt->rowCount() > 0) {
        echo "<p><span class='success'>✅ Base de datos 'baldur_db' existe</span></p>";
        
        // Conectar a la base de datos específica
        $pdo = new PDO("mysql:host=$host;dbname=$dbName", $user, $pass);
        echo "<p><span class='success'>✅ Conexión a 'baldur_db' exitosa</span></p>";
        
        // Verificar tablas
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "<p><span class='success'>✅ Tablas encontradas: " . count($tables) . "</span></p>";
        
        if (count($tables) > 0) {
            echo "<p><span class='info'>📋 Tablas disponibles:</span></p><ul>";
            foreach ($tables as $table) {
                echo "<li>$table</li>";
            }
            echo "</ul>";
        }
        
        $dbStatus = 'ok';
        
    } else {
        echo "<p><span class='error'>❌ Base de datos 'baldur_db' NO existe</span></p>";
        $dbStatus = 'no_db';
    }
    
} catch (PDOException $e) {
    echo "<p><span class='error'>❌ Error de conexión: " . $e->getMessage() . "</span></p>";
    $dbStatus = 'connection_error';
    $dbError = $e->getMessage();
}

// Verificar usuario MySQL
echo "<h3>🔍 Verificación de Usuario MySQL</h3>";
try {
    $pdo = new PDO("mysql:host=$host", 'root', ''); // Intentar con root sin contraseña
    echo "<p><span class='success'>✅ Conexión como root exitosa</span></p>";
    
    $stmt = $pdo->query("SELECT User, Host FROM mysql.user WHERE User = 'byfrost_app_user'");
    if ($stmt->rowCount() > 0) {
        echo "<p><span class='success'>✅ Usuario 'byfrost_app_user' existe</span></p>";
    } else {
        echo "<p><span class='error'>❌ Usuario 'byfrost_app_user' NO existe</span></p>";
    }
    
} catch (PDOException $e) {
    echo "<p><span class='warning'>⚠️ No se pudo conectar como root: " . $e->getMessage() . "</span></p>";
}

echo "</div>";

// =============================================
// 5. SOLUCIONES ESPECÍFICAS
// =============================================
echo "<div class='section'>
    <h2>5. 🛠️ Soluciones Específicas</h2>";

if ($dbStatus === 'no_db') {
    echo "<div class='solution'>
        <h3>🔧 Crear Base de Datos</h3>
        <p>La base de datos 'baldur_db' no existe. Para crearla:</p>
        <div class='code'>
            -- En phpMyAdmin o MySQL CLI:
            CREATE DATABASE IF NOT EXISTS baldur_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
            CREATE USER 'byfrost_app_user'@'localhost' IDENTIFIED BY 'ByFrost2024!Secure#';
            GRANT ALL PRIVILEGES ON baldur_db.* TO 'byfrost_app_user'@'localhost';
            FLUSH PRIVILEGES;
            USE baldur_db;
            -- Luego importar el archivo app/scripts/Baldur.sql
        </div>
    </div>";
}

if ($dbStatus === 'connection_error') {
    echo "<div class='solution'>
        <h3>🔧 Error de Conexión</h3>
        <p>No se pudo conectar a la base de datos. Verifica:</p>
        <ul>
            <li>Que MySQL esté ejecutándose en XAMPP</li>
            <li>Que el usuario 'byfrost_app_user' exista</li>
            <li>Que la contraseña sea correcta</li>
            <li>Que el host 'localhost' sea accesible</li>
        </ul>
        <p><strong>Error específico:</strong> $dbError</p>
    </div>";
}

echo "<div class='solution'>
    <h3>🔧 Pasos para Solucionar Problemas</h3>
    <ol>
        <li><strong>Verificar XAMPP:</strong> Asegúrate de que Apache y MySQL estén ejecutándose</li>
        <li><strong>Crear Base de Datos:</strong> Ejecuta el script Baldur.sql en phpMyAdmin</li>
        <li><strong>Crear Usuario:</strong> Crea el usuario 'byfrost_app_user' con los privilegios necesarios</li>
        <li><strong>Verificar Archivos:</strong> Asegúrate de que todos los archivos críticos estén presentes</li>
        <li><strong>Probar URLs:</strong> Usa los enlaces de prueba que aparecen más abajo</li>
    </ol>
</div>";

echo "</div>";

// =============================================
// 6. PRUEBAS DE URL
// =============================================
echo "<div class='section'>
    <h2>6. 🌐 Pruebas de URL</h2>
    <p>Haz clic en los siguientes enlaces para probar diferentes rutas de la aplicación:</p>";

$baseUrl = 'http://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($_SERVER['SERVER_PORT'] == '80' ? '' : ':' . $_SERVER['SERVER_PORT']);
$testUrls = [
    '/' => 'Página principal',
    '/index.php' => 'Index.php directo',
    '/?view=index' => 'Index con parámetro',
    '/?view=login' => 'Página de login',
    '/?view=register' => 'Página de registro',
    '/test-database-connection.php' => 'Test de base de datos',
    '/test-server-status.php' => 'Test del servidor'
];

foreach ($testUrls as $url => $description) {
    $fullUrl = $baseUrl . $url;
    echo "<div class='url-test'>
        <a href='$fullUrl' target='_blank'>🔗 $description</a> 
        <span class='info'>($fullUrl)</span>
    </div>";
}

echo "</div>";

// =============================================
// 7. RESUMEN Y RECOMENDACIONES
// =============================================
echo "<div class='section'>
    <h2>7. 📋 Resumen y Recomendaciones</h2>";

$issues = [];
if (!empty($missingFiles)) {
    $issues[] = "Archivos críticos faltantes: " . implode(', ', $missingFiles);
}
if ($dbStatus !== 'ok') {
    $issues[] = "Problemas con la base de datos";
}

if (empty($issues)) {
    echo "<p><span class='success'>✅ Todo parece estar configurado correctamente</span></p>";
    echo "<p>Si aún no puedes acceder a la aplicación, verifica:</p>
    <ul>
        <li>Que Apache esté ejecutándose en XAMPP</li>
        <li>Que no haya conflictos de puerto</li>
        <li>Que la URL sea correcta (probablemente http://localhost/byfrost/)</li>
        <li>Los logs de error de Apache y PHP</li>
    </ul>";
} else {
    echo "<p><span class='error'>❌ Se encontraron los siguientes problemas:</span></p>
    <ul>";
    foreach ($issues as $issue) {
        echo "<li>$issue</li>";
    }
    echo "</ul>
    <p>Resuelve estos problemas antes de intentar acceder a la aplicación.</p>";
}

echo "</div>";

echo "</div></body></html>";
?> 