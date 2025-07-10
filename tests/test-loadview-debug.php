<?php
require_once '../config.php';
require_once '../app/controllers/mainController.php';
require_once '../app/controllers/IndexController.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Test loadView Debug</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css' rel='stylesheet'>
</head>
<body>
    <div class='container mt-4'>
        <h1>🔍 Test Debug loadView</h1>
        
        <div class='row'>
            <div class='col-md-6'>
                <div class='card'>
                    <div class='card-header'>
                        <h5>1. Test de Construcción de URLs</h5>
                    </div>
                    <div class='card-body'>
                        <h6>URLs que debería generar loadView.js:</h6>
                        <ul>
                            <li><strong>Vista simple:</strong> school/createSchool → ?view=school&action=loadPartial&partialView=createSchool</li>
                            <li><strong>Vista con parámetros:</strong> user/assignRole?section=usuarios → ?view=user&action=loadPartial&partialView=assignRole&section=usuarios</li>
                            <li><strong>Vista directa:</strong> payroll/dashboard → ?view=payroll&action=loadPartial&partialView=dashboard</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class='col-md-6'>
                <div class='card'>
                    <div class='card-header'>
                        <h5>2. Test de Controlador</h5>
                    </div>
                    <div class='card-body'>";

// Test del controlador
$indexController = new IndexController($dbConn);

echo "<h6>Test de loadPartial con diferentes parámetros:</h6>";

// Test 1: Vista simple
echo "<p><strong>Test 1 - Vista simple:</strong></p>";
$_GET['view'] = 'school';
$_GET['action'] = 'loadPartial';
$_GET['partialView'] = 'createSchool';
echo "<p>Parámetros: view=school, action=loadPartial, partialView=createSchool</p>";

ob_start();
$indexController->loadPartial();
$output1 = ob_get_clean();

if (strpos($output1, 'Vista parcial no encontrada') !== false) {
    echo "<p class='text-danger'>❌ Error: Vista no encontrada</p>";
    echo "<p>Output: " . htmlspecialchars(substr($output1, 0, 200)) . "...</p>";
} else {
    echo "<p class='text-success'>✅ Vista cargada correctamente</p>";
}

// Test 2: Vista con parámetros
echo "<p><strong>Test 2 - Vista con parámetros:</strong></p>";
$_GET['view'] = 'user';
$_GET['action'] = 'loadPartial';
$_GET['partialView'] = 'assignRole';
$_GET['section'] = 'usuarios';
echo "<p>Parámetros: view=user, action=loadPartial, partialView=assignRole, section=usuarios</p>";

ob_start();
$indexController->loadPartial();
$output2 = ob_get_clean();

if (strpos($output2, 'Vista parcial no encontrada') !== false) {
    echo "<p class='text-danger'>❌ Error: Vista no encontrada</p>";
    echo "<p>Output: " . htmlspecialchars(substr($output2, 0, 200)) . "...</p>";
} else {
    echo "<p class='text-success'>✅ Vista cargada correctamente</p>";
}

echo "</div></div></div>";

echo "<div class='row mt-4'>
    <div class='col-md-6'>
        <div class='card'>
            <div class='card-header'>
                <h5>3. Test de JavaScript loadView</h5>
            </div>
            <div class='card-body'>
                <p>Haz clic en los botones para probar loadView.js:</p>
                <button class='btn btn-primary mb-2' onclick='testLoadView(\"school/createSchool\")'>Test school/createSchool</button><br>
                <button class='btn btn-info mb-2' onclick='testLoadView(\"user/assignRole\")'>Test user/assignRole</button><br>
                <button class='btn btn-success mb-2' onclick='testLoadView(\"payroll/dashboard\")'>Test payroll/dashboard</button><br>
                <button class='btn btn-warning mb-2' onclick='testLoadView(\"user/assignRole?section=usuarios\")'>Test con parámetros</button>
            </div>
        </div>
    </div>
    
    <div class='col-md-6'>
        <div class='card'>
            <div class='card-header'>
                <h5>4. Resultado del Test</h5>
            </div>
            <div class='card-body'>
                <div id='testResult' class='alert alert-info'>
                    Haz clic en un botón para ver el resultado
                </div>
            </div>
        </div>
    </div>
</div>

<div class='row mt-4'>
    <div class='col-12'>
        <div class='card'>
            <div class='card-header'>
                <h5>5. Contenido Cargado</h5>
            </div>
            <div class='card-body'>
                <div id='mainContent' class='border p-3' style='min-height: 200px;'>
                    <p class='text-muted'>El contenido se cargará aquí...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js'></script>
<script src='../app/resources/js/loadView.js'></script>
<script>
function testLoadView(viewName) {
    console.log('🧪 Test loadView con:', viewName);
    
    const resultDiv = document.getElementById('testResult');
    resultDiv.innerHTML = '<div class=\"spinner-border spinner-border-sm\" role=\"status\"></div> Probando...';
    resultDiv.className = 'alert alert-info';
    
    // Llamar a loadView
    try {
        loadView(viewName);
        resultDiv.innerHTML = '✅ loadView ejecutado correctamente';
        resultDiv.className = 'alert alert-success';
    } catch (error) {
        resultDiv.innerHTML = '❌ Error en loadView: ' + error.message;
        resultDiv.className = 'alert alert-danger';
        console.error('Error en loadView:', error);
    }
}

// Interceptar console.log para mostrar en la página
const originalLog = console.log;
console.log = function(...args) {
    originalLog.apply(console, args);
    
    const resultDiv = document.getElementById('testResult');
    if (resultDiv.innerHTML.includes('Probando...')) {
        resultDiv.innerHTML += '<br><small class=\"text-muted\">' + args.join(' ') + '</small>';
    }
};
</script>

</body>
</html>";
?> 