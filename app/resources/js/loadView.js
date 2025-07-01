window.loadView = function(viewName) {
    const target = document.getElementById("mainContent");
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
        const [controller, action] = viewName.split('/');
        url = `${BASE_URL}?view=${controller}&action=${action}`;
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
        if (!response.ok) throw new Error("Vista no encontrada.");
        return response.text();
    })
    .then(html => {
        // Verificar si la respuesta contiene JavaScript
        if (html.includes('<script>')) {
            // Ejecutar el JavaScript y luego actualizar el contenido
            const scriptMatch = html.match(/<script>([\s\S]*?)<\/script>/);
            if (scriptMatch) {
                const scriptContent = scriptMatch[1];
                eval(scriptContent);
                return; // No actualizar el contenido si hay JavaScript
            }
        }
        
        target.innerHTML = html;
        
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
        }
    })
    .catch(err => {
        console.error("Error al cargar la vista:", err);
        target.innerHTML = '<div class="alert alert-danger">Error al cargar la vista: ' + err.message + '</div>';
        
        if (typeof Swal !== "undefined") {
            Swal.fire({
                title: 'Error',
                text: 'No se pudo cargar la vista.',
                icon: 'error',
                timer: 4000
            });
        } else {
            alert('No se pudo cargar la vista.');
        }
    });
};