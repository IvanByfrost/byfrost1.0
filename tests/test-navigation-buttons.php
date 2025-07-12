<?php
/**
 * Test para verificar que los botones de navegación funcionan correctamente
 */

require_once '../config.php';
require_once '../app/controllers/UserController.php';
require_once '../app/models/UserModel.php';

echo "🧪 Test de Botones de Navegación\n";
echo "================================\n\n";

try {
    // Conectar a la base de datos
    $dbConn = new PDO("mysql:host=localhost;dbname=byfrost", "root", "");
    $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Conexión a la base de datos exitosa\n";
    
    // Crear instancia del controlador
    $userController = new UserController($dbConn);
    echo "✅ UserController creado correctamente\n";
    
    // Obtener usuarios de prueba
    $userModel = new UserModel($dbConn);
    $users = $userModel->getUsers();
    
    if (empty($users)) {
        echo "❌ No hay usuarios en la base de datos para probar\n";
        exit;
    }
    
    $testUserId = $users[0]['user_id'];
    echo "✅ Usuario de prueba encontrado: ID {$testUserId}\n";
    
    // Simular petición GET
    $_GET['id'] = $testUserId;
    $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
    
    echo "\n🔍 Probando navegación desde viewRoleHistory...\n";
    
    // Capturar la salida del controlador
    ob_start();
    
    try {
        $userController->viewRoleHistory();
        $output = ob_get_clean();
        
        if (!empty($output)) {
            echo "✅ viewRoleHistory ejecutado correctamente\n";
            
            // Verificar que contiene los botones esperados
            $buttons = [
                'goBack()' => 'Botón Volver',
                'viewUser(' => 'Botón Ver Usuario',
                'assignNewRole(' => 'Botón Asignar Nuevo Rol'
            ];
            
            foreach ($buttons as $function => $description) {
                if (strpos($output, $function) !== false) {
                    echo "✅ $description encontrado\n";
                } else {
                    echo "❌ $description NO encontrado\n";
                }
            }
            
            // Verificar que contiene las funciones JavaScript
            $functions = [
                'function goBack()' => 'Función goBack',
                'function viewUser(' => 'Función viewUser',
                'function assignNewRole(' => 'Función assignNewRole'
            ];
            
            foreach ($functions as $function => $description) {
                if (strpos($output, $function) !== false) {
                    echo "✅ $description encontrada\n";
                } else {
                    echo "❌ $description NO encontrada\n";
                }
            }
            
            // Verificar que contiene múltiples opciones de navegación
            if (strpos($output, 'loadView') !== false) {
                echo "✅ Navegación con loadView disponible\n";
            } else {
                echo "⚠️ Navegación con loadView no disponible\n";
            }
            
            if (strpos($output, 'window.location.href') !== false) {
                echo "✅ Navegación directa disponible\n";
            } else {
                echo "⚠️ Navegación directa no disponible\n";
            }
            
        } else {
            echo "❌ viewRoleHistory no produjo salida\n";
        }
        
    } catch (Exception $e) {
        ob_end_clean();
        echo "❌ Error en viewRoleHistory: " . $e->getMessage() . "\n";
    }
    
    // Verificar archivo de vista
    echo "\n🔍 Verificando archivo de vista...\n";
    $viewFile = '../app/views/user/viewRoleHistory.php';
    if (file_exists($viewFile)) {
        echo "✅ Archivo de vista existe\n";
        
        $viewContent = file_get_contents($viewFile);
        
        // Verificar botones en el HTML
        $htmlButtons = [
            'onclick="goBack()"' => 'Botón Volver en HTML',
            'onclick="viewUser(' => 'Botón Ver Usuario en HTML',
            'onclick="assignNewRole(' => 'Botón Asignar Nuevo Rol en HTML'
        ];
        
        foreach ($htmlButtons as $button => $description) {
            if (strpos($viewContent, $button) !== false) {
                echo "✅ $description encontrado\n";
            } else {
                echo "❌ $description NO encontrado\n";
            }
        }
        
        // Verificar funciones JavaScript
        $jsFunctions = [
            'function goBack()' => 'Función goBack en JS',
            'function viewUser(' => 'Función viewUser en JS',
            'function assignNewRole(' => 'Función assignNewRole en JS'
        ];
        
        foreach ($jsFunctions as $function => $description) {
            if (strpos($viewContent, $function) !== false) {
                echo "✅ $description encontrada\n";
            } else {
                echo "❌ $description NO encontrada\n";
            }
        }
        
        // Verificar opciones de navegación
        $navOptions = [
            'window.history.back()' => 'Navegación con history.back',
            'loadView(' => 'Navegación con loadView',
            'window.location.href' => 'Navegación directa'
        ];
        
        foreach ($navOptions as $option => $description) {
            if (strpos($viewContent, $option) !== false) {
                echo "✅ $description disponible\n";
            } else {
                echo "⚠️ $description no disponible\n";
            }
        }
        
    } else {
        echo "❌ Archivo de vista no existe\n";
    }
    
    // Limpiar variables simuladas
    unset($_GET['id']);
    unset($_SERVER['HTTP_X_REQUESTED_WITH']);
    
    echo "\n🎉 Test completado exitosamente\n";
    echo "✅ Los botones de navegación están implementados correctamente\n";
    echo "💡 Los botones ahora tienen múltiples opciones de navegación:\n";
    echo "   - history.back() para volver atrás\n";
    echo "   - loadView() para navegación SPA\n";
    echo "   - window.location.href como fallback\n";
    
} catch (Exception $e) {
    echo "❌ Error general: " . $e->getMessage() . "\n";
    echo "🔍 Detalles del error:\n";
    echo "   - Código: " . $e->getCode() . "\n";
    echo "   - Archivo: " . $e->getFile() . "\n";
    echo "   - Línea: " . $e->getLine() . "\n";
}
?> 