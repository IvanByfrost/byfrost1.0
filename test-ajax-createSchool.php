<?php
/**
 * Script de prueba para verificar la petición AJAX de creación de escuela
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
    <title>Prueba AJAX - Crear Escuela</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Prueba AJAX - Crear Escuela</h1>
        <p>Verificando que la petición AJAX funcione correctamente.</p>
        
        <div class="card">
            <div class="card-header">
                <h5>Formulario de Prueba AJAX</h5>
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
                        <i class="fas fa-save"></i> Probar AJAX
                    </button>
                </form>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5>Respuesta del Servidor</h5>
            </div>
            <div class="card-body">
                <div id="response" style="background-color: #f8f9fa; padding: 10px; border-radius: 5px; font-family: monospace; min-height: 100px;"></div>
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
    
    // Configurar el formulario
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('createSchool');
        const responseDiv = document.getElementById('response');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            console.log('Enviando petición AJAX...');
            responseDiv.textContent = 'Enviando petición...';
            
            const formData = new FormData(form);
            
            // Enviar petición AJAX
            fetch('app/processes/schoolProcess.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Respuesta recibida:', response.status, response.statusText);
                console.log('Headers:', response.headers);
                
                // Verificar el tipo de contenido
                const contentType = response.headers.get('content-type');
                console.log('Content-Type:', contentType);
                
                if (!response.ok) {
                    throw new Error('Error de red: ' + response.status);
                }
                
                return response.text(); // Usar text() en lugar de json() para debugging
            })
            .then(text => {
                console.log('Respuesta en texto:', text);
                
                try {
                    const data = JSON.parse(text);
                    console.log('Datos parseados:', data);
                    
                    if (data.status === 'success') {
                        responseDiv.innerHTML = `<div class="alert alert-success"><i class="fas fa-check-circle"></i> ${data.msg}</div>`;
                    } else {
                        responseDiv.innerHTML = `<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> ${data.msg}</div>`;
                    }
                } catch (parseError) {
                    console.error('Error al parsear JSON:', parseError);
                    responseDiv.innerHTML = `<div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> Respuesta no es JSON válido:<br><pre>${text}</pre></div>`;
                }
            })
            .catch(error => {
                console.error('Error en la petición:', error);
                responseDiv.innerHTML = `<div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> Error: ${error.message}</div>`;
            });
        });
    });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 