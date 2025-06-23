// Dashboard de Actividades - Funcionalidades JavaScript
$(document).ready(function() {
    // Inicializar DataTable
    initializeDataTable();
    
    // Cargar datos para el formulario de creación
    loadFormData();
    
    // Configurar eventos del formulario
    setupFormEvents();
});

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

// Cargar datos para los selectores
function loadFormData() {
    // Cargar tipos de actividad
    $.get('activity/getActivityTypes', function(data) {
        const select = $('#activity_type_id');
        select.empty();
        select.append('<option value="">Seleccionar tipo</option>');
        data.forEach(function(type) {
            select.append(`<option value="${type.activity_type_id}">${type.type_name}</option>`);
        });
    }).fail(function() {
        console.error('Error al cargar tipos de actividad');
    });
    
    // Cargar grupos de clase
    $.get('activity/getClassGroups', function(data) {
        const select = $('#class_group_id');
        select.empty();
        select.append('<option value="">Seleccionar grupo</option>');
        data.forEach(function(group) {
            select.append(`<option value="${group.class_group_id}">${group.grade_name} ${group.group_name} - ${group.school_name}</option>`);
        });
    }).fail(function() {
        console.error('Error al cargar grupos de clase');
    });
    
    // Cargar períodos académicos
    $.get('activity/getAcademicTerms', function(data) {
        const select = $('#term_id');
        select.empty();
        select.append('<option value="">Seleccionar período</option>');
        data.forEach(function(term) {
            select.append(`<option value="${term.term_id}">${term.term_name} (${term.school_year})</option>`);
        });
    }).fail(function() {
        console.error('Error al cargar períodos académicos');
    });
}

// Configurar eventos del formulario
function setupFormEvents() {
    // Crear actividad
    $('#createActivityForm').on('submit', function(e) {
        e.preventDefault();
        createActivity($(this));
    });
    
    // Cargar materias cuando se seleccione un grupo
    $('#class_group_id').on('change', function() {
        const classGroupId = $(this).val();
        if (classGroupId) {
            loadProfessorSubjects();
        }
    });
}

// Crear actividad
function createActivity(form) {
    // Validar campos requeridos
    const requiredFields = ['activity_name', 'activity_type_id', 'class_group_id', 
                           'term_id', 'professor_subject_id', 'max_score', 'due_date'];
    
    let isValid = true;
    requiredFields.forEach(function(field) {
        if (!$('#' + field).val()) {
            $('#' + field).addClass('is-invalid');
            isValid = false;
        } else {
            $('#' + field).removeClass('is-invalid');
        }
    });
    
    if (!isValid) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Por favor completa todos los campos obligatorios'
        });
        return;
    }
    
    // Mostrar loading
    const submitBtn = form.find('button[type="submit"]');
    const originalText = submitBtn.html();
    submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Creando...');
    submitBtn.prop('disabled', true);
    
    // Enviar formulario
    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: response.msg
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.msg
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al crear la actividad'
            });
        },
        complete: function() {
            // Restaurar botón
            submitBtn.html(originalText);
            submitBtn.prop('disabled', false);
        }
    });
}

// Cargar materias del profesor
function loadProfessorSubjects() {
    // En una implementación real, esto debería hacer una llamada AJAX
    // para obtener las materias del profesor logueado
    const subjects = [
        { professor_subject_id: 1, subject_name: 'Matemáticas' },
        { professor_subject_id: 2, subject_name: 'Español' },
        { professor_subject_id: 3, subject_name: 'Ciencias' },
        { professor_subject_id: 4, subject_name: 'Historia' },
        { professor_subject_id: 5, subject_name: 'Geografía' }
    ];
    
    const select = $('#professor_subject_id');
    select.empty();
    select.append('<option value="">Seleccionar materia</option>');
    
    subjects.forEach(function(subject) {
        select.append(`<option value="${subject.professor_subject_id}">${subject.subject_name}</option>`);
    });
}

// Ver actividad
function viewActivity(activityId) {
    // Mostrar loading en el modal
    $('#viewActivityContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Cargando...</p></div>');
    $('#viewActivityModal').modal('show');
    
    $.get(`activity/viewActivity/${activityId}`, function(data) {
        $('#viewActivityContent').html(data);
    }).fail(function() {
        $('#viewActivityContent').html('<div class="alert alert-danger">Error al cargar los detalles de la actividad</div>');
    });
}

// Editar actividad
function editActivity(activityId) {
    window.location.href = `activity/showEditForm/${activityId}`;
}

// Eliminar actividad
function deleteActivity(activityId) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar loading
            Swal.fire({
                title: 'Eliminando...',
                text: 'Por favor espera',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            $.ajax({
                url: `activity/deleteActivity/${activityId}`,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Eliminado!',
                            text: response.msg
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.msg
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al eliminar la actividad'
                    });
                }
            });
        }
    });
}

// Filtrar actividades por estado
function filterActivities(status) {
    const table = $('#activitiesTable').DataTable();
    
    if (status === 'all') {
        table.column(7).search('').draw();
    } else {
        table.column(7).search(status).draw();
    }
}

// Exportar actividades
function exportActivities(format) {
    const table = $('#activitiesTable').DataTable();
    
    switch(format) {
        case 'excel':
            table.button('excel').trigger();
            break;
        case 'pdf':
            table.button('pdf').trigger();
            break;
        case 'csv':
            table.button('csv').trigger();
            break;
        default:
            console.error('Formato no soportado');
    }
} 