<?php
/**
 * Test para verificar el flujo de login y redirección
 */

// Definir constantes necesarias
if (!defined('ROOT')) {
    define('ROOT', dirname(__DIR__));
}

require_once ROOT . '/config.php';
require_once ROOT . '/app/scripts/connection.php';
require_once ROOT . '/app/library/SessionManager.php';

// Inicializar conexión y SessionManager
$dbConn = getConnection();
$sessionManager = new SessionManager();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test - Login y Redirección</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .test-section { margin: 20px 0; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .success { background-color: #d4edda; border-color: #c3e6cb; }
        .error { background-color: #f8d7da; border-color: #f5c6cb; }
        .info { background-color: #d1ecf1; border-color: #bee5eb; }
        .test-result { margin: 10px 0; padding: 10px; border-radius: 3px; }
        .btn { padding: 10px 20px; margin: 5px; border: none; border-radius: 5px; cursor: pointer; }
        .btn-primary { background: #007bff; color: white; }
        .btn-success { background: #28a745; color: white; }
        .btn-warning { background: #ffc107; color: black; }
    </style>
</head>
<body>
    <h1>🔐 Test - Login y Redirección</h1>
    
    <div class="test-section info">
        <h2>Estado Actual del Sistema</h2>
        <p><strong>URL Base:</strong> <?php echo url; ?></p>
        <p><strong>Usuario Logueado:</strong> <?php echo $sessionManager->isLoggedIn() ? 'Sí' : 'No'; ?></p>
        <?php if ($sessionManager->isLoggedIn()): ?>
            <p><strong>Rol:</strong> <?php echo $sessionManager->getUserRole(); ?></p>
            <p><strong>Usuario:</strong> <?php echo $sessionManager->getCurrentUser()['full_name'] ?? 'N/A'; ?></p>
        <?php endif; ?>
    </div>

    <div class="test-section">
        <h2>🔍 Verificar Usuarios en Base de Datos</h2>
        <?php
        try {
            $query = "SELECT u.credential_type, u.credential_number, u.email, r.role_type 
                      FROM users u 
                      JOIN user_roles r ON u.user_id = r.user_id 
                      WHERE r.is_active = 1 AND u.is_active = 1 
                      LIMIT 5";
            $stmt = $dbConn->prepare($query);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($users) {
                echo '<div class="test-result success">';
                echo '<h3>Usuarios disponibles para testing:</h3>';
                echo '<table border="1" style="border-collapse: collapse; width: 100%;">';
                echo '<tr><th>Tipo</th><th>Documento</th><th>Email</th><th>Rol</th></tr>';
                foreach ($users as $user) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($user['credential_type']) . '</td>';
                    echo '<td>' . htmlspecialchars($user['credential_number']) . '</td>';
                    echo '<td>' . htmlspecialchars($user['email']) . '</td>';
                    echo '<td>' . htmlspecialchars($user['role_type']) . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
                echo '</div>';
            } else {
                echo '<div class="test-result error">No se encontraron usuarios activos en la base de datos.</div>';
            }
        } catch (Exception $e) {
            echo '<div class="test-result error">Error al consultar usuarios: ' . htmlspecialchars($e->getMessage()) . '</div>';
        }
        ?>
    </div>

    <div class="test-section">
        <h2>🧪 Probar Flujo de Login</h2>
        <p>Usa uno de los usuarios de arriba para probar el login:</p>
        
        <form id="loginTestForm" style="max-width: 400px;">
            <div style="margin: 10px 0;">
                <label>Tipo de documento:</label>
                <select id="credType" name="credType" required style="width: 100%; padding: 8px;">
                    <option value="">Seleccione...</option>
                    <option value="CC">Cédula de ciudadanía</option>
                    <option value="TI">Tarjeta de identidad</option>
                    <option value="PP">Pasaporte</option>
                    <option value="CE">Cédula de extranjería</option>
                </select>
            </div>
            
            <div style="margin: 10px 0;">
                <label>Número de documento:</label>
                <input type="text" id="userDocument" name="userDocument" required style="width: 100%; padding: 8px;">
            </div>
            
            <div style="margin: 10px 0;">
                <label>Contraseña:</label>
                <input type="password" id="userPassword" name="userPassword" required style="width: 100%; padding: 8px;">
            </div>
            
            <button type="submit" class="btn btn-primary">🔐 Probar Login</button>
        </form>
        
        <div id="loginResult" style="margin-top: 15px;"></div>
    </div>

    <div class="test-section">
        <h2>🔗 URLs de Prueba</h2>
        <p><strong>Login:</strong> <a href="<?php echo url; ?>?view=login" target="_blank"><?php echo url; ?>?view=login</a></p>
        <p><strong>Charger (página de carga):</strong> <a href="<?php echo url; ?>app/views/index/charger.php" target="_blank"><?php echo url; ?>app/views/index/charger.php</a></p>
        <p><strong>Dashboard Root:</strong> <a href="<?php echo url; ?>?view=root&action=dashboard" target="_blank"><?php echo url; ?>?view=root&action=dashboard</a></p>
        <p><strong>Dashboard Coordinator:</strong> <a href="<?php echo url; ?>?view=coordinator&action=dashboard" target="_blank"><?php echo url; ?>?view=coordinator&action=dashboard</a></p>
    </div>

    <div class="test-section">
        <h2>🛠️ Acciones de Debug</h2>
        <button onclick="clearSession()" class="btn btn-warning">🗑️ Limpiar Sesión</button>
        <button onclick="checkSession()" class="btn btn-success">👁️ Ver Sesión Actual</button>
        <button onclick="testCharger()" class="btn btn-primary">⚡ Probar Charger</button>
    </div>

    <script>
        const ROOT = "<?php echo url; ?>";
        
        // Test de login
        document.getElementById('loginTestForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData();
            formData.append('subject', 'login');
            formData.append('credType', document.getElementById('credType').value);
            formData.append('userDocument', document.getElementById('userDocument').value);
            formData.append('userPassword', document.getElementById('userPassword').value);
            
            const resultDiv = document.getElementById('loginResult');
            resultDiv.innerHTML = '<div class="test-result info">🔄 Procesando login...</div>';
            
            fetch(ROOT + 'app/processes/loginProcess.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                console.log('Respuesta del login:', data);
                
                if (data.status === 'ok') {
                    resultDiv.innerHTML = `
                        <div class="test-result success">
                            <h3>✅ Login Exitoso</h3>
                            <p><strong>Mensaje:</strong> ${data.msg}</p>
                            <p><strong>Redirección:</strong> ${data.redirect}</p>
                            <button onclick="window.open('${data.redirect}', '_blank')" class="btn btn-success">
                                🔗 Abrir Página de Carga
                            </button>
                        </div>
                    `;
                } else if (data.status === 'not_registered') {
                    resultDiv.innerHTML = `
                        <div class="test-result error">
                            <h3>❌ Usuario No Registrado</h3>
                            <p>${data.msg}</p>
                            <button onclick="window.open('${data.redirect}', '_blank')" class="btn btn-warning">
                                📝 Ir a Registro
                            </button>
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div class="test-result error">
                            <h3>❌ Error en Login</h3>
                            <p>${data.msg}</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                resultDiv.innerHTML = `
                    <div class="test-result error">
                        <h3>❌ Error de Conexión</h3>
                        <p>No se pudo conectar con el servidor.</p>
                        <p>Error: ${error.message}</p>
                    </div>
                `;
            });
        });
        
        function clearSession() {
            fetch(ROOT + 'app/processes/outProcess.php')
            .then(() => {
                alert('Sesión limpiada. Recarga la página.');
                location.reload();
            })
            .catch(error => {
                console.error('Error al limpiar sesión:', error);
                alert('Error al limpiar sesión');
            });
        }
        
        function checkSession() {
            fetch(ROOT + '?view=login')
            .then(res => res.text())
            .then(html => {
                console.log('Respuesta de login:', html);
                alert('Revisa la consola para ver la respuesta del servidor');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al verificar sesión');
            });
        }
        
        function testCharger() {
            window.open(ROOT + 'app/views/index/charger.php', '_blank');
        }
    </script>
</body>
</html> 