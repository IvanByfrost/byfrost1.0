// profileSettings.js

// Load authenticated user data when the view loads
async function loadUserProfile() {
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
    const msgDiv = document.getElementById('profileSettingsMsg');
    msgDiv.innerText = '';

    // Validar archivo
    const fileInput = document.getElementById('fileInput');
    const file = fileInput.files[0];
    if (file) {
      const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
      if (!allowedTypes.includes(file.type)) {
        msgDiv.style.color = 'red';
        msgDiv.innerText = 'Solo se permiten imágenes JPG, PNG o WEBP.';
        return;
      }
      if (file.size > 2 * 1024 * 1024) { // 2MB
        msgDiv.style.color = 'red';
        msgDiv.innerText = 'La imagen no debe superar los 2MB.';
        return;
      }
    }

    // Usar FormData para enviar todos los datos
    const form = document.getElementById('profileSettingsForm');
    const formData = new FormData(form);
    formData.append('subject', 'updateProfile');

    try {
      const res = await fetch('app/processes/profileProcess.php', {
        method: 'POST',
        body: formData
      });
      const data = await res.json();
      if (data.success) {
        msgDiv.style.color = 'green';
        msgDiv.innerText = data.message || 'Perfil actualizado correctamente.';
        // Recargar la página para mostrar la nueva foto
        setTimeout(() => window.location.reload(), 1000);
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

// Initialize
loadUserProfile(); 