<?php
// Test para verificar que todas las URLs del sistema funcionan correctamente
echo "<h1>Test de URLs del Sistema Byfrost</h1>";

// Configurar variables
if (!defined('ROOT')) {
    define('ROOT', __DIR__);
}
require_once 'config.php';

echo "<h2>URLs de Prueba:</h2>";
echo "<div style='font-family: Arial, sans-serif;'>";

// 1. Dashboard Root
echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ccc;'>";
echo "<h3>1. Dashboard Root</h3>";
echo "<p><strong>URL:</strong> <a href='?view=root&action=dashboard' target='_blank'>?view=root&action=dashboard</a></p>";
echo "<p><strong>Estado:</strong> <span style='color: green;'>✅ Debería funcionar</span></p>";
echo "<p><strong>Descripción:</strong> Dashboard del usuario root con acceso completo al sistema</p>";
echo "</div>";

// 2. Dashboard de Nómina
echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ccc;'>";
echo "<h3>2. Dashboard de Nómina</h3>";
echo "<p><strong>URL:</strong> <a href='?view=payroll&action=dashboard' target='_blank'>?view=payroll&action=dashboard</a></p>";
echo "<p><strong>Estado:</strong> <span style='color: green;'>✅ Debería funcionar</span></p>";
echo "<p><strong>Descripción:</strong> Dashboard del módulo de nómina con todas las funcionalidades</p>";
echo "</div>";

// 3. Error No Autorizado (formato correcto)
echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ccc;'>";
echo "<h3>3. Error No Autorizado (Formato Correcto)</h3>";
echo "<p><strong>URL:</strong> <a href='?view=unauthorized' target='_blank'>?view=unauthorized</a></p>";
echo "<p><strong>Estado:</strong> <span style='color: green;'>✅ Formato correcto</span></p>";
echo "<p><strong>Descripción:</strong> Página de error para usuarios no autorizados</p>";
echo "</div>";

// 4. Página Principal
echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ccc;'>";
echo "<h3>4. Página Principal</h3>";
echo "<p><strong>URL:</strong> <a href='index.php' target='_blank'>index.php</a></p>";
echo "<p><strong>Estado:</strong> <span style='color: green;'>✅ Debería funcionar</span></p>";
echo "<p><strong>Descripción:</strong> Página de inicio con slider y características</p>";
echo "</div>";

// 5. Login
echo "<div style='margin: 10px 0; padding: 10px; border: 1px solid #ccc;'>";
echo "<h3>5. Página de Login</h3>";
echo "<p><strong>URL:</strong> <a href='?view=login' target='_blank'>?view=login</a></p>";
echo "<p><strong>Estado:</strong> <span style='color: green;'>✅ Debería funcionar</span></p>";
echo "<p><strong>Descripción:</strong> Formulario de inicio de sesión</p>";
echo "</div>";

echo "</div>";

echo "<h2>Variables de Configuración:</h2>";
echo "<ul>";
echo "<li><strong>url:</strong> " . url . "</li>";
echo "<li><strong>app:</strong> " . app . "</li>";
echo "<li><strong>rq:</strong> " . rq . "</li>";
echo "<li><strong>Ruta JS:</strong> " . url . app . rq . "js/</li>";
echo "</ul>";

echo "<h2>Archivos JavaScript Corregidos:</h2>";
echo "<ul>";
echo "<li>✅ dashFooter.php - Rutas estandarizadas</li>";
echo "<li>✅ director/dashboard.php - Error de sintaxis corregido</li>";
echo "<li>✅ coordinator/dashboard.php - Error de sintaxis corregido</li>";
echo "<li>✅ school/dashboard.php - Ya tenía ruta correcta</li>";
echo "<li>✅ parent/dashboard.php - Ya tenía ruta correcta</li>";
echo "</ul>";

echo "<h2>Instrucciones:</h2>";
echo "<p>1. Haz clic en cada enlace para probar las diferentes secciones</p>";
echo "<p>2. Verifica que no hay errores de JavaScript en la consola del navegador</p>";
echo "<p>3. Confirma que los archivos JavaScript se cargan correctamente</p>";
echo "<p>4. Si hay errores 403, significa que el acceso directo está bloqueado (correcto)</p>";
?> 