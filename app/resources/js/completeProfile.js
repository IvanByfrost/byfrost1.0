(function (){
    $(document).ready(function () {
        console.log("Script cargado");

        $("#completeProfile").on("submit", function (e) {
            console.log("Formulario enviado");
            e.preventDefault();
            
            // Obtener los datos del formulario
            let formData = {
                userDocument: $('#userDocument').val(),
                userName: $('#userName').val(),
                lastnameUser: $('#lastnameUser').val(),
                dob: $('#dob').val(),
                userPhone: $('#userPhone').val(),
                addressUser: $('#addressUser').val()
            };

            // Validaciones básicas
            if (!formData.userName || !formData.lastnameUser || !formData.dob || 
                !formData.userPhone || !formData.addressUser) {
                Swal.fire({
                    title: "Error",
                    text: "Todos los campos son obligatorios",
                    icon: "error"
                });
                return;
            }

            // Validar teléfono
            if (formData.userPhone.length !== 10) {
                Swal.fire({
                    title: "Error",
                    text: "El teléfono debe tener exactamente 10 dígitos",
                    icon: "error"
                });
                return;
            }

            $.ajax({
                type: 'POST',
                url: ROOT + "app/processes/profileProcess.php",
                data: formData,
                dataType: 'json',
                success: function (response) {
                    console.log("Respuesta recibida:", response);

                    if (response.status === "ok") {
                        Swal.fire({
                            title: "¡Perfil completado!",
                            text: response.msg,
                            icon: "success",
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        });

                        setTimeout(() => {
                            console.log("Redirigiendo...");
                            window.location.href = ROOT + 'app/views/index/login.php';
                        }, 2100);
                    } else {
                        Swal.fire({
                            title: "Error",
                            text: response.msg,
                            icon: "error"
                        });
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error AJAX:", xhr.responseText, status, error);
                    Swal.fire({
                        title: "Error de conexión",
                        text: "No se pudo conectar con el servidor. Inténtalo de nuevo.",
                        icon: "error"
                    });
                }
            });
        });
    });
})();