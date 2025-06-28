<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(__DIR__)) . '/');
}

require_once ROOT . '/config.php';

$view = $_GET['view'] ?? '';

// Debug: mostrar la ruta que se está construyendo
$viewPath = ROOT . "/app/views/" . $view . ".php";
echo "<!-- Debug: ROOT = " . ROOT . " -->";
echo "<!-- Debug: view = " . htmlspecialchars($view) . " -->";
echo "<!-- Debug: viewPath = " . $viewPath . " -->";
echo "<!-- Debug: file_exists = " . (file_exists($viewPath) ? 'true' : 'false') . " -->";

// Seguridad extendida
if (
    empty($view) ||
    preg_match('/\.\.|\.env|config|\.htaccess/i', $view)
) {
    http_response_code(403);
    echo "<h2>Error 403</h2><p>Acceso denegado.</p>";
    echo "<p>Vista solicitada: <code>" . htmlspecialchars($view) . "</code></p>";
    exit;
}

$parts = explode('/', $view);

// Validar carpeta permitida
$allowedDirs = ['root', 'teacher', 'director', 'student', 'coordinator', 'treasurer', 'school', 'index', 'activity', 'schedule', 'user', 'parent'];

// Mapeo de roles a directorios
$roleMapping = [
    'professor' => 'teacher',
    'coordinator' => 'coordinator',
    'director' => 'director',
    'student' => 'student',
    'root' => 'root',
    'treasurer' => 'treasurer',
    'parent' => 'parent'
];

// Si no está en carpetas permitidas (comparación insensible a mayúsculas/minúsculas)
if (!in_array(strtolower($parts[0]), array_map('strtolower', $allowedDirs))) {
    // Verificar si es un rol mapeado
    $mappedDir = $roleMapping[strtolower($parts[0])] ?? null;
    if ($mappedDir) {
        // Reemplazar el primer segmento con el directorio mapeado
        $parts[0] = $mappedDir;
        $view = implode('/', $parts);
        $viewPath = ROOT . "/app/views/" . $view . ".php";
        echo "<!-- Debug: Mapeado de rol - vista mapeada a: " . $view . " -->";
        echo "<!-- Debug: Nueva viewPath = " . $viewPath . " -->";
    } else {
        http_response_code(403);
        echo "<h2>Error 403</h2><p>No tienes permiso para acceder a esta vista.</p>";
        echo "<p>Directorio solicitado: <code>" . htmlspecialchars($parts[0]) . "</code></p>";
        echo "<p>Directorios permitidos: <code>" . implode(', ', $allowedDirs) . "</code></p>";
        echo "<p>Roles mapeados: <code>" . implode(', ', array_keys($roleMapping)) . "</code></p>";
        exit;
    }
}

// Construir la ruta al archivo
$viewPath = ROOT . "/app/views/" . $view . ".php";

// Si existe, mostrarla
if (file_exists($viewPath)) {
    echo "<!-- Debug: Cargando vista: " . $viewPath . " -->";
    require_once $viewPath;
} else {
    http_response_code(404);
    echo "<h2>Error 404</h2><p>La vista <code>" . htmlspecialchars($view) . "</code> no existe.</p>";
    echo "<p>Ruta buscada: <code>" . htmlspecialchars($viewPath) . "</code></p>";
    echo "<p>Verifica que el archivo existe en la ubicación correcta.</p>";
}
