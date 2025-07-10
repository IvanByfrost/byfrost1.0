<?php
// Script de prueba para verificar la conexión a la base de datos
echo "<h1>🔍 Diagnóstico de Base de Datos</h1>";

// 1. Verificar si PHP está funcionando
echo "<h2>1. Estado de PHP</h2>";
echo "✅ PHP está funcionando correctamente<br>";
echo "Versión de PHP: " . phpversion() . "<br>";
echo "Extensiones cargadas: " . implode(', ', get_loaded_extensions()) . "<br><br>";

// 2. Verificar extensión PDO
echo "<h2>2. Extensión PDO</h2>";
if (extension_loaded('pdo')) {
    echo "✅ Extensión PDO está cargada<br>";
    echo "Drivers PDO disponibles: " . implode(', ', PDO::getAvailableDrivers()) . "<br>";
} else {
    echo "❌ Extensión PDO NO está cargada<br>";
}
echo "<br>";

// 3. Verificar extensión MySQL
echo "<h2>3. Extensión MySQL</h2>";
if (extension_loaded('pdo_mysql')) {
    echo "✅ Extensión PDO MySQL está cargada<br>";
} else {
    echo "❌ Extensión PDO MySQL NO está cargada<br>";
}
echo "<br>";

// 4. Probar conexión a MySQL
echo "<h2>4. Prueba de Conexión MySQL</h2>";
$host = 'localhost';
$user = 'byfrost_app_user';
$pass = 'ByFrost2024!Secure#';
$dbName = 'baldur_db';

try {
    // Intentar conexión sin especificar base de datos
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    echo "✅ Conexión a MySQL exitosa<br>";
    
    // Verificar si la base de datos existe
    $stmt = $pdo->query("SHOW DATABASES LIKE 'baldur_db'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Base de datos 'baldur_db' existe<br>";
        
        // Conectar a la base de datos específica
        $pdo = new PDO("mysql:host=$host;dbname=$dbName", $user, $pass);
        echo "✅ Conexión a 'baldur_db' exitosa<br>";
        
        // Verificar tablas
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "📋 Tablas encontradas (" . count($tables) . "):<br>";
        foreach ($tables as $table) {
            echo "- $table<br>";
        }
        
    } else {
        echo "❌ Base de datos 'baldur_db' NO existe<br>";
        echo "💡 Necesitas crear la base de datos ejecutando el script Baldur.sql<br>";
    }
    
} catch (PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "<br>";
    echo "💡 Verifica que:<br>";
    echo "- MySQL esté ejecutándose<br>";
    echo "- El usuario 'byfrost_app_user' exista<br>";
    echo "- La contraseña sea correcta<br>";
    echo "- El host 'localhost' sea accesible<br>";
}

echo "<br>";

// 5. Verificar usuario MySQL
echo "<h2>5. Verificar Usuario MySQL</h2>";
try {
    $pdo = new PDO("mysql:host=$host", 'root', ''); // Intentar con root sin contraseña
    echo "✅ Conexión como root exitosa<br>";
    
    // Verificar si el usuario existe
    $stmt = $pdo->query("SELECT User, Host FROM mysql.user WHERE User = 'byfrost_app_user'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Usuario 'byfrost_app_user' existe<br>";
        $userInfo = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "Host: " . $userInfo['Host'] . "<br>";
    } else {
        echo "❌ Usuario 'byfrost_app_user' NO existe<br>";
        echo "💡 Necesitas crear el usuario:<br>";
        echo "<code>CREATE USER 'byfrost_app_user'@'localhost' IDENTIFIED BY 'ByFrost2024!Secure#';</code><br>";
        echo "<code>GRANT ALL PRIVILEGES ON baldur_db.* TO 'byfrost_app_user'@'localhost';</code><br>";
        echo "<code>FLUSH PRIVILEGES;</code><br>";
    }
    
} catch (PDOException $e) {
    echo "❌ No se pudo conectar como root: " . $e->getMessage() . "<br>";
    echo "💡 Verifica que MySQL esté ejecutándose y que puedas acceder como root<br>";
}

echo "<br>";

// 6. Instrucciones para solucionar problemas
echo "<h2>6. Instrucciones de Solución</h2>";
echo "<h3>Si la base de datos no existe:</h3>";
echo "1. Abre phpMyAdmin<br>";
echo "2. Crea una nueva base de datos llamada 'baldur_db'<br>";
echo "3. Importa el archivo 'app/scripts/Baldur.sql'<br>";
echo "<br>";

echo "<h3>Si el usuario no existe:</h3>";
echo "1. En phpMyAdmin, ve a la pestaña 'Usuarios'<br>";
echo "2. Crea un nuevo usuario 'byfrost_app_user'@'localhost'<br>";
echo "3. Asigna la contraseña 'ByFrost2024!Secure#'<br>";
echo "4. Dale todos los privilegios sobre 'baldur_db'<br>";
echo "<br>";

echo "<h3>Comandos SQL para ejecutar:</h3>";
echo "<code>CREATE DATABASE IF NOT EXISTS baldur_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;</code><br>";
echo "<code>CREATE USER 'byfrost_app_user'@'localhost' IDENTIFIED BY 'ByFrost2024!Secure#';</code><br>";
echo "<code>GRANT ALL PRIVILEGES ON baldur_db.* TO 'byfrost_app_user'@'localhost';</code><br>";
echo "<code>FLUSH PRIVILEGES;</code><br>";
echo "<code>USE baldur_db;</code><br>";
echo "<code>-- Luego importar el contenido de Baldur.sql</code><br>";

echo "<br><hr>";
echo "<p><strong>💡 Después de solucionar los problemas de base de datos, intenta acceder a tu aplicación nuevamente.</strong></p>";
?> 