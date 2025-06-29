(function(){
    console.log("Script cargado");

    // Asegurarse de que el evento submit solo se registre una vez
    $("#registerForm").off("submit").on("submit", function(e) {
        e.preventDefault();
        
        // Obtener solo los campos que existen en el formulario
        var credType = $('#credType').val();
        var userDocument = $('#userDocument').val();
        var userEmail = $('#userEmail').val();
        var userPassword = $('#userPassword').val();
        var passwordConf = $('#passwordConf').val();

        // Validaciones básicas
        if (!credType) {
            Swal.fire({
                title: 'Error',
                text: 'Debe seleccionar un tipo de documento',
                icon: 'error',
                position: 'center',
                timer: 5000
            });
            return false;
        }

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

        if (!userEmail) {
            Swal.fire({
                title: 'Error',
                text: 'El correo electrónico es obligatorio',
                icon: 'error',
                position: 'center',
                timer: 5000
            });
            return false;
        }

        if (userPassword.length < 4 || userPassword.length > 12) {
            var textoError1 = "";
            if (userPassword.length < 4) {
                textoError1 = "La password debe tener mínimo 4 caracteres";
            } else if (userPassword.length > 12) {
                textoError1 = "La password debe tener máximo 12 caracteres";
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

        if (userPassword != passwordConf) {
            Swal.fire({
                title: 'Error',
                text: 'Las contraseñas no coinciden',
                icon: 'error',
                position: 'center',
                timer: 5000
            });
            return false;
        }

        // Deshabilitar el botón de submit para evitar doble envío
        var $submitBtn = $("#registerForm button[type=submit]");
        $submitBtn.prop("disabled", true);

        $.ajax({
            type: 'POST',
            url: ROOT + 'app/processes/registerProcess.php',
            dataType: "JSON",
            data: {
                "credType": credType,
                "userDocument": userDocument,
                "userEmail": userEmail,
                "userPassword": userPassword,
                "subject": "register",
            },

            success: function(response) {
                console.log("=== DEBUG REGISTRO ===");
                console.log("Respuesta completa:", response);
                console.log("Tipo de respuesta:", typeof response);
                console.log("Status:", response["status"]);
                console.log("Mensaje:", response["msg"]);
                console.log("=====================");
                
                if (response["status"] == "ok") {
                    Swal.fire({
                        title: '¡Registro exitoso!',
                        text: response["msg"],
                        icon: 'success',
                        position: 'center',
                        timer: 5000
                    });
                    // Deshabilitar el botón para evitar doble submit
                    $submitBtn.prop("disabled", true);
                    setTimeout(function () {
                        // Redirigir al formulario de completar perfil con el documento
                        window.location.href = "completeProf.php?user=" + encodeURIComponent(userDocument);
                    }, 2100);
                } else if (response["status"] == "error") {
                    Swal.fire({
                        title: 'Error',
                        text: response["msg"],
                        icon: 'error',
                        position: 'center',
                        timer: 5000
                    });
                    // Rehabilitar el botón si hubo error
                    $submitBtn.prop("disabled", false);
                } else {
                    console.error("Status inesperado:", response["status"]);
                    Swal.fire({
                        title: 'Error inesperado',
                        text: 'Respuesta del servidor no reconocida: ' + response["status"],
                        icon: 'error',
                        position: 'center',
                        timer: 5000
                    });
                    $submitBtn.prop("disabled", false);
                }
            },

            error: function(xhr, status, error) {
                console.log("=== ERROR AJAX ===");
                console.log("Status:", status);
                console.log("Error:", error);
                console.log("Response Text:", xhr.responseText);
                console.log("Response Status:", xhr.status);
                console.log("==================");
                
                Swal.fire({
                    title: 'Error de conexión',
                    text: 'No se pudo conectar con el servidor. Inténtalo de nuevo.',
                    icon: 'error',
                    position: 'center',
                    timer: 5000
                });
                // Rehabilitar el botón si hubo error de conexión
                $submitBtn.prop("disabled", false);
            }
        });
    });
})();
