<?php
/**
 * Script de prueba simple para verificar JavaScript
 */

// Configuración
define('ROOT', __DIR__);
require_once 'config.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba JavaScript Simple</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Prueba JavaScript Simple</h1>
        
        <div class="card">
            <div class="card-header">
                <h5>Formulario de Prueba</h5>
            </div>
            <div class="card-body">
                <form method="POST" id="createSchool" class="dash-form">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="school_name" class="form-label">Nombre de la Escuela *</label>
                            <input type="text" id="school_name" name="school_name" class="form-control" placeholder="Ej: Colegio San José" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="school_dane" class="form-label">Código DANE *</label>
                            <input type="text" id="school_dane" name="school_dane" class="form-control" placeholder="Ej: 11100123456" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="school_document" class="form-label">NIT *</label>
                            <input type="text" id="school_document" name="school_document" class="form-control" placeholder="Ej: 900123456-7" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Ej: info@colegio.edu.co">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Probar Envío
                    </button>
                </form>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5>Estado del JavaScript</h5>
            </div>
            <div class="card-body">
                <div id="js-status">Verificando...</div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5>Logs de Consola</h5>
            </div>
            <div class="card-body">
                <div id="console-log" style="background-color: #f8f9fa; padding: 10px; border-radius: 5px; font-family: monospace; max-height: 200px; overflow-y: auto;"></div>
            </div>
        </div>
    </div>

    <!-- Incluir el JavaScript -->
    <script src="<?php echo url; ?>app/resources/js/createSchool.js"></script>
    
    <script>
    // Interceptar console.log para mostrar en la página
    const originalLog = console.log;
    const originalError = console.error;
    const logDiv = document.getElementById('console-log');
    
    console.log = function(...args) {
        originalLog.apply(console, args);
        const message = args.join(' ') + '\n';
        logDiv.textContent += message;
        logDiv.scrollTop = logDiv.scrollHeight;
    };
    
    console.error = function(...args) {
        originalError.apply(console, args);
        const message = 'ERROR: ' + args.join(' ') + '\n';
        logDiv.textContent += message;
        logDiv.scrollTop = logDiv.scrollHeight;
    };
    
    // Verificar carga del JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        const statusDiv = document.getElementById('js-status');
        
        setTimeout(() => {
            if (typeof validateForm === 'function') {
                statusDiv.innerHTML = '<div class="alert alert-success"><i class="fas fa-check-circle"></i> JavaScript cargado correctamente</div>';
            } else {
                statusDiv.innerHTML = '<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error: JavaScript no se cargó correctamente</div>';
            }
        }, 1000);
    });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 