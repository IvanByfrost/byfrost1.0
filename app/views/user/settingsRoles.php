<main class="main">
  <!-- Usuarios -->
  <section id="usuarios" class="section active">
    <h3>👥 Lista de usuarios BYFROST</h3>
    <table id="tablaUsuarios">
      <thead>
        <tr><th>Nombre</th><th>Usuario</th><th>Rol</th><th>Tipo Doc.</th><th>Número</th><th>Acciones</th></tr>
      </thead>
      <tbody></tbody>
    </table>
  </section>

  <!-- Recuperar -->
  <section id="recuperar" class="section">
    <h3>🔐 Recuperar contraseña</h3>
    <input id="usuarioRecuperar" placeholder="Usuario (correo)" />
    <button class="action" onclick="recuperarClave()">Recuperar</button>
    <div id="recuperarMsg"></div>
  </section>
</main>

<!-- Modal de edición de usuario -->
<div id="editUserModal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); z-index:9999; align-items:center; justify-content:center;">
  <div class="modal-content" style="background:#fff; padding:2rem; border-radius:8px; max-width:400px; margin:auto; position:relative;">
    <span id="closeEditUserModal" style="position:absolute; top:10px; right:15px; cursor:pointer; font-size:1.5rem;">&times;</span>
    <h3>Editar usuario</h3>
    <form id="editUserForm">
      <input type="hidden" id="editUserId" name="user_id">
      <div class="mb-2">
        <input type="text" class="inputEstilo1" id="editFirstName" name="first_name" placeholder="Nombre" required>
      </div>
      <div class="mb-2">
        <input type="text" class="inputEstilo1" id="editLastName" name="last_name" placeholder="Apellido" required>
      </div>
      <div class="mb-2">
        <input type="email" class="inputEstilo1" id="editEmail" name="email" placeholder="Correo electrónico" required>
      </div>
      <div class="mb-2">
        <input type="text" class="inputEstilo1" id="editPhone" name="phone" placeholder="Teléfono">
      </div>
      <div class="mb-2">
        <input type="text" class="inputEstilo1" id="editAddress" name="address" placeholder="Dirección">
      </div>
      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary">Guardar cambios</button>
      </div>
      <div id="editUserMsg" style="margin-top:10px;"></div>
    </form>
  </div>
</div> 