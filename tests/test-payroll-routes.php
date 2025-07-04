<?php
// Archivo de prueba para verificar rutas de nómina
echo "<h1>Prueba de Rutas de Nómina</h1>";

// Simular parámetros GET
$_GET['view'] = 'payroll';
$_GET['action'] = 'dashboard';

echo "<p>View: " . $_GET['view'] . "</p>";
echo "<p>Action: " . $_GET['action'] . "</p>";

// Verificar que el controlador existe
$controllerPath = __DIR__ . "/app/controllers/payrollController.php";
echo "<p>Controlador existe: " . (file_exists($controllerPath) ? "SÍ" : "NO") . "</p>";
echo "<p>Ruta: " . $controllerPath . "</p>";

// Verificar que las vistas existen
$viewPath = __DIR__ . "/app/views/payroll/dashboard.php";
echo "<p>Vista dashboard existe: " . (file_exists($viewPath) ? "SÍ" : "NO") . "</p>";
echo "<p>Ruta: " . $viewPath . "</p>";

// Verificar que el modelo existe
$modelPath = __DIR__ . "/app/models/payrollModel.php";
echo "<p>Modelo existe: " . (file_exists($modelPath) ? "SÍ" : "NO") . "</p>";
echo "<p>Ruta: " . $modelPath . "</p>";

// Listar todas las vistas de nómina
echo "<h2>Vistas de Nómina Disponibles:</h2>";
$payrollViewsDir = __DIR__ . "/app/views/payroll/";
if (is_dir($payrollViewsDir)) {
    $files = scandir($payrollViewsDir);
    echo "<ul>";
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..' && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            echo "<li>" . $file . "</li>";
        }
    }
    echo "</ul>";
} else {
    echo "<p>Directorio de vistas no existe</p>";
}

echo "<h2>Prueba de Acceso Directo:</h2>";
echo "<p><a href='?view=payroll&action=dashboard'>Dashboard de Nómina</a></p>";
echo "<p><a href='?view=payroll&action=employees'>Empleados</a></p>";
echo "<p><a href='?view=payroll&action=periods'>Períodos</a></p>";
echo "<p><a href='?view=payroll&action=absences'>Ausencias</a></p>";
echo "<p><a href='?view=payroll&action=overtime'>Horas Extras</a></p>";
echo "<p><a href='?view=payroll&action=bonuses'>Bonificaciones</a></p>";
echo "<p><a href='?view=payroll&action=reports'>Reportes</a></p>";
?> 