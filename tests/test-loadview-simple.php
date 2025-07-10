<?php
require_once '../config.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Test loadView Simple</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>🧪 Test loadView Simple</h1>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Test de loadView</h5>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-primary mb-2" onclick="testSimpleLoadView('school/createSchool')">Test school/createSchool</button><br>
                        <button class="btn btn-info mb-2" onclick="testSimpleLoadView('user/assignRole')">Test user/assignRole</button><br>
                        <button class="btn btn-success mb-2" onclick="testSimpleLoadView('payroll/dashboard')">Test payroll/dashboard</button><br>
                        <button class="btn btn-warning mb-2" onclick="testDirectFetch('school/createSchool')">Test Fetch Directo</button>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Resultado</h5>
                    </div>
                    <div class="card-body">
                        <div id="testResult" class="alert alert-info">
                            Haz clic en un botón para ver el resultado
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Contenido Cargado</h5>
                    </div>
                    <div class="card-body">
                        <div id="mainContent" class="border p-3" style="min-height: 200px;">
                            <p class="text-muted">El contenido se cargará aquí...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../app/resources/js/loadView.js"></script>
    
    <script>
    function testSimpleLoadView(viewName) {
        console.log('🧪 Test loadView con:', viewName);
        
        const resultDiv = document.getElementById('testResult');
        resultDiv.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"></div> Probando...';
        resultDiv.className = 'alert alert-info';
        
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
    
    function testDirectFetch(viewName) {
        console.log('🧪 Test Fetch Directo con:', viewName);
        
        const resultDiv = document.getElementById('testResult');
        resultDiv.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"></div> Probando fetch directo...';
        resultDiv.className = 'alert alert-info';
        
        // Construir URL manualmente
        const baseUrl = window.location.origin + window.location.pathname;
        const [module, partialView] = viewName.split('/');
        const url = `${baseUrl}?view=${module}&action=loadPartial&partialView=${partialView}`;
        
        console.log('URL construida:', url);
        
        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Respuesta del servidor:', response.status, response.statusText);
            resultDiv.innerHTML += '<br>Status: ' + response.status + ' ' + response.statusText;
            
            if (!response.ok) {
                throw new Error("Vista no encontrada.");
            }
            return response.text();
        })
        .then(html => {
            console.log('Contenido de la respuesta:', html.substring(0, 200) + '...');
            resultDiv.innerHTML += '<br>Contenido recibido: ' + html.substring(0, 100) + '...';
            
            const target = document.getElementById('mainContent');
            if (target) {
                target.innerHTML = html;
                resultDiv.innerHTML += '<br>✅ Contenido cargado en mainContent';
            } else {
                resultDiv.innerHTML += '<br>❌ Elemento mainContent no encontrado';
            }
            
            resultDiv.className = 'alert alert-success';
        })
        .catch(err => {
            console.error("Error al cargar la vista:", err);
            resultDiv.innerHTML += '<br>❌ Error: ' + err.message;
            resultDiv.className = 'alert alert-danger';
        });
    }
    
    // Interceptar console.log para mostrar en la página
    const originalLog = console.log;
    console.log = function(...args) {
        originalLog.apply(console, args);
        
        const resultDiv = document.getElementById('testResult');
        if (resultDiv.innerHTML.includes('Probando')) {
            resultDiv.innerHTML += '<br><small class="text-muted">' + args.join(' ') + '</small>';
        }
    };
    </script>
</body>
</html> 