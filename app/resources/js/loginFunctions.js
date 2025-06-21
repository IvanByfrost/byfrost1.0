    console.log("Script cargado");
    function onlyNumbers(id,value){
        var input = $("#"+id);
        input.val(input.val().replace(/[^0-9]/g, ''));
    }

    $("#loginForm").on("submit", function(e){
        e.preventDefault();
        var credType = $('#credType').val();
        var userDocument = $('#userDocument').val();
        var userPassword = $('#userPassword').val();
        
        if(userDocument.length<4 || userDocument.length>12){
            var textoError1 = "";
            if(userDocument.length<4){
                textoError1 = "El documento debe tener mínimo 4 caracteres";
            }else if(userDocument.length>12){
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

        if(userPassword.length<4 || userPassword.length>12){
            var ErrorTextP = "";
            if(userPassword.length<4){
                ErrorTextP = "La contraseña debe tener mínimo 4 caracteres";
            }else if(userPassword.length>12){
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

        $.ajax({
            type: 'POST',
            url: ROOT + 'processes/loginProcess.php',
            dataType: "JSON",
            data: {
                "credType": credType,
                "userDocument": userDocument,
                "userPassword": userPassword,
                "subject": "login",
            },

            success: function(respuesta) {
                console.log(respuesta);
                if(respuesta["estatus"]=="ok"){
                    Swal.fire({
                        title: 'Ok',
                        text: respuesta["msg"],
                        icon: 'success',
                        position: 'center',
                        timer: 5000
                    });
                }else if(respuesta["estatus"]=="error"){
                    Swal.fire({
                        title: 'Error',
                        text: respuesta["msg"],
                        icon: 'error',
                        position: 'center',
                        timer: 5000
                    });
                }
            },

            error: function(respuesta) {
                console.log(respuesta['responseText']);
            }
        });
    });