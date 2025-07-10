<?php
/**
 * Test simple para verificar la función initUserManagementAfterLoad
 */
require_once '../config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test: initUserManagementAfterLoad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Test: Verificación de initUserManagementAfterLoad</h1>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Verificación de Archivos</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $userManagementFile = ROOT . '/app/resources/js/userManagement.js';
                        $loadViewFile = ROOT . '/app/resources/js/loadView.js';
                        
                        echo "<h6>Archivos JavaScript:</h6>";
                        echo "<ul>";
                        
                        if (file_exists($userManagementFile)) {
                            echo "<li class='text-success'>✅ userManagement.js encontrado</li>";
                            
                            // Verificar si contiene la función
                            $content = file_get_contents($userManagementFile);
                            if (strpos($content, 'initUserManagementAfterLoad') !== false) {
                                echo "<li class='text-success'>✅ Función initUserManagementAfterLoad encontrada</li>";
                            } else {
                                echo "<li class='text-danger'>❌ Función initUserManagementAfterLoad NO encontrada</li>";
                            }
                        } else {
                            echo "<li class='text-danger'>❌ userManagement.js NO encontrado</li>";
                        }
                        
                        if (file_exists($loadViewFile)) {
                            echo "<li class='text-success'>✅ loadView.js encontrado</li>";
                        } else {
                            echo "<li class='text-danger'>❌ loadView.js NO encontrado</li>";
                        }
                        
                        echo "</ul>";
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Test en Vivo</h5>
                    </div>
                    <div class="card-body">
                        <button id="testFunction" class="btn btn-primary">Probar Función</button>
                        <div id="testResults" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Console Log</h5>
                    </div>
                    <div class="card-body">
                        <div id="consoleOutput" style="background: #f8f9fa; padding: 10px; border-radius: 5px; font-family: monospace; height: 200px; overflow-y: auto;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Cargar los archivos JavaScript -->
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/loadView.js"></script>
    <script type="text/javascript" src="<?php echo url . app . rq ?>js/userManagement.js"></script>
    
    <script>
        // Interceptar console.log para mostrar en la página
        const originalConsoleLog = console.log;
        const originalConsoleWarn = console.warn;
        const originalConsoleError = console.error;
        
        function addToConsoleOutput(message, type = 'log') {
            const output = document.getElementById('consoleOutput');
            const timestamp = new Date().toLocaleTimeString();
            const color = type === 'error' ? 'red' : type === 'warn' ? 'orange' : 'black';
            output.innerHTML += `<div style="color: ${color};">[${timestamp}] ${message}</div>`;
            output.scrollTop = output.scrollHeight;
        }
        
        console.log = function(...args) {
            originalConsoleLog.apply(console, args);
            addToConsoleOutput(args.join(' '));
        };
        
        console.warn = function(...args) {
            originalConsoleWarn.apply(console, args);
            addToConsoleOutput(args.join(' '), 'warn');
        };
        
        console.error = function(...args) {
            originalConsoleError.apply(console, args);
            addToConsoleOutput(args.join(' '), 'error');
        };
        
        // Test de la función
        document.getElementById('testFunction').addEventListener('click', function() {
            const results = document.getElementById('testResults');
            results.innerHTML = '';
            
            console.log('=== INICIANDO TEST ===');
            
            // Verificar si las funciones están disponibles
            console.log('Verificando funciones disponibles...');
            console.log('loadView:', typeof loadView);
            console.log('initUserManagementAfterLoad:', typeof initUserManagementAfterLoad);
            
            if (typeof initUserManagementAfterLoad === 'function') {
                console.log('✅ initUserManagementAfterLoad está disponible');
                results.innerHTML += '<div class="alert alert-success">✅ Función disponible</div>';
                
                try {
                    console.log('Ejecutando initUserManagementAfterLoad...');
                    initUserManagementAfterLoad();
                    results.innerHTML += '<div class="alert alert-success">✅ Función ejecutada sin errores</div>';
                } catch (error) {
                    console.error('Error al ejecutar initUserManagementAfterLoad:', error);
                    results.innerHTML += '<div class="alert alert-danger">❌ Error al ejecutar: ' + error.message + '</div>';
                }
            } else {
                console.error('❌ initUserManagementAfterLoad NO está disponible');
                results.innerHTML += '<div class="alert alert-danger">❌ Función NO disponible</div>';
            }
            
            console.log('=== FIN TEST ===');
        });
        
        // Verificación automática al cargar
        window.addEventListener('load', function() {
            console.log('Página cargada, verificando funciones...');
            console.log('initUserManagementAfterLoad disponible:', typeof initUserManagementAfterLoad);
        });
    </script>
</body>
</html> 