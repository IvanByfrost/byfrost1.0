// profileSettings.js

// Cargar datos del usuario autenticado al cargar la vista
async function cargarPerfilUsuario() {
  try {
    const res = await fetch('?view=user&action=getProfileAjax');
    const data = await res.json();
    if (data.success && data.user) {
      document.getElementById('profileFirstName').value = data.user.first_name || '';
      document.getElementById('profileLastName').value = data.user.last_name || '';
      document.getElementById('profileEmail').value = data.user.email || '';
      document.getElementById('profilePhone').value = data.user.phone || '';
      document.getElementById('profileAddress').value = data.user.address || '';
      document.getElementById('profileDocumentType').value = data.user.credential_type || '';
      document.getElementById('profileDocumentNumber').value = data.user.credential_number || '';
    }
  } catch (e) {
    // No mostrar error, solo dejar vacío
  }
}

// Guardar cambios de perfil
if (document.getElementById('profileSettingsForm')) {
  document.getElementById('profileSettingsForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const first_name = document.getElementById('profileFirstName').value;
    const last_name = document.getElementById('profileLastName').value;
    const email = document.getElementById('profileEmail').value;
    const phone = document.getElementById('profilePhone').value;
    const address = document.getElementById('profileAddress').value;
    const credential_type = document.getElementById('profileDocumentType').value;
    const credential_number = document.getElementById('profileDocumentNumber').value;
    const msgDiv = document.getElementById('profileSettingsMsg');
    msgDiv.innerText = '';
    
    // Validaciones
    if (!credential_type || !credential_number) {
      msgDiv.style.color = 'red';
      msgDiv.innerText = 'El tipo de documento y número de documento son obligatorios.';
      return;
    }
    
    try {
      const res = await fetch('app/processes/profileProcess.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
          subject: 'updateProfile',
          first_name,
          last_name,
          email,
          phone,
          address,
          credential_type,
          credential_number
        })
      });
      const data = await res.json();
      if (data.success) {
        msgDiv.style.color = 'green';
        msgDiv.innerText = data.message || 'Perfil actualizado correctamente.';
      } else {
        msgDiv.style.color = 'red';
        msgDiv.innerText = data.message || 'Error al actualizar perfil.';
      }
    } catch (err) {
      msgDiv.style.color = 'red';
      msgDiv.innerText = 'Error de conexión.';
    }
  });
}

// Inicializar
cargarPerfilUsuario(); 