<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Byfrost</title>
    <link rel="stylesheet" href="css\header.css">
    <link rel="stylesheet" href="css\features.css">
    <link rel="stylesheet" href="css\footer.css">
    <link rel="stylesheet" href="css\loginstyle.css">
    <link rel="stylesheet" href="css\slider.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,200,0,0">
</head>

<header>
    <div class="main-header">
    <div class="logo-header">
        <a href="index.php">
            <img src="img\horizontal-logo.svg" alt="Byfrost Logo" width="200">
        </a>
    </div>

    <div class="menu-bar">
        <a href="plans.htm" class="btn-menu">Planes</a>
        <a href="contact.php" class="btn-menu">Contáctenos</a>
        <a href="faq.html" class="btn-menu">FAQ</a>
    </div>

    <a href="login.php">
        <div class="login-bttn">
    <img src="img\user-icon.svg" alt="User Icon" width="30"> 
    Iniciar sesión
        </div>
    </a>
</div>
</header>

<body>
<div class="container">
    <form id="formulario1" class="login-box">
        <div class="logo">
            <a href="index.php">
                <img src="img\horizontal-logo.svg" alt="Byfrost Logo">
			</a>
        </div>
        <div class="row">
            <div class="col-12 mt-3 mb-3" style="font-weight: bold; font-size: 30px;">INICIAR SESIÓN</div>
            <div class="col-12">
                <select class="inputEstilo1" id="tipoDocumento" required>
                    <option value="">Seleccione</option>
                    <option value="Cédula de ciudadanía">Cédula de ciudadanía</option>
                    <option value="Tarjeta de identidad">Tarjeta de identidad</option>
                    <option value="Pasaporte">Pasaporte</option>
                    <option value="Cédula de extranjería">Cédula de extranjería</option>
                    <option value="Permiso de permanencia">Permiso de permanencia</option>
                    <option value="Registro civil">Registro civil</option>
                </select>
            </div>
            <div class="col-12">
                <input type="text" class="inputEstilo1" id="documento" name="documento" placeholder="Número de documento" pattern="[0-9]+" title="Solo números" onkeyup="soloNumeros('documento',value);" autocomplete="off" required>
            </div>
            <div class="col-12">
                <input type="password" class="inputEstilo1" id="password" name="password" placeholder="Contraseña" required>
            </div>

            <div class="col-6">
                <button type="submit" id="iniciar-sesion" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Iniciar sesión</button>
            </div>
            <div class="col-6">
                <a href="register.php">
                    <button type="button" id="register-bttn" class="bg-green-700 hover:bg-green-800 text-yellow-300 font-bold py-2 px-4 rounded" style="color: white;">Crear una nueva cuenta</button>
                </a>
            </div>
        </div>
    </form>
</div>


    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="about">
                    <h2>Nosotros</h2>
                    <p>Nuestra solución tecnológica innovadora propone transformar la gestión administrativa de las instituciones educativas, ofreciendo una plataforma intuitiva y sostenible.</p>
                </div>
        <div class="contact">
                    <h2>Contacto</h2>
                    <p>Cra 7 # 98-25, Bogotá, Colombia</p>
                    <p>(601) 7886590</p>
                    <p>(601) 4567890</p>
                    <a href="www.byfrost.com.co">www.byfrost.com.co</a>
                    <p>info@byfrost.com</p>
                </div>
        <div class="site-map">
                    <p><a href="#">Inicio</a></p>
                    <p><a href="plans.htm">Planes</a></p>
                    <p><a href="contact.htm">Contáctenos</a></p>
                    <p><a href="faq.htm">FAQ</a></p>
                    <p><a href="site-map.htm">Mapa del sitio</a></p>
                </div>
            </div>
    <div class = "copyright">
            <p>Byfrost &copy; 2026. Todos los derechos reservados.</p>
            <p>Diseñado por Byfrost Software.</p>
        </div>
    </div>
    </footer>

</body>
</html>

<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/bootstrap.bundle.js"></script>
<script type="text/javascript" src="js/sweetalert2.js"></script>

<script>
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
</script>