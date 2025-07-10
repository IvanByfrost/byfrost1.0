window.loadView = function(viewName, useAction = false) {
    const target = document.getElementById("mainContent");
    console.log("Router - Target mainContent:", target);
    if (!target) {
        console.error("Router - Elemento con id 'mainContent' no encontrado.");
        return;
    }

    console.log("Router - Cargando vista:", viewName);
    
    // Mostrar indicador de carga
    target.innerHTML = '<div class="text-center p-4"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>';
    
    // Función para construir URL usando Router
    function buildViewUrl(viewName) {
        const baseUrl = window.location.origin + window.location.pathname;
        
        // Router - Procesamiento automático de rutas
        if (viewName.includes('?')) {
            const [view, params] = viewName.split('?');
            // Router maneja automáticamente los parámetros
            return `${baseUrl}?view=${view}&${params}`;
        }
        
        // Router - Rutas con módulos (ej: school/createSchool)
        if (viewName.includes('/')) {
            const [module, action] = viewName.split('/');
            // Router detecta automáticamente la acción
            return `${baseUrl}?view=${module}&action=${action}`;
        }
        
        // Router - Vista directa
        return `${baseUrl}?view=${viewName}`;
    }
    
    const localUrl = buildViewUrl(viewName);
    console.log("URL construida:", localUrl);
    
    fetch(localUrl, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Respuesta del servidor:', response.status, response.statusText);
        if (!response.ok) {
            console.error('Error HTTP:', response.status);
            throw new Error(`Error ${response.status}: ${response.statusText}`);
        }
        return response.text();
    })
    .then(html => {
        console.log('Contenido de la respuesta:', html.substring(0, 200) + '...');
        
        // Detectar si la respuesta es JSON de error
        let isJson = false;
        let json = null;
        
        // Verificar si la respuesta parece ser HTML (contiene tags HTML)
        const hasHtmlTags = /<[^>]*>/g.test(html);
        
        if (hasHtmlTags) {
            console.log('Respuesta detectada como HTML (contiene tags HTML)');
            isJson = false;
        } else {
            // Solo intentar parsear como JSON si no parece ser HTML
            try {
                json = JSON.parse(html);
                isJson = typeof json === 'object' && json !== null && 
                        (json.hasOwnProperty('success') || json.hasOwnProperty('message') || json.hasOwnProperty('msg'));
                console.log('Respuesta detectada como JSON:', isJson, json);
            } catch (e) {
                console.log('No es JSON válido:', e.message);
            }
        }

        if (isJson) {
            // Mostrar el mensaje de error con Swal.fire
            const errorMessage = json.message || json.msg || 'No tienes permisos para realizar esta acción.';
            console.log('Error JSON recibido:', json);
            
            // Mostrar error en la página primero
            target.innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                '<i class="fas fa-exclamation-triangle"></i> <strong>Error:</strong> ' + errorMessage +
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                '</div>';
            
            // Intentar mostrar SweetAlert2 con retraso para asegurar que esté cargado
            setTimeout(() => {
                if (typeof Swal !== "undefined" && Swal.fire) {
                    console.log('Mostrando SweetAlert2...');
                    Swal.fire({
                        title: 'Error',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: '#d33',
                        allowOutsideClick: true,
                        allowEscapeKey: true
                    });
                } else {
                    console.log('SweetAlert2 no disponible, usando alert nativo');
                    alert('Error: ' + errorMessage);
                }
            }, 100);
            
            return;
        }

        // Si es HTML válido, mostrarlo en el contenedor
        if (hasHtmlTags) {
            console.log('Mostrando contenido HTML en el contenedor');
            target.innerHTML = html;

            // Ejecutar cualquier <script> embebido en la respuesta
            const scripts = html.match(/<script>([\s\S]*?)<\/script>/gi);
            if (scripts) {
                scripts.forEach(scriptTag => {
                    const scriptContent = scriptTag.replace(/<script>|<\/script>/gi, '');
                    try {
                        eval(scriptContent);
                    } catch (e) {
                        console.error('Error ejecutando script embebido:', e);
                    }
                });
            }
        } else {
            // Si no es HTML, mostrar como texto plano
            console.log('Mostrando contenido como texto plano');
            target.innerHTML = '<div class="alert alert-info">' + html + '</div>';
        }
        
        // Inicializar JavaScript específico según la vista cargada
        initializeViewSpecificJS(viewName);
        
        // Reinicializar submenús después de cargar contenido dinámicamente
        if (typeof window.reinitializeSidebarSubmenus === 'function') {
            console.log('Reinicializando submenús del sidebar...');
            setTimeout(() => {
                window.reinitializeSidebarSubmenus();
            }, 100);
        }
        
        console.log('Vista cargada exitosamente:', viewName);
    })
    .catch(error => {
        console.error('Error cargando vista:', error);
        
        // Mostrar error más descriptivo
        const errorMessage = `Error cargando la vista "${viewName}": ${error.message}`;
        target.innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
            '<i class="fas fa-exclamation-triangle"></i> <strong>Error de Carga:</strong> ' + errorMessage +
            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
            '</div>';
        
        // Intentar mostrar SweetAlert2
        setTimeout(() => {
            if (typeof Swal !== "undefined" && Swal.fire) {
                Swal.fire({
                    title: 'Error de Carga',
                    text: errorMessage,
                    icon: 'error',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#d33'
                });
            } else {
                alert('Error de Carga: ' + errorMessage);
            }
        }, 100);
    });
};

