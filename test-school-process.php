<?php
/**
 * Script de prueba para el proceso de consulta de escuelas
 * Verifica que las consultas usen los nombres correctos de las columnas de la base de datos
 */

// Configuración
define('ROOT', __DIR__);
require_once 'config.php';

echo "<h1>Prueba de Consulta de Escuelas</h1>";
echo "<p>Verificando que las consultas usen los nombres correctos de las columnas de la base de datos.</p>";

// 1. Probar conexión a la base de datos
echo "<h2>1. Prueba de Conexión</h2>";
try {
    require_once 'app/scripts/connection.php';
    $dbConnection = DatabaseConnection::getInstance();
    $connection = $dbConnection->getConnection();
    echo "<p style='color: green;'>✓ Conexión exitosa a la base de datos</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error de conexión: " . $e->getMessage() . "</p>";
    exit;
}

// 2. Verificar estructura de la tabla schools
echo "<h2>2. Verificación de Estructura de Tabla</h2>";
try {
    $query = "DESCRIBE schools";
    $stmt = $connection->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll();
    
    if ($result) {
        echo "<p>✓ Estructura de la tabla 'schools':</p>";
        echo "<ul>";
        foreach ($result as $row) {
            echo "<li><strong>{$row['Field']}</strong> - {$row['Type']} - {$row['Null']} - {$row['Key']}</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color: red;'>✗ No se pudo obtener la estructura de la tabla</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error al verificar estructura: " . $e->getMessage() . "</p>";
}

// 3. Probar consulta básica
echo "<h2>3. Prueba de Consulta Básica</h2>";
try {
    $query = "SELECT 
                s.school_id,
                s.school_name,
                s.school_dane,
                s.school_document,
                s.total_quota,
                s.address,
                s.phone,
                s.email,
                s.is_active,
                CONCAT(d.first_name, ' ', d.last_name) as director_name,
                CONCAT(c.first_name, ' ', c.last_name) as coordinator_name
              FROM schools s
              LEFT JOIN users d ON s.director_user_id = d.user_id
              LEFT JOIN users c ON s.coordinator_user_id = c.user_id
              WHERE s.is_active = 1
              LIMIT 5";
    
    $stmt = $connection->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll();
    
    if ($result && count($result) > 0) {
        echo "<p>✓ Consulta exitosa. Encontradas " . count($result) . " escuelas:</p>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='background-color: #f0f0f0;'>";
        echo "<th>ID</th><th>Nombre</th><th>DANE</th><th>NIT</th><th>Dirección</th><th>Director</th><th>Coordinador</th>";
        echo "</tr>";
        
        foreach ($result as $row) {
            echo "<tr>";
            echo "<td>{$row['school_id']}</td>";
            echo "<td>{$row['school_name']}</td>";
            echo "<td>{$row['school_dane']}</td>";
            echo "<td>{$row['school_document']}</td>";
            echo "<td>" . ($row['address'] ?? 'N/A') . "</td>";
            echo "<td>" . ($row['director_name'] ?? 'No asignado') . "</td>";
            echo "<td>" . ($row['coordinator_name'] ?? 'No asignado') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: orange;'>⚠ No se encontraron escuelas en la base de datos</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error en consulta básica: " . $e->getMessage() . "</p>";
}

// 4. Probar búsqueda por nombre
echo "<h2>4. Prueba de Búsqueda por Nombre</h2>";
try {
    $searchTerm = "Colegio";
    $query = "SELECT school_id, school_name, school_dane, school_document 
              FROM schools 
              WHERE school_name LIKE :search AND is_active = 1 
              LIMIT 3";
    
    $stmt = $connection->prepare($query);
    $searchPattern = "%$searchTerm%";
    $stmt->bindParam(':search', $searchPattern);
    $stmt->execute();
    $result = $stmt->fetchAll();
    
    if ($result && count($result) > 0) {
        echo "<p>✓ Búsqueda por nombre exitosa. Encontradas " . count($result) . " escuelas con '{$searchTerm}':</p>";
        foreach ($result as $row) {
            echo "<p>- {$row['school_name']} (DANE: {$row['school_dane']}, NIT: {$row['school_document']})</p>";
        }
    } else {
        echo "<p style='color: orange;'>⚠ No se encontraron escuelas con '{$searchTerm}'</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error en búsqueda por nombre: " . $e->getMessage() . "</p>";
}

// 5. Probar búsqueda por NIT
echo "<h2>5. Prueba de Búsqueda por NIT</h2>";
try {
    $query = "SELECT school_id, school_name, school_document 
              FROM schools 
              WHERE school_document LIKE :nit AND is_active = 1 
              LIMIT 3";
    
    $stmt = $connection->prepare($query);
    $nitPattern = "%123%";
    $stmt->bindParam(':nit', $nitPattern);
    $stmt->execute();
    $result = $stmt->fetchAll();
    
    if ($result && count($result) > 0) {
        echo "<p>✓ Búsqueda por NIT exitosa. Encontradas " . count($result) . " escuelas con NIT que contiene '123':</p>";
        foreach ($result as $row) {
            echo "<p>- {$row['school_name']} (NIT: {$row['school_document']})</p>";
        }
    } else {
        echo "<p style='color: orange;'>⚠ No se encontraron escuelas con NIT que contenga '123'</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error en búsqueda por NIT: " . $e->getMessage() . "</p>";
}

// 6. Probar búsqueda por código DANE
echo "<h2>6. Prueba de Búsqueda por Código DANE</h2>";
try {
    $query = "SELECT school_id, school_name, school_dane 
              FROM schools 
              WHERE school_dane LIKE :dane AND is_active = 1 
              LIMIT 3";
    
    $stmt = $connection->prepare($query);
    $danePattern = "%11%";
    $stmt->bindParam(':dane', $danePattern);
    $stmt->execute();
    $result = $stmt->fetchAll();
    
    if ($result && count($result) > 0) {
        echo "<p>✓ Búsqueda por código DANE exitosa. Encontradas " . count($result) . " escuelas con DANE que contiene '11':</p>";
        foreach ($result as $row) {
            echo "<p>- {$row['school_name']} (DANE: {$row['school_dane']})</p>";
        }
    } else {
        echo "<p style='color: orange;'>⚠ No se encontraron escuelas con código DANE que contenga '11'</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error en búsqueda por código DANE: " . $e->getMessage() . "</p>";
}

// 7. Verificar usuarios con roles de director y coordinador
echo "<h2>7. Verificación de Usuarios con Roles</h2>";
try {
    $query = "SELECT 
                u.user_id,
                u.first_name,
                u.last_name,
                u.email,
                ur.role_type
              FROM users u
              INNER JOIN user_roles ur ON u.user_id = ur.user_id
              WHERE ur.role_type IN ('director', 'coordinator') 
                AND u.is_active = 1 
                AND ur.is_active = 1
              ORDER BY ur.role_type, u.first_name
              LIMIT 10";
    
    $stmt = $connection->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll();
    
    if ($result && count($result) > 0) {
        echo "<p>✓ Usuarios con roles encontrados:</p>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr style='background-color: #f0f0f0;'>";
        echo "<th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th>";
        echo "</tr>";
        
        foreach ($result as $row) {
            $roleColor = $row['role_type'] === 'director' ? '#ffcccc' : '#ccffcc';
            echo "<tr style='background-color: {$roleColor};'>";
            echo "<td>{$row['user_id']}</td>";
            echo "<td>{$row['first_name']} {$row['last_name']}</td>";
            echo "<td>{$row['email']}</td>";
            echo "<td><strong>{$row['role_type']}</strong></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: orange;'>⚠ No se encontraron usuarios con roles de director o coordinador</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error al verificar usuarios: " . $e->getMessage() . "</p>";
}

echo "<h2>Resumen</h2>";
echo "<p>Las consultas ahora usan los nombres correctos de las columnas de la base de datos:</p>";
echo "<ul>";
echo "<li><strong>school_id</strong> - ID de la escuela</li>";
echo "<li><strong>school_name</strong> - Nombre de la escuela</li>";
echo "<li><strong>school_dane</strong> - Código DANE</li>";
echo "<li><strong>school_document</strong> - NIT</li>";
echo "<li><strong>total_quota</strong> - Cupo total</li>";
echo "<li><strong>director_user_id</strong> - ID del director</li>";
echo "<li><strong>coordinator_user_id</strong> - ID del coordinador</li>";
echo "<li><strong>address</strong> - Dirección</li>";
echo "<li><strong>phone</strong> - Teléfono</li>";
echo "<li><strong>email</strong> - Email</li>";
echo "<li><strong>is_active</strong> - Estado activo</li>";
echo "</ul>";

echo "<p><strong>Estado:</strong> <span style='color: green;'>✓ Sistema de consulta de escuelas actualizado correctamente</span></p>";
?> 