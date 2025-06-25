console.log("Script de olvidé contraseña cargado");

$(document).ready(function() {
    console.log("Document ready ejecutado");
    console.log("ROOT disponible:", ROOT);
    
    // Manejar clic en el enlace de olvidé contraseña
    $("#forgotPassword").on("click", function(e) {
        console.log("Clic en olvidé contraseña detectado");
        e.preventDefault();
        const targetUrl = ROOT + "app/views/index/forgotPassword.php";
        console.log("Redirigiendo a:", targetUrl);
        window.location.href = targetUrl;
    });

    // Manejar formulario de solicitud de restablecimiento
    $("#forgotPasswordForm").on("submit", function(e) {
        console.log("Formulario de olvidé contraseña enviado");
        e.preventDefault();
        
        const formData = {
            credType: $('#credType').val(),
            userDocument: $('#userDocument').val(),
            userEmail: $('#userEmail').val(),
            subject: 'requestReset'
        };

        console.log("Datos del formulario:", formData);

        // Validaciones
        if (!formData.credType || !formData.userDocument || !formData.userEmail) {
            Swal.fire({
                title: 'Error',
                text: 'Todos los campos son obligatorios',
                icon: 'error'
            });
            return;
        }

        // Mostrar indicador de carga
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.text();
        submitBtn.text('Enviando...').prop('disabled', true);

        const processUrl = ROOT + 'app/processes/forgotPasswordProcess.php';
        console.log("Enviando a:", processUrl);

        $.ajax({
            type: 'POST',
            url: processUrl,
            data: formData,
            dataType: 'json',
            success: function(response) {
                console.log('Respuesta recibida:', response);
                
                if (response.status === 'ok') {
                    Swal.fire({
                        title: '¡Enlace enviado!',
                        text: response.msg,
                        icon: 'success',
                        confirmButtonText: 'Entendido'
                    }).then(() => {
                        // En desarrollo, mostrar el enlace
                        if (response.resetLink) {
                            Swal.fire({
                                title: 'Enlace de desarrollo',
                                text: 'Para desarrollo, usa este enlace:',
                                input: 'text',
                                inputValue: response.resetLink,
                                inputAttributes: {
                                    readonly: true
                                },
                                confirmButtonText: 'Copiar enlace',
                                showCancelButton: true,
                                cancelButtonText: 'Cerrar'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    navigator.clipboard.writeText(response.resetLink);
                                    Swal.fire('¡Copiado!', 'Enlace copiado al portapapeles', 'success');
                                }
                            });
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.msg,
                        icon: 'error'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error AJAX:', xhr.responseText, status, error);
                Swal.fire({
                    title: 'Error de conexión',
                    text: 'No se pudo conectar con el servidor. Inténtalo de nuevo.',
                    icon: 'error'
                });
            },
            complete: function() {
                // Restaurar botón
                submitBtn.text(originalText).prop('disabled', false);
            }
        });
    });

    // Manejar formulario de restablecimiento de contraseña
    $("#resetPasswordForm").on("submit", function(e) {
        console.log("Formulario de reset password enviado");
        e.preventDefault();
        
        const formData = {
            token: $('input[name="token"]').val(),
            newPassword: $('#newPassword').val(),
            confirmPassword: $('#confirmPassword').val(),
            subject: 'resetPassword'
        };

        console.log("Datos del formulario reset:", formData);

        // Validaciones
        if (!formData.newPassword || !formData.confirmPassword) {
            Swal.fire({
                title: 'Error',
                text: 'Todos los campos son obligatorios',
                icon: 'error'
            });
            return;
        }

        if (formData.newPassword !== formData.confirmPassword) {
            Swal.fire({
                title: 'Error',
                text: 'Las contraseñas no coinciden',
                icon: 'error'
            });
            return;
        }

        if (formData.newPassword.length < 6) {
            Swal.fire({
                title: 'Error',
                text: 'La contraseña debe tener al menos 6 caracteres',
                icon: 'error'
            });
            return;
        }

        // Mostrar indicador de carga
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.text();
        submitBtn.text('Cambiando...').prop('disabled', true);

        const processUrl = ROOT + 'app/processes/forgotPasswordProcess.php';
        console.log("Enviando reset a:", processUrl);

        $.ajax({
            type: 'POST',
            url: processUrl,
            data: formData,
            dataType: 'json',
            success: function(response) {
                console.log('Respuesta reset recibida:', response);
                
                if (response.status === 'ok') {
                    Swal.fire({
                        title: '¡Contraseña actualizada!',
                        text: response.msg,
                        icon: 'success',
                        confirmButtonText: 'Ir al login'
                    }).then(() => {
                        window.location.href = ROOT + 'app/views/index/login.php';
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: response.msg,
                        icon: 'error'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error AJAX reset:', xhr.responseText, status, error);
                Swal.fire({
                    title: 'Error de conexión',
                    text: 'No se pudo conectar con el servidor. Inténtalo de nuevo.',
                    icon: 'error'
                });
            },
            complete: function() {
                // Restaurar botón
                submitBtn.text(originalText).prop('disabled', false);
            }
        });
    });
}); 