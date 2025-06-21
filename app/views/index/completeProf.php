<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(dirname(__DIR__)));
}
require_once ROOT . '/app/layouts/head.php';
require_once ROOT . '/app/layouts/header.php';
?>

<body>
    <div class="container mt-5">
        <form id="CompleteProfile">
            <h2>¡Vamos a completar tu perfil!</h2>
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['user'] ?? '') ?>">

            <input type="text" class="form-control mb-3" id="userName" name="userName" placeholder="Nombre" required>
            <input type="text" class="form-control mb-3" id="lastnameUser" name="userName" placeholder="Apellido" required>
            <input type="text" class="form-control mb-3" name="userPhone" placeholder="Teléfono" pattern="[0-9]+" title="Only Numbers" onkeyup="onlyNumbers('userPhone',value);" autocomplete="off" required>
            <input type="text" class="form-control mb-3" name="addressUser" placeholder="Dirección" required>

            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>

    <script src="<?php echo url . rq ?>js/completarPerfil.js"></script>
</body>