<form id="changePasswordForm">
    <input type="hidden" name="csrf_token" value='<?= Validator::generateCSRFToken() ?>'>

    <div class="mb-3">
        <input type="password" class="inputEstilo1" id="currentPassword" name="currentPassword"
            placeholder="Contraseña actual" required>
    </div>
    <div class="mb-3">
        <input type="password" class="inputEstilo1" id="newPassword" name="newPassword"
            placeholder="Nueva contraseña" required>
    </div>
    <div class="mb-3">
        <input type="password" class="inputEstilo1" id="confirmPassword" name="confirmPassword"
            placeholder="Confirmar nueva contraseña" required>
    </div>
    <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary">
            Cambiar contraseña
        </button>
    </div>
</form>