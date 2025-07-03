// settingsRoles.js

function showSection(id, event) {
  document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
  document.getElementById(id).classList.add('active');
  document.querySelectorAll('.sidebar button').forEach(b => b.classList.remove('active'));
  if(event) event.target.classList.add('active');
  if (id === 'usuarios') cargarUsuarios();
}

// Cargar usuarios vía AJAX
async function cargarUsuarios() {
  const tbody = document.querySelector("#tablaUsuarios tbody");
  tbody.innerHTML = '<tr><td colspan="6">Cargando...</td></tr>';
  try {
    const res = await fetch('?view=user&action=getUsersAjax');
    const data = await res.json();
    if (data.success && Array.isArray(data.users)) {
      tbody.innerHTML = '';
      data.users.forEach(u => {
        tbody.innerHTML += `
          <tr>
            <td>${u.first_name} ${u.last_name}</td>
            <td>${u.email}</td>
            <td>${u.role_type || ''}</td>
            <td>${u.credential_type || ''}</td>
            <td>${u.credential_number || ''}</td>
            <td>
              <button class="action edit-btn" onclick='abrirModalEditarUsuario(${JSON.stringify(u)})'>Editar</button>
              <button class="action delete-btn" onclick="eliminarUsuario(${u.user_id})">Eliminar</button>
            </td>
          </tr>`;
      });
    } else {
      tbody.innerHTML = '<tr><td colspan="6">No hay usuarios.</td></tr>';
    }
  } catch (e) {
    tbody.innerHTML = '<tr><td colspan="6">Error al cargar usuarios.</td></tr>';
  }
}

// Abrir modal de edición de usuario
function abrirModalEditarUsuario(user) {
  const modal = document.getElementById('editUserModal');
  document.getElementById('editUserId').value = user.user_id;
  document.getElementById('editFirstName').value = user.first_name || '';
  document.getElementById('editLastName').value = user.last_name || '';
  document.getElementById('editEmail').value = user.email || '';
  document.getElementById('editPhone').value = user.phone || '';
  document.getElementById('editAddress').value = user.address || '';
  document.getElementById('editUserMsg').innerText = '';
  modal.style.display = 'flex';
}

// Cerrar modal
if (document.getElementById('closeEditUserModal')) {
  document.getElementById('closeEditUserModal').onclick = function() {
    document.getElementById('editUserModal').style.display = 'none';
  };
}

// Cerrar modal al hacer click fuera del contenido
window.onclick = function(event) {
  const modal = document.getElementById('editUserModal');
  if (event.target === modal) {
    modal.style.display = 'none';
  }
};

// Enviar edición de usuario por AJAX
if (document.getElementById('editUserForm')) {
  document.getElementById('editUserForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const user_id = document.getElementById('editUserId').value;
    const first_name = document.getElementById('editFirstName').value;
    const last_name = document.getElementById('editLastName').value;
    const email = document.getElementById('editEmail').value;
    const phone = document.getElementById('editPhone').value;
    const address = document.getElementById('editAddress').value;
    const msgDiv = document.getElementById('editUserMsg');
    msgDiv.innerText = '';
    try {
      const res = await fetch('?view=user&action=editUserAjax', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          user_id,
          first_name,
          last_name,
          email,
          phone,
          address
        })
      });
      const data = await res.json();
      if (data.success) {
        msgDiv.style.color = 'green';
        msgDiv.innerText = data.message || 'Usuario editado correctamente.';
        setTimeout(() => {
          document.getElementById('editUserModal').style.display = 'none';
          cargarUsuarios();
        }, 1000);
      } else {
        msgDiv.style.color = 'red';
        msgDiv.innerText = data.message || 'Error al editar usuario.';
      }
    } catch (err) {
      msgDiv.style.color = 'red';
      msgDiv.innerText = 'Error de conexión.';
    }
  });
}

// Eliminar usuario
async function eliminarUsuario(userId) {
  if (!confirm('¿Eliminar este usuario?')) return;
  const res = await fetch('?view=user&action=deleteUserAjax', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ user_id: userId })
  });
  const data = await res.json();
  alert(data.message || (data.success ? 'Usuario eliminado' : 'Error al eliminar usuario'));
  if (data.success) cargarUsuarios();
}

// Recuperar clave (simulado)
async function recuperarClave() {
  const usuario = document.getElementById('usuarioRecuperar').value;
  if (!usuario) return alert('Ingresa el usuario (correo)');
  document.getElementById('recuperarMsg').innerText = 'Funcionalidad en desarrollo.';
}

// Inicializar cuando se cargue la vista
function initSettingsRoles() {
  // Leer parámetro 'section' de la URL
  const params = new URLSearchParams(window.location.search);
  const section = params.get('section');
  if (section && document.getElementById(section)) {
    showSection(section);
  } else {
    cargarUsuarios();
    showSection('usuarios');
  }
}

// Si se carga dinámicamente, llamar a initSettingsRoles()
if (typeof window !== 'undefined') {
  setTimeout(initSettingsRoles, 0);
}

// Cambio de contraseña desde perfil
if (document.getElementById('changePasswordForm')) {
  document.getElementById('changePasswordForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const currentPassword = document.getElementById('currentPassword').value;
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    const form = this;
    // Limpia mensajes previos
    let msgDiv = document.getElementById('changePasswordMsg');
    if (!msgDiv) {
      msgDiv = document.createElement('div');
      msgDiv.id = 'changePasswordMsg';
      form.appendChild(msgDiv);
    }
    msgDiv.innerText = '';
    try {
      const res = await fetch('app/processes/profileProcess.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
          subject: 'changePassword',
          currentPassword,
          newPassword,
          confirmPassword
        })
      });
      const data = await res.json();
      if (data.success) {
        msgDiv.style.color = 'green';
        msgDiv.innerText = data.message || 'Contraseña cambiada correctamente.';
        form.reset();
      } else {
        msgDiv.style.color = 'red';
        msgDiv.innerText = data.message || 'Error al cambiar la contraseña.';
      }
    } catch (err) {
      msgDiv.style.color = 'red';
      msgDiv.innerText = 'Error de conexión.';
    }
  });
} 