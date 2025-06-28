<?php
// Test de la página no autorizada
require_once 'app/views/layouts/head.php';
require_once 'app/views/layouts/header.php';

echo "<h1>Test de Página No Autorizada</h1>";

// Simular datos de usuario
$user = [
    'id' => 1,
    'email' => 'test@byfrost.com',
    'role' => 'student',
    'name' => 'Juan',
    'lastname' => 'Pérez',
    'full_name' => 'Juan Pérez'
];

// Incluir el layout head

?>

<body>
    <div class="error-container text-center">
        <div class="error-content">
            <h1 class="error-title">403</h1>
            <h2 class="error-subtitle">Acceso No Autorizado</h2>
            <p class="error-message">
                No tienes permisos para acceder a esta página.
            </p>
            
            <div class="user-info">
                <p><strong>Usuario actual:</strong> <?= htmlspecialchars($user['full_name']) ?></p>
                <p><strong>Rol:</strong> <?= htmlspecialchars(ucfirst($user['role'])) ?></p>
            </div>
            
            <div class="error-actions">
                <a href="/" class="btn btn-primary">Ir al Inicio</a>
                <a href="/login" class="btn btn-secondary">Iniciar Sesión</a>
            </div>
        </div>
    </div>
</body>
</html> 