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
                    <div class="input-group password-group">
                        <input type="password" class="inputEstilo1" id="userPassword" name="userPassword" placeholder="Password" required>
                        <button type="button" class="btn-eye" id="togglePassword" tabindex="-1">
                            <span id="eyeIcon">
                                <!-- Ojo cerrado por defecto, tamaño y color ajustados -->
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" style="display:block;" fill="none" viewBox="0 0 24 24" stroke="#888">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-5.523 0-10-4.477-10-10 0-1.657.336-3.234.938-4.675M15 12a3 3 0 11-6 0 3 3 0 016 0zm6.062-4.675A9.956 9.956 0 0122 9c0 5.523-4.477 10-10 10a9.956 9.956 0 01-4.675-.938"/>
                                </svg>
                            </span>
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
    </style>
</body>