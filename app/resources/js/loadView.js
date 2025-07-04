window.loadView = function(viewName) {
    const target = document.getElementById("mainContent");
    console.log("Target mainContent:", target);
    if (!target) {
        console.error("Elemento con id 'mainContent' no encontrado.");
        return;
    }

    console.log("Cargando vista:", viewName);
    
    // Mostrar indicador de carga
    target.innerHTML = '<div class="text-center p-4"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>';
    
    // Si la vista tiene formato 'controller/action', construye la URL
    let url;
    if (viewName.includes('/')) {
        const [controller, actionWithParams] = viewName.split('/');
        const [action, params] = actionWithParams.split('?');
        url = `${BASE_URL}?view=${controller}&action=${action}`;
        
        // Agregar parámetros adicionales si existen
        if (params) {
            url += `&${params}`;
        }
    } else {
        url = `${BASE_URL}?view=${viewName}`;
    }
    
    console.log("URL construida:", url);
    
    fetch(url, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Respuesta del servidor:', response.status, response.statusText);
        if (!response.ok) throw new Error("Vista no encontrada.");
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
                '<i class="fas fa-exclamation-triangle"></i> <strong>Error de Permisos:</strong> ' + errorMessage +
                '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                '</div>';
            
            // Intentar mostrar SweetAlert2 con retraso para asegurar que esté cargado
            setTimeout(() => {
                if (typeof Swal !== "undefined" && Swal.fire) {
                    console.log('Mostrando SweetAlert2...');
                    Swal.fire({
                        title: 'Error de Permisos',
                        text: errorMessage,
                        icon: 'error',
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: '#d33',
                        allowOutsideClick: true,
                        allowEscapeKey: true
                    });
                } else {
                    console.log('SweetAlert2 no disponible, usando alert nativo');
                    alert('Error de Permisos: ' + errorMessage);
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
        if (viewName === 'school/createSchool') {
            if (typeof window.initCreateSchoolForm === 'function') {
                window.initCreateSchoolForm();
            }
        }
        if (viewName === 'user/assignRole' || viewName.includes('assignRole') || 
            viewName === 'user/consultUser' || viewName.includes('consultUser') ||
            viewName === 'user/showRoleHistory' || viewName.includes('showRoleHistory') ||
            viewName === 'user/roleHistory' || viewName.includes('roleHistory')) {
            console.log('Vista de gestión de usuarios cargada, inicializando JavaScript...');
            if (typeof initUserManagementAfterLoad === 'function') {
                initUserManagementAfterLoad();
            } else {
                console.warn('Función initUserManagementAfterLoad no encontrada');
            }
        }
        if (viewName === 'role/index' || viewName.includes('role')) {
            console.log('Vista de gestión de roles cargada');
            // Inicializar formulario de roles después de cargar la vista
            if (typeof initRoleEditForm === 'function') {
                setTimeout(() => {
                    initRoleEditForm();
                }, 100);
            }
        }
    })
    .catch(err => {
        console.error("Error al cargar la vista:", err);
        target.innerHTML = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
            '<i class="fas fa-exclamation-triangle"></i> <strong>Error:</strong> No se pudo cargar la vista: ' + err.message +
            '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
            '</div>';
        
        // Intentar mostrar SweetAlert2 con retraso
        setTimeout(() => {
            if (typeof Swal !== "undefined" && Swal.fire) {
                console.log('Mostrando SweetAlert2 para error de carga...');
                Swal.fire({
                    title: 'Error',
                    text: 'No se pudo cargar la vista.',
                    icon: 'error',
                    confirmButtonText: 'Entendido',
                    timer: 4000,
                    timerProgressBar: true
                });
            } else {
                console.log('SweetAlert2 no disponible para error de carga, usando alert nativo');
                alert('Error: No se pudo cargar la vista.');
            }
        }, 100);
    });
};

// Función segura para cargar vistas que verifica si loadView está disponible
window.safeLoadView = function(viewName) {
    console.log('safeLoadView llamado con:', viewName);
    
    if (typeof loadView === 'function') {
        console.log('loadView disponible, ejecutando...');
        loadView(viewName);
    } else {
        console.error('loadView no está disponible, intentando cargar manualmente...');
        
        // Fallback: cargar la vista manualmente
        const target = document.getElementById("mainContent");
        if (!target) {
            console.error("Elemento con id 'mainContent' no encontrado.");
            alert('Error: No se puede cargar la vista. Elemento mainContent no encontrado.');
            return;
        }
        
        // Mostrar indicador de carga
        target.innerHTML = '<div class="text-center p-4"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>';
        
        // Construir URL manualmente
        let url;
        if (viewName.includes('/')) {
            const [controller, actionWithParams] = viewName.split('/');
            const [action, params] = actionWithParams.split('?');
            url = `${BASE_URL}?view=${controller}&action=${action}`;
            
            if (params) {
                url += `&${params}`;
            }
        } else {
            url = `${BASE_URL}?view=${viewName}`;
        }
        
        console.log('URL construida manualmente:', url);
        
        // Hacer la petición manualmente
        fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error("Vista no encontrada.");
            return response.text();
        })
        .then(html => {
            target.innerHTML = html;
        })
        .catch(err => {
            console.error("Error al cargar la vista:", err);
            target.innerHTML = '<div class="alert alert-danger">Error al cargar la vista: ' + err.message + '</div>';
        });
    }
};