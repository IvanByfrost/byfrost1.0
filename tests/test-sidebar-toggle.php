<?php
/**
 * Test para verificar que el toggle de submenús funciona correctamente
 */

// Incluir configuración
require_once 'config.php';
?>

<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Test Sidebar Toggle</title>
    <link rel='stylesheet' href='app/resources/css/sidebar.css'>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .test-container { display: flex; min-height: 100vh; }
        .test-sidebar { width: 200px; background: #88e1ff; padding: 20px; }
        .test-content { flex: 1; padding: 20px; }
        .test-info { background: #f0f0f0; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .test-button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; margin: 5px; }
        .test-button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <h1>Test de Sidebar Toggle</h1>
    
    <div class='test-container'>
        <div class='test-sidebar'>
            <h3>Sidebar de Prueba</h3>
            
            <ul>
                <li><a href='#'><i data-lucide='home'></i>Inicio</a></li>
                <li class='has-submenu'>
                    <a href='#'><i data-lucide='school'></i>Colegios</a>
                    <ul class='submenu'>
                        <li><a href='#'>Registrar Colegio</a></li>
                        <li><a href='#'>Consultar Colegios</a></li>
                    </ul>
                </li>
                <li class='has-submenu'>
                    <a href='#'><i data-lucide='users'></i>Usuarios</a>
                    <ul class='submenu'>
                        <li><a href='#'>Consultar Usuarios</a></li>
                        <li><a href='#'>Asignar Roles</a></li>
                        <li><a href='#'>Historial de Roles</a></li>
                    </ul>
                </li>
                <li class='has-submenu'>
                    <a href='#'><i data-lucide='dollar-sign'></i>Nómina</a>
                    <ul class='submenu'>
                        <li><a href='#'>Dashboard de Nómina</a></li>
                        <li><a href='#'>Gestionar Empleados</a></li>
                        <li><a href='#'>Períodos de Pago</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        
        <div class='test-content'>
            <div class='test-info'>
                <h3>Instrucciones de Prueba:</h3>
                <ol>
                    <li><strong>Haz clic en los elementos con submenús</strong> (Colegios, Usuarios, Nómina)</li>
                    <li><strong>Verifica que los submenús se expanden/colapsan</strong></li>
                    <li><strong>Abre la consola del navegador</strong> (F12) para ver logs</li>
                    <li><strong>Verifica que solo un submenú esté abierto a la vez</strong></li>
                </ol>
            </div>
            
            <div class='test-info'>
                <h3>Funcionalidades a Verificar:</h3>
                <ul>
                    <li>✅ <strong>Toggle de submenús:</strong> Los submenús deben expandirse/colapsarse al hacer clic</li>
                    <li>✅ <strong>Animaciones suaves:</strong> Las transiciones deben ser fluidas</li>
                    <li>✅ <strong>Un solo submenú abierto:</strong> Solo debe haber un submenú abierto a la vez</li>
                    <li>✅ <strong>Logs en consola:</strong> Debe haber mensajes de debug en la consola</li>
                </ul>
            </div>
            
            <div class='test-info'>
                <h3>Botones de Prueba:</h3>
                <button class='test-button' onclick='testReinitialize()'>Reinicializar Submenús</button>
                <button class='test-button' onclick='testToggleAll()'>Alternar Todos los Submenús</button>
                <button class='test-button' onclick='testCloseAll()'>Cerrar Todos los Submenús</button>
            </div>
            
            <div class='test-info'>
                <h3>Estado Actual:</h3>
                <div id='status'>Cargando...</div>
            </div>
        </div>
    </div>
    
    <script src='https://unpkg.com/lucide@latest/dist/umd/lucide.js'></script>
    <script src='app/resources/js/sidebarToggle.js'></script>
    
    <script>
        // Función para actualizar el estado
        function updateStatus() {
            const submenus = document.querySelectorAll('.has-submenu');
            const activeSubmenus = document.querySelectorAll('.has-submenu.active');
            const statusDiv = document.getElementById('status');
            
            statusDiv.innerHTML = `
                <p><strong>Submenús totales:</strong> ${submenus.length}</p>
                <p><strong>Submenús activos:</strong> ${activeSubmenus.length}</p>
                <p><strong>Submenús activos:</strong> ${Array.from(activeSubmenus).map(s => s.querySelector('a').textContent.trim()).join(', ') || 'Ninguno'}</p>
            `;
        }
        
        // Función para reinicializar submenús
        function testReinitialize() {
            console.log('Test: Reinicializando submenús...');
            if (typeof window.reinitializeSidebarSubmenus === 'function') {
                window.reinitializeSidebarSubmenus();
                updateStatus();
            } else {
                console.error('Función reinitializeSidebarSubmenus no disponible');
            }
        }
        
        // Función para alternar todos los submenús
        function testToggleAll() {
            console.log('Test: Alternando todos los submenús...');
            const submenus = document.querySelectorAll('.has-submenu');
            submenus.forEach(submenu => {
                const link = submenu.querySelector('a');
                if (link) {
                    link.click();
                }
            });
            setTimeout(updateStatus, 500);
        }
        
        // Función para cerrar todos los submenús
        function testCloseAll() {
            console.log('Test: Cerrando todos los submenús...');
            const activeSubmenus = document.querySelectorAll('.has-submenu.active');
            activeSubmenus.forEach(submenu => {
                const link = submenu.querySelector('a');
                if (link) {
                    link.click();
                }
            });
            setTimeout(updateStatus, 500);
        }
        
        // Actualizar estado cada segundo
        setInterval(updateStatus, 1000);
        
        // Inicializar Lucide
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
            updateStatus();
        });
        
        console.log('Test de Sidebar Toggle cargado');
    </script>
</body>
</html> 