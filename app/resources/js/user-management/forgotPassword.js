console.log("Script de olvidé contraseña cargado");

document.addEventListener("DOMContentLoaded", () => {
    console.log("DOM listo");

    const forgotPasswordLink = document.getElementById("forgotPassword");
    const forgotPasswordForm = document.getElementById("forgotPasswordForm");
    const resetPasswordForm = document.getElementById("resetPasswordForm");

    // Click en el enlace "Olvidé mi contraseña"
    if (forgotPasswordLink) {
        forgotPasswordLink.addEventListener("click", (e) => {
            e.preventDefault();
            const targetUrl = ROOT + "app/views/index/forgotPassword.php";
            console.log("Redirigiendo a:", targetUrl);
            window.location.href = targetUrl;
        });
    }

    // Enviar formulario de solicitud de recuperación
    if (forgotPasswordForm) {
        forgotPasswordForm.addEventListener("submit", async (e) => {
            e.preventDefault();

            const credType = document.getElementById('credType').value;
            const userDocument = document.getElementById('userDocument').value;
            const userEmail = document.getElementById('userEmail').value;

            console.log("Datos del formulario:", { credType, userDocument, userEmail });

            if (!credType || !userDocument || !userEmail) {
                Swal.fire({
                    title: 'Error',
                    text: 'Todos los campos son obligatorios',
                    icon: 'error'
                });
                return;
            }

            const submitBtn = forgotPasswordForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Enviando...';
            submitBtn.disabled = true;

            const processUrl = ROOT + 'app/processes/forgotPasswordProcess.php';
            console.log("Enviando a:", processUrl);

            try {
                const response = await fetch(processUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        subject: 'requestReset',
                        credType,
                        userDocument,
                        userEmail
                    })
                });

                const data = await response.json();
                console.log('Respuesta recibida:', data);

                if (data.status === 'ok') {
                    Swal.fire({
                        title: '¡Enlace enviado!',
                        text: data.msg,
                        icon: 'success',
                        confirmButtonText: 'Entendido'
                    }).then(() => {
                        if (data.resetLink) {
                            Swal.fire({
                                title: 'Enlace de desarrollo',
                                text: 'Para desarrollo, usa este enlace:',
                                input: 'text',
                                inputValue: data.resetLink,
                                inputAttributes: {
                                    readonly: true
                                },
                                confirmButtonText: 'Copiar enlace',
                                showCancelButton: true,
                                cancelButtonText: 'Cerrar'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    navigator.clipboard.writeText(data.resetLink);
                                    Swal.fire('¡Copiado!', 'Enlace copiado al portapapeles', 'success');
                                }
                            });
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.msg,
                        icon: 'error'
                    });
                }
            } catch (error) {
                console.error('Error AJAX:', error);
                Swal.fire({
                    title: 'Error de conexión',
                    text: 'No se pudo conectar con el servidor. Inténtalo de nuevo.',
                    icon: 'error'
                });
            } finally {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }
        });
    }

    // Enviar formulario de restablecimiento de contraseña
    if (resetPasswordForm) {
        resetPasswordForm.addEventListener("submit", async (e) => {
            e.preventDefault();

            const token = document.querySelector('input[name="token"]').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            console.log("Datos del formulario reset:", { token, newPassword, confirmPassword });

            if (!newPassword || !confirmPassword) {
                Swal.fire({
                    title: 'Error',
                    text: 'Todos los campos son obligatorios',
                    icon: 'error'
                });
                return;
            }

            if (newPassword !== confirmPassword) {
                Swal.fire({
                    title: 'Error',
                    text: 'Las contraseñas no coinciden',
                    icon: 'error'
                });
                return;
            }

            if (newPassword.length < 6) {
                Swal.fire({
                    title: 'Error',
                    text: 'La contraseña debe tener al menos 6 caracteres',
                    icon: 'error'
                });
                return;
            }

            const submitBtn = resetPasswordForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Cambiando...';
            submitBtn.disabled = true;

            const processUrl = ROOT + 'app/processes/forgotPasswordProcess.php';
            console.log("Enviando reset a:", processUrl);

            try {
                const response = await fetch(processUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        subject: 'resetPassword',
                        token,
                        newPassword,
                        confirmPassword
                    })
                });

                const data = await response.json();
                console.log('Respuesta reset recibida:', data);

                if (data.status === 'ok') {
                    Swal.fire({
                        title: '¡Contraseña actualizada!',
                        text: data.msg,
                        icon: 'success',
                        confirmButtonText: 'Ir al login'
                    }).then(() => {
                        window.location.href = ROOT + 'app/views/index/login.php';
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.msg,
                        icon: 'error'
                    });
                }
            } catch (error) {
                console.error('Error AJAX reset:', error);
                Swal.fire({
                    title: 'Error de conexión',
                    text: 'No se pudo conectar con el servidor. Inténtalo de nuevo.',
                    icon: 'error'
                });
            } finally {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }
        });
    }
});
