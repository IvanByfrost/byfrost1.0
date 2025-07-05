<?php
// Script para arreglar todos los archivos de payroll quitando sidebars
echo "<h1>🔧 Arreglando Sidebars de Payroll</h1>";

// Configurar variables
if (!defined('ROOT')) {
    define('ROOT', __DIR__);
}

$payrollFiles = [
    'app/views/payroll/employees.php',
    'app/views/payroll/periods.php',
    'app/views/payroll/absences.php',
    'app/views/payroll/overtime.php',
    'app/views/payroll/bonuses.php',
    'app/views/payroll/reports.php',
    'app/views/payroll/createEmployee.php',
    'app/views/payroll/createPeriod.php'
];

echo "<h2>Archivos a procesar:</h2>";
echo "<ul>";
foreach ($payrollFiles as $file) {
    $fullPath = ROOT . '/' . $file;
    $exists = file_exists($fullPath);
    echo "<li><strong>$file:</strong> " . ($exists ? "✅ EXISTE" : "❌ NO EXISTE") . "</li>";
}
echo "</ul>";

echo "<h2>Procesando archivos...</h2>";

foreach ($payrollFiles as $file) {
    $fullPath = ROOT . '/' . $file;
    
    if (!file_exists($fullPath)) {
        echo "<p>❌ Archivo no encontrado: $file</p>";
        continue;
    }
    
    echo "<h3>Procesando: $file</h3>";
    
    $content = file_get_contents($fullPath);
    $originalContent = $content;
    
    // 1. Arreglar sessionManager
    $content = preg_replace(
        '/if \(!isset\(\$this->sessionManager\) \|\| !\$this->sessionManager->isLoggedIn\(\)\)/',
        'if (!$sessionManager->isLoggedIn())',
        $content
    );
    
    $content = preg_replace(
        '/if \(!\$this->sessionManager->hasRole\(/',
        'if (!$sessionManager->hasRole(',
        $content
    );
    
    // 2. Quitar sidebar completo
    $sidebarPattern = '/<!-- Sidebar -->\s*<nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">\s*<div class="position-sticky pt-3">\s*<ul class="nav flex-column">\s*.*?\s*<\/ul>\s*<\/div>\s*<\/nav>\s*/s';
    $content = preg_replace($sidebarPattern, '', $content);
    
    // 3. Arreglar layout principal
    $content = preg_replace(
        '/<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">/',
        '<main class="col-12 px-4">',
        $content
    );
    
    // 4. Mejorar headers
    $headerPattern = '/<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">\s*<h1 class="h2">([^<]+)<\/h1>/';
    $content = preg_replace(
        $headerPattern,
        '<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-4 pb-3 mb-4 border-bottom">
                <div>
                    <h1 class="h2 mb-0">$1</h1>
                    <p class="text-muted mb-0">Administra la información del sistema</p>
                </div>',
        $content
    );
    
    // 5. Mejorar botones
    $content = preg_replace(
        '/<button type="button" class="btn btn-sm btn-outline-primary"/',
        '<button type="button" class="btn btn-primary"',
        $content
    );
    
    $content = preg_replace(
        '/<button type="button" class="btn btn-sm btn-outline-secondary"/',
        '<button type="button" class="btn btn-outline-secondary"',
        $content
    );
    
    // 6. Agregar botón de volver al dashboard
    $buttonGroupPattern = '/<div class="btn-group me-2">\s*(.*?)\s*<\/div>/s';
    $content = preg_replace(
        $buttonGroupPattern,
        '<div class="btn-group me-2">
                        $1
                        <button type="button" class="btn btn-outline-secondary" onclick="safeLoadView(\'payroll/dashboard\')">
                            <i class="fas fa-arrow-left"></i> Volver al Dashboard
                        </button>
                    </div>',
        $content
    );
    
    // Guardar archivo si hubo cambios
    if ($content !== $originalContent) {
        file_put_contents($fullPath, $content);
        echo "<p>✅ Archivo actualizado: $file</p>";
    } else {
        echo "<p>⚠️ No se encontraron cambios en: $file</p>";
    }
}

echo "<h2>✅ Proceso completado</h2>";
echo "<p>Se han procesado " . count($payrollFiles) . " archivos.</p>";

echo "<h2>Próximos pasos:</h2>";
echo "<ul>";
echo "<li><a href='../?view=payroll&action=dashboard'>Probar Dashboard</a></li>";
echo "<li><a href='../?view=payroll&action=employees'>Probar Empleados</a></li>";
echo "<li><a href='../?view=payroll&action=periods'>Probar Períodos</a></li>";
echo "</ul>";

echo "<h2>Cambios realizados:</h2>";
echo "<ul>";
echo "<li>✅ Quitados todos los sidebars</li>";
echo "<li>✅ Arreglado el layout para usar todo el ancho</li>";
echo "<li>✅ Corregido el uso de sessionManager</li>";
echo "<li>✅ Mejorados los headers con subtítulos</li>";
echo "<li>✅ Agregados botones de 'Volver al Dashboard'</li>";
echo "<li>✅ Mejorados los estilos de botones</li>";
echo "</ul>";
?> 