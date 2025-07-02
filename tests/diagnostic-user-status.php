<?php
require_once '../config.php';
require_once '../app/library/SessionManager.php';

echo "<h2>Diagnóstico de Estado del Usuario</h2>";

// Verificar conexión a la base de datos
try {
    require_once '../app/scripts/connection.php';
    $dbConn = getConnection();
    echo "<p>✅ Conexión a la base de datos: OK</p>";
} catch (PDOException $e) {
    echo "<p>❌ Error de conexión a la base de datos: " . $e->getMessage() . "</p>";
    exit;
}

// Iniciar sesión si no está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    echo "<p>✅ Sesión iniciada</p>";
} else {
    echo "<p>✅ Sesión ya activa</p>";
}

// Verificar SessionManager
try {
    $sessionManager = new SessionManager($dbConn);
    echo "<p>✅ SessionManager inicializado correctamente</p>";
} catch (Exception $e) {
    echo "<p>❌ Error al inicializar SessionManager: " . $e->getMessage() . "</p>";
    exit;
}

// Verificar si el usuario está logueado
if ($sessionManager->isLoggedIn()) {
    echo "<p>✅ Usuario logueado</p>";
    
    // Obtener información del usuario actual
    $currentUser = $sessionManager->getCurrentUser();
    echo "<h3>Información del Usuario Actual:</h3>";
    echo "<ul>";
    echo "<li><strong>ID:</strong> " . ($currentUser['user_id'] ?? 'No disponible') . "</li>";
    echo "<li><strong>Email:</strong> " . ($currentUser['email'] ?? 'No disponible') . "</li>";
    echo "<li><strong>Nombre:</strong> " . ($currentUser['name'] ?? 'No disponible') . "</li>";
    echo "<li><strong>Rol actual:</strong> " . ($currentUser['role'] ?? 'Sin rol asignado') . "</li>";
    echo "</ul>";
    
    // Verificar roles específicos
    echo "<h3>Verificación de Roles:</h3>";
    $roles = ['root', 'director', 'coordinator', 'teacher', 'student', 'parent', 'treasurer'];
    
    foreach ($roles as $role) {
        $hasRole = $sessionManager->hasRole($role);
        $status = $hasRole ? "✅" : "❌";
        echo "<p>$status Tiene rol '$role': " . ($hasRole ? 'SÍ' : 'NO') . "</p>";
    }
    
    // Verificar si tiene permisos de root
    if ($sessionManager->hasRole('root')) {
        echo "<p>🎉 <strong>Tienes permisos de root - deberías poder acceder a todas las funcionalidades</strong></p>";
    } else {
        echo "<p>⚠️ <strong>NO tienes permisos de root - algunas funcionalidades estarán restringidas</strong></p>";
        
        // Mostrar qué roles tienes
        $userRoles = [];
        foreach ($roles as $role) {
            if ($sessionManager->hasRole($role)) {
                $userRoles[] = $role;
            }
        }
        
        if (!empty($userRoles)) {
            echo "<p>Roles que tienes: " . implode(', ', $userRoles) . "</p>";
        } else {
            echo "<p>❌ No tienes ningún rol asignado</p>";
        }
    }
    
} else {
    echo "<p>❌ Usuario NO está logueado</p>";
    echo "<p>Para usar funcionalidades que requieren autenticación, necesitas:</p>";
    echo "<ol>";
    echo "<li>Ir a la página de login</li>";
    echo "<li>Iniciar sesión con una cuenta que tenga rol de root</li>";
    echo "<li>O contactar al administrador para asignar el rol de root a tu cuenta</li>";
    echo "</ol>";
}

// Verificar información de la sesión
echo "<h3>Información de la Sesión:</h3>";
echo "<ul>";
echo "<li><strong>Session ID:</strong> " . session_id() . "</li>";
echo "<li><strong>Session Name:</strong> " . session_name() . "</li>";
echo "<li><strong>Session Status:</strong> " . session_status() . "</li>";
echo "<li><strong>Session Save Path:</strong> " . session_save_path() . "</li>";
echo "</ul>";

// Mostrar variables de sesión (sin información sensible)
echo "<h3>Variables de Sesión:</h3>";
if (!empty($_SESSION)) {
    echo "<ul>";
    foreach ($_SESSION as $key => $value) {
        if (in_array($key, ['user_id', 'email', 'name', 'role'])) {
            echo "<li><strong>$key:</strong> " . htmlspecialchars($value) . "</li>";
        } else {
            echo "<li><strong>$key:</strong> [Oculto por seguridad]</li>";
        }
    }
    echo "</ul>";
} else {
    echo "<p>No hay variables de sesión</p>";
}

echo "<hr>";
echo "<h3>Recomendaciones:</h3>";
if ($sessionManager->isLoggedIn()) {
    if (!$sessionManager->hasRole('root')) {
        echo "<div style='background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 10px; border-radius: 5px;'>";
        echo "<p><strong>Para resolver el error de permisos:</strong></p>";
        echo "<ol>";
        echo "<li>Contacta al administrador del sistema</li>";
        echo "<li>Solicita que te asigne el rol de 'root' a tu cuenta</li>";
        echo "<li>O usa una cuenta que ya tenga rol de root</li>";
        echo "</ol>";
        echo "</div>";
    } else {
        echo "<div style='background-color: #d4edda; border: 1px solid #c3e6cb; padding: 10px; border-radius: 5px;'>";
        echo "<p><strong>✅ Tienes todos los permisos necesarios</strong></p>";
        echo "<p>Si sigues viendo errores de permisos, puede ser un problema temporal de sesión.</p>";
        echo "</div>";
    }
} else {
    echo "<div style='background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px;'>";
    echo "<p><strong>❌ Necesitas iniciar sesión primero</strong></p>";
    echo "<p>Ve a la página de login e inicia sesión con una cuenta válida.</p>";
    echo "</div>";
}
?> 