<?php
// Script simple para probar acceso básico
echo "<h1>🧪 Prueba de Acceso Simple</h1>";

// 1. Probar configuración básica
echo "<h2>1. Configuración Básica</h2>";
try {
    require_once 'config.php';
    echo "<p style='color: green;'>✅ config.php cargado</p>";
    echo "<p>ROOT: " . ROOT . "</p>";
    echo "<p>URL: " . url . "</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error en config.php: " . $e->getMessage() . "</p>";
}

// 2. Probar conexión a BD
echo "<h2>2. Base de Datos</h2>";
try {
    require_once 'app/scripts/connection.php';
    $dbConn = getConnection();
    echo "<p style='color: green;'>✅ Conexión a BD exitosa</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error de BD: " . $e->getMessage() . "</p>";
}

// 3. Probar clase Views
echo "<h2>3. Clase Views</h2>";
try {
    require_once 'app/library/Views.php';
    $view = new Views();
    echo "<p style='color: green;'>✅ Clase Views cargada</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error en Views: " . $e->getMessage() . "</p>";
}

// 4. Probar autocarga
echo "<h2>4. Autocarga</h2>";
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
}

// 5. Probar router
echo "<h2>5. Router</h2>";
try {
    if (file_exists('app/scripts/routerView.php')) {
        echo "<p style='color: green;'>✅ routerView.php existe</p>";
    } else {
        echo "<p style='color: red;'>❌ routerView.php no existe</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error verificando router: " . $e->getMessage() . "</p>";
}

// 6. URLs de prueba
echo "<h2>6. URLs de Prueba</h2>";
$testUrls = [
    'Página Principal' => 'http://localhost:8000/',
    'Login' => 'http://localhost:8000/?view=index&action=login',
    'Dashboard' => 'http://localhost:8000/?view=root&action=dashboard',
    'Asignar Roles' => 'http://localhost:8000/?view=user&action=assignRole'
];

foreach ($testUrls as $name => $url) {
    echo "<p><strong>$name:</strong> <a href='$url' target='_blank'>$url</a></p>";
}

echo "<h2>🚀 Instrucciones</h2>";
echo "<ol>";
echo "<li>Primero, asegúrate de que el servidor esté corriendo: <code>php -S localhost:8000</code></li>";
echo "<li>Prueba cada URL de arriba una por una</li>";
echo "<li>Si alguna falla, revisa la consola del navegador (F12)</li>";
echo "<li>Si hay errores de PHP, revisa la terminal donde corre el servidor</li>";
echo "</ol>";

echo "<h2>🔧 Comando para Iniciar Servidor</h2>";
echo "<p>Ejecuta este comando en la terminal desde el directorio del proyecto:</p>";
echo "<code style='background: #f0f0f0; padding: 10px; display: block;'>php -S localhost:8000</code>";
?> 