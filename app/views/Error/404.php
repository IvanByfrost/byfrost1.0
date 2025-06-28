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
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; background-color: #f8f9fa; }
        .error-container { max-width: 600px; margin: 0 auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .error-code { font-size: 72px; color: #e74c3c; margin-bottom: 20px; font-weight: bold; }
        .error-message { font-size: 24px; color: #2c3e50; margin-bottom: 20px; }
        .error-description { color: #6c757d; margin-bottom: 30px; line-height: 1.6; }
        .home-link { display: inline-block; background: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; margin-top: 20px; }
        .home-link:hover { background: #0056b3; color: white; text-decoration: none; }
        .debug-info { background: #f8f9fa; padding: 20px; border-radius: 5px; margin-top: 30px; text-align: left; border-left: 4px solid #007bff; }
        .debug-info h3 { color: #495057; margin-top: 0; }
        .debug-info p { margin: 5px 0; color: #6c757d; }
        .debug-info strong { color: #495057; }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-code">404</div>
        <div class="error-message">Página no encontrada</div>
        <div class="error-description">
            La página que buscas no existe o no tienes permisos para acceder a ella.<br>
            Si intentaste acceder directamente a un archivo del sistema, usa las rutas correctas de la aplicación.
        </div>
        <a href="/byfrost/" class="home-link">Volver al inicio</a>
        
        <div class="debug-info">
            <h3>Información de depuración:</h3>
            <p><strong>URL solicitada:</strong> <?php echo htmlspecialchars($_SERVER['REQUEST_URI'] ?? 'N/A'); ?></p>
            <p><strong>Método:</strong> <?php echo htmlspecialchars($_SERVER['REQUEST_METHOD'] ?? 'N/A'); ?></p>
            <p><strong>Timestamp:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
            <p><strong>Servidor:</strong> <?php echo htmlspecialchars($_SERVER['SERVER_NAME'] ?? 'N/A'); ?></p>
        </div>
    </div>
</body>
</html> 