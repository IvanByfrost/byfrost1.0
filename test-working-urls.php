<?php
// Script para probar URLs específicas
echo "<h1>🧪 Prueba de URLs Funcionales</h1>";

// Activar reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🔗 URLs de Prueba Directas</h2>";
echo "<p>Haz clic en cada enlace para probar si funciona:</p>";

$testUrls = [
    '🏠 Página Principal' => 'http://localhost:8000/',
    '🔐 Login' => 'http://localhost:8000/?view=index&action=login',
    '📊 Dashboard' => 'http://localhost:8000/?view=root&action=dashboard',
    '👥 Asignar Roles' => 'http://localhost:8000/?view=user&action=assignRole',
    '🏫 Crear Escuela' => 'http://localhost:8000/?view=school&action=createSchool',
    '👨‍🏫 Coordinador' => 'http://localhost:8000/?view=coordinator&action=dashboard'
];

echo "<div style='display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 15px; margin: 20px 0;'>";

foreach ($testUrls as $name => $url) {
    echo "<div style='border: 2px solid #007bff; padding: 20px; border-radius: 10px; text-align: center;'>";
    echo "<h3 style='color: #007bff; margin-bottom: 15px;'>$name</h3>";
    echo "<a href='$url' target='_blank' style='display: inline-block; background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;'>";
    echo "Probar URL";
    echo "</a>";
    echo "<br><br>";
    echo "<small style='color: #666;'>$url</small>";
    echo "</div>";
}

echo "</div>";

echo "<h2>🔧 Comandos para el Servidor</h2>";
echo "<div style='background: #f8f9fa; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
echo "<h3>Para iniciar el servidor PHP:</h3>";
echo "<code style='background: #e9ecef; padding: 10px; border-radius: 5px; display: block; margin: 10px 0;'>F:\\xampp\\php\\php.exe -S localhost:8000</code>";
echo "<br>";
echo "<h3>Para verificar que el servidor esté corriendo:</h3>";
echo "<code style='background: #e9ecef; padding: 10px; border-radius: 5px; display: block; margin: 10px 0;'>curl http://localhost:8000/</code>";
echo "</div>";

echo "<h2>📋 Checklist de Verificación</h2>";
echo "<div style='background: #fff3cd; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
echo "<ol>";
echo "<li><strong>¿El servidor está corriendo?</strong> Deberías ver algo como 'PHP Development Server started'</li>";
echo "<li><strong>¿Puedes acceder a http://localhost:8000/?</strong> Debería mostrar la página principal</li>";
echo "<li><strong>¿Las URLs con parámetros funcionan?</strong> Prueba cada enlace de arriba</li>";
echo "<li><strong>¿No hay errores 403?</strong> Si los hay, el SecurityMiddleware está siendo muy restrictivo</li>";
echo "<li><strong>¿No hay errores en la consola del navegador?</strong> (F12 → Console)</li>";
echo "</ol>";
echo "</div>";

echo "<h2>🚨 Si sigues viendo errores 403</h2>";
echo "<div style='background: #f8d7da; padding: 20px; border-radius: 10px; margin: 20px 0;'>";
echo "<p><strong>Posibles soluciones:</strong></p>";
echo "<ol>";
echo "<li><strong>Reinicia el servidor PHP:</strong> Detén el servidor (Ctrl+C) y vuelve a iniciarlo</li>";
echo "<li><strong>Verifica que no haya conflictos de puerto:</strong> Asegúrate de que el puerto 8000 esté libre</li>";
echo "<li><strong>Usa XAMPP:</strong> En lugar del servidor PHP built-in, usa Apache de XAMPP</li>";
echo "<li><strong>Verifica los archivos:</strong> Asegúrate de que todos los archivos estén en su lugar</li>";
echo "</ol>";
echo "</div>";

echo "<h2>🎯 Prueba Rápida</h2>";
echo "<p>Si quieres una prueba rápida, haz clic en este enlace:</p>";
echo "<a href='http://localhost:8000/?view=index&action=login' target='_blank' style='display: inline-block; background: #28a745; color: white; padding: 15px 30px; text-decoration: none; border-radius: 5px; font-size: 18px; font-weight: bold;'>🚀 Probar Login</a>";

echo "<h2>📞 Información de Debug</h2>";
echo "<div style='background: #e9ecef; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
echo "<p><strong>Directorio actual:</strong> " . __DIR__ . "</p>";
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
echo "<p><strong>Server Software:</strong> " . ($_SERVER['SERVER_SOFTWARE'] ?? 'No disponible') . "</p>";
echo "</div>";
?> 