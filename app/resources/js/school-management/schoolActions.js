function confirmDelete(schoolId) {
    if (typeof Swal !== "undefined") {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción eliminará la escuela y no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                loadView(`school/delete?id=${schoolId}`);
            }
        });
    } else {
        // Fallback clásico si Swal no está cargado
        if (confirm('¿Estás seguro de que deseas eliminar esta escuela? Esta acción no se puede deshacer.')) {
            loadView(`school/delete?id=${schoolId}`);
        }
    }
}
