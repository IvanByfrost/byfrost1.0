<?php
require_once '../config.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Test loadView Específico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>🔍 Test loadView Específico</h1>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Diagnóstico</h5>
                    </div>
                    <div class="card-body">
                        <div id="diagnostic">
                            <p>Verificando elementos...</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Test Manual</h5>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-primary mb-2" onclick="testLoadView('school/createSchool')">Test school/createSchool</button><br>
                        <button class="btn btn-info mb-2" onclick="testFetch('school/createSchool')">Test Fetch Directo</button><br>
                        <button class="btn btn-success mb-2" onclick="checkElements()">Verificar Elementos</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Contenido</h5>
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
    function checkElements() {
        const diagnostic = document.getElementById('diagnostic');
        const mainContent = document.getElementById('mainContent');
        const loadViewFunc = typeof loadView;
        
        let html = '<h6>Estado de elementos:</h6>';
        html += '<p><strong>mainContent:</strong> ' + (mainContent ? '✅ Encontrado' : '❌ No encontrado') + '</p>';
        html += '<p><strong>loadView:</strong> ' + (loadViewFunc === 'function' ? '✅ Función disponible' : '❌ No disponible (' + loadViewFunc + ')') + '</p>';
        
        if (mainContent) {
            html += '<p><strong>mainContent.innerHTML:</strong> ' + mainContent.innerHTML.substring(0, 50) + '...</p>';
        }
        
        diagnostic.innerHTML = html;
    }
    
    function testLoadView(viewName) {
        console.log('🧪 Test loadView con:', viewName);
        
        const diagnostic = document.getElementById('diagnostic');
        diagnostic.innerHTML = '<div class="spinner-border spinner-border-sm"></div> Probando loadView...';
        
        try {
            if (typeof loadView === 'function') {
                loadView(viewName);
                diagnostic.innerHTML = '✅ loadView ejecutado correctamente';
            } else {
                diagnostic.innerHTML = '❌ loadView no es una función';
            }
        } catch (error) {
            diagnostic.innerHTML = '❌ Error en loadView: ' + error.message;
            console.error('Error en loadView:', error);
        }
    }
    
    function testFetch(viewName) {
        console.log('🧪 Test Fetch con:', viewName);
        
        const diagnostic = document.getElementById('diagnostic');
        diagnostic.innerHTML = '<div class="spinner-border spinner-border-sm"></div> Probando fetch...';
        
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
            console.log('Respuesta:', response.status, response.statusText);
            diagnostic.innerHTML += '<br>Status: ' + response.status + ' ' + response.statusText;
            
            if (!response.ok) {
                throw new Error("Vista no encontrada.");
            }
            return response.text();
        })
        .then(html => {
            console.log('Contenido:', html.substring(0, 200) + '...');
            diagnostic.innerHTML += '<br>Contenido recibido: ' + html.substring(0, 100) + '...';
            
            const target = document.getElementById('mainContent');
            if (target) {
                target.innerHTML = html;
                diagnostic.innerHTML += '<br>✅ Contenido cargado en mainContent';
            } else {
                diagnostic.innerHTML += '<br>❌ Elemento mainContent no encontrado';
            }
        })
        .catch(err => {
            console.error("Error:", err);
            diagnostic.innerHTML += '<br>❌ Error: ' + err.message;
        });
    }
    
    // Verificar al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Página cargada, verificando elementos...');
        checkElements();
    });
    
    // Interceptar console.log para mostrar en la página
    const originalLog = console.log;
    console.log = function(...args) {
        originalLog.apply(console, args);
        
        const diagnostic = document.getElementById('diagnostic');
        if (diagnostic.innerHTML.includes('Probando')) {
            diagnostic.innerHTML += '<br><small class="text-muted">' + args.join(' ') + '</small>';
        }
    };
    </script>
</body>
</html> 