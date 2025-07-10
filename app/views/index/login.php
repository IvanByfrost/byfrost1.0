<?php
//require_once(__DIR__ . '/../../config.php');
require_once ROOT . '/app/views/layouts/head.php';
require_once ROOT . '/app/views/layouts/header.php';
?>

<body>
    <br>
    <div class="container">
        <form id="loginForm" class="login-box" method="POST" action="<?php echo url ?>
    <input type="hidden" name="csrf_token" value='<?= Validator::generateCSRFToken() ?>'>
app/processes/loginProcess.php">
            <!--<div class="logo">
            <a href="index.php">
                <img src="<?php echo url . rq ?>img\horizontal-logo.svg" alt="Byfrost Logo">
			</a>
        </div>-->
            <div class="row">
                <div class="col-12 mt-3 mb-3" style="font-weight: bold; font-size: 30px;">Bienvenido(a) de nuevo</div>
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
                        <input type="text" class="inputEstilo1 input-pill" id="userDocument" name="userDocument" placeholder="Número de documento" pattern="[0-9]+" title="Sólo se admiten números" onkeyup="onlyNumbers('userDocument',value);" autocomplete="off" required>
                    </div>


                    <div class="col-12">
                        <div class="input-container">
                            <input id="userPassword" type="password" name="userPassword" class="inputEstilo1 input-pill" required placeholder="Contraseña">
                            <button type="button" toggle-target="userPassword" tabindex="-1" class="toggle-password input-eye-pill" aria-label="Mostrar contraseña">
                                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path d="M1.5 12s4-7 10.5-7 10.5 7 10.5 7-4 7-10.5 7S1.5 12 1.5 12z" stroke="#222" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    <circle cx="12" cy="12" r="3.5" stroke="#222" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Enlace de olvidé contraseña -->
                    <div class="col-12 text-center mb-3">
                        <a href="#" id="forgotPassword" class="text-primary" style="text-decoration: none; font-size: 14px;">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>
                </div>
                <div class="col-12">
                        <div class="row">
                            <div class="col-6">
                                <button type="submit" id="iniciar-sesion"
                                    class="btn btn-primary d-block w-100 text-center">
                                    Iniciar sesión
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="button" id="register-bttn"
                                    class="btn btn-success d-block w-100 text-center"
                                    onclick="window.location.href='register.php'">
                                    Crear una nueva cuenta
                                </button>
                            </div>
                        </div>
                    </div>
            </div>
        </form>
    </div>

    <br>

    <?php
    require_once ROOT . '/app/views/layouts/footer.php';
    ?>

    <!-- Scripts específicos -->
    <script src="<?php echo url . app . rq ?>js/loginPage.js"></script>
</body>