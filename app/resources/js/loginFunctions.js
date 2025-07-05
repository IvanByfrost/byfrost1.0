console.log("Script cargado");

$("#loginForm").on("submit", function (e) {
    e.preventDefault();
    var credType = $('#credType').val();
    var userDocument = $('#userDocument').val();
    var userPassword = $('#userPassword').val();

    if (userDocument.length < 4 || userDocument.length > 12) {
        var textoError1 = "";
        if (userDocument.length < 4) {
            textoError1 = "El documento debe tener mínimo 4 caracteres";
        } else if (userDocument.length > 12) {
            textoError1 = "El documento debe tener máximo 12 caracteres";
        }
        Swal.fire({
            title: 'Error',
            text: textoError1,
            icon: 'error',
            position: 'center',
            timer: 5000
        });
        return false;
    }

    if (userPassword.length < 4 || userPassword.length > 12) {
        var ErrorTextP = "";
        if (userPassword.length < 4) {
            ErrorTextP = "La contraseña debe tener mínimo 4 caracteres";
        } else if (userPassword.length > 12) {
            ErrorTextP = "La contraseña debe tener máximo 12 caracteres";
        }
        Swal.fire({
            title: 'Error',
            text: ErrorTextP,
            icon: 'error',
            position: 'center',
            timer: 5000
        });
        return false;
    }

    const formData = new FormData();
    formData.append("credType", credType);
    formData.append("userDocument", userDocument);
    formData.append("userPassword", userPassword);
    formData.append("subject", "login");

    fetch(ROOT + 'app/processes/loginProcess.php', {
        method: 'POST',
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            console.log(data);
            if (data.status === 'ok') {
                Swal.fire({
                    title: '¡Bienvenido!',
                    text: data.msg,
                    icon: 'success',
                    position: 'center',
                    timer: 2000
                }).then(() => {
                    window.location.href = data.redirect;
                });
            } else if (data.status === 'not_registered') {
                // Usuario no registrado - mostrar opción de registro
                Swal.fire({
                    title: 'Usuario no encontrado',
                    text: data.msg,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, registrarme',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = data.redirect;
                    }
                });
            } else if (data.status === 'no_role') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Sin rol asignado',
                    text: 'Tu cuenta aún no tiene un rol asignado. Por favor, contacta a Byfrost o a tu colegio.',
                    confirmButtonText: 'Entendido',
                    position: 'center'
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: data.msg,
                    icon: 'error',
                    position: 'center',
                    timer: 5000
                });
            }
        })
        .catch(error => {
            console.error("Error en la solicitud:", error);
            Swal.fire({
                title: 'Error de conexión',
                text: 'No se pudo conectar con el servidor. Inténtalo de nuevo.',
                icon: 'error',
                position: 'center',
                timer: 5000
            });
        });
});

function showPassword() {
  const input = document.getElementById("clave");
  input.type = input.type === "password" ? "text" : "password";
}

document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('userPassword');
    const togglePassword = document.getElementById('togglePassword');
    const eyeIcon = document.getElementById('eyeIcon');

    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            // Cambia el icono
            eyeIcon.innerHTML = isPassword
                // Ojo abierto
                ? `<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm7.5 0a9.77 9.77 0 01-1.5 3.5c-2.5 3.5-6.5 5.5-10 5.5s-7.5-2-10-5.5A9.77 9.77 0 011.5 12a9.77 9.77 0 011.5-3.5C5.5 5 9.5 3 13 3s7.5 2 10 5.5A9.77 9.77 0 0122.5 12z"/>
                </svg>`
                // Ojo cerrado
                : `<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-5.523 0-10-4.477-10-10 0-1.657.336-3.234.938-4.675M15 12a3 3 0 11-6 0 3 3 0 016 0zm6.062-4.675A9.956 9.956 0 0122 9c0 5.523-4.477 10-10 10a9.956 9.956 0 01-4.675-.938"/>
                </svg>`;
        });
    }
});