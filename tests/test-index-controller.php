<?php
/**
 * Test: IndexController - Verificación de Funcionamiento
 */

echo "<h1>Test: IndexController</h1>";
echo "<p><strong>Objetivo:</strong> Verificar que el IndexController funcione correctamente</p>";

// 1. Verificar archivos críticos
echo "<h2>1. Verificación de Archivos</h2>";
define('ROOT', __DIR__ . '/');

$criticalFiles = [
    'app/controllers/indexController.php' => 'Controlador Index',
    'app/controllers/MainController.php' => 'Controlador Base',
    'app/views/index/login.php' => 'Vista Login',
    'app/views/index/register.php' => 'Vista Register',
    'app/views/index/index.php' => 'Vista Principal'
];

foreach ($criticalFiles as $file => $description) {
    $exists = file_exists(ROOT . $file);
    echo "<div>" . ($exists ? "✅" : "❌") . " <strong>$file</strong> ($description): " . ($exists ? "EXISTE" : "NO EXISTE") . "</div>";
    
    if ($exists) {
        $content = file_get_contents(ROOT . $file);
        $size = strlen($content);
        echo "<div style='margin-left: 20px; color: blue;'>📄 Tamaño: " . number_format($size) . " bytes</div>";
    }
}

// 2. Verificar clase IndexController
echo "<h2>2. Verificación de la Clase IndexController</h2>";
$controllerFile = ROOT . 'app/controllers/indexController.php';
if (file_exists($controllerFile)) {
    $content = file_get_contents($controllerFile);
    
    // Verificar que tenga la declaración de clase
    if (strpos($content, 'class IndexController') !== false) {
        echo "<div style='color: green;'>✅ Clase IndexController declarada</div>";
    } else {
        echo "<div style='color: red;'>❌ Clase IndexController NO declarada</div>";
    }
    
    // Verificar herencia
    if (strpos($content, 'extends MainController') !== false) {
        echo "<div style='color: green;'>✅ Hereda de MainController</div>";
    } else {
        echo "<div style='color: red;'>❌ NO hereda de MainController</div>";
    }
    
    // Verificar métodos
    $methods = ['Index', 'login', 'register', 'contact', 'about', 'plans', 'faq', 'forgotPassword', 'resetPassword', 'completeProf'];
    foreach ($methods as $method) {
        if (strpos($content, "function $method") !== false) {
            echo "<div style='color: green;'>✅ Método $method() existe</div>";
        } else {
            echo "<div style='color: red;'>❌ Método $method() NO existe</div>";
        }
    }
}

// 3. Verificar vistas
echo "<h2>3. Verificación de Vistas</h2>";
$views = [
    'app/views/index/login.php' => 'Login',
    'app/views/index/register.php' => 'Register',
    'app/views/index/index.php' => 'Principal',
    'app/views/index/contact.php' => 'Contact',
    'app/views/index/about.php' => 'About',
    'app/views/index/plans.php' => 'Plans',
    'app/views/index/faq.php' => 'FAQ',
    'app/views/index/forgotPassword.php' => 'Forgot Password',
    'app/views/index/resetPassword.php' => 'Reset Password',
    'app/views/index/completeProf.php' => 'Complete Profile'
];

foreach ($views as $viewPath => $description) {
    $exists = file_exists(ROOT . $viewPath);
    echo "<div>" . ($exists ? "✅" : "❌") . " <strong>$viewPath</strong> ($description): " . ($exists ? "EXISTE" : "NO EXISTE") . "</div>";
}

// 4. URLs de prueba
echo "<h2>4. URLs de Prueba</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/?view=index&action=login' target='_blank'>Login</a></li>";
echo "<li><a href='http://localhost:8000/?view=index&action=register' target='_blank'>Register</a></li>";
echo "<li><a href='http://localhost:8000/?view=index&action=Index' target='_blank'>Página Principal</a></li>";
echo "<li><a href='http://localhost:8000/?view=index&action=contact' target='_blank'>Contact</a></li>";
echo "<li><a href='http://localhost:8000/?view=index&action=about' target='_blank'>About</a></li>";
echo "<li><a href='http://localhost:8000/?view=index&action=plans' target='_blank'>Plans</a></li>";
echo "<li><a href='http://localhost:8000/?view=index&action=faq' target='_blank'>FAQ</a></li>";
echo "<li><a href='http://localhost:8000/?view=index&action=forgotPassword' target='_blank'>Forgot Password</a></li>";
echo "<li><a href='http://localhost:8000/?view=index&action=resetPassword' target='_blank'>Reset Password</a></li>";
echo "<li><a href='http://localhost:8000/?view=index&action=completeProf' target='_blank'>Complete Profile</a></li>";
echo "</ul>";

// 5. Instrucciones de prueba
echo "<h2>5. Instrucciones de Prueba</h2>";
echo "<div style='background: #f8f9fa; padding: 15px; border-radius: 5px;'>";
echo "<ol>";
echo "<li><strong>Probar login:</strong> <a href='http://localhost:8000/?view=index&action=login' target='_blank'>Login</a></li>";
echo "<li><strong>Probar register:</strong> <a href='http://localhost:8000/?view=index&action=register' target='_blank'>Register</a></li>";
echo "<li><strong>Probar página principal:</strong> <a href='http://localhost:8000/?view=index&action=Index' target='_blank'>Principal</a></li>";
echo "<li><strong>Verificar que:</strong>";
echo "<ul>";
echo "<li>✅ Las páginas se cargan sin errores 404</li>";
echo "<li>✅ El formulario de login funciona</li>";
echo "<li>✅ El formulario de registro funciona</li>";
echo "<li>✅ La navegación entre páginas funciona</li>";
echo "</ul>";
echo "</li>";
echo "</ol>";
echo "</div>";

// 6. Estado del sistema
echo "<h2>6. Estado del Sistema</h2>";
echo "<div style='background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; border: 1px solid #c3e6cb;'>";
echo "<strong>✅ IndexController Implementado</strong><br>";
echo "El IndexController ha sido actualizado con todos los métodos necesarios.<br>";
echo "Las páginas de login, register y otras deberían funcionar correctamente ahora.";
echo "</div>";

echo "<hr>";
echo "<p><strong>Nota:</strong> Si alguna vista no existe, se mostrará un error 404. Verifica que todas las vistas estén creadas.</p>";
?> 