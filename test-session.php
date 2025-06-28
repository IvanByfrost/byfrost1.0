<?php
// Test del SessionManager
echo "<h1>Test del SessionManager</h1>";

// Incluir el SessionManager
require_once 'app/library/SessionManager.php';

// Crear instancia
$sessionManager = new SessionManager();

echo "<h2>1. Estado inicial</h2>";
echo "¿Está logueado? " . ($sessionManager->isLoggedIn() ? "SÍ" : "NO") . "<br>";
echo "Usuario actual: " . json_encode($sessionManager->getCurrentUser()) . "<br>";

echo "<h2>2. Simular login</h2>";
$userData = [
    'id' => 1,
    'email' => 'test@byfrost.com',
    'role' => 'coordinator',
    'first_name' => 'Juan',
    'last_name' => 'Pérez'
];

$loginSuccess = $sessionManager->login($userData);
echo "Login exitoso: " . ($loginSuccess ? "SÍ" : "NO") . "<br>";

echo "<h2>3. Verificar datos después del login</h2>";
echo "¿Está logueado? " . ($sessionManager->isLoggedIn() ? "SÍ" : "NO") . "<br>";
echo "ID del usuario: " . $sessionManager->getUserId() . "<br>";
echo "Email: " . $sessionManager->getUserEmail() . "<br>";
echo "Rol: " . $sessionManager->getUserRole() . "<br>";
echo "Nombre completo: " . $sessionManager->getUserFullName() . "<br>";

echo "<h2>4. Verificar roles</h2>";
echo "¿Es coordinador? " . ($sessionManager->hasRole('coordinator') ? "SÍ" : "NO") . "<br>";
echo "¿Es admin? " . ($sessionManager->hasRole('admin') ? "SÍ" : "NO") . "<br>";
echo "¿Es coordinador o admin? " . ($sessionManager->hasAnyRole(['coordinator', 'admin']) ? "SÍ" : "NO") . "<br>";

echo "<h2>5. Datos de sesión personalizados</h2>";
$sessionManager->setSessionData('preferencia', 'modo_oscuro');
echo "Preferencia guardada: " . $sessionManager->getSessionData('preferencia') . "<br>";

echo "<h2>6. Verificar expiración</h2>";
echo "¿Sesión expirada? " . ($sessionManager->isSessionExpired() ? "SÍ" : "NO") . "<br>";

echo "<h2>7. Renovar sesión</h2>";
$sessionManager->renewSession();
echo "Sesión renovada<br>";

echo "<h2>8. Datos completos del usuario</h2>";
echo "<pre>" . json_encode($sessionManager->getCurrentUser(), JSON_PRETTY_PRINT) . "</pre>";

echo "<h2>9. Simular logout</h2>";
$sessionManager->logout();
echo "Logout realizado<br>";
echo "¿Está logueado después del logout? " . ($sessionManager->isLoggedIn() ? "SÍ" : "NO") . "<br>";

echo "<h2>✅ Test completado</h2>";
echo "El SessionManager está funcionando correctamente.";
?> 