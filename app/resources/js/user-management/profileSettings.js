// profileSettings.js

/**
 * Carga el perfil del usuario autenticado.
 */
async function loadUserProfile() {
  try {
    const res = await fetch('?view=user&action=getProfileAjax');
    if (!res.ok) {
      console.error('[ProfileSettings] Error HTTP al cargar perfil:', res.status);
      return;
    }
    const data = await res.json();
    if (data.success && data.user) {
      console.log('[ProfileSettings] Perfil cargado:', data.user);
      document.getElementById('profileFirstName')?.value = data.user.first_name || '';
      document.getElementById('profileLastName')?.value = data.user.last_name || '';
      document.getElementById('profileEmail')?.value = data.user.email || '';
      document.getElementById('profilePhone')?.value = data.user.phone || '';
      document.getElementById('profileAddress')?.value = data.user.address || '';
      document.getElementById('profileDocumentType')?.value = data.user.credential_type || '';
      document.getElementById('profileDocumentNumber')?.value = data.user.credential_number || '';
    } else {
      console.log('[ProfileSettings] No se encontró información de usuario.');
    }
  } catch (e) {
    console.error('[ProfileSettings] Error al cargar perfil:', e);
  }
}

/**
 * Muestra un mensaje en SweetAlert o en un div.
 */
function showProfileMessage(message, type = 'info') {
  console.log('[ProfileSettings] Mensaje:', message);
  if (typeof Swal !== 'undefined') {
    Swal.fire({
      title: type === 'success' ? 'Éxito' : 'Error',
      text: message,
      icon: type,
      confirmButtonText: 'OK'
    });
  } else {
    const msgDiv = document.getElementById('profileSettingsMsg');
    if (msgDiv) {
      msgDiv.style.color = (type === 'success') ? 'green' : 'red';
      msgDiv.innerText = message;
    } else {
      alert(message);
    }
  }
}

/**
 * Procesa el envío del formulario de configuración de perfil.
 */
async function handleProfileFormSubmit(e) {
  e.preventDefault();

  const fileInput = document.getElementById('fileInput');
  const file = fileInput?.files[0];

  if (file) {
    const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
    if (!allowedTypes.includes(file.type)) {
      showProfileMessage('Solo se permiten imágenes JPG, PNG o WEBP.', 'error');
      return;
    }
    if (file.size > 2 * 1024 * 1024) {
      showProfileMessage('La imagen no debe superar los 2MB.', 'error');
      return;
    }
  }

  const form = document.getElementById('profileSettingsForm');
  if (!form) {
    console.error('[ProfileSettings] Formulario no encontrado.');
    return;
  }

  const formData = new FormData(form);
  formData.append('subject', 'updateProfile');

  try {
    const res = await fetch('app/processes/profileProcess.php', {
      method: 'POST',
      body: formData
    });

    if (!res.ok) {
      throw new Error(`Error HTTP: ${res.status}`);
    }

    const data = await res.json();
    console.log('[ProfileSettings] Respuesta al guardar perfil:', data);

    if (data.success) {
      showProfileMessage(data.message || 'Perfil actualizado correctamente.', 'success');
      setTimeout(() => window.location.reload(), 1000);
    } else {
      showProfileMessage(data.message || 'Error al actualizar perfil.', 'error');
    }
  } catch (err) {
    console.error('[ProfileSettings] Error en petición:', err);
    showProfileMessage('Error de conexión al actualizar el perfil.', 'error');
  }
}

// Event listener para el formulario
const profileForm = document.getElementById('profileSettingsForm');
if (profileForm) {
  profileForm.addEventListener('submit', handleProfileFormSubmit);
} else {
  console.log('[ProfileSettings] Formulario de configuración no presente.');
}

// Inicialización
loadUserProfile();
