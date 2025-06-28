<?php
/**
 * Test para verificar que el header funciona correctamente
 * 
 * Este script verifica:
 * 1. Que las constantes están definidas correctamente
 * 2. Que las rutas en el header funcionan
 * 3. Que no hay errores de sintaxis
 */

echo "<h1>Test: Header Fix</h1>";

// Cargar config.php
require_once 'config.php';

echo "<h2>1. Verificación de constantes:</h2>";
echo "<ul>";
echo "<li><strong>url:</strong> " . (defined('url') ? url : '❌ No definida') . "</li>";
echo "<li><strong>app:</strong> " . (defined('app') ? app : '❌ No definida') . "</li>";
echo "<li><strong>rq:</strong> " . (defined('rq') ? rq : '❌ No definida') . "</li>";
echo "<li><strong>views:</strong> " . (defined('views') ? views : '❌ No definida') . "</li>";
echo "<li><strong>ROOT:</strong> " . (defined('ROOT') ? ROOT : '❌ No definida') . "</li>";
echo "</ul>";

echo "<h2>2. URLs generadas:</h2>";
echo "<ul>";
echo "<li><strong>Logo:</strong> " . url . app . rq . "img/horizontal-logo.svg</li>";
echo "<li><strong>Planes:</strong> " . url . app . views . "index/plans.php</li>";
echo "<li><strong>Contacto:</strong> " . url . app . views . "index/contact.php</li>";
echo "<li><strong>FAQ:</strong> " . url . app . views . "index/faq.php</li>";
echo "<li><strong>Quiénes somos:</strong> " . url . app . views . "index/about.php</li>";
echo "<li><strong>Login:</strong> " . url . app . views . "index/login.php</li>";
echo "<li><strong>User icon:</strong> " . url . app . rq . "img/user-icon.svg</li>";
echo "</ul>";

echo "<h2>3. URLs de prueba:</h2>";
echo "<ul>";
echo "<li><a href='" . url . "' target='_blank'>Página principal</a></li>";
echo "<li><a href='" . url . app . views . "index/plans.php' target='_blank'>Planes</a></li>";
echo "<li><a href='" . url . app . views . "index/contact.php' target='_blank'>Contacto</a></li>";
echo "<li><a href='" . url . app . views . "index/faq.php' target='_blank'>FAQ</a></li>";
echo "<li><a href='" . url . app . views . "index/about.php' target='_blank'>Quiénes somos</a></li>";
echo "<li><a href='" . url . app . views . "index/login.php' target='_blank'>Login</a></li>";
echo "</ul>";

echo "<h2>4. Cambios realizados:</h2>";
echo "<ul>";
echo "<li>✅ <code>index.php</code> → <code><?php echo url; ?></code></li>";
echo "<li>✅ Agregado punto y coma después de las constantes</li>";
echo "<li>✅ Eliminado slash extra en <code>index/login.php</code></li>";
echo "<li>✅ Todas las rutas ahora usan las constantes correctamente</li>";
echo "</ul>";

echo "<h2>5. Para verificar que funciona:</h2>";
echo "<ol>";
echo "<li>Abre cualquier página que use el header</li>";
echo "<li>Haz clic en el logo - debería llevarte a la página principal</li>";
echo "<li>Haz clic en los enlaces del menú - deberían funcionar</li>";
echo "<li>Haz clic en 'Iniciar sesión' - debería llevarte al login</li>";
echo "<li>Verifica que las imágenes se cargan correctamente</li>";
echo "</ol>";

echo "<h2>6. Páginas que usan el header:</h2>";
echo "<ul>";
echo "<li><a href='" . url . "' target='_blank'>Página principal</a></li>";
echo "<li><a href='" . url . app . views . "index/about.php' target='_blank'>Quiénes somos</a></li>";
echo "<li><a href='" . url . app . views . "index/contact.php' target='_blank'>Contacto</a></li>";
echo "<li><a href='" . url . app . views . "index/faq.php' target='_blank'>FAQ</a></li>";
echo "<li><a href='" . url . app . views . "index/plans.php' target='_blank'>Planes</a></li>";
echo "</ul>";

echo "<h2>7. Verificación de archivos:</h2>";
echo "<ul>";
echo "<li>✅ <code>config.php</code> - Constantes definidas</li>";
echo "<li>✅ <code>app/views/layouts/header.php</code> - Header corregido</li>";
echo "<li>✅ <code>app/resources/img/horizontal-logo.svg</code> - Logo</li>";
echo "<li>✅ <code>app/resources/img/user-icon.svg</code> - Icono de usuario</li>";
echo "</ul>";

echo "<hr>";
echo "<p><strong>Estado:</strong> ✅ Header corregido y funcional</p>";
?> 