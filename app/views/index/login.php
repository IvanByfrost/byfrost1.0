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
                    <div class="input-container" style="position: relative; padding: 0;">
                        <input id="userPassword" type="password" name="userPassword" class="inputEstilo1 input-pill" required placeholder="Contraseña">
                        <button type="button" toggle-target="userPassword" tabindex="-1" class="toggle-password input-eye-pill" aria-label="Mostrar contraseña">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path d="M1.5 12s4-7 10.5-7 10.5 7 10.5 7-4 7-10.5 7S1.5 12 1.5 12z" stroke="#222" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="12" cy="12" r="3.5" stroke="#222" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
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
    .input-container {
      position: relative !important;
      width: 100%;
    }
    .input-container input.input-pill {
      width: 100% !important;
      border-radius: 999px !important;
      background: #f2f2f2 !important;
      border: none !important;
      padding-left: 24px !important;
      padding-right: 56px !important;
      font-weight: 400 !important;
      font-size: 18px !important;
      color: #222 !important;
      box-shadow: none !important;
      height: 54px !important;
      margin-bottom: 0 !important;
    }
    .input-container input.input-pill:focus {
      outline: none !important;
      border: 1.5px solid #a5ffa0 !important;
      background: #f8f8f8 !important;
    }
    .input-container input.input-pill::placeholder {
      color: #bdbdbd !important;
      font-weight: 300 !important;
      font-size: 18px !important;
      opacity: 1 !important;
    }
    .input-container .toggle-password, .input-container .input-eye-pill {
      position: absolute !important;
      top: 50% !important;
      right: 16px !important;
      transform: translateY(-50%) !important;
      background: none !important;
      border: none !important;
      padding: 0 !important;
      margin: 0 !important;
      width: 32px !important;
      height: 32px !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      cursor: pointer !important;
      z-index: 3 !important;
      box-shadow: none !important;
    }
    .input-container .toggle-password svg, .input-container .input-eye-pill svg {
      width: 28px !important;
      height: 28px !important;
      color: #222 !important;
      pointer-events: none !important;
    }
    </style>
</body>