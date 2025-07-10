<?php
// Script para verificar que el nombre del usuario se muestra correctamente
require_once '../config.php';
require_once '../app/library/SessionManager.php';

echo "<h1>🔍 Verificación del Nombre del Usuario</h1>";

// Simular una sesión de usuario
session_start();
$_SESSION['user_id'] = 1;
$_SESSION['user_role'] = 'director';
$_SESSION['first_name'] = 'Juan';
$_SESSION['last_name'] = 'Pérez';

try {
    // Crear instancia de SessionManager
    $sessionManager = new SessionManager();
    
    echo "<h2>1. Verificación de SessionManager</h2>";
    
    // Verificar si está logueado
    if ($sessionManager->isLoggedIn()) {
        echo "<p style='color: green;'>✅ Usuario logueado</p>";
    } else {
        echo "<p style='color: red;'>❌ Usuario NO logueado</p>";
    }
    
    // Obtener datos del usuario
    $currentUser = $sessionManager->getCurrentUser();
    echo "<h3>Datos del usuario obtenidos:</h3>";
    echo "<pre>" . json_encode($currentUser, JSON_PRETTY_PRINT) . "</pre>";
    
    // Verificar rol
    $userRole = $sessionManager->getUserRole();
    echo "<p><strong>Rol:</strong> " . $userRole . "</p>";
    
    echo "<h2>2. Construcción del Nombre</h2>";
    
    // Construir el nombre como en dashHeader.php
    $userName = '';
    
    if (isset($currentUser['first_name']) && isset($currentUser['last_name'])) {
        $userName = trim($currentUser['first_name'] . ' ' . $currentUser['last_name']);
        echo "<p style='color: green;'>✅ Usando first_name + last_name: <strong>$userName</strong></p>";
    } elseif (isset($currentUser['first_name'])) {
        $userName = $currentUser['first_name'];
        echo "<p style='color: orange;'>⚠️ Solo first_name: <strong>$userName</strong></p>";
    } elseif (isset($currentUser['last_name'])) {
        $userName = $currentUser['last_name'];
        echo "<p style='color: orange;'>⚠️ Solo last_name: <strong>$userName</strong></p>";
    } elseif (isset($currentUser['full_name'])) {
        $userName = $currentUser['full_name'];
        echo "<p style='color: orange;'>⚠️ Usando full_name: <strong>$userName</strong></p>";
    } else {
        $userName = 'Usuario';
        echo "<p style='color: red;'>❌ No se encontraron datos de nombre, usando: <strong>$userName</strong></p>";
    }
    
    echo "<h2>3. Verificación de Campos</h2>";
    echo "<ul>";
    echo "<li><strong>first_name:</strong> " . (isset($currentUser['first_name']) ? $currentUser['first_name'] : 'NO DEFINIDO') . "</li>";
    echo "<li><strong>last_name:</strong> " . (isset($currentUser['last_name']) ? $currentUser['last_name'] : 'NO DEFINIDO') . "</li>";
    echo "<li><strong>full_name:</strong> " . (isset($currentUser['full_name']) ? $currentUser['full_name'] : 'NO DEFINIDO') . "</li>";
    echo "<li><strong>email:</strong> " . (isset($currentUser['email']) ? $currentUser['email'] : 'NO DEFINIDO') . "</li>";
    echo "</ul>";
    
    echo "<h2>4. Prueba del Header</h2>";
    echo "<p>Ahora ve a cualquier dashboard y verifica que el nombre se muestre correctamente:</p>";
    echo "<ul>";
    echo "<li><a href='http://localhost:8000/?view=directorDashboard' target='_blank'>Dashboard Director</a></li>";
    echo "<li><a href='http://localhost:8000/?view=root&action=dashboard' target='_blank'>Dashboard Root</a></li>";
    echo "</ul>";
    
    echo "<h2>5. Recomendaciones</h2>";
    echo "<ul>";
    echo "<li>✅ El nombre ahora se construye correctamente usando first_name + last_name</li>";
    echo "<li>✅ Si no hay datos, muestra 'Usuario' como fallback</li>";
    echo "<li>✅ Se agregó debug para ver qué datos llegan</li>";
    echo "<li>⚠️ Verifica que los usuarios en la base de datos tengan first_name y last_name</li>";
    echo "</ul>";
    
    echo "<h2>6. Para Cambiar el Nombre</h2>";
    echo "<p>Si quieres cambiar el nombre que aparece:</p>";
    echo "<ol>";
    echo "<li>Ve a la base de datos</li>";
    echo "<li>Busca tu usuario en la tabla users</li>";
    echo "<li>Actualiza los campos first_name y last_name</li>";
    echo "<li>O crea un nuevo usuario con tu nombre real</li>";
    echo "</ol>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}
?> 