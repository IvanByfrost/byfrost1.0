/**
 * JavaScript para manejar el formulario de crear escuela dentro del dashboard
 * Módulo principal que coordina los sub-módulos
 */

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    console.log('Inicializando módulo de creación de escuela...');
    
    // Verificar que los módulos necesarios estén disponibles
    if (typeof validateRequiredFields === 'undefined') {
        console.error('Módulo de validación no encontrado');
        return;
    }
    
    if (typeof initCreateSchoolForm === 'undefined') {
        console.error('Módulo de formulario no encontrado');
        return;
    }
    
    // Inicializar formulario
    initCreateSchoolForm();
    
    // Reemplazar cualquier fetch de coordinador por el endpoint correcto
    const form = document.getElementById('searchCoordinatorForm');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const query = document.getElementById('search_coordinator_query').value.trim();
        if (!query) return;

        fetch('?view=school&action=searchCoordinatorAjax', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'document=' + encodeURIComponent(query)
        })
        .then(r => r.json())
        .then(data => {
            const resultsDiv = document.getElementById('searchCoordinatorResults');
            if (data.status === 'ok' && data.data.length > 0) {
                resultsDiv.innerHTML = data.data.map(d =>
                    `<button type="button" class="list-group-item list-group-item-action select-coordinator-btn"
                        data-user-id="${d.user_id}"
                        data-name="${d.first_name} ${d.last_name}">
                        ${d.first_name} ${d.last_name} - ${d.email}
                    </button>`
                ).join('');
            } else {
                resultsDiv.innerHTML = '<div class="alert alert-warning">No se encontraron coordinadores con ese documento.</div>';
            }
        });
    });
    
    console.log('Módulo de creación de escuela inicializado correctamente');
});

// Función para inicializar después de que loadViews.js cargue el contenido
function initCreateSchoolAfterLoad() {
    console.log('Inicializando creación de escuela después de carga de vista...');
    
    // Pequeño delay para asegurar que el DOM esté actualizado
    setTimeout(() => {
        if (typeof initCreateSchoolForm !== 'undefined') {
            initCreateSchoolForm();
        }
    }, 100);
}

// Validación adicional
if (!query || query.length === 0) {
    const resultsDiv = document.getElementById('searchDirectorResults');
    resultsDiv.innerHTML = '<div class="alert alert-warning">Por favor, ingrese un número de documento para buscar.</div>';
    queryInput.focus();
    return false;
}

// Validar que sea solo números
if (!/^\d+$/.test(query)) {
    const resultsDiv = document.getElementById('searchDirectorResults');
    resultsDiv.innerHTML = '<div class="alert alert-warning">Por favor, ingrese solo números para el documento.</div>';
    queryInput.focus();
    return false;
}

// Asegurar que la función esté disponible globalmente
window.initCreateSchoolAfterLoad = initCreateSchoolAfterLoad; 