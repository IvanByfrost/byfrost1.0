<?php
/**
 * Script para reemplazar safeLoadView por loadView en todas las vistas
 */

$viewsDir = __DIR__ . '/app/views/';
$jsDir = __DIR__ . '/app/resources/js/';

echo "🔧 Iniciando reemplazo de safeLoadView por loadView...\n";

function replaceInFiles($dir, $pattern) {
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir)
    );
    
    $count = 0;
    foreach ($files as $file) {
        if ($file->isFile() && pathinfo($file->getPathname(), PATHINFO_EXTENSION) === 'php') {
            $content = file_get_contents($file->getPathname());
            $originalContent = $content;
            
            // Reemplazar safeLoadView por loadView
            $content = str_replace('safeLoadView', 'loadView', $content);
            
            if ($content !== $originalContent) {
                file_put_contents($file->getPathname(), $content);
                echo "✅ " . str_replace(__DIR__, '', $file->getPathname()) . "\n";
                $count++;
            }
        }
    }
    
    return $count;
}

// Reemplazar en vistas
$viewsCount = replaceInFiles($viewsDir, '*.php');
echo "\n📁 Vistas actualizadas: $viewsCount\n";

// Reemplazar en archivos JS
$jsCount = replaceInFiles($jsDir, '*.js');
echo "📁 Archivos JS actualizados: $jsCount\n";

echo "\n🎉 Reemplazo completado!\n";
echo "Total de archivos actualizados: " . ($viewsCount + $jsCount) . "\n";
?> 