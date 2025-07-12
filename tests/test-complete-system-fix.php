<?php
/**
 * Test Completo del Sistema - ByFrost
 * Verifica el estado del sistema después de las correcciones
 */

require_once '../config.php';
require_once '../app/controllers/MainController.php';
require_once '../app/controllers/IndexController.php';
require_once '../app/controllers/UserController.php';
require_once '../app/models/UserModel.php';

echo "<h1>🧪 Test Completo del Sistema ByFrost</h1>\n";
echo "<h2>Verificando correcciones aplicadas...</h2>\n";

// 1. Verificar que los controladores se cargan correctamente
echo "<h3>1. ✅ Verificación de Controladores</h3>\n";

try {
    $dbConn = new PDO("mysql:host=localhost;dbname=byfrost", "root", "");
    echo "   ✅ Conexión a base de datos exitosa\n";
    
    $indexController = new IndexController($dbConn);
    echo "   ✅ IndexController cargado correctamente\n";
    
    $userController = new UserController($dbConn);
    echo "   ✅ UserController cargado correctamente\n";
    
} catch (Exception $e) {
    echo "   ❌ Error cargando controladores: " . $e->getMessage() . "\n";
}

// 2. Verificar métodos loadPartial
echo "<h3>2. ✅ Verificación de Métodos loadPartial</h3>\n";

try {
    // Verificar que el método existe en UserController
    if (method_exists($userController, 'loadPartial')) {
        echo "   ✅ Método loadPartial existe en UserController\n";
    } else {
        echo "   ❌ Método loadPartial NO existe en UserController\n";
    }
    
    // Verificar que el método existe en IndexController
    if (method_exists($indexController, 'loadPartial')) {
        echo "   ✅ Método loadPartial existe en IndexController\n";
    } else {
        echo "   ❌ Método loadPartial NO existe en IndexController\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Error verificando métodos: " . $e->getMessage() . "\n";
}

// 3. Verificar UserModel y método getRoleHistory
echo "<h3>3. ✅ Verificación de UserModel</h3>\n";

try {
    $userModel = new UserModel($dbConn);
    echo "   ✅ UserModel cargado correctamente\n";
    
    if (method_exists($userModel, 'getRoleHistory')) {
        echo "   ✅ Método getRoleHistory existe en UserModel\n";
        
        // Probar el método con un usuario de prueba
        $users = $userModel->getUsers();
        if (!empty($users)) {
            $testUserId = $users[0]['user_id'];
            $roleHistory = $userModel->getRoleHistory($testUserId);
            echo "   ✅ Método getRoleHistory funciona correctamente\n";
            echo "   📊 Historial de roles encontrado: " . count($roleHistory) . " registros\n";
        } else {
            echo "   ⚠️ No hay usuarios para probar getRoleHistory\n";
        }
    } else {
        echo "   ❌ Método getRoleHistory NO existe en UserModel\n";
    }
    
} catch (Exception $e) {
    echo "   ❌ Error verificando UserModel: " . $e->getMessage() . "\n";
}

// 4. Verificar archivos JavaScript
echo "<h3>4. ✅ Verificación de Archivos JavaScript</h3>\n";

$jsFiles = [
    'app/resources/js/loadView.js',
    'app/resources/js/navigation/formHandler.js',
    'app/resources/js/utils/onlyNumber.js',
    'app/resources/js/utils/toggles.js',
    'app/resources/js/utils/sessionHandler.js',
    'app/resources/js/utils/Uploadpicture.js',
    'app/resources/js/utils/sidebarToggle.js'
];

foreach ($jsFiles as $file) {
    if (file_exists($file)) {
        echo "   ✅ $file existe\n";
        
        // Verificar que no tiene imports problemáticos
        $content = file_get_contents($file);
        if (strpos($content, 'import ') !== false) {
            echo "   ⚠️ $file contiene imports (verificar si es módulo)\n";
        } else {
            echo "   ✅ $file no tiene imports problemáticos\n";
        }
    } else {
        echo "   ❌ $file NO existe\n";
    }
}

// 5. Verificar vistas de usuario
echo "<h3>5. ✅ Verificación de Vistas de Usuario</h3>\n";

$userViews = [
    'app/views/user/consultUser.php',
    'app/views/user/viewUser.php',
    'app/views/user/editUser.php',
    'app/views/user/createUser.php'
];

foreach ($userViews as $view) {
    if (file_exists($view)) {
        echo "   ✅ $view existe\n";
    } else {
        echo "   ❌ $view NO existe\n";
    }
}

// 6. Simular petición AJAX a loadPartial
echo "<h3>6. ✅ Simulación de Petición AJAX</h3>\n";

try {
    // Simular petición AJAX
    $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
    $_GET['view'] = 'user';
    $_GET['action'] = 'loadPartial';
    $_GET['partialView'] = 'consultUser';
    
    // Verificar que isAjaxRequest funciona
    if ($userController->isAjaxRequest()) {
        echo "   ✅ isAjaxRequest detecta correctamente peticiones AJAX\n";
    } else {
        echo "   ❌ isAjaxRequest NO detecta peticiones AJAX\n";
    }
    
    // Limpiar variables simuladas
    unset($_SERVER['HTTP_X_REQUESTED_WITH']);
    unset($_GET['view']);
    unset($_GET['action']);
    unset($_GET['partialView']);
    
} catch (Exception $e) {
    echo "   ❌ Error en simulación AJAX: " . $e->getMessage() . "\n";
}

// 7. Verificar configuración de URL
echo "<h3>7. ✅ Verificación de Configuración</h3>\n";

if (defined('url')) {
    echo "   ✅ Constante 'url' definida: " . url . "\n";
} else {
    echo "   ❌ Constante 'url' NO definida\n";
}

if (defined('ROOT')) {
    echo "   ✅ Constante 'ROOT' definida: " . ROOT . "\n";
} else {
    echo "   ❌ Constante 'ROOT' NO definida\n";
}

// 8. Verificar errores de sintaxis en controladores
echo "<h3>8. ✅ Verificación de Errores de Sintaxis</h3>\n";

$controllers = [
    'app/controllers/IndexController.php',
    'app/controllers/UserController.php',
    'app/controllers/PayrollController.php',
    'app/controllers/coordinatorController.php',
    'app/controllers/activityController.php'
];

foreach ($controllers as $controller) {
    if (file_exists($controller)) {
        $content = file_get_contents($controller);
        
        // Verificar errores comunes
        $errors = [];
        
        if (strpos($content, '_POST[') !== false) {
            $errors[] = 'Usa $_POST en lugar de _POST';
        }
        
        if (strpos($content, '_GET[') !== false) {
            $errors[] = 'Usa $_GET en lugar de _GET';
        }
        
        if (empty($errors)) {
            echo "   ✅ $controller sin errores de sintaxis detectados\n";
        } else {
            echo "   ❌ $controller tiene errores: " . implode(', ', $errors) . "\n";
        }
    } else {
        echo "   ❌ $controller NO existe\n";
    }
}

echo "<h2>🎉 Test Completo Finalizado</h2>\n";
echo "<p>Si todos los checks muestran ✅, el sistema debería funcionar correctamente.</p>\n";
echo "<p>Si hay ❌, revisar los errores específicos mencionados.</p>\n";

?> 