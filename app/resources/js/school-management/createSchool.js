/**
 * JavaScript para manejar el formulario de crear escuela dentro del dashboard
 */

// Función para inicializar después de que loadViews.js cargue el contenido
function initCreateSchoolAfterLoad() {
    console.log('Inicializando creación de escuela después de carga de vista...');
    setTimeout(() => {
        if (typeof initCreateSchoolForm !== 'undefined') {
            initCreateSchoolForm();
        }
    }, 100);
}

// Función para manejar búsqueda de coordinador
function initCoordinatorSearchForm() {
    const form = document.getElementById('searchCoordinatorForm');
    if (!form) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const input = document.getElementById('search_coordinator_query');
        const query = input?.value.trim();
        const resultsDiv = document.getElementById('searchCoordinatorResults');

        if (!query) {
            resultsDiv.innerHTML = '<div class="alert alert-warning">Por favor, ingrese un número de documento para buscar.</div>';
            input.focus();
            return;
        }

        if (!/^\d+$/.test(query)) {
            resultsDiv.innerHTML = '<div class="alert alert-warning">Por favor, ingrese solo números para el documento.</div>';
            input.focus();
            return;
        }

        fetch('?view=school&action=searchCoordinatorAjax', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'document=' + encodeURIComponent(query)
        })
        .then(r => r.json())
        .then(data => {
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
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function () {
    console.log('Inicializando módulo de creación de escuela...');

    if (typeof validateRequiredFields === 'undefined') {
        console.error('Módulo de validación no encontrado');
        return;
    }

    if (typeof initCreateSchoolForm === 'undefined') {
        console.error('Módulo de formulario no encontrado');
        return;
    }

    initCreateSchoolForm();
    initCoordinatorSearchForm();

    console.log('Módulo de creación de escuela inicializado correctamente');
});

// Exponer para uso externo
window.initCreateSchoolAfterLoad = initCreateSchoolAfterLoad;
