<?php
require_once '../config.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Test loadView Final</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>✅ Test loadView Final</h1>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Test de loadView</h5>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-primary mb-2" onclick="testLoadView('school/createSchool')">
                            <i class="fas fa-school"></i> Test school/createSchool
                        </button><br>
                        <button class="btn btn-info mb-2" onclick="testLoadView('user/assignRole')">
                            <i class="fas fa-users"></i> Test user/assignRole
                        </button><br>
                        <button class="btn btn-success mb-2" onclick="testLoadView('payroll/dashboard')">
                            <i class="fas fa-chart-bar"></i> Test payroll/dashboard
                        </button><br>
                        <button class="btn btn-warning mb-2" onclick="testLoadView('user/assignRole?section=usuarios')">
                            <i class="fas fa-cog"></i> Test con parámetros
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Estado</h5>
                    </div>
                    <div class="card-body">
                        <div id="status" class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Haz clic en un botón para probar loadView
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
                        <div id="mainContent" class="border p-3" style="min-height: 300px;">
                            <div class="text-center text-muted">
                                <i class="fas fa-arrow-up fa-2x mb-3"></i>
                                <p>El contenido se cargará aquí cuando hagas clic en un botón</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../app/resources/js/loadView.js"></script>
    
    <script>
    function testLoadView(viewName) {
        console.log('🧪 Test loadView con:', viewName);
        
        const statusDiv = document.getElementById('status');
        statusDiv.innerHTML = '<div class="spinner-border spinner-border-sm"></div> <i class="fas fa-sync-alt fa-spin"></i> Cargando vista...';
        statusDiv.className = 'alert alert-info';
        
        try {
            if (typeof loadView === 'function') {
                loadView(viewName);
                statusDiv.innerHTML = '<i class="fas fa-check-circle"></i> ✅ loadView ejecutado correctamente';
                statusDiv.className = 'alert alert-success';
            } else {
                statusDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> ❌ loadView no es una función';
                statusDiv.className = 'alert alert-danger';
            }
        } catch (error) {
            statusDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> ❌ Error en loadView: ' + error.message;
            statusDiv.className = 'alert alert-danger';
            console.error('Error en loadView:', error);
        }
    }
    
    // Interceptar console.log para mostrar en la página
    const originalLog = console.log;
    console.log = function(...args) {
        originalLog.apply(console, args);
        
        const statusDiv = document.getElementById('status');
        if (statusDiv.innerHTML.includes('Cargando vista')) {
            statusDiv.innerHTML += '<br><small class="text-muted"><i class="fas fa-terminal"></i> ' + args.join(' ') + '</small>';
        }
    };
    
    // Verificar al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Página cargada, loadView está listo');
        
        const statusDiv = document.getElementById('status');
        if (typeof loadView === 'function') {
            statusDiv.innerHTML = '<i class="fas fa-check-circle"></i> ✅ loadView está disponible y listo para usar';
            statusDiv.className = 'alert alert-success';
        } else {
            statusDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> ❌ loadView no está disponible';
            statusDiv.className = 'alert alert-danger';
        }
    });
    </script>
</body>
</html> 