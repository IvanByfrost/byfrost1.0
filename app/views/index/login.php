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
        <form id="loginForm" class="login-box" method="POST" action="<?php echo url ?>app/processes/loginProcess.php">
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
                    <div class="col-12" style="position: relative; padding: 0;">
                        <input type="password" class="inputEstilo1" id="userPassword" name="userPassword" placeholder="Password" required style="padding-right: 36px;">
                        <button type="button" id="togglePassword" tabindex="-1" class="btn-eye-icon-fix" aria-label="Show password">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 16 16" stroke="currentColor">
                                <path d="M1.333 8s2.333-4 6.667-4 6.667 4 6.667 4-2.333 4-6.667 4-6.667-4-6.667-4z" stroke="#888" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="8" cy="8" r="2" stroke="#888" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="col-12 mb-2">
                    <!-- Eliminado: checkbox y texto 'Show password' -->
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
    <style>
    .input-group.password-group {
        position: relative;
        width: 100%;
    }
    .input-group.password-group input[type='password'],
    .input-group.password-group input[type='text'] {
        width: 100%;
        padding-right: 38px;
    }
    .btn-eye {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 2;
        padding: 0;
        background: none;
        border: none;
        outline: none;
        box-shadow: none;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    .btn-eye:active, .btn-eye:focus {
        outline: none;
        box-shadow: none;
    }
    #eyeIcon svg {
        width: 20px;
        height: 20px;
        color: #888;
        display: block;
    }
    .btn-eye-icon {
        position: absolute;
        top: 50%;
        right: 18px;
        transform: translateY(-50%);
        background: none;
        border: none;
        padding: 0;
        margin: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 3;
    }
    .btn-eye-icon:focus {
        outline: none;
        box-shadow: none;
    }
    .btn-eye-icon svg {
        width: 16px;
        height: 16px;
        color: #888;
        pointer-events: none;
    }
    .position-relative {
        position: relative !important;
    }
    .btn-eye-icon-fix {
        position: absolute;
        top: 0;
        bottom: 0;
        right: 12px;
        margin: auto 0;
        background: none !important;
        border: none;
        padding: 0;
        width: 22px;
        height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 3;
        box-shadow: none !important;
    }
    .btn-eye-icon-fix:focus {
        outline: none;
        box-shadow: none;
    }
    .btn-eye-icon-fix svg {
        width: 18px;
        height: 18px;
        color: #888;
        pointer-events: none;
    }
    </style>
</body>