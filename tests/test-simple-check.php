<?php
echo "🧪 Test Simple del Sistema ByFrost\n";
echo "================================\n\n";

// 1. Verificar config.php
echo "1. Verificando config.php...\n";
if (file_exists('config.php')) {
    echo "   ✅ config.php existe\n";
    require_once 'config.php';
    
    if (defined('url')) {
        echo "   ✅ URL configurada: " . url . "\n";
    } else {
        echo "   ❌ URL no configurada\n";
    }
} else {
    echo "   ❌ config.php no existe\n";
}

// 2. Verificar controladores principales
echo "\n2. Verificando controladores...\n";
$controllers = [
    'app/controllers/MainController.php',
    'app/controllers/IndexController.php',
    'app/controllers/UserController.php'
];

foreach ($controllers as $controller) {
    if (file_exists($controller)) {
        echo "   ✅ $controller existe\n";
    } else {
        echo "   ❌ $controller no existe\n";
    }
}

// 3. Verificar modelos
echo "\n3. Verificando modelos...\n";
$models = [
    'app/models/UserModel.php',
    'app/models/MainModel.php'
];

foreach ($models as $model) {
    if (file_exists($model)) {
        echo "   ✅ $model existe\n";
    } else {
        echo "   ❌ $model no existe\n";
    }
}

// 4. Verificar archivos JS críticos
echo "\n4. Verificando archivos JavaScript...\n";
$jsFiles = [
    'app/resources/js/loadView.js',
    'app/resources/js/navigation/formHandler.js'
];

foreach ($jsFiles as $jsFile) {
    if (file_exists($jsFile)) {
        echo "   ✅ $jsFile existe\n";
        
        // Verificar contenido
        $content = file_get_contents($jsFile);
        if (strpos($content, 'function loadView') !== false) {
            echo "   ✅ $jsFile contiene función loadView\n";
        } else {
            echo "   ⚠️ $jsFile no contiene función loadView\n";
        }
    } else {
        echo "   ❌ $jsFile no existe\n";
    }
}

// 5. Verificar vistas de usuario
echo "\n5. Verificando vistas de usuario...\n";
$views = [
    'app/views/user/consultUser.php',
    'app/views/user/viewUser.php'
];

foreach ($views as $view) {
    if (file_exists($view)) {
        echo "   ✅ $view existe\n";
    } else {
        echo "   ❌ $view no existe\n";
    }
}

echo "\n🎉 Test Simple Completado\n";
echo "Si ves ✅, el sistema está bien configurado.\n";
echo "Si ves ❌, hay problemas que resolver.\n";
?> 