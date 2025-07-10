<?php
/**
 * Test de Nomenclatura Completa - ByFrost
 * 
 * Verifica que todos los archivos sigan la convención de nomenclatura correcta:
 * - Controladores: NombreController.php y class NombreController
 * - Modelos: NombreModel.php y class NombreModel  
 * - Vistas: carpetas PascalCase, archivos camelCase
 * - Referencias: require_once actualizados
 */

require_once 'config.php';

echo "<h1>🔍 Test de Nomenclatura Completa - ByFrost</h1>";
echo "<p><strong>Fecha:</strong> " . date('Y-m-d H:i:s') . "</p>";

// =============================================
// 1. VERIFICAR CONTROLADORES
// =============================================

echo "<h2>📁 1. Verificación de Controladores</h2>";

$controllersDir = ROOT . '/app/controllers/';
$controllers = scandir($controllersDir);
$controllerIssues = [];

foreach ($controllers as $file) {
    if ($file === '.' || $file === '..') continue;
    if (pathinfo($file, PATHINFO_EXTENSION) !== 'php') continue;
    
    $className = pathinfo($file, PATHINFO_FILENAME);
    $filePath = $controllersDir . $file;
    $content = file_get_contents($filePath);
    
    // Verificar que el archivo siga la convención PascalCase
    $expectedFileName = ucfirst($className) . '.php';
    if ($file !== $expectedFileName) {
        $controllerIssues[] = [
            'type' => 'filename',
            'file' => $file,
            'expected' => $expectedFileName,
            'message' => "Archivo debería llamarse: $expectedFileName"
        ];
    }
    
    // Verificar que la clase siga la convención PascalCase
    if (preg_match('/class\s+(\w+)/', $content, $matches)) {
        $classInFile = $matches[1];
        $expectedClassName = ucfirst($className);
        
        if ($classInFile !== $expectedClassName) {
            $controllerIssues[] = [
                'type' => 'classname',
                'file' => $file,
                'class' => $classInFile,
                'expected' => $expectedClassName,
                'message' => "Clase debería llamarse: $expectedClassName"
            ];
        }
    }
}

if (empty($controllerIssues)) {
    echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<h3>✅ Controladores: Nomenclatura correcta</h3>";
    echo "</div>";
} else {
    echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<h3>⚠️ Controladores: Problemas encontrados</h3>";
    echo "<ul>";
    foreach ($controllerIssues as $issue) {
        echo "<li><strong>{$issue['file']}</strong>: {$issue['message']}</li>";
    }
    echo "</ul>";
    echo "</div>";
}

// =============================================
// 2. VERIFICAR MODELOS
// =============================================

echo "<h2>📁 2. Verificación de Modelos</h2>";

$modelsDir = ROOT . '/app/models/';
$models = scandir($modelsDir);
$modelIssues = [];

foreach ($models as $file) {
    if ($file === '.' || $file === '..') continue;
    if (pathinfo($file, PATHINFO_EXTENSION) !== 'php') continue;
    
    $className = pathinfo($file, PATHINFO_FILENAME);
    $filePath = $modelsDir . $file;
    $content = file_get_contents($filePath);
    
    // Verificar que el archivo siga la convención PascalCase
    $expectedFileName = ucfirst($className) . '.php';
    if ($file !== $expectedFileName) {
        $modelIssues[] = [
            'type' => 'filename',
            'file' => $file,
            'expected' => $expectedFileName,
            'message' => "Archivo debería llamarse: $expectedFileName"
        ];
    }
    
    // Verificar que la clase siga la convención PascalCase
    if (preg_match('/class\s+(\w+)/', $content, $matches)) {
        $classInFile = $matches[1];
        $expectedClassName = ucfirst($className);
        
        if ($classInFile !== $expectedClassName) {
            $modelIssues[] = [
                'type' => 'classname',
                'file' => $file,
                'class' => $classInFile,
                'expected' => $expectedClassName,
                'message' => "Clase debería llamarse: $expectedClassName"
            ];
        }
    }
}

