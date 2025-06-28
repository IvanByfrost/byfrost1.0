<?php
//require_once(__DIR__ . '/../../config.php');
require_once __DIR__ . '/../layouts/head.php';
require_once __DIR__ . '/../layouts/header.php';
?>
<script>
    const ROOT = "<?php echo url ?>"; 
</script>
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
                    <input type="email" class="inputEstilo1" id="userEmail" name="userEmail" placeholder="Correo Electrónico" autocomplete="off" required>
                </div>
                <div class="col-12">
                    <input type="password" class="inputEstilo1" id="userPassword" name="userPassword" placeholder="Contraseña" required>
                </div>
                <div class="col-12">
                    <input type="password" class="inputEstilo1" id="passwordConf" name="passwordConf" placeholder="Confirmar Contraseña" required>
                </div>

                <div class="col-6">
                    <button type="submit" id="register-bttn" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Completar Registro</button>
                </div>
                <div class="col-6">
                    <a href="login.php">
                        <button type="button" id="iniciar-sesion" class="bg-green-700 hover:bg-green-800 text-yellow-300 font-bold py-2 px-4 rounded" style="color: white;">Iniciar Sesión</button>
                    </a>
                </div>
            </div>
        </form>
    </div>
    <br>

    <?php
    require_once __DIR__ . '/../layouts/footer.php';
    ?>
    
    <!-- Scripts específicos -->
    <script src="<?php echo url . app . rq ?>js/registerFunction.js"></script>
    <script>
        console.log("Página de registro cargada");
        console.log("URL del script:", "<?php echo url . app . rq ?>js/registerFunction.js");
    </script>
</body>