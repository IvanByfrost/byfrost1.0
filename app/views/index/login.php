<?php
//require_once(__DIR__ . '/../../config.php');
require_once __DIR__ . '/../layouts/head.php';
require_once __DIR__ . '/../layouts/header.php';
?>
<script>
    const ROOT = "<?php echo url ?>";
    console.log("ROOT definido como:", ROOT);
</script>
<body>
    <br>
    <div class="container">
        <form id="loginForm" class="login-box" method="POST" action="<?php echo url . rq ?>app/processes/loginProcess.php">
            <!--<div class="logo">
            <a href="index.php">
                <img src="<?php echo url . rq ?>img\horizontal-logo.svg" alt="Byfrost Logo">
			</a>
        </div>-->
            <div class="row">
                <div class="col-12 mt-3 mb-3" style="font-weight: bold; font-size: 30px;">Bienvenido(a) de nuevo</div>
                <div class="col-12">
                    <select class="inputEstilo1" id="credType" name="credType" required>
                        <option value="">Seleccione</option>
                        <option value="CC">Cédula de ciudadanía</option>
                        <option value="TI">Tarjeta de identidad</option>
                        <option value="PP">Pasaporte</option>
                        <option value="CE">Cédula de extranjería</option>
                        <option value="PM">Permiso de permanencia</option>
                        <option value="RC">Registro civil</option>
                    </select>
                </div>
                <div class="col-12">
                    <input type="text" class="inputEstilo1" id="userDocument" name="userDocument" placeholder="Número de documento" pattern="[0-9]+" title="Only Numbers" onkeyup="onlyNumbers('userDocument',value);" autocomplete="off" required>
                </div>
                <div class="col-12">
                    <input type="password" class="inputEstilo1" id="userPassword" name="userPassword" placeholder="Contraseña" required>
                </div>
                
                <!-- Enlace de olvidé contraseña -->
                <div class="col-12 text-center mb-3">
                    <a href="#" id="forgotPassword" class="text-primary" style="text-decoration: none; font-size: 14px;">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>

                <div class="row">
                    <div class="col-6">
                        <button type="submit" id="iniciar-sesion"
                            class="btn btn-primary d-block w-100 text-center">
                            Iniciar sesión
                        </button>
                    </div>
                    <div class="col-6">
                        <a href="register.php" id="register-bttn"
                           class="btn btn-success d-block w-100 text-center">
                            Crear una nueva cuenta
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <br>

    <?php
    require_once __DIR__ . '/../layouts/footer.php';
    ?>
    
    <!-- Scripts específicos -->
    <script src="<?php echo url . app . rq ?>js/forgotPassword.js"></script>
    <script>
        console.log("Página de login cargada");
        console.log("URL del script:", "<?php echo url . app .rq ?>js/forgotPassword.js");
    </script>
</body>