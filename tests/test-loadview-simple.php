<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Simple loadView.js</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Test Simple loadView.js</h1>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Pruebas Básicas</h5>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-primary mb-2" onclick="testLoadView()">
                            <i class="fas fa-play"></i> Probar loadView
                        </button>
                        <br>
                        <button class="btn btn-success mb-2" onclick="testMenuRoot()">
                            <i class="fas fa-home"></i> Probar menuRoot
                        </button>
                        <br>
                        <button class="btn btn-info mb-2" onclick="checkLoadViewFunction()">
                            <i class="fas fa-check"></i> Verificar Función
                        </button>
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
                            Haz clic en un botón para probar
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

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Configuración de URL base -->
    <script>
        const BASE_URL = 'http://localhost:8000/';
        console.log('BASE_URL configurada:', BASE_URL);
    </script>
    
    <!-- Cargar loadView.js -->
    <script src="../app/resources/js/loadView.js"></script>
    
    <script>
        function updateResult(message, type = 'info') {
            const resultDiv = document.getElementById('testResult');
            resultDiv.className = `alert alert-${type}`;
            resultDiv.innerHTML = message;
        }
        
        function checkLoadViewFunction() {
            console.log('Verificando función loadView...');
            
            if (typeof loadView === 'function') {
                updateResult('✅ Función loadView está disponible', 'success');
                console.log('loadView es una función:', loadView);
            } else {
                updateResult('❌ Función loadView NO está disponible', 'danger');
                console.error('loadView no es una función:', typeof loadView);
            }
        }
        
        function testLoadView() {
            console.log('Probando loadView básico...');
            updateResult('🔄 Probando loadView...', 'info');
            
            try {
                // Probar con una vista simple
                loadView('root/menuRoot');
                updateResult('✅ loadView ejecutado sin errores', 'success');
            } catch (error) {
                updateResult(`❌ Error en loadView: ${error.message}`, 'danger');
                console.error('Error en loadView:', error);
            }
        }
        
        function testMenuRoot() {
            console.log('Probando carga de menuRoot...');
            updateResult('🔄 Cargando menuRoot...', 'info');
            
            try {
                loadView('root/menuRoot');
                updateResult('✅ menuRoot cargado', 'success');
            } catch (error) {
                updateResult(`❌ Error cargando menuRoot: ${error.message}`, 'danger');
                console.error('Error cargando menuRoot:', error);
            }
        }
        
        // Verificar al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Página cargada, verificando loadView...');
            checkLoadViewFunction();
        });
    </script>
</body>
</html> 