window.loadView = function(viewName) {
    const target = document.getElementById("mainContent");
    if (!target) {
        console.error("Elemento con id 'mainContent' no encontrado.");
        return;
    }

    console.log("Cargando vista:", viewName);
    
    // Mostrar indicador de carga
    target.innerHTML = '<div class="text-center p-4"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>';
    
    // Construir la URL correctamente
    let url;
    if (viewName.includes('/')) {
        // Si la vista tiene formato 'controller/action'
        const parts = viewName.split('/');
        const controller = parts[0];
        const action = parts[1];
        url = `${BASE_URL}?view=${controller}&action=${action}`;
    } else {
        // Si solo es el nombre de la vista
        url = `${BASE_URL}?view=${viewName}`;
    }
    
    console.log("URL construida:", url);
    
    fetch(url)
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