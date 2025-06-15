    function soloNumeros(id,value){
        var input = $("#"+id);
        input.val(input.val().replace(/[^0-9]/g, ''));
    }

    $("#formulario1").on("submit", function(e){
        e.preventDefault();
        var tipoDocumento = $('#tipoDocumento').val();
        var documento = $('#documento').val();
        var password = $('#password').val();
        
        if(documento.length<4 || documento.length>12){
            var textoError1 = "";
            if(documento.length<4){
                textoError1 = "El documento debe tener mínimo 4 caracteres";
            }else if(documento.length>12){
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

        if(password.length<4 || password.length>12){
            var textoError1 = "";
            if(password.length<4){
                textoError1 = "La password debe tener mínimo 4 caracteres";
            }else if(password.length>12){
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

        $.ajax({
            type: 'POST',
            url: 'script/login.php',
            dataType: "JSON",
            data: {
                "tipoDocumento": tipoDocumento,
                "documento": documento,
                "password": password,
                "asunto": "login",
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