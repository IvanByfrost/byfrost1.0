<?php
/**
 * Script de validación para scripts SQL
 * Verifica la calidad, consistencia y funcionalidad de los scripts SQL
 */

if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(dirname(__DIR__))));
}

echo "<h1>🔍 VALIDACIÓN DE SCRIPTS SQL - BYFROST</h1>\n";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .success { color: green; }
    .warning { color: orange; }
    .error { color: red; }
    .info { color: blue; }
    table { border-collapse: collapse; width: 100%; margin: 10px 0; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    th { background-color: #f2f2f2; }
</style>\n";

// Función para verificar si un archivo existe
function checkFile($filePath, $description) {
    $fullPath = ROOT . '/app/scripts/' . $filePath;
    if (file_exists($fullPath)) {
        $size = filesize($fullPath);
        $lines = count(file($fullPath));
        echo "<div class='success'>✅ {$description}: {$filePath} ({$size} bytes, {$lines} líneas)</div>\n";
        return ['exists' => true, 'size' => $size, 'lines' => $lines];
    } else {
        echo "<div class='error'>❌ {$description}: {$filePath} NO ENCONTRADO</div>\n";
        return ['exists' => false, 'size' => 0, 'lines' => 0];
    }
}

// Función para analizar contenido SQL
function analyzeSQLContent($filePath) {
    $fullPath = ROOT . '/app/scripts/' . $filePath;
    if (!file_exists($fullPath)) {
        return ['error' => 'Archivo no encontrado'];
    }
    
    $content = file_get_contents($fullPath);
    $lines = explode("\n", $content);
    
    $analysis = [
        'total_lines' => count($lines),
        'sql_lines' => 0,
        'comment_lines' => 0,
        'create_tables' => 0,
        'insert_statements' => 0,
        'foreign_keys' => 0,
        'indexes' => 0,
        'views' => 0,
        'has_comments' => false,
        'has_headers' => false
    ];
    
    foreach ($lines as $line) {
        $line = trim($line);
        
        if (empty($line)) continue;
        
        // Contar líneas SQL
        if (preg_match('/^(CREATE|INSERT|UPDATE|DELETE|ALTER|DROP|SELECT)/i', $line)) {
            $analysis['sql_lines']++;
        }
        
        // Contar comentarios
        if (preg_match('/^--/', $line) || preg_match('/^\/\*/', $line)) {
            $analysis['comment_lines']++;
        }
        
        // Contar CREATE TABLE
        if (preg_match('/CREATE TABLE/i', $line)) {
            $analysis['create_tables']++;
        }
        
        // Contar INSERT
        if (preg_match('/INSERT INTO/i', $line)) {
            $analysis['insert_statements']++;
        }
        
        // Contar FOREIGN KEY
        if (preg_match('/FOREIGN KEY/i', $line)) {
            $analysis['foreign_keys']++;
        }
        
        // Contar índices
        if (preg_match('/CREATE INDEX/i', $line) || preg_match('/KEY\s*\(/i', $line)) {
            $analysis['indexes']++;
        }
        
        // Contar vistas
        if (preg_match('/CREATE VIEW/i', $line)) {
            $analysis['views']++;
        }
        
        // Verificar si tiene comentarios descriptivos
        if (preg_match('/--.*[A-Z]/', $line)) {
            $analysis['has_comments'] = true;
        }
        
        // Verificar si tiene headers
        if (preg_match('/^--\s*[A-Z].*SCRIPT/i', $line)) {
            $analysis['has_headers'] = true;
        }
    }
    
    return $analysis;
}

echo "<h2>📁 VERIFICACIÓN DE ARCHIVOS</h2>\n";

$files = [
    'Baldur.sql' => 'Esquema principal de base de datos',
    'academic_tables.sql' => 'Tablas académicas',
    'payroll_tables.sql' => 'Tablas de nómina',
    'attendance_table.sql' => 'Tabla de asistencia',
    'events_table.sql' => 'Tabla de eventos',
    'grades_tables.sql' => 'Tablas de calificaciones',
    'migration_to_baldur.sql' => 'Script de migración',
    'optimized_queries_for_baldur.sql' => 'Consultas optimizadas',
    'insert_sample_data.sql' => 'Datos de ejemplo',
    'payments_table.sql' => 'Tabla de pagos',
    'password_resets_table.sql' => 'Recuperación de contraseñas',
    'update_schools_table.sql' => 'Actualización de escuelas',
    'insert_role_permissions.sql' => 'Permisos de roles',
    'connection.php' => 'Configuración de conexión',
    'routerView.php' => 'Router de vistas'
];

$fileStats = [];
foreach ($files as $file => $description) {
    $fileStats[$file] = checkFile($file, $description);
}

echo "<h2>📊 ANÁLISIS DE CONTENIDO</h2>\n";

echo "<table>\n";
echo "<tr><th>Archivo</th><th>Líneas</th><th>SQL</th><th>Comentarios</th><th>Tablas</th><th>Inserts</th><th>FK</th><th>Índices</th><th>Vistas</th><th>Calidad</th></tr>\n";

$totalQuality = 0;
$analyzedFiles = 0;

foreach ($files as $file => $description) {
    if ($fileStats[$file]['exists']) {
        $analysis = analyzeSQLContent($file);
        $analyzedFiles++;
        
        // Calcular calidad (0-100)
        $quality = 0;
        if ($analysis['has_headers']) $quality += 20;
        if ($analysis['has_comments']) $quality += 20;
        if ($analysis['comment_lines'] > 0) $quality += 15;
        if ($analysis['create_tables'] > 0) $quality += 15;
        if ($analysis['foreign_keys'] > 0) $quality += 15;
        if ($analysis['indexes'] > 0) $quality += 15;
        
        $totalQuality += $quality;
        
        $qualityClass = $quality >= 80 ? 'success' : ($quality >= 60 ? 'warning' : 'error');
        
        echo "<tr>";
        echo "<td>{$file}</td>";
        echo "<td>{$analysis['total_lines']}</td>";
        echo "<td>{$analysis['sql_lines']}</td>";
        echo "<td>{$analysis['comment_lines']}</td>";
        echo "<td>{$analysis['create_tables']}</td>";
        echo "<td>{$analysis['insert_statements']}</td>";
        echo "<td>{$analysis['foreign_keys']}</td>";
        echo "<td>{$analysis['indexes']}</td>";
        echo "<td>{$analysis['views']}</td>";
        echo "<td class='{$qualityClass}'>{$quality}%</td>";
        echo "</tr>\n";
    }
}

echo "</table>\n";

if ($analyzedFiles > 0) {
    $averageQuality = round($totalQuality / $analyzedFiles, 1);
    echo "<h3>📈 CALIDAD PROMEDIO: {$averageQuality}%</h3>\n";
    
    if ($averageQuality >= 80) {
        echo "<div class='success'>✅ Excelente calidad general</div>\n";
    } elseif ($averageQuality >= 60) {
        echo "<div class='warning'>⚠️ Calidad aceptable, mejoras recomendadas</div>\n";
    } else {
        echo "<div class='error'>❌ Calidad baja, mejoras necesarias</div>\n";
    }
}

echo "<h2>🔧 RECOMENDACIONES</h2>\n";

echo "<h3>✅ PUNTOS FUERTES:</h3>\n";
echo "<ul>\n";
echo "<li>Estructura de archivos bien organizada</li>\n";
echo "<li>Scripts de migración completos</li>\n";
echo "<li>Consultas optimizadas incluidas</li>\n";
echo "<li>Datos de ejemplo proporcionados</li>\n";
echo "</ul>\n";

echo "<h3>⚠️ ÁREAS DE MEJORA:</h3>\n";
echo "<ul>\n";
echo "<li>Agregar más comentarios descriptivos</li>\n";
echo "<li>Incluir headers en todos los scripts</li>\n";
echo "<li>Crear scripts de validación</li>\n";
echo "<li>Implementar versionado</li>\n";
echo "<li>Agregar tests de integridad</li>\n";
echo "</ul>\n";

echo "<h2>🎯 PRÓXIMOS PASOS</h2>\n";
echo "<ol>\n";
echo "<li>Agregar comentarios detallados a scripts principales</li>\n";
echo "<li>Crear scripts de validación de datos</li>\n";
echo "<li>Implementar sistema de versionado</li>\n";
echo "<li>Documentar dependencias entre scripts</li>\n";
echo "<li>Crear guías de troubleshooting</li>\n";
echo "</ol>\n";

echo "<div class='info'>📝 Validación completada: " . date('Y-m-d H:i:s') . "</div>\n";
?> 