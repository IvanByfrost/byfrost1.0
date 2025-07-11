// schoolCoordinatorSelect.js

document.addEventListener('DOMContentLoaded', function() {
    document.body.addEventListener('click', function(e) {
        if (e.target.classList.contains('select-coordinator-btn')) {
            const userId = e.target.getAttribute('data-user-id');
            const name = e.target.getAttribute('data-name');
            document.getElementById('coordinator_user_id').value = userId;
            document.getElementById('selectedCoordinatorName').textContent = name;
            // Cierra el modal (si usas Bootstrap 5)
            const modal = bootstrap.Modal.getInstance(document.getElementById('searchCoordinatorModal'));
            if (modal) modal.hide();
        }
    });
}); 