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

// Asegurar que la función esté disponible globalmente
window.initCreateSchoolAfterLoad = initCreateSchoolAfterLoad; 