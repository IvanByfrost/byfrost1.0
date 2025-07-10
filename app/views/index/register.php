<?php
//require_once(__DIR__ . '/../../config.php');
require_once __DIR__ . '/../layouts/head.php';
require_once __DIR__ . '/../layouts/header.php';
?>

<body>
    <br>
    <div class="container">
        <form id="registerForm" class="login-box" method="POST" action="<?php echo url ?>app/processes/registerProcess.php">
            <!--<div class="logo">
            <a href="index.php">
                <img src="img\horizontal-logo.svg" alt="Byfrost Logo">
			</a>
        </div>-->
            <div class="row">
                <div class="col-12 mt-3 mb-3" style="font-weight: bold; font-size: 30px;">Bienvenido(a) a Byfrost</div>
                <div class="col-12">
                    <div class="input-container">
                        <select class="inputEstilo1 input-pill" id="credType" name="credType" required>
                            <option value="">Seleccione</option>
                            <option value="CC">Cédula de ciudadanía</option>
                            <option value="TI">Tarjeta de identidad</option>
                            <option value="PP">Pasaporte</option>
                            <option value="CE">Cédula de extranjería</option>
                            <option value="PM">Permiso de permanencia</option>
                            <option value="RC">Registro civil</option>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="input-container">
                        <input type="text" class="inputEstilo1 input-pill" id="userDocument" name="userDocument" placeholder="Número de documento" pattern="[0-9]+" title="Only Numbers" onkeyup="onlyNumbers('userDocument',value);" autocomplete="off" required>
                    </div>
                </div>
                <div class="col-12">
                    <div class="input-container">
                        <input type="email" class="inputEstilo1 input-pill" id="userEmail" name="userEmail" placeholder="Correo Electrónico" autocomplete="off" required>
                    </div>
                </div>
                <div class="col-12">
                    <div class="input-container">
                        <input type="password" class="inputEstilo1 input-pill" id="userPassword" name="userPassword" placeholder="Contraseña" required>
                    </div>
                </div>
                <div class="col-12">
                    <div class="input-container">
                        <input type="password" class="inputEstilo1 input-pill" id="passwordConf" name="passwordConf" placeholder="Confirmar Contraseña" required>
                    </div>
                </div>

                <div class="col-6">
                    <button type="submit" id="register-bttn" class="btn btn-primary d-block w-100 text-center">Completar Registro</button>
                </div>
                <div class="col-6">
                    <button type="button" id="iniciar-sesion" class="btn btn-primary d-block w-100 text-center" onclick="window.location.href='login.php'">
                        Iniciar Sesión
                    </button>
                </div>
            </div>
        </form>
    </div>
    <br>

    <?php
    require_once __DIR__ . '/../layouts/footer.php';
    ?>
    
    <!-- Scripts específicos -->
    <script src="<?php echo url . app . rq ?>js/registerPage.js"></script>
</body>