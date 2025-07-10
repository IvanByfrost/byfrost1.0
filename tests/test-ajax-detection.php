<?php
require_once '../config.php';
require_once '../app/controllers/mainController.php';
require_once '../app/controllers/IndexController.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Test AJAX Detection</title>
    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css' rel='stylesheet'>
</head>
<body>
    <div class='container mt-4'>
        <h1>🔍 Test AJAX Detection</h1>
        
        <div class='row'>
            <div class='col-md-6'>
                <div class='card'>
                    <div class='card-header'>
                        <h5>1. Test de Detección AJAX</h5>
                    </div>
                    <div class='card-body'>";

// Test del controlador
$indexController = new IndexController($dbConn);

echo "<h6>Test de isAjaxRequest con diferentes headers:</h6>";

// Simular diferentes tipos de peticiones
$testCases = [
    'Normal GET' => [],
    'AJAX con X-Requested-With' => ['HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'],
    'AJAX con Accept JSON' => ['HTTP_ACCEPT' => 'application/json'],
    'AJAX con parámetro ajax' => ['GET' => ['ajax' => '1']],
    'AJAX con action=loadPartial' => ['GET' => ['action' => 'loadPartial']],
    'AJAX con partialView' => ['GET' => ['partialView' => 'test']]
];

foreach ($testCases as $name => $headers) {
    echo "<p><strong>$name:</strong></p>";
    
    // Simular headers
    foreach ($headers as $key => $value) {
        if ($key === 'GET') {
            $_GET = array_merge($_GET ?? [], $value);
        } else {
            $_SERVER[$key] = $value;
        }
    }
    
    // Usar reflexión para acceder al método protegido
    $reflection = new ReflectionClass($indexController);
    $method = $reflection->getMethod('isAjaxRequest');
    $method->setAccessible(true);
    $isAjax = $method->invoke($indexController);
    echo "<p>Resultado: " . ($isAjax ? "✅ AJAX detectado" : "❌ No es AJAX") . "</p>";
    
    // Limpiar para el siguiente test
    unset($_SERVER['HTTP_X_REQUESTED_WITH'], $_SERVER['HTTP_ACCEPT'], $_GET['ajax'], $_GET['action'], $_GET['partialView']);
}

echo "</div></div></div>";

echo "<div class='col-md-6'>
    <div class='card'>
        <div class='card-header'>
            <h5>2. Test de Construcción de URLs</h5>
        </div>
        <div class='card-body'>
            <h6>URLs que debería generar loadView.js:</h6>
            <ul>
                <li><strong>school/createSchool:</strong> ?view=school&action=loadPartial&partialView=createSchool</li>
                <li><strong>user/assignRole:</strong> ?view=user&action=loadPartial&partialView=assignRole</li>
                <li><strong>user/assignRole?section=usuarios:</strong> ?view=user&action=loadPartial&partialView=assignRole&section=usuarios</li>
            </ul>
        </div>
    </div>
</div>";

echo "</div>";

echo "<div class='row mt-4'>
    <div class='col-12'>
        <div class='card'>
            <div class='card-header'>
                <h5>3. Test de Respuesta del Servidor</h5>
            </div>
            <div class='card-body'>";

// Test de respuestas del servidor
echo "<h6>Test de loadPartial con diferentes vistas:</h6>";

$testViews = [
    'school/createSchool' => ['view' => 'school', 'action' => 'loadPartial', 'partialView' => 'createSchool'],
    'user/assignRole' => ['view' => 'user', 'action' => 'loadPartial', 'partialView' => 'assignRole'],
    'payroll/dashboard' => ['view' => 'payroll', 'action' => 'loadPartial', 'partialView' => 'dashboard']
];

foreach ($testViews as $viewName => $params) {
    echo "<p><strong>Test: $viewName</strong></p>";
    
    // Simular parámetros
    $_GET = $params;
    $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
    
    echo "<p>Parámetros: " . implode(', ', array_map(fn($k, $v) => "$k=$v", array_keys($params), $params)) . "</p>";
    
    ob_start();
    $indexController->loadPartial();
    $output = ob_get_clean();
    
    if (strpos($output, 'Vista parcial no encontrada') !== false) {
        echo "<p class='text-danger'>❌ Error: Vista no encontrada</p>";
        echo "<p>Output: " . htmlspecialchars(substr($output, 0, 200)) . "...</p>";
    } else {
        echo "<p class='text-success'>✅ Vista cargada correctamente</p>";
        echo "<p>Output: " . htmlspecialchars(substr($output, 0, 200)) . "...</p>";
    }
    
    echo "<hr>";
}

echo "</div></div></div>";

echo "<div class='row mt-4'>
    <div class='col-12'>
        <div class='card'>
            <div class='card-header'>
                <h5>4. Test de JavaScript</h5>
            </div>
            <div class='card-body'>
                <button class='btn btn-primary' onclick='testUrlConstruction()'>Test Construcción de URLs</button>
                <button class='btn btn-info' onclick='testAjaxRequest()'>Test Petición AJAX</button>
                <div id='jsResult' class='mt-3 alert alert-info'>Haz clic en un botón para ver el resultado</div>
            </div>
        </div>
    </div>
</div>";

echo "<script>
function testUrlConstruction() {
    const resultDiv = document.getElementById('jsResult');
    resultDiv.innerHTML = '<div class=\"spinner-border spinner-border-sm\"></div> Probando construcción de URLs...';
    resultDiv.className = 'alert alert-info';
    
    // Simular la función buildViewUrl de loadView.js
    function buildViewUrl(viewName) {
        const baseUrl = window.location.origin + window.location.pathname;
        
        if (viewName.includes('?')) {
            const [view, params] = viewName.split('?');
            let partialView = view;
            if (view.includes('/')) {
                const parts = view.split('/');
                partialView = parts[1];
            }
            return `${baseUrl}?view=${view}&action=loadPartial&partialView=${partialView}&${params}`;
        }
        
        if (viewName.includes('/')) {
            const [module, partialView] = viewName.split('/');
            return `${baseUrl}?view=${module}&action=loadPartial&partialView=${partialView}`;
        }
        
        return `${baseUrl}?view=${viewName}&action=loadPartial`;
    }
    
    const testCases = [
        'school/createSchool',
        'user/assignRole',
        'user/assignRole?section=usuarios',
        'payroll/dashboard'
    ];
    
    let results = [];
    testCases.forEach(testCase => {
        const url = buildViewUrl(testCase);
        results.push(`${testCase} → ${url}`);
    });
    
    resultDiv.innerHTML = '<strong>URLs construidas:</strong><br>' + results.join('<br>');
    resultDiv.className = 'alert alert-success';
}

function testAjaxRequest() {
    const resultDiv = document.getElementById('jsResult');
    resultDiv.innerHTML = '<div class=\"spinner-border spinner-border-sm\"></div> Probando petición AJAX...';
    resultDiv.className = 'alert alert-info';
    
    const url = window.location.origin + window.location.pathname + '?view=school&action=loadPartial&partialView=createSchool';
    
    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        resultDiv.innerHTML += '<br>Status: ' + response.status + ' ' + response.statusText;
        return response.text();
    })
    .then(html => {
        resultDiv.innerHTML += '<br>Contenido: ' + html.substring(0, 100) + '...';
        resultDiv.className = 'alert alert-success';
    })
    .catch(err => {
        resultDiv.innerHTML += '<br>Error: ' + err.message;
        resultDiv.className = 'alert alert-danger';
    });
}
</script>

</body>
</html>";
?> 