<?php
require_once '../config.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Test Submenu Conflict</title>
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
                        </ul>
                    </li>
                </ul>
            </div>
        </aside>
        
        <div id="mainContent" class="mainContent">
            <div class="container-fluid">
                <h1>🔍 Test Submenu Conflict</h1>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Test de Conflictos</h5>
                            </div>
                            <div class="card-body">
                                <button class="btn btn-primary mb-2" onclick="testWithoutConflicts()">
                                    <i class="fas fa-play"></i> Test Sin Conflictos
                                </button><br>
                                <button class="btn btn-info mb-2" onclick="testWithToggles()">
                                    <i class="fas fa-toggle-on"></i> Test Con toggles.js
                                </button><br>
                                <button class="btn btn-success mb-2" onclick="testWithLoadView()">
                                    <i class="fas fa-eye"></i> Test Con loadView.js
                                </button><br>
                                <button class="btn btn-warning mb-2" onclick="testWithAllScripts()">
                                    <i class="fas fa-cogs"></i> Test Con Todos los Scripts
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5>Resultados</h5>
                            </div>
                            <div class="card-body">
                                <div id="results">
                                    <p>Haz clic en un botón para ver los resultados</p>
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
    
    <script>
    function addToLog(message, type = 'info') {
        const logDiv = document.getElementById('eventLog');
        const timestamp = new Date().toLocaleTimeString();
        const color = type === 'error' ? '#ff6b6b' : type === 'warn' ? '#ffd93d' : '#6bcf7f';
        logDiv.innerHTML += `<div style="color: ${color};">[${timestamp}] ${message}</div>`;
        logDiv.scrollTop = logDiv.scrollHeight;
    }
    
    function testClick(itemName) {
        addToLog(`Click en: ${itemName}`, 'info');
    }
    
    function testWithoutConflicts() {
        addToLog('=== Test Sin Conflictos ===');
        
        // Cargar solo sidebarToggle.js
        const script = document.createElement('script');
        script.src = '../app/resources/js/sidebarToggle.js';
        script.onload = function() {
            addToLog('sidebarToggle.js cargado sin conflictos');
            setTimeout(() => {
                const submenus = document.querySelectorAll('.has-submenu');
                addToLog(`Submenús encontrados: ${submenus.length}`);
                
                // Verificar eventos
                let eventCount = 0;
                submenus.forEach(submenu => {
                    const link = submenu.querySelector('a');
                    if (link && link._submenuHandler) {
                        eventCount++;
                    }
                });
                
                addToLog(`Eventos registrados: ${eventCount}/${submenus.length}`);
                
                if (eventCount === submenus.length) {
                    addToLog('✅ Test sin conflictos: EXITOSO', 'info');
                } else {
                    addToLog('❌ Test sin conflictos: FALLIDO', 'error');
                }
            }, 500);
        };
        document.head.appendChild(script);
    }
    
    function testWithToggles() {
        addToLog('=== Test Con toggles.js ===');
        
        // Cargar toggles.js primero
        const togglesScript = document.createElement('script');
        togglesScript.src = '../app/resources/js/toggles.js';
        togglesScript.onload = function() {
            addToLog('toggles.js cargado');
            
            // Luego cargar sidebarToggle.js
            const sidebarScript = document.createElement('script');
            sidebarScript.src = '../app/resources/js/sidebarToggle.js';
            sidebarScript.onload = function() {
                addToLog('sidebarToggle.js cargado después de toggles.js');
                setTimeout(() => {
                    const submenus = document.querySelectorAll('.has-submenu');
                    let eventCount = 0;
                    submenus.forEach(submenu => {
                        const link = submenu.querySelector('a');
                        if (link && link._submenuHandler) {
                            eventCount++;
                        }
                    });
                    
                    addToLog(`Eventos registrados: ${eventCount}/${submenus.length}`);
                    
                    if (eventCount === submenus.length) {
                        addToLog('✅ Test con toggles.js: EXITOSO', 'info');
                    } else {
                        addToLog('❌ Test con toggles.js: FALLIDO', 'error');
                    }
                }, 500);
            };
            document.head.appendChild(sidebarScript);
        };
        document.head.appendChild(togglesScript);
    }
    
    function testWithLoadView() {
        addToLog('=== Test Con loadView.js ===');
        
        // Cargar loadView.js primero
        const loadViewScript = document.createElement('script');
        loadViewScript.src = '../app/resources/js/loadView.js';
        loadViewScript.onload = function() {
            addToLog('loadView.js cargado');
            
            // Luego cargar sidebarToggle.js
            const sidebarScript = document.createElement('script');
            sidebarScript.src = '../app/resources/js/sidebarToggle.js';
            sidebarScript.onload = function() {
                addToLog('sidebarToggle.js cargado después de loadView.js');
                setTimeout(() => {
                    const submenus = document.querySelectorAll('.has-submenu');
                    let eventCount = 0;
                    submenus.forEach(submenu => {
                        const link = submenu.querySelector('a');
                        if (link && link._submenuHandler) {
                            eventCount++;
                        }
                    });
                    
                    addToLog(`Eventos registrados: ${eventCount}/${submenus.length}`);
                    
                    if (eventCount === submenus.length) {
                        addToLog('✅ Test con loadView.js: EXITOSO', 'info');
                    } else {
                        addToLog('❌ Test con loadView.js: FALLIDO', 'error');
                    }
                }, 500);
            };
            document.head.appendChild(sidebarScript);
        };
        document.head.appendChild(loadViewScript);
    }
    
    function testWithAllScripts() {
        addToLog('=== Test Con Todos los Scripts ===');
        
        const scripts = [
            '../app/resources/js/toggles.js',
            '../app/resources/js/loadView.js',
            '../app/resources/js/sidebarToggle.js'
        ];
        
        let loadedCount = 0;
        scripts.forEach((src, index) => {
            const script = document.createElement('script');
            script.src = src;
            script.onload = function() {
                loadedCount++;
                addToLog(`${src} cargado (${loadedCount}/${scripts.length})`);
                
                if (loadedCount === scripts.length) {
                    setTimeout(() => {
                        const submenus = document.querySelectorAll('.has-submenu');
                        let eventCount = 0;
                        submenus.forEach(submenu => {
                            const link = submenu.querySelector('a');
                            if (link && link._submenuHandler) {
                                eventCount++;
                            }
                        });
                        
                        addToLog(`Eventos registrados: ${eventCount}/${submenus.length}`);
                        
                        if (eventCount === submenus.length) {
                            addToLog('✅ Test con todos los scripts: EXITOSO', 'info');
                        } else {
                            addToLog('❌ Test con todos los scripts: FALLIDO', 'error');
                        }
                    }, 500);
                }
            };
            document.head.appendChild(script);
        });
    }
    
    // Interceptar console.log
    const originalLog = console.log;
    console.log = function(...args) {
        originalLog.apply(console, args);
        addToLog('Console: ' + args.join(' '), 'info');
    };
    
    // Interceptar console.error
    const originalError = console.error;
    console.error = function(...args) {
        originalError.apply(console, args);
        addToLog('Error: ' + args.join(' '), 'error');
    };
    </script>
</body>
</html> 