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
              <button class="action edit-btn" onclick="editarUsuario(${u.user_id})">Editar</button>
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

// Editar usuario (abrir prompt)
async function editarUsuario(userId) {
  const nuevoNombre = prompt('Nuevo nombre completo:');
  if (!nuevoNombre) return;
  const res = await fetch('?view=user&action=editUserAjax', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ user_id: userId, nombre: nuevoNombre })
  });
  const data = await res.json();
  alert(data.message || (data.success ? 'Usuario editado' : 'Error al editar usuario'));
  if (data.success) cargarUsuarios();
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