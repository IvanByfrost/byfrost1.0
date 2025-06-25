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
        <h2>¡Vamos a completar tu perfil!</h2>
        <br>
        <input type="hidden" name="userDocument" value="<?php echo htmlspecialchars($_GET['user'] ?? '') ?>">
        <input type="text" class="inputEstilo1" id="userName" name="userName" placeholder="Nombre" required>
        <input type="text" class="inputEstilo1" id="lastnameUser" name="lastnameUser" placeholder="Apellido" required>
        <input type="date" class="inputEstilo1" id="dob" name="dob" placeholder="Fecha de nacimiento" required>
        <input type="text" class="inputEstilo1" name="userPhone" id="userPhone" placeholder="Teléfono" pattern="[0-9]+" title="Only Numbers" onkeyup="onlyNumbers('userPhone',value);" autocomplete="off" required>
        <input type="text" class="inputEstilo1" name="addressUser" id="addressUser" placeholder="Dirección" required>

        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</div>
<br>
<?php
require_once __DIR__ . '/../layouts/footer.php';
?>