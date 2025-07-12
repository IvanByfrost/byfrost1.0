document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('editUserForm');
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const firstName = document.getElementById('first_name')?.value.trim();
            const lastName = document.getElementById('last_name')?.value.trim();
            const email = document.getElementById('email')?.value.trim();

            if (!firstName || !lastName || !email) {
                alert('Por favor, completa todos los campos obligatorios.');
                return false;
            }

            if (!email.includes('@')) {
                alert('Por favor, ingresa un email válido.');
                return false;
            }

            const formData = new FormData(form);

            fetch("?view=user&action=updateUserAjax", {
                method: "POST",
                body: formData,
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: data.message
                    }).then(() => {
                        loadView('user/view', null, '#mainContent', true, {
                            id: formData.get('user_id')
                        });
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message
                    });
                }
            })
            .catch(error => {
                console.error(error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se pudo procesar la solicitud.'
                });
            });

            return false;
        });
    }
});

function submitEditUserForm() {
    const form = document.getElementById('editUserForm');
    if (form) {
        form.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
    } else {
        alert('No se encontró el formulario de edición de usuario.');
    }
}
