<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Simple Modal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Test Simple Modal</h2>
        
        <form id="testForm">
            <div class="mb-3">
                <label for="testField" class="form-label">Campo de prueba *</label>
                <input type="text" class="form-control" id="testField" name="testField" required>
            </div>
            
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#testModal">
                Abrir Modal
            </button>
        </form>
    </div>

    <!-- Modal de prueba -->
    <div class="modal fade" id="testModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modal de Prueba</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Este es un modal de prueba.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM cargado');
            
            const testModal = document.getElementById('testModal');
            const testField = document.getElementById('testField');
            
            console.log('Modal:', testModal);
            console.log('Campo:', testField);
            
            if (testModal) {
                testModal.addEventListener('show.bs.modal', function(e) {
                    console.log('Evento show.bs.modal disparado');
                    
                    if (!testField.value.trim()) {
                        console.log('Campo vacío, previniendo modal');
                        e.preventDefault();
                        alert('Por favor complete el campo antes de abrir el modal');
                        return false;
                    }
                    
                    console.log('Campo válido, permitiendo modal');
                });
            }
        });
    </script>
</body>
</html> 