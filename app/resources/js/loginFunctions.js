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

    fetch(ROOT + 'processes/loginProcess.php', {
        method: 'POST',
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            console.log(data);
            if (data.status === 'ok') {
                Swal.fire({
                    title: 'Ok',
                    text: data.msg,
                    icon: 'success',
                    position: 'center',
                    timer: 2000
                }).then(() => {
                    window.location.href = data.redirect;
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
        });
});