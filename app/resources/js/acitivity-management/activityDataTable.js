/**
 * Módulo de DataTable para actividades
 */

// Inicializar DataTable
function initializeDataTable() {
    $('#activitiesTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        order: [[6, 'asc']], // Ordenar por fecha límite
        pageLength: 25,
        responsive: true,
        columnDefs: [
            {
                targets: [0, 8], // ID y Acciones
                orderable: false
            }
        ]
    });
}

// Filtrar actividades por estado
function filterActivities(status) {
    const table = $('#activitiesTable').DataTable();
    
    if (status === 'all') {
        table.column(7).search('').draw(); // Columna de estado
    } else {
        table.column(7).search(status).draw();
    }
}

// Exportar actividades
function exportActivities(format) {
    const table = $('#activitiesTable').DataTable();
    
    switch (format) {
        case 'excel':
            table.button('.buttons-excel').trigger();
            break;
        case 'pdf':
            table.button('.buttons-pdf').trigger();
            break;
        case 'csv':
            table.button('.buttons-csv').trigger();
            break;
        default:
            console.error('Formato de exportación no soportado:', format);
    }
} 