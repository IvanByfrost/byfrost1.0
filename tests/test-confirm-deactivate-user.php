<?php
/**
 * Test: Verificar que confirmDeactivateUser esté disponible
 * 
 * Este test verifica que:
 * 1. La función confirmDeactivateUser esté definida en userManagement.js
 * 2. El archivo userManagement.js esté incluido en el footer
 * 3. La función esté disponible cuando se carga la vista consultUser
 */

echo "=== Test: Verificar confirmDeactivateUser ===\n\n";

// 1. Verificar que userManagement.js existe
$userManagementJsPath = 'app/resources/js/user-management/userManagement.js';
if (file_exists($userManagementJsPath)) {
    echo "✓ userManagement.js existe\n";
    
    // Verificar que contiene la función confirmDeactivateUser
    $content = file_get_contents($userManagementJsPath);
    if (strpos($content, 'function confirmDeactivateUser') !== false) {
        echo "✓ confirmDeactivateUser está definida en userManagement.js\n";
    } else {
        echo "✗ confirmDeactivateUser NO está definida en userManagement.js\n";
    }
} else {
    echo "✗ userManagement.js NO existe\n";
}

// 2. Verificar que userManagement.js esté incluido en el footer
$footerPath = 'app/views/layouts/footers/dashFooter.php';
if (file_exists($footerPath)) {
    echo "✓ dashFooter.php existe\n";
    
    $footerContent = file_get_contents($footerPath);
    if (strpos($footerContent, 'userManagement.js') !== false) {
        echo "✓ userManagement.js está incluido en dashFooter.php\n";
    } else {
        echo "✗ userManagement.js NO está incluido en dashFooter.php\n";
    }
} else {
    echo "✗ dashFooter.php NO existe\n";
}

// 3. Verificar que las vistas que usan confirmDeactivateUser existan
$viewsToCheck = [
    'app/views/user/consultUser.php',
    'app/views/user/viewUser.php'
];

foreach ($viewsToCheck as $viewPath) {
    if (file_exists($viewPath)) {
        echo "✓ $viewPath existe\n";
        
        $viewContent = file_get_contents($viewPath);
        if (strpos($viewContent, 'confirmDeactivateUser') !== false) {
            echo "✓ $viewPath usa confirmDeactivateUser\n";
        } else {
            echo "✗ $viewPath NO usa confirmDeactivateUser\n";
        }
    } else {
        echo "✗ $viewPath NO existe\n";
    }
}

// 4. Verificar que la función esté disponible globalmente
echo "\n=== Verificación de disponibilidad global ===\n";

// Simular la carga de userManagement.js
if (file_exists($userManagementJsPath)) {
    $jsContent = file_get_contents($userManagementJsPath);
    
    // Verificar que la función esté definida correctamente
    if (preg_match('/function\s+confirmDeactivateUser\s*\([^)]*\)\s*\{/', $jsContent)) {
        echo "✓ confirmDeactivateUser está definida correctamente\n";
    } else {
        echo "✗ confirmDeactivateUser NO está definida correctamente\n";
    }
    
    // Verificar que use showConfirm
    if (strpos($jsContent, 'showConfirm') !== false) {
        echo "✓ confirmDeactivateUser usa showConfirm\n";
    } else {
        echo "✗ confirmDeactivateUser NO usa showConfirm\n";
    }
    
    // Verificar que use loadView
    if (strpos($jsContent, 'loadView') !== false) {
        echo "✓ confirmDeactivateUser usa loadView\n";
    } else {
        echo "✗ confirmDeactivateUser NO usa loadView\n";
    }
}

echo "\n=== Resumen ===\n";
echo "La función confirmDeactivateUser debe estar disponible cuando:\n";
echo "1. Se carga userManagement.js desde dashFooter.php\n";
echo "2. Se navega a user/consultUser o user/viewUser\n";
echo "3. Se hace clic en el botón de desactivar usuario\n";

echo "\nTest completado.\n";
?> 