// settingsRoles.js

function showSection(id, event) {
  document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
  document.getElementById(id).classList.add('active');
  document.querySelectorAll('.sidebar button').forEach(b => b.classList.remove('active'));
  if(event) event.target.classList.add('active');
  if (id === 'usuarios') loadUsers();
}

// Load users via AJAX
async function loadUsers() {
  const tbody = document.querySelector("#tablaUsuarios tbody");
  tbody.innerHTML = '<tr><td colspan="6">Loading...</td></tr>';
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
              <button class="action edit-btn" onclick='openEditUserModal(${JSON.stringify(u)})'>Edit</button>
              <button class="action delete-btn" onclick="deleteUser(${u.user_id})">Delete</button>
            </td>
          </tr>`;
      });
    } else {
      tbody.innerHTML = '<tr><td colspan="6">No users found.</td></tr>';
    }
  } catch (e) {
    tbody.innerHTML = '<tr><td colspan="6">Error loading users.</td></tr>';
  }
}

// Open user edit modal
function openEditUserModal(user) {
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
          loadUsers();
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

// Deactivate user (soft delete)
async function deactivateUser(userId) {
  if (!confirm('¿Desactivar este usuario? El usuario no podrá acceder al sistema pero los datos se mantendrán.')) return;
  const res = await fetch('?view=user&action=deleteUserAjax', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ user_id: userId })
  });
  const data = await res.json();
  alert(data.message || (data.success ? 'Usuario desactivado' : 'Error al desactivar usuario'));
  if (data.success) loadUsers();
}

// Delete user permanently (hard delete)
async function deleteUserPermanently(userId) {
  // First confirmation
  if (!confirm('⚠️ WARNING: This action will permanently delete the user and all their data.\n\n¿Está seguro de que desea continuar?')) return;
  
  // Second confirmation
  if (!confirm('🚨 PERMANENT DELETION\n\nThis action CANNOT be undone.\n\n¿Confirma que desea eliminar permanentemente este usuario?')) return;
  
  try {
    // Check if user can be deleted
    const checkRes = await fetch('?view=user&action=checkCanDeleteUserAjax', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ user_id: userId })
    });
    const checkData = await checkRes.json();
    
    if (!checkData.success) {
      alert('❌ Cannot delete: ' + checkData.message);
      return;
    }
    
    // Proceed with deletion
    const res = await fetch('?view=user&action=deleteUserPermanentlyAjax', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ user_id: userId })
    });
    const data = await res.json();
    alert(data.message || (data.success ? 'Usuario eliminado permanentemente' : 'Error al eliminar usuario'));
    if (data.success) loadUsers();
    
  } catch (error) {
    alert('Connection error: ' + error.message);
  }
}

// Delete user (main function with options)
async function deleteUser(userId) {
  // Show options to user
  const action = confirm('¿Qué acción desea realizar?\n\nOK = Desactivar usuario (reversible)\nCancelar = Eliminar permanentemente (irreversible)');
  
  if (action) {
    // Deactivate
    await deactivateUser(userId);
  } else {
    // Delete permanently
    await deleteUserPermanently(userId);
  }
}

// Recover password (simulated)
async function recoverPassword() {
  const user = document.getElementById('usuarioRecuperar').value;
  if (!user) return alert('Enter user (email)');
  document.getElementById('recuperarMsg').innerText = 'Feature in development.';
}

// Initialize when view loads
function initSettingsRoles() {
  // Read 'section' parameter from URL
  const params = new URLSearchParams(window.location.search);
  const section = params.get('section');
  if (section && document.getElementById(section)) {
    showSection(section);
  } else {
    loadUsers();
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