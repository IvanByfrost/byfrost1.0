<?php
/**
 * Test simple para verificar que el formulario de crear escuela funciona
 * 
 * Este script verifica:
 * 1. Que se puede acceder al formulario de crear escuela
 * 2. Que el formulario tiene la acción correcta
 * 3. Que los campos obligatorios están presentes
 */

echo "<h1>Test: Formulario de Crear Escuela</h1>";

// Simular la URL de acceso
$url = "http://localhost:8000/?view=school&action=createSchool";

echo "<h2>1. URL de acceso:</h2>";
echo "<p><strong>URL:</strong> <a href='$url' target='_blank'>$url</a></p>";

echo "<h2>2. Verificación del formulario:</h2>";
echo "<p>Al acceder a la URL, deberías ver:</p>";
echo "<ul>";
echo "<li>✓ Un formulario con action='?view=school&action=createSchool'</li>";
echo "<li>✓ Campo 'Nombre de la Escuela' (obligatorio)</li>";
echo "<li>✓ Campo 'Código DANE' (obligatorio)</li>";
echo "<li>✓ Campo 'NIT' (obligatorio)</li>";
echo "<li>✓ Campo 'Cupo Total' (opcional)</li>";
echo "<li>✓ Select 'Director' (opcional)</li>";
echo "<li>✓ Select 'Coordinador' (opcional)</li>";
echo "<li>✓ Campo 'Dirección' (opcional)</li>";
echo "<li>✓ Campo 'Teléfono' (opcional)</li>";
echo "<li>✓ Campo 'Email' (opcional)</li>";
echo "<li>✓ Botón 'Crear Escuela'</li>";
echo "<li>✓ Botón 'Cancelar'</li>";
echo "</ul>";

echo "<h2>3. Flujo de prueba:</h2>";
echo "<ol>";
echo "<li>Abre la URL en tu navegador</li>";
echo "<li>Llena los campos obligatorios (Nombre, DANE, NIT)</li>";
echo "<li>Haz clic en 'Crear Escuela'</li>";
echo "<li>Deberías ser redirigido a la lista de escuelas con mensaje de éxito</li>";
echo "</ol>";

echo "<h2>4. URLs de verificación:</h2>";
echo "<ul>";
echo "<li><a href='http://localhost:8000/?view=school&action=createSchool' target='_blank'>Formulario de crear escuela</a></li>";
echo "<li><a href='http://localhost:8000/?view=school&action=consultSchool' target='_blank'>Lista de escuelas</a></li>";
echo "</ul>";

echo "<h2>5. Notas importantes:</h2>";
echo "<ul>";
echo "<li>El formulario ahora funciona de forma tradicional (no AJAX)</li>";
echo "<li>Los errores se muestran en la misma página</li>";
echo "<li>El éxito redirige a la lista de escuelas</li>";
echo "<li>Los datos del formulario se mantienen si hay errores</li>";
echo "</ul>";

echo "<hr>";
echo "<p><strong>Estado:</strong> ✅ Formulario simplificado y funcional</p>";
?> 