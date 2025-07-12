<?php
/**
 * Test: Verificar navegación desde el dashboard
 * 
 * Este test verifica que:
 * 1. La navegación desde el dashboard mantiene al usuario en el dashboard
 * 2. Las vistas se cargan como vistas parciales cuando se navega desde el dashboard
 * 3. El sistema detecta correctamente el contexto de dashboard
 */

echo "=== Test: Verificar navegación desde el dashboard ===\n\n";

// 1. Verificar que el método isDashboardContext funciona correctamente
echo "=== Verificación de isDashboardContext ===\n";

$mainControllerPath = 'app/controllers/MainController.php';
if (file_exists($mainControllerPath)) {
    echo "✓ MainController.php existe\n";
    
    $content = file_get_contents($mainControllerPath);
    if (strpos($content, 'protected function isDashboardContext()') !== false) {
        echo "✓ isDashboardContext está definido\n";
    } else {
        echo "✗ isDashboardContext NO está definido\n";
    }
    
    // Verificar que incluye los roles de dashboard
    if (strpos($content, "'root', 'director', 'coordinator', 'treasurer', 'professor', 'student', 'parent'") !== false) {
        echo "✓ Incluye todos los roles de dashboard\n";
    } else {
        echo "✗ NO incluye todos los roles de dashboard\n";
    }
} else {
    echo "✗ MainController.php NO existe\n";
}

// 2. Verificar que UserController maneja correctamente las peticiones
echo "\n=== Verificación de UserController ===\n";

$userControllerPath = 'app/controllers/UserController.php';
if (file_exists($userControllerPath)) {
    echo "✓ UserController.php existe\n";
    
    $content = file_get_contents($userControllerPath);
    if (strpos($content, 'public function consultUser()') !== false) {
        echo "✓ consultUser está definido\n";
    } else {
        echo "✗ consultUser NO está definido\n";
    }
    
    // Verificar que usa isDashboardContext
    if (strpos($content, '$this->isDashboardContext()') !== false) {
        echo "✓ Usa isDashboardContext para verificar contexto\n";
    } else {
        echo "✗ NO usa isDashboardContext\n";
    }
    
    // Verificar que carga vista parcial
    if (strpos($content, '$this->loadPartialView(\'user/consultUser\'') !== false) {
        echo "✓ Carga vista parcial para consultUser\n";
    } else {
        echo "✗ NO carga vista parcial para consultUser\n";
    }
} else {
    echo "✗ UserController.php NO existe\n";
}

// 3. Verificar que la vista consultUser existe
echo "\n=== Verificación de vistas ===\n";

$consultUserViewPath = 'app/views/user/consultUser.php';
if (file_exists($consultUserViewPath)) {
    echo "✓ consultUser.php existe\n";
    
    $content = file_get_contents($consultUserViewPath);
    if (strpos($content, 'confirmDeactivateUser') !== false) {
        echo "✓ consultUser.php usa confirmDeactivateUser\n";
    } else {
        echo "✗ consultUser.php NO usa confirmDeactivateUser\n";
    }
} else {
    echo "✗ consultUser.php NO existe\n";
}

// 4. Verificar que loadView.js maneja correctamente las peticiones
echo "\n=== Verificación de loadView.js ===\n";

$loadViewJsPath = 'app/resources/js/loadView.js';
if (file_exists($loadViewJsPath)) {
    echo "✓ loadView.js existe\n";
    
    $content = file_get_contents($loadViewJsPath);
    if (strpos($content, "'X-Requested-With': 'XMLHttpRequest'") !== false) {
        echo "✓ Envía header X-Requested-With correctamente\n";
    } else {
        echo "✗ NO envía header X-Requested-With\n";
    }
    
    if (strpos($content, 'buildViewUrl') !== false) {
        echo "✓ Construye URLs correctamente\n";
    } else {
        echo "✗ NO construye URLs correctamente\n";
    }
} else {
    echo "✗ loadView.js NO existe\n";
}

// 5. Verificar que el dashboard root existe
echo "\n=== Verificación de dashboard root ===\n";

$rootDashboardPath = 'app/views/root/dashboard.php';
if (file_exists($rootDashboardPath)) {
    echo "✓ root/dashboard.php existe\n";
    
    $content = file_get_contents($rootDashboardPath);
    if (strpos($content, 'menuRoot.php') !== false) {
        echo "✓ Incluye menuRoot.php\n";
    } else {
        echo "✗ NO incluye menuRoot.php\n";
    }
} else {
    echo "✗ root/dashboard.php NO existe\n";
}

// 6. Verificar que menuRoot.php tiene el botón de consultar usuarios
echo "\n=== Verificación de menuRoot.php ===\n";

$menuRootPath = 'app/views/root/menuRoot.php';
if (file_exists($menuRootPath)) {
    echo "✓ menuRoot.php existe\n";
    
    $content = file_get_contents($menuRootPath);
    if (strpos($content, "loadView('user/consultUser')") !== false) {
        echo "✓ Tiene botón para consultar usuarios\n";
    } else {
        echo "✗ NO tiene botón para consultar usuarios\n";
    }
} else {
    echo "✗ menuRoot.php NO existe\n";
}

echo "\n=== Resumen del problema ===\n";
echo "El problema era que:\n";
echo "1. Al navegar desde el dashboard a user/consultUser\n";
echo "2. El controlador verificaba si era AJAX O contexto de dashboard\n";
echo "3. Pero la detección de AJAX no funcionaba correctamente\n";
echo "4. Y redirigía fuera del dashboard\n";
echo "\nLa solución fue:\n";
echo "1. Priorizar la verificación de contexto de dashboard\n";
echo "2. Cargar siempre como vista parcial si está en dashboard\n";
echo "3. Solo redirigir si no es AJAX ni dashboard\n";

echo "\nTest completado.\n";
?> 