<?php
/**
 * Test AJAX para AssignRole
 */

echo "<h1>🔧 Test AJAX - AssignRole</h1>";

// Incluir dependencias
require_once '../config.php';
require_once '../app/library/SessionManager.php';
require_once '../app/scripts/connection.php';

$dbConn = getConnection();
$sessionManager = new SessionManager();

echo "<h2>1. Estado de la Sesión</h2>";
if (!$sessionManager->isLoggedIn()) {
    echo "<div class='alert alert-danger'>❌ No estás logueado</div>";
    echo "<p><a href='http://localhost:8000/?view=index&action=login' target='_blank'>Ir al Login</a></p>";
    exit;
} else {
    $user = $sessionManager->getCurrentUser();
    echo "<div class='alert alert-success'>✅ Logueado como: " . $user['email'] . " (Rol: " . $user['role'] . ")</div>";
    
    if (!$sessionManager->hasRole('root')) {
        echo "<div class='alert alert-danger'>❌ No tienes rol root</div>";
        exit;
    }
}

echo "<h2>2. Test de Endpoints AJAX</h2>";

// Simular peticiones AJAX
$endpoints = [
    [
        'name' => 'getUsersWithoutRole',
        'url' => 'http://localhost:8000/?view=user&action=getUsersWithoutRole',
        'method' => 'GET'
    ],
    [
        'name' => 'processAssignRole',
        'url' => 'http://localhost:8000/?view=user&action=processAssignRole',
        'method' => 'POST',
        'data' => [
            'user_id' => '1',
            'role_type' => 'student'
        ]
    ]
];

foreach ($endpoints as $endpoint) {
    echo "<h3>Test: " . $endpoint['name'] . "</h3>";
    echo "<div>URL: <code>" . $endpoint['url'] . "</code></div>";
    echo "<div>Método: <code>" . $endpoint['method'] . "</code></div>";
    
    if ($endpoint['method'] === 'POST' && isset($endpoint['data'])) {
        echo "<div>Datos: <code>" . json_encode($endpoint['data']) . "</code></div>";
    }
    
    echo "<div class='mt-2'>";
    echo "<button onclick='testEndpoint(\"" . $endpoint['name'] . "\", \"" . $endpoint['url'] . "\", \"" . $endpoint['method'] . "\", " . json_encode($endpoint['data'] ?? []) . ")' class='btn btn-primary btn-sm'>Probar Endpoint</button>";
    echo "<div id='result-" . $endpoint['name'] . "' class='mt-2'></div>";
    echo "</div>";
    echo "<hr>";
}

echo "<h2>3. Test Manual de UserController</h2>";
try {
    require_once '../app/controllers/UserController.php';
    $userController = new UserController($dbConn);
    
    echo "<div class='alert alert-success'>✅ UserController cargado</div>";
    
    // Test getUsersWithoutRole
    echo "<h4>Test getUsersWithoutRole:</h4>";
    try {
        // Simular petición AJAX
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        
        // Capturar output
        ob_start();
        $userController->getUsersWithoutRole();
        $output = ob_get_clean();
        
        echo "<div class='alert alert-info'>Respuesta:</div>";
        echo "<pre>" . htmlspecialchars($output) . "</pre>";
        
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>❌ Error: " . $e->getMessage() . "</div>";
    }
    
} catch (Exception $e) {
    echo "<div class='alert alert-danger'>❌ Error cargando UserController: " . $e->getMessage() . "</div>";
}

echo "<h2>4. Debug JavaScript</h2>";
echo "<div class='alert alert-info'>";
echo "<strong>Para debuggear JavaScript:</strong><br>";
echo "1. Abre las herramientas de desarrollador (F12)<br>";
echo "2. Ve a la pestaña Console<br>";
echo "3. Navega a AssignRole y revisa los errores<br>";
echo "4. Ve a la pestaña Network para ver las peticiones AJAX";
echo "</div>";

echo "<h2>5. URLs para probar</h2>";
echo "<div class='d-grid gap-2'>";
echo "<a href='http://localhost:8000/?view=user&action=assignRole' class='btn btn-primary' target='_blank'>AssignRole (con debug)</a>";
echo "<a href='test-assign-role-complete.php' class='btn btn-secondary'>Volver al diagnóstico</a>";
echo "</div>";

?>

<script>
function testEndpoint(name, url, method, data = {}) {
    const resultDiv = document.getElementById('result-' + name);
    resultDiv.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Probando...</div>';
    
    const options = {
        method: method,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    };
    
    if (method === 'POST' && Object.keys(data).length > 0) {
        const formData = new URLSearchParams();
        for (const [key, value] of Object.entries(data)) {
            formData.append(key, value);
        }
        options.body = formData.toString();
    }
    
    fetch(url, options)
        .then(response => {
            resultDiv.innerHTML = '<div class="alert alert-info">Status: ' + response.status + ' ' + response.statusText + '</div>';
            return response.text();
        })
        .then(data => {
            resultDiv.innerHTML += '<div class="alert alert-success">Respuesta:</div><pre>' + data + '</pre>';
        })
        .catch(error => {
            resultDiv.innerHTML = '<div class="alert alert-danger">Error: ' + error.message + '</div>';
        });
}
</script>

<style>
.btn {
    display: inline-block;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    text-align: center;
    text-decoration: none;
    vertical-align: middle;
    cursor: pointer;
    border: 1px solid transparent;
    border-radius: 0.375rem;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.btn-primary {
    color: #fff;
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.btn-secondary {
    color: #fff;
    background-color: #6c757d;
    border-color: #6c757d;
}

.btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
    border-radius: 0.25rem;
}

.alert {
    position: relative;
    padding: 1rem 1rem;
    margin-bottom: 1rem;
    border: 1px solid transparent;
    border-radius: 0.375rem;
}

.alert-success {
    color: #0f5132;
    background-color: #d1e7dd;
    border-color: #badbcc;
}

.alert-danger {
    color: #842029;
    background-color: #f8d7da;
    border-color: #f5c2c7;
}

.alert-info {
    color: #055160;
    background-color: #cff4fc;
    border-color: #b6effb;
}

.alert-warning {
    color: #664d03;
    background-color: #fff3cd;
    border-color: #ffecb5;
}

pre {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 0.375rem;
    overflow-x: auto;
}

.d-grid {
    display: grid;
}

.gap-2 {
    gap: 0.5rem;
}

.mt-2 {
    margin-top: 0.5rem;
}
</style> 