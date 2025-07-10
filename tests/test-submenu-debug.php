<?php
require_once '../config.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Test Submenu Debug</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../app/resources/css/sidebar.css" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="root-sidebar">
                <ul>
                    <li><a href="#"><i class="fas fa-home"></i>Inicio</a></li>
                    <li class="has-submenu">
                        <a href="#"><i class="fas fa-school"></i>Colegios</a>
                        <ul class="submenu">
                            <li><a href="#" onclick="testClick('Registrar Colegio')">Registrar Colegio</a></li>
                            <li><a href="#" onclick="testClick('Consultar Colegio')">Consultar Colegio</a></li>
                        </ul>
                    </li>
                    <li class="has-submenu">
                        <a href="#"><i class="fas fa-users"></i>Usuarios</a>
                        <ul class="submenu">
                            <li><a href="#" onclick="testClick('Consultar Usuario')">Consultar Usuario</a></li>
                            <li><a href="#" onclick="testClick('Asignar rol')">Asignar rol</a></li>
                            <li><a href="#" onclick="testClick('Historial de roles')">Historial de roles</a></li>
                        </ul>
                    </li>
                    <li class="has-submenu">
                        <a href="#"><i class="fas fa-dollar-sign"></i>Nómina</a>
                        <ul class="submenu">
                            <li><a href="#" onclick="testClick('Dashboard')">Dashboard</a></li>
                            <li><a href="#" onclick="testClick('Empleados')">Empleados</a></li>
                            <li><a href="#" onclick="testClick('Períodos')">Períodos</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </aside>
        
        <div id="mainContent" class="mainContent">
            <div class="container-fluid">
                <h1>🔍 Test Submenu Debug</h1>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Diagnóstico de Submenús</h5>
                            </div>
                            <div class="card-body">
                                <button class="btn btn-primary mb-2" onclick="checkSubmenus()">
                                    <i class="fas fa-search"></i> Verificar Submenús
                                </button><br>
                                <button class="btn btn-info mb-2" onclick="testSubmenuToggle()">
                                    <i class="fas fa-toggle-on"></i> Test Toggle
                                </button><br>
                                <button class="btn btn-success mb-2" onclick="forceReinitialize()">
                                    <i class="fas fa-sync"></i> Forzar Reinicialización
                                </button><br>
                                <button class="btn btn-warning mb-2" onclick="showSubmenuState()">
                                    <i class="fas fa-info-circle"></i> Estado Actual
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Información del Diagnóstico</h5>
                            </div>
                            <div class="card-body">
                                <div id="diagnosticInfo">
                                    <p>Haz clic en "Verificar Submenús" para ver el diagnóstico</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5>Log de Eventos</h5>
                            </div>
                            <div class="card-body">
                                <div id="eventLog" class="bg-dark text-light p-3" style="height: 200px; overflow-y: auto; font-family: monospace; font-size: 12px;">
                                    <div>Esperando eventos...</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../app/resources/js/sidebarToggle.js"></script>
    
    <script>
    function addToLog(message, type = 'info') {
        const logDiv = document.getElementById('eventLog');
        const timestamp = new Date().toLocaleTimeString();
        const color = type === 'error' ? '#ff6b6b' : type === 'warn' ? '#ffd93d' : '#6bcf7f';
        logDiv.innerHTML += `<div style="color: ${color};">[${timestamp}] ${message}</div>`;
        logDiv.scrollTop = logDiv.scrollHeight;
    }
    
    function checkSubmenus() {
        addToLog('=== Verificando submenús ===');
        
        const submenus = document.querySelectorAll('.has-submenu');
        addToLog(`Encontrados ${submenus.length} elementos con clase has-submenu`);
        
        submenus.forEach((submenu, index) => {
            const link = submenu.querySelector('a');
            const submenuList = submenu.querySelector('.submenu');
            
            addToLog(`Submenú ${index + 1}: ${link ? link.textContent.trim() : 'Sin enlace'}`);
            addToLog(`  - Tiene enlace: ${!!link}`);
            addToLog(`  - Tiene submenu: ${!!submenuList}`);
            addToLog(`  - Clase active: ${submenu.classList.contains('active')}`);
            
            if (submenuList) {
                addToLog(`  - Altura del submenu: ${submenuList.scrollHeight}px`);
                addToLog(`  - Max-height actual: ${submenuList.style.maxHeight || 'no definido'}`);
            }
        });
    }
    
    function testSubmenuToggle() {
        addToLog('=== Test de toggle de submenús ===');
        
        const submenus = document.querySelectorAll('.has-submenu');
        submenus.forEach((submenu, index) => {
            const link = submenu.querySelector('a');
            if (link) {
                addToLog(`Probando toggle en submenú ${index + 1}: ${link.textContent.trim()}`);
                
                // Simular click
                const clickEvent = new Event('click', { bubbles: true });
                link.dispatchEvent(clickEvent);
                
                setTimeout(() => {
                    const isActive = submenu.classList.contains('active');
                    addToLog(`  - Estado después del click: ${isActive ? 'Activo' : 'Inactivo'}`);
                }, 100);
            }
        });
    }
    
    function forceReinitialize() {
        addToLog('=== Forzando reinicialización ===');
        
        if (typeof window.reinitializeSidebarSubmenus === 'function') {
            window.reinitializeSidebarSubmenus();
            addToLog('Reinicialización ejecutada');
        } else {
            addToLog('ERROR: Función reinitializeSidebarSubmenus no disponible', 'error');
        }
    }
    
    function showSubmenuState() {
        addToLog('=== Estado actual de submenús ===');
        
        const submenus = document.querySelectorAll('.has-submenu');
        submenus.forEach((submenu, index) => {
            const link = submenu.querySelector('a');
            const submenuList = submenu.querySelector('.submenu');
            const isActive = submenu.classList.contains('active');
            
            addToLog(`Submenú ${index + 1}: ${link ? link.textContent.trim() : 'Sin enlace'}`);
            addToLog(`  - Estado: ${isActive ? 'ACTIVO' : 'Inactivo'}`);
            addToLog(`  - Eventos: ${link ? 'Tiene enlace' : 'Sin enlace'}`);
            
            if (submenuList) {
                const computedStyle = window.getComputedStyle(submenuList);
                addToLog(`  - Max-height: ${computedStyle.maxHeight}`);
                addToLog(`  - Opacity: ${computedStyle.opacity}`);
                addToLog(`  - Display: ${computedStyle.display}`);
            }
        });
    }
    
    function testClick(itemName) {
        addToLog(`Click en: ${itemName}`, 'info');
    }
    
    // Interceptar eventos de submenús
    document.addEventListener('click', function(e) {
        if (e.target.closest('.has-submenu a')) {
            const submenu = e.target.closest('.has-submenu');
            const link = e.target.closest('a');
            addToLog(`Click detectado en: ${link.textContent.trim()}`, 'info');
            
            setTimeout(() => {
                const isActive = submenu.classList.contains('active');
                addToLog(`  - Estado después del click: ${isActive ? 'Activo' : 'Inactivo'}`, 'info');
            }, 50);
        }
    });
    
    // Verificar al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        addToLog('Página cargada, iniciando diagnóstico...', 'info');
        checkSubmenus();
    });
    
    // Interceptar console.log
    const originalLog = console.log;
    console.log = function(...args) {
        originalLog.apply(console, args);
        addToLog('Console: ' + args.join(' '), 'info');
    };
    </script>
</body>
</html> 