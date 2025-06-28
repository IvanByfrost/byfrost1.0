<?php
http_response_code(404);
// Prevenir cache
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 404 - Página no encontrada</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        .error-container { max-width: 600px; margin: 0 auto; }
        .error-code { font-size: 72px; color: #e74c3c; margin-bottom: 20px; }
        .error-message { font-size: 24px; color: #2c3e50; margin-bottom: 20px; }
        .debug-info { background: #f8f9fa; padding: 20px; border-radius: 5px; margin-top: 30px; text-align: left; }
        .debug-info h3 { color: #495057; }
        .debug-info p { margin: 5px 0; color: #6c757d; }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">404</div>
        <div class="error-message">Página no encontrada</div>
        <p>La página que buscas no existe o ha sido movida.</p>
        <a href="/byfrost1.0/">Volver al inicio</a>
        
        <div class="debug-info">
            <h3>Información de depuración:</h3>
            <p><strong>URL solicitada:</strong> <?php echo htmlspecialchars($_SERVER['REQUEST_URI'] ?? 'N/A'); ?></p>
            <p><strong>Método:</strong> <?php echo htmlspecialchars($_SERVER['REQUEST_METHOD'] ?? 'N/A'); ?></p>
            <p><strong>Timestamp:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
        </div>
    </div>
</body>
</html> 