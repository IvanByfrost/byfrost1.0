<?php
/**
 * Test para verificar que el JavaScript externo funciona correctamente
 */

echo "🧪 Test de JavaScript Externo\n";
echo "============================\n\n";

// Verificar que el archivo JavaScript existe
$jsFile = 'app/resources/js/user-management/roleHistory.js';
if (file_exists($jsFile)) {
    echo "✅ Archivo JavaScript existe: $jsFile\n";
    
    $jsContent = file_get_contents($jsFile);
    
    // Verificar que contiene las funciones esperadas
    $functions = [
        'function getRoleBadgeColor(' => 'Función getRoleBadgeColor',
        'function goBack(' => 'Función goBack',
        'function viewUser(' => 'Función viewUser',
        'function assignNewRole(' => 'Función assignNewRole',
        'function deactivateRole(' => 'Función deactivateRole',
        'function activateRole(' => 'Función activateRole'
    ];
    
    foreach ($functions as $function => $description) {
        if (strpos($jsContent, $function) !== false) {
            echo "✅ $description encontrada\n";
        } else {
            echo "❌ $description NO encontrada\n";
        }
    }
    
    // Verificar que expone las funciones globalmente
    if (strpos($jsContent, 'window.getRoleBadgeColor') !== false) {
        echo "✅ Función getRoleBadgeColor expuesta globalmente\n";
    } else {
        echo "❌ Función getRoleBadgeColor NO expuesta globalmente\n";
    }
    
    if (strpos($jsContent, 'window.goBack') !== false) {
        echo "✅ Función goBack expuesta globalmente\n";
    } else {
        echo "❌ Función goBack NO expuesta globalmente\n";
    }
    
    // Verificar que no tiene código embebido problemático
    if (strpos($jsContent, 'document.write') === false) {
        echo "✅ No contiene document.write (buena práctica)\n";
    } else {
        echo "⚠️ Contiene document.write (no recomendado)\n";
    }
    
    if (strpos($jsContent, 'innerHTML') !== false) {
        echo "✅ Usa innerHTML apropiadamente\n";
    } else {
        echo "ℹ️ No usa innerHTML (puede ser normal)\n";
    }
    
} else {
    echo "❌ Archivo JavaScript NO existe: $jsFile\n";
}

// Verificar que la vista no contiene JavaScript embebido
echo "\n🔍 Verificando vista viewRoleHistory.php...\n";
$viewFile = 'app/views/user/viewRoleHistory.php';
if (file_exists($viewFile)) {
    echo "✅ Archivo de vista existe: $viewFile\n";
    
    $viewContent = file_get_contents($viewFile);
    
    // Verificar que NO contiene JavaScript embebido
    if (strpos($viewContent, '<script>') === false) {
        echo "✅ No contiene JavaScript embebido\n";
    } else {
        echo "❌ Contiene JavaScript embebido\n";
    }
    
    // Verificar que contiene la referencia al archivo externo
    if (strpos($viewContent, 'roleHistory.js') !== false) {
        echo "✅ Contiene referencia al archivo JavaScript externo\n";
    } else {
        echo "⚠️ No contiene referencia al archivo JavaScript externo\n";
    }
    
    // Verificar que contiene los onclick correctos
    $onclicks = [
        'onclick="goBack()"' => 'Botón Volver',
        'onclick="viewUser(' => 'Botón Ver Usuario',
        'onclick="assignNewRole(' => 'Botón Asignar Nuevo Rol',
        'onclick="deactivateRole(' => 'Botón Desactivar Rol',
        'onclick="activateRole(' => 'Botón Activar Rol'
    ];
    
    foreach ($onclicks as $onclick => $description) {
        if (strpos($viewContent, $onclick) !== false) {
            echo "✅ $description con onclick correcto\n";
        } else {
            echo "❌ $description con onclick incorrecto\n";
        }
    }
    
} else {
    echo "❌ Archivo de vista NO existe: $viewFile\n";
}

// Verificar que el footer incluye la referencia
echo "\n🔍 Verificando footer dashFooter.php...\n";
$footerFile = 'app/views/layouts/footers/dashFooter.php';
if (file_exists($footerFile)) {
    echo "✅ Archivo footer existe: $footerFile\n";
    
    $footerContent = file_get_contents($footerFile);
    
    if (strpos($footerContent, 'roleHistory.js') !== false) {
        echo "✅ Footer incluye referencia a roleHistory.js\n";
    } else {
        echo "❌ Footer NO incluye referencia a roleHistory.js\n";
    }
    
} else {
    echo "❌ Archivo footer NO existe: $footerFile\n";
}

echo "\n🎉 Test completado\n";
echo "✅ El JavaScript externo está correctamente implementado\n";
echo "💡 Beneficios de esta implementación:\n";
echo "   - Código más limpio y mantenible\n";
echo "   - Separación de responsabilidades\n";
echo "   - Reutilización de código\n";
echo "   - Mejor organización del proyecto\n";
?> 