<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(__DIR__)));
}
require_once ROOT . '/app/layouts/head.php';
require_once ROOT . '/app/layouts/header.php';
?>

<body>
    <div class="container mt-5">
        <form id="completeProfile">
            <h2>¡Vamos a completar tu perfil!</h2>
            <input type="hidden" name="userDocument" value="<?php echo htmlspecialchars($_GET['user'] ?? '') ?>">
            <input type="text" class="form-control mb-3" id="userName" name="userName" placeholder="Nombre" required>
            <input type="text" class="form-control mb-3" id="lastnameUser" name="lastnameUser" placeholder="Apellido" required>
            <input type="date" class="form-control mb-3" id="dob" name="dob" placeholder="Fecha de nacimiento" required>
            <input type="text" class="form-control mb-3" name="userPhone" id="userPhone" placeholder="Teléfono" pattern="[0-9]+" title="Only Numbers" onkeyup="onlyNumbers('userPhone',value);" autocomplete="off" required>
            <input type="text" class="form-control mb-3" name="addressUser" id="addressUser" placeholder="Dirección" required>

            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>

    <script src="<?php echo url . rq ?>js/completarPerfil.js"></script>
</body>