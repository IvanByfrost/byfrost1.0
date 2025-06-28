<?php
// Script para probar el flujo completo del sistema
echo "<h1>🔄 Prueba de Flujo Completo</h1>";

// Activar reporte de errores para debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>📋 Paso 1: Verificar Configuración</h2>";
try {
    // Definir ROOT si no está definido
    if (!defined('ROOT')) {
        define('ROOT', __DIR__);
    }
    
    require_once 'config.php';
    echo "<p style='color: green;'>✅ Configuración cargada</p>";
    echo "<p>ROOT: " . ROOT . "</p>";
    echo "<p>URL: " . url . "</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error en configuración: " . $e->getMessage() . "</p>";
    exit;
}

echo "<h2>📋 Paso 2: Verificar Conexión a BD</h2>";
try {
    require_once 'app/scripts/connection.php';
    $dbConn = getConnection();
    echo "<p style='color: green;'>✅ Conexión a BD exitosa</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error de BD: " . $e->getMessage() . "</p>";
    echo "<p><strong>Posibles soluciones:</strong></p>";
    echo "<ul>";
    echo "<li>Verifica que MySQL esté corriendo</li>";
    echo "<li>Verifica que la base de datos 'baldur_db' exista</li>";
    echo "<li>Verifica las credenciales en connection.php</li>";
    echo "</ul>";
    exit;
}

echo "<h2>📋 Paso 3: Verificar Clases</h2>";
try {
    require_once 'app/library/Views.php';
    $view = new Views();
    echo "<p style='color: green;'>✅ Clase Views cargada</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error en Views: " . $e->getMessage() . "</p>";
    exit;
}

echo "<h2>📋 Paso 4: Verificar Autocarga</h2>";
try {
    spl_autoload_register(function ($class) {
        $paths = ['library', 'controllers', 'models'];
        foreach ($paths as $folder) {
            $file = ROOT . "/app/$folder/$class.php";
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    });
    echo "<p style='color: green;'>✅ Autocarga configurada</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error en autocarga: " . $e->getMessage() . "</p>";
    exit;
}

echo "<h2>📋 Paso 5: Verificar Controladores</h2>";
$controllers = [
    'RootController',
    'UserController',
    'IndexController',
    'ErrorController'
];

foreach ($controllers as $controller) {
    $file = ROOT . "/app/controllers/{$controller}.php";
    if (file_exists($file)) {
        echo "<p style='color: green;'>✅ $controller existe</p>";
    } else {
        echo "<p style='color: red;'>❌ $controller no existe</p>";
    }
}

echo "<h2>📋 Paso 6: Verificar Vistas</h2>";
$views = [
    'app/views/root/dashboard.php',
    'app/views/user/assignRole.php',
    'app/views/index/login.php',
    'app/views/layouts/head.php',
    'app/views/layouts/footer.php'
];

foreach ($views as $view) {
    if (file_exists($view)) {
        echo "<p style='color: green;'>✅ $view existe</p>";
    } else {
        echo "<p style='color: red;'>❌ $view no existe</p>";
    }
}

echo "<h2>📋 Paso 7: Verificar JavaScript</h2>";
$jsFiles = [
    'app/resources/js/loadView.js',
    'app/resources/js/assignRole.js'
];

foreach ($jsFiles as $js) {
    if (file_exists($js)) {
        echo "<p style='color: green;'>✅ $js existe</p>";
    } else {
        echo "<p style='color: red;'>❌ $js no existe</p>";
    }
}

echo "<h2>📋 Paso 8: Simular Router</h2>";
try {
    // Simular parámetros GET
    $_GET['view'] = 'index';
    $_GET['action'] = 'login';
    
    // Verificar que routerView.php existe
    if (file_exists('app/scripts/routerView.php')) {
        echo "<p style='color: green;'>✅ routerView.php existe</p>";
        
        // Verificar que SecurityMiddleware existe
        if (file_exists('app/library/SecurityMiddleware.php')) {
            echo "<p style='color: green;'>✅ SecurityMiddleware existe</p>";
        } else {
            echo "<p style='color: red;'>❌ SecurityMiddleware no existe</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ routerView.php no existe</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error verificando router: " . $e->getMessage() . "</p>";
}

echo "<h2>🚀 URLs de Prueba</h2>";
echo "<div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 10px; margin: 20px 0;'>";

$testUrls = [
    '🏠 Página Principal' => 'http://localhost:8000/',
    '🔐 Login' => 'http://localhost:8000/?view=index&action=login',
    '📊 Dashboard' => 'http://localhost:8000/?view=root&action=dashboard',
    '👥 Asignar Roles' => 'http://localhost:8000/?view=user&action=assignRole'
];

foreach ($testUrls as $name => $url) {
    echo "<div style='border: 1px solid #ddd; padding: 15px; border-radius: 5px;'>";
    echo "<h3>$name</h3>";
    echo "<a href='$url' target='_blank' style='color: #007bff; text-decoration: none;'>$url</a>";
    echo "</div>";
}

echo "</div>";

echo "<h2>🔧 Comandos Importantes</h2>";
echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
echo "<h3>Para iniciar el servidor:</h3>";
echo "<code style='background: #e9ecef; padding: 5px; border-radius: 3px;'>php -S localhost:8000</code>";
echo "<br><br>";
echo "<h3>Para verificar que el servidor esté corriendo:</h3>";
echo "<code style='background: #e9ecef; padding: 5px; border-radius: 3px;'>curl http://localhost:8000/</code>";
echo "</div>";

echo "<h2>📝 Checklist Final</h2>";
echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
echo "<ol>";
echo "<li><strong>¿El servidor está corriendo?</strong> Ejecuta: <code>php -S localhost:8000</code></li>";
echo "<li><strong>¿Puedes acceder a http://localhost:8000/?</strong></li>";
echo "<li><strong>¿No hay errores en la consola del navegador?</strong> (F12 → Console)</li>";
echo "<li><strong>¿No hay errores en la terminal del servidor?</strong></li>";
echo "<li><strong>¿La base de datos está conectada?</strong></li>";
echo "</ol>";
echo "</div>";

echo "<h2>🚨 Si Nada Funciona</h2>";
echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
echo "<p><strong>Pasos de emergencia:</strong></p>";
echo "<ol>";
echo "<li>Verifica que PHP esté instalado: <code>php --version</code></li>";
echo "<li>Verifica que MySQL esté corriendo</li>";
echo "<li>Verifica que estés en el directorio correcto del proyecto</li>";
echo "<li>Revisa los logs de error de PHP</li>";
echo "<li>Prueba con un servidor web como XAMPP en lugar del servidor PHP built-in</li>";
echo "</ol>";
echo "</div>";
?> 