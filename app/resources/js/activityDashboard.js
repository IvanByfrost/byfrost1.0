/**
 * Dashboard de Actividades - Módulo principal
 * Coordina los sub-módulos de actividades
 */

// Inicializar cuando el DOM esté listo
$(document).ready(function() {
    console.log('Inicializando dashboard de actividades...');
    
    // Verificar que jQuery esté disponible
    if (typeof $ === 'undefined') {
        console.error('jQuery no está disponible. El dashboard de actividades no funcionará.');
        return;
    }
    
    // Verificar que los módulos necesarios estén disponibles
    if (typeof initializeDataTable === 'undefined') {
        console.error('Módulo de DataTable no encontrado');
        return;
    }
    
    if (typeof loadFormData === 'undefined') {
        console.error('Módulo de formulario no encontrado');
        return;
    }
    
    if (typeof setupFormEvents === 'undefined') {
        console.error('Módulo de eventos no encontrado');
        return;
    }
    
    // Inicializar componentes
    initializeDataTable();
    loadFormData();
    setupFormEvents();
    
    console.log('Dashboard de actividades inicializado correctamente');
});

// Función para inicializar después de que loadViews.js cargue el contenido
function initActivityDashboardAfterLoad() {
    console.log('Inicializando dashboard de actividades después de carga de vista...');
    
    // Pequeño delay para asegurar que el DOM esté actualizado
    setTimeout(() => {
        if (typeof initializeDataTable !== 'undefined') {
            initializeDataTable();
        }
        if (typeof loadFormData !== 'undefined') {
            loadFormData();
        }
        if (typeof setupFormEvents !== 'undefined') {
            setupFormEvents();
        }
    }, 100);
}

// Asegurar que la función esté disponible globalmente
window.initActivityDashboardAfterLoad = initActivityDashboardAfterLoad; 