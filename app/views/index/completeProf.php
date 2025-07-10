<?php
if (!defined('ROOT')) {
    define('ROOT', dirname(__DIR__, 3));
}
require_once ROOT . '/config.php';
require_once ROOT . '/app/views/layouts/head.php';
require_once ROOT . '/app/views/layouts/header.php';
?>
<script>
    const ROOT = "<?php echo url ?>"; 
</script>
<br>
<div class="container">
    <form id="completeProfile" class="login-box">
    <input type="hidden" name="csrf_token" value='<?= Validator::generateCSRFToken() ?>'>

        <h2>¡Vamos a completar tu perfil!</h2>
        <br>
        <input type="hidden" name="userDocument" value="<?php echo htmlspecialchars($_GET['user'] ?? '') ?>">
        <div class="input-container">
            <input type="text" class="inputEstilo1 input-pill" id="userName" name="userName" placeholder="Nombre" required>
        </div>
        <div class="input-container">
            <input type="text" class="inputEstilo1 input-pill" id="lastnameUser" name="lastnameUser" placeholder="Apellido" required>
        </div>
        <div class="input-container">
            <input type="date" class="inputEstilo1 input-pill" id="dob" name="dob" placeholder="Fecha de nacimiento" required>
        </div>
        <div class="input-container">
            <input type="text" class="inputEstilo1 input-pill" name="userPhone" id="userPhone" placeholder="Teléfono" pattern="[0-9]+" title="Only Numbers" onkeyup="onlyNumbers('userPhone',value);" autocomplete="off" required>
        </div>
        <div class="input-container">
            <input type="text" class="inputEstilo1 input-pill" name="addressUser" id="addressUser" placeholder="Dirección" required>
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</div>
<br>
<?php
require_once __DIR__ . '/../layouts/footer.php';
?>