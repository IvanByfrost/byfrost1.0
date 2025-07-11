<?php
// Script de prueba para verificar la búsqueda de directores y coordinadores
require_once 'app/scripts/connection.php';
require_once 'app/models/UserModel.php';
require_once 'app/library/SessionManager.php';

echo "<h1>Prueba de Búsqueda de Directores y Coordinadores</h1>";

try {
    $dbConn = getConnection();
    $model = new UserModel($dbConn);
    $sessionManager = new SessionManager();
    
    // Simular un usuario director
    $currentUserRole = 'director';
    
    echo "<h2>1. Prueba de búsqueda de director por documento</h2>";
    
    // Buscar un director existente
    $testDocument = "12345678"; // Cambiar por un documento real
    
    try {
        $users = $model->searchUsersByRoleAndDocument('director', $testDocument, $currentUserRole);
        echo "<p><strong>Resultado:</strong> " . count($users) . " usuarios encontrados</p>";
        
        if (!empty($users)) {
            echo "<ul>";
            foreach ($users as $user) {
                echo "<li>" . $user['first_name'] . " " . $user['last_name'] . " - " . $user['email'] . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No se encontraron directores con el documento: $testDocument</p>";
        }
    } catch (Exception $e) {
        echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    }
    
    echo "<h2>2. Prueba de permisos - Director intentando buscar otros directores</h2>";
    
    try {
        $users = $model->searchUsersByRoleAndDocument('director', $testDocument, 'director');
        echo "<p><strong>Resultado:</strong> " . count($users) . " usuarios encontrados</p>";
    } catch (Exception $e) {
        echo "<p><strong>Error esperado:</strong> " . $e->getMessage() . "</p>";
    }
    
    echo "<h2>3. Prueba de búsqueda de coordinadores</h2>";
    
    try {
        $users = $model->searchUsersByRoleAndDocument('coordinator', $testDocument, $currentUserRole);
        echo "<p><strong>Resultado:</strong> " . count($users) . " usuarios encontrados</p>";
        
        if (!empty($users)) {
            echo "<ul>";
            foreach ($users as $user) {
                echo "<li>" . $user['first_name'] . " " . $user['last_name'] . " - " . $user['email'] . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No se encontraron coordinadores con el documento: $testDocument</p>";
        }
    } catch (Exception $e) {
        echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    }
    
    echo "<h2>4. Prueba de permisos - Coordinador intentando buscar otros coordinadores</h2>";
    
    try {
        $users = $model->searchUsersByRoleAndDocument('coordinator', $testDocument, 'coordinator');
        echo "<p><strong>Resultado:</strong> " . count($users) . " usuarios encontrados</p>";
    } catch (Exception $e) {
        echo "<p><strong>Error esperado:</strong> " . $e->getMessage() . "</p>";
    }
    
    echo "<h2>5. Prueba de parámetros vacíos</h2>";
    
    try {
        $users = $model->searchUsersByRoleAndDocument('', '', $currentUserRole);
        echo "<p><strong>Resultado:</strong> " . count($users) . " usuarios encontrados</p>";
    } catch (Exception $e) {
        echo "<p><strong>Error esperado:</strong> " . $e->getMessage() . "</p>";
    }
    
    echo "<h2>6. Prueba de búsqueda de profesores (permiso permitido)</h2>";
    
    try {
        $users = $model->searchUsersByRoleAndDocument('professor', $testDocument, $currentUserRole);
        echo "<p><strong>Resultado:</strong> " . count($users) . " usuarios encontrados</p>";
        
        if (!empty($users)) {
            echo "<ul>";
            foreach ($users as $user) {
                echo "<li>" . $user['first_name'] . " " . $user['last_name'] . " - " . $user['email'] . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No se encontraron profesores con el documento: $testDocument</p>";
        }
    } catch (Exception $e) {
        echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p><strong>Error de conexión:</strong> " . $e->getMessage() . "</p>";
}

echo "<h2>7. Información de la base de datos</h2>";

try {
    $query = "SELECT COUNT(*) as total FROM users u 
              INNER JOIN user_roles ur ON u.user_id = ur.user_id 
              WHERE ur.role_type = 'director' AND u.is_active = 1 AND ur.is_active = 1";
    $stmt = $dbConn->prepare($query);
    $stmt->execute();
    $directorCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    echo "<p><strong>Total de directores activos:</strong> $directorCount</p>";
    
    $query = "SELECT COUNT(*) as total FROM users u 
              INNER JOIN user_roles ur ON u.user_id = ur.user_id 
              WHERE ur.role_type = 'coordinator' AND u.is_active = 1 AND ur.is_active = 1";
    $stmt = $dbConn->prepare($query);
    $stmt->execute();
    $coordinatorCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    echo "<p><strong>Total de coordinadores activos:</strong> $coordinatorCount</p>";
    
    $query = "SELECT COUNT(*) as total FROM users u 
              INNER JOIN user_roles ur ON u.user_id = ur.user_id 
              WHERE ur.role_type = 'professor' AND u.is_active = 1 AND ur.is_active = 1";
    $stmt = $dbConn->prepare($query);
    $stmt->execute();
    $professorCount = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    
    echo "<p><strong>Total de profesores activos:</strong> $professorCount</p>";
    
} catch (Exception $e) {
    echo "<p><strong>Error al consultar estadísticas:</strong> " . $e->getMessage() . "</p>";
}

echo "<h2>8. Prueba de simulación de petición AJAX</h2>";

echo "<p>Para probar la funcionalidad AJAX completa, puedes:</p>";
echo "<ol>";
echo "<li>Ir al modal de crear escuela</li>";
echo "<li>Intentar buscar un director o coordinador</li>";
echo "<li>Verificar que los errores se muestran apropiadamente en el modal</li>";
echo "<li>Revisar la consola del navegador para logs detallados</li>";
echo "</ol>";

echo "<h2>9. Logs de debug</h2>";
echo "<p>Los logs de debug se pueden encontrar en:</p>";
echo "<ul>";
echo "<li>Error log del servidor web</li>";
echo "<li>Console del navegador (F12)</li>";
echo "<li>Network tab para ver las peticiones AJAX</li>";
echo "</ul>";
?> 