// Función para inicializar JavaScript específico de cada vista
function initializeViewSpecificJS(viewName) {
    console.log('Inicializando JavaScript específico para:', viewName);
    
    // Director views
    if (viewName === 'director/dashboard') {
        if (typeof window.initDirectorDashboard === 'function') {
            window.initDirectorDashboard();
        }
    }
    if (viewName === 'director/dashboard-simple') {
        if (typeof window.initDirectorDashboardSimple === 'function') {
            window.initDirectorDashboardSimple();
        }
    }
    if (viewName === 'director/dashboardPartial') {
        if (typeof window.initDirectorDashboardPartial === 'function') {
            window.initDirectorDashboardPartial();
        }
    }
    if (viewName === 'director/dashboardHome') {
        if (typeof window.initDirectorDashboardHome === 'function') {
            window.initDirectorDashboardHome();
        }
    }
    
    // School views
    if (viewName === 'school/createSchool') {
        if (typeof window.initCreateSchoolForm === 'function') {
            window.initCreateSchoolForm();
        }
    }
    
    // User management views
    if (viewName === 'user/assignRole' || viewName.includes('assignRole') || 
        viewName === 'user/consultUser' || viewName.includes('consultUser') ||
        viewName === 'user/showRoleHistory' || viewName.includes('showRoleHistory') ||
        viewName === 'user/roleHistory' || viewName.includes('roleHistory')) {
        console.log('Vista de gestión de usuarios cargada, inicializando JavaScript...');
        
        // Función para intentar inicializar con retraso
        function tryInitUserManagement(attempts = 0) {
            if (typeof initUserManagementAfterLoad === 'function') {
                console.log('✅ initUserManagementAfterLoad encontrada, ejecutando...');
                try {
                    initUserManagementAfterLoad();
                    console.log('✅ initUserManagementAfterLoad ejecutada exitosamente');
                } catch (error) {
                    console.error('❌ Error al ejecutar initUserManagementAfterLoad:', error);
                }
            } else if (attempts < 10) {
                console.log(`⏳ initUserManagementAfterLoad no disponible (intento ${attempts + 1}/10), reintentando en 200ms...`);
                setTimeout(() => tryInitUserManagement(attempts + 1), 200);
            } else {
                console.warn('❌ Función initUserManagementAfterLoad no encontrada después de 10 intentos');
            }
        }
        
        tryInitUserManagement();
    }
    
    // Role management views
    if (viewName === 'role/index' || viewName.includes('role')) {
        console.log('Vista de gestión de roles cargada');
        if (typeof initRoleEditForm === 'function') {
            setTimeout(() => {
                initRoleEditForm();
            }, 100);
        }
    }
    
    // Payroll views
    if (viewName.includes('payroll/')) {
        console.log('Vista de nómina cargada:', viewName);
        // Inicializar funcionalidades específicas de nómina si existen
        if (typeof window.initPayrollView === 'function') {
            setTimeout(() => {
                window.initPayrollView(viewName);
            }, 100);
        }
    }
    
    // Student views
    if (viewName.includes('student/')) {
        console.log('Vista de estudiante cargada:', viewName);
        if (typeof window.initStudentView === 'function') {
            setTimeout(() => {
                window.initStudentView(viewName);
            }, 100);
        }
    }
}

// Función de respaldo mejorada (solo para casos extremos)
window.safeLoadView = function(viewName) {
    console.warn('⚠️ safeLoadView llamado - Esto indica un problema en el routing');
    console.log('Intentando cargar vista con loadView:', viewName);
    
    // Intentar con loadView primero
    if (typeof loadView === 'function') {
        loadView(viewName);
    } else {
        console.error('❌ loadView no está disponible');
        // Fallback: redirigir a la página
        const url = `${window.location.origin}${window.location.pathname}?view=${viewName.replace('/', '&action=')}`;
        window.location.href = url;
    }
};