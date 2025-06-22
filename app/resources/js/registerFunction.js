(function(){
    console.log("Script cargado");

    $("#registerForm").on("submit", function(e) {
        e.preventDefault();
        var userEmail = $('#userEmail').val();
        var credType = $('#credType').val();
        var userDocument = $('#userDocument').val();
        var userPassword = $('#userPassword').val();
        var passwordConf = $('#passwordConf').val();

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

        $.ajax({
            type: 'POST',
            url: ROOT + 'processes/registerProcess.php',
            dataType: "JSON",
            data: {
                "userEmail": userEmail,
                "credType": credType,
                "userDocument": userDocument,
                "userPassword": userPassword,
                "subject": "register",
            },

            success: function(response) {
                console.log(response);
                if (response["status"] == "ok") {
                    Swal.fire({
                        title: 'Ok',
                        text: response["msg"],
                        icon: 'success',
                        position: 'center',
                        timer: 5000
                    });
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
                }
            },

            error: function(response) {
                console.log(response['responseText']);
            }
        });
    });
})();
