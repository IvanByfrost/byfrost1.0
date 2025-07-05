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
    document.querySelectorAll('.toggle-password').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const inputId = btn.getAttribute('toggle-target');
            const input = document.getElementById(inputId);
            if (!input) return;
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            // Cambia el SVG del botón (opcional, si quieres ojo abierto/cerrado)
            btn.innerHTML = isPassword
                ? `<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M1.5 12s4-7 10.5-7 10.5 7 10.5 7-4 7-10.5 7S1.5 12 1.5 12z" stroke="#222" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="3.5" stroke="#222" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>`
                : `<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M17.94 17.94A10.06 10.06 0 0112 19.5c-6.5 0-10.5-7.5-10.5-7.5a18.6 18.6 0 013.06-4.44M6.12 6.12A9.94 9.94 0 0112 4.5c6.5 0 10.5 7.5 10.5 7.5a18.6 18.6 0 01-3.06 4.44M1.5 1.5l21 21" stroke="#222" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>`;
        });
    });
});