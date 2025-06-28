window.loadView = function(viewName) {
    const target = document.getElementById("mainContent");
    if (!target) {
        console.error("Elemento con id 'mainContent' no encontrado.");
        return;
    }

    console.log("Cargando vista:", viewName);
    
    // Mostrar indicador de carga
    target.innerHTML = '<div class="text-center p-4"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>';
    
    // Construir la URL para cargar vista parcial
    let url;
    let formData = new FormData();
    
    if (viewName.includes('/')) {
        // Si la vista tiene formato 'controller/action'
        const parts = viewName.split('/');
        const controller = parts[0];
        const action = parts[1];
        formData.append('view', controller);
        formData.append('action', action);
    } else {
        // Si solo es el nombre de la vista
        formData.append('view', viewName);
        formData.append('action', 'index');
    }
    
    // Usar el endpoint de vistas parciales
    url = `${BASE_URL}?view=index&action=loadPartial`;
    
    console.log("URL construida:", url);
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) throw new Error("Vista no encontrada.");
        return response.text();
    })
    .then(html => {
        target.innerHTML = html;
        
        // Inicializar JavaScript específico según la vista cargada
        if (viewName === 'user/assignRole' || viewName.includes('assignRole') || 
            viewName === 'user/consultUser' || viewName.includes('consultUser')) {
            console.log('Vista de gestión de usuarios cargada, inicializando JavaScript...');
            if (typeof initUserManagementAfterLoad === 'function') {
                initUserManagementAfterLoad();
            } else {
                console.warn('Función initUserManagementAfterLoad no encontrada');
            }
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