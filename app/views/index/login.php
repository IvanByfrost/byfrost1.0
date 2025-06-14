<?php
require_once(__DIR__ . '/../../config.php');
require_once __DIR__ . '/../default/head.php';
require_once __DIR__ . '/../default/header.php';
?>

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