<?php
// Script de debug para identificar eventos que causan redirección
echo "<h1>Debug de Eventos de Formulario</h1>";

echo "<h2>Problema: Redirección al hacer clic en input vacío</h2>";
echo "<p>Vamos a identificar qué eventos están causando la redirección.</p>";

echo "<h3>Posibles Causas:</h3>";
echo "<ol>";
echo "<li><strong>Evento onkeyup</strong>: La función onlyNumbers podría estar interfiriendo</li>";
echo "<li><strong>Evento submit del formulario</strong>: Podría estar ejecutándose sin validación</li>";
echo "<li><strong>Evento click del botón</strong>: Podría estar causando envío del formulario</li>";
echo "<li><strong>Evento keypress</strong>: Enter podría estar enviando el formulario</li>";
echo "<li><strong>Evento change</strong>: Podría estar disparando algún evento</li>";
echo "</ol>";

echo "<h3>Script de Debug para Implementar:</h3>";
echo "<pre>";
echo "// Agregar este código temporalmente para debug
document.addEventListener('DOMContentLoaded', function() {
    console.log('=== DEBUG: Iniciando monitoreo de eventos ===');
    
    // Monitorear todos los eventos del formulario
    const directorForm = document.getElementById('searchDirectorForm');
    if (directorForm) {
        console.log('DEBUG: Formulario de director encontrado');
        
        // Monitorear evento submit
        directorForm.addEventListener('submit', function(e) {
            console.log('DEBUG: Evento submit disparado');
            console.log('DEBUG: e.defaultPrevented:', e.defaultPrevented);
            console.log('DEBUG: e.target:', e.target);
            console.log('DEBUG: e.currentTarget:', e.currentTarget);
        });
        
        // Monitorear evento click del botón
        const submitBtn = directorForm.querySelector('button[type=\"submit\"]');
        if (submitBtn) {
            submitBtn.addEventListener('click', function(e) {
                console.log('DEBUG: Click en botón submit');
                console.log('DEBUG: e.target:', e.target);
            });
        }
        
        // Monitorear evento keypress en el input
        const input = document.getElementById('search_director_query');
        if (input) {
            input.addEventListener('keypress', function(e) {
                console.log('DEBUG: Keypress en input:', e.key);
                if (e.key === 'Enter') {
                    console.log('DEBUG: Enter presionado');
                }
            });
            
            input.addEventListener('keyup', function(e) {
                console.log('DEBUG: Keyup en input:', e.key);
            });
            
            input.addEventListener('change', function(e) {
                console.log('DEBUG: Change en input');
            });
        }
    }
    
    // Monitorear eventos de window
    window.addEventListener('beforeunload', function(e) {
        console.log('DEBUG: beforeunload disparado');
    });
    
    window.addEventListener('unload', function(e) {
        console.log('DEBUG: unload disparado');
    });
    
    // Monitorear navegación
    window.addEventListener('popstate', function(e) {
        console.log('DEBUG: popstate disparado');
    });
    
    console.log('DEBUG: Monitoreo de eventos configurado');
});";
echo "</pre>";

echo "<h3>Instrucciones de Debug:</h3>";
echo "<ol>";
echo "<li>Agregar el código de debug temporalmente en createSchool.php</li>";
echo "<li>Abrir la consola del navegador (F12)</li>";
echo "<li>Ir al modal de crear escuela</li>";
echo "<li>Hacer clic en el input vacío</li>";
echo "<li>Presionar Enter en el input vacío</li>";
echo "<li>Hacer clic en el botón Buscar</li>";
echo "<li>Revisar los logs en la consola</li>";
echo "</ol>";

echo "<h3>Posibles Soluciones:</h3>";
echo "<ol>";
echo "<li><strong>Remover onkeyup</strong>: Eliminar onkeyup=\"onlyNumbers('search_director_query',value);\" del HTML</li>";
echo "<li><strong>Agregar novalidate</strong>: Agregar novalidate al formulario</li>";
echo "<li><strong>Prevenir eventos</strong>: Usar e.stopImmediatePropagation()</li>";
echo "<li><strong>Validar antes</strong>: Validar antes de cualquier evento</li>";
echo "</ol>";

echo "<h3>Prueba Rápida:</h3>";
echo "<p>Para probar si el problema es el evento onkeyup:</p>";
echo "<ol>";
echo "<li>Remover temporalmente onkeyup=\"onlyNumbers('search_director_query',value);\" del input</li>";
echo "<li>Probar si el problema persiste</li>";
echo "<li>Si no persiste, el problema es la función onlyNumbers</li>";
echo "<li>Si persiste, buscar otra causa</li>";
echo "</ol>";

echo "<h3>Logs a Revisar:</h3>";
echo "<ul>";
echo "<li><strong>Console del navegador</strong>: Eventos disparados</li>";
echo "<li><strong>Network tab</strong>: Peticiones HTTP</li>";
echo "<li><strong>Application tab</strong>: Cambios en sessionStorage/localStorage</li>";
echo "<li><strong>Sources tab</strong>: Breakpoints en JavaScript</li>";
echo "</ul>";
?> 