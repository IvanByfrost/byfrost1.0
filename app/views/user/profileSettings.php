<?php
// Vista de configuración de perfil personal para el usuario autenticado
?>
<div class="profile-settings-container" style="max-width: 500px; margin: 2rem auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.07); padding: 2rem;">
  <h2 style="text-align:center;">⚙️ Mi perfil</h2>
  <form id="profileSettingsForm">
    <div class="mb-3">
      <label for="profileFirstName">Nombre</label>
      <input type="text" class="inputEstilo1" id="profileFirstName" name="first_name" required>
    </div>
    <div class="mb-3">
      <label for="profileLastName">Apellido</label>
      <input type="text" class="inputEstilo1" id="profileLastName" name="last_name" required>
    </div>
    <div class="mb-3">
      <label for="profileEmail">Correo electrónico</label>
      <input type="email" class="inputEstilo1" id="profileEmail" name="email" required>
    </div>
    <div class="mb-3">
      <label for="profilePhone">Teléfono</label>
      <input type="text" class="inputEstilo1" id="profilePhone" name="phone">
    </div>
    <div class="mb-3">
      <label for="profileAddress">Dirección</label>
      <input type="text" class="inputEstilo1" id="profileAddress" name="address">
    </div>
    <div class="d-grid gap-2">
      <button type="submit" class="btn btn-primary">Guardar cambios</button>
    </div>
    <div id="profileSettingsMsg" style="margin-top:10px;"></div>
  </form>
</div>