document.getElementById('editUserForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const firstName = document.getElementById('first_name').value.trim();
    const lastName = document.getElementById('last_name').value.trim();
    const email = document.getElementById('email').value.trim();

    if (!firstName || !lastName || !email) {
        alert('Por favor, completa todos los campos obligatorios.');
        return false;
    }

    if (!email.includes('@')) {
        alert('Por favor, ingresa un email válido.');
        return false;
    }

    // Construir FormData con todos los campos del formulario
    const form = document.getElementById('editUserForm');
    const formData = new FormData(form);

    // Hacer la petición AJAX
    fetch('<?php echo url; ?>app/processes/userProcess.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);

        if (data.success) {
            alert(data.message || 'Usuario actualizado correctamente.');
            // Opcional: recargar vista o redirigir
            loadView('user/view?id=' + formData.get('user_id'));
        } else {
            alert(data.message || 'Hubo un error al actualizar el usuario.');
        }
    })
    .catch(error => {
        console.error('Error AJAX:', error);
        alert('Error de conexión con el servidor.');
    });

    return false;
});