if (empty($modelIssues)) {
    echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<h3>✅ Modelos: Nomenclatura correcta</h3>";
    echo "</div>";
} else {
    echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<h3>⚠️ Modelos: Problemas encontrados</h3>";
    echo "<ul>";
    foreach ($modelIssues as $issue) {
        echo "<li><strong>{$issue['file']}</strong>: {$issue['message']}</li>";
    }
    echo "</ul>";
    echo "</div>";
}

// =============================================
// 3. VERIFICAR REFERENCIAS ROTAS
// =============================================

echo "<h2>🔗 3. Verificación de Referencias</h2>";

$brokenReferences = [];

// Buscar referencias a archivos que ya no existen
$oldReferences = [
    'payrollController.php' => 'PayrollController.php',
    'indexController.php' => 'IndexController.php',
    'loginController.php' => 'LoginController.php',
    'registerController.php' => 'RegisterController.php',
    'errorController.php' => 'ErrorController.php',
    'mainModel.php' => 'MainModel.php',
    'workModel.php' => 'WorkModel.php',
    'taskModel.php' => 'TaskModel.php',
    'studentModel.php' => 'StudentModel.php',
    'subjectModel.php' => 'SubjectModel.php',
    'scheduleModel.php' => 'ScheduleModel.php',
    'studentCategoryModel.php' => 'StudentCategoryModel.php',
    'reportModel.php' => 'ReportModel.php',
    'documentModel.php' => 'DocumentModel.php',
    'academicHistoryModel.php' => 'AcademicHistoryModel.php',
    'rootModel.php' => 'RootModel.php'
];

foreach ($oldReferences as $oldFile => $newFile) {
    $pattern = '/require_once.*' . preg_quote($oldFile, '/') . '/';
    $files = glob(ROOT . '/**/*.php', GLOB_BRACE);
    
    foreach ($files as $file) {
        $content = file_get_contents($file);
        if (preg_match($pattern, $content)) {
            $relativePath = str_replace(ROOT . '/', '', $file);
            $brokenReferences[] = [
                'file' => $relativePath,
                'old_reference' => $oldFile,
                'new_reference' => $newFile,
                'message' => "Referencia obsoleta: $oldFile → $newFile"
            ];
        }
    }
}

if (empty($brokenReferences)) {
    echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<h3>✅ Referencias: Todas actualizadas</h3>";
    echo "</div>";
} else {
    echo "<div style='background: #ffe6e6; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<h3>❌ Referencias: Problemas encontrados</h3>";
    echo "<ul>";
    foreach ($brokenReferences as $ref) {
        echo "<li><strong>{$ref['file']}</strong>: {$ref['message']}</li>";
    }
    echo "</ul>";
    echo "</div>";
}

// =============================================
// 4. VERIFICAR VISTAS
// =============================================

echo "<h2>📁 4. Verificación de Vistas</h2>";

$viewsDir = ROOT . '/app/views/';
$viewIssues = [];

function scanViews($dir, $basePath = '') {
    global $viewIssues;
    
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        
        $fullPath = $dir . '/' . $item;
        $relativePath = $basePath . '/' . $item;
        
        if (is_dir($fullPath)) {
            // Verificar que las carpetas de módulos usen PascalCase
            if (!preg_match('/^(Error|index|layouts|widgets)$/', $item) && 
                $item !== strtolower($item) && $item !== ucfirst($item)) {
                $viewIssues[] = [
                    'type' => 'folder_case',
                    'path' => $relativePath,
                    'message' => "Carpeta debería usar PascalCase: " . ucfirst($item)
                ];
            }
            scanViews($fullPath, $relativePath);
        } else {
            // Verificar que los archivos usen camelCase
            if (pathinfo($item, PATHINFO_EXTENSION) === 'php') {
                $filename = pathinfo($item, PATHINFO_FILENAME);
                if ($filename !== lcfirst($filename) && $filename !== strtolower($filename)) {
                    $viewIssues[] = [
                        'type' => 'file_case',
                        'path' => $relativePath,
                        'message' => "Archivo debería usar camelCase: " . lcfirst($filename) . '.php'
                    ];
                }
            }
        }
    }
}

scanViews($viewsDir);

if (empty($viewIssues)) {
    echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<h3>✅ Vistas: Nomenclatura correcta</h3>";
    echo "</div>";
} else {
    echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<h3>⚠️ Vistas: Problemas encontrados</h3>";
    echo "<ul>";
    foreach ($viewIssues as $issue) {
        echo "<li><strong>{$issue['path']}</strong>: {$issue['message']}</li>";
    }
    echo "</ul>";
    echo "</div>";
}

// =============================================
// 5. VERIFICAR PROCESOS
// =============================================

echo "<h2>⚙️ 5. Verificación de Procesos</h2>";

$processesDir = ROOT . '/app/processes/';
$processes = scandir($processesDir);
$processIssues = [];

foreach ($processes as $file) {
    if ($file === '.' || $file === '..') continue;
    if (pathinfo($file, PATHINFO_EXTENSION) !== 'php') continue;
    
    $className = pathinfo($file, PATHINFO_FILENAME);
    $expectedFileName = lcfirst($className) . 'Process.php';
    
    if ($file !== $expectedFileName) {
        $processIssues[] = [
            'file' => $file,
            'expected' => $expectedFileName,
            'message' => "Archivo debería llamarse: $expectedFileName"
        ];
    }
}

if (empty($processIssues)) {
    echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<h3>✅ Procesos: Nomenclatura correcta</h3>";
    echo "</div>";
} else {
    echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<h3>⚠️ Procesos: Problemas encontrados</h3>";
    echo "<ul>";
    foreach ($processIssues as $issue) {
        echo "<li><strong>{$issue['file']}</strong>: {$issue['message']}</li>";
    }
    echo "</ul>";
    echo "</div>";
}

// =============================================
// 6. RESUMEN FINAL
// =============================================

echo "<h2>📊 Resumen Final</h2>";

$totalIssues = count($controllerIssues) + count($modelIssues) + count($brokenReferences) + count($viewIssues) + count($processIssues);

if ($totalIssues === 0) {
    echo "<div style='background: #e8f5e8; padding: 20px; border-radius: 10px; margin: 20px 0; border: 3px solid #28a745;'>";
    echo "<h3>🎉 ¡SISTEMA PERFECTO!</h3>";
    echo "<p><strong>Nomenclatura 100% consistente y profesional</strong></p>";
    echo "<ul>";
    echo "<li>✅ Controladores: Nomenclatura correcta</li>";
    echo "<li>✅ Modelos: Nomenclatura correcta</li>";
    echo "<li>✅ Referencias: Todas actualizadas</li>";
    echo "<li>✅ Vistas: Nomenclatura correcta</li>";
    echo "<li>✅ Procesos: Nomenclatura correcta</li>";
    echo "</ul>";
    echo "<p><strong>¡El sistema está listo para crecer sin problemas!</strong></p>";
    echo "</div>";
} else {
    echo "<div style='background: #ffe6e6; padding: 20px; border-radius: 10px; margin: 20px 0; border: 3px solid #dc3545;'>";
    echo "<h3>⚠️ PROBLEMAS ENCONTRADOS: $totalIssues</h3>";
    echo "<p><strong>Se encontraron problemas de nomenclatura que deben corregirse:</strong></p>";
    echo "<ul>";
    echo "<li>Controladores: " . count($controllerIssues) . " problemas</li>";
    echo "<li>Modelos: " . count($modelIssues) . " problemas</li>";
    echo "<li>Referencias: " . count($brokenReferences) . " problemas</li>";
    echo "<li>Vistas: " . count($viewIssues) . " problemas</li>";
    echo "<li>Procesos: " . count($processIssues) . " problemas</li>";
    echo "</ul>";
    echo "<p><strong>Recomendación: Corregir estos problemas antes de continuar con el desarrollo.</strong></p>";
    echo "</div>";
}

echo "<h2>🚀 Próximos Pasos</h2>";
echo "<ol>";
echo "<li>Ejecutar este test en tu entorno local</li>";
echo "<li>Corregir cualquier problema encontrado</li>";
echo "<li>Verificar que todas las funcionalidades sigan funcionando</li>";
echo "<li>Continuar con el desarrollo con nomenclatura consistente</li>";
echo "</ol>";

echo "<p><strong>¡Test de nomenclatura completo! 🎯</strong></p>";
?> 