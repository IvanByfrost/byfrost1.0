<?php
// Vista de configuración de perfil personal para el usuario autenticado
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Configuración de Perfil - Byfrost</title>
  <link rel="stylesheet" href="<?php echo url; ?>app/resources/css/bootstrap.css">
  <link rel="stylesheet" href="<?php echo url; ?>app/resources/css/profileSettings.css">
</head>
<body>

  <div class="profile-settings-container">
    <h2 style="text-align:center;">⚙️ Mi perfil</h2>
    
    <!-- Sección de subida de foto -->
    <div class="upload-container">
      <div class="profile-placeholder" id="profileImage">
        <?php if (isset($_SESSION['user']['profile_photo']) && $_SESSION['user']['profile_photo'] && file_exists(ROOT . '/app/resources/img/profiles/' . $_SESSION['user']['profile_photo'])): ?>
          <img src="<?php echo url . 'app/resources/img/profiles/' . $_SESSION['user']['profile_photo']; ?>" alt="Foto de perfil" class="profile-photo">
        <?php else: ?>
          <img src="https://upload.wikimedia.org/wikipedia/commons/9/99/Sample_User_Icon.png" alt="Foto de perfil" class="profile-photo">
        <?php endif; ?>
      </div>
      <button class="btn-upload" onclick="document.getElementById('fileInput').click(); return false;">Cargar foto</button><br>
      <input type="file" id="fileInput" name="profile_photo" accept="image/*" style="display: none;">
    </div>

    <!-- Formulario de configuración -->
    <form id="profileSettingsForm" enctype="multipart/form-data" method="post">
      <input type="hidden" name="csrf_token" value='<?= Validator::generateCSRFToken() ?>'>

      <!-- Sección de documento -->
      <div class="document-section">
        <h4>📋 Información de Documento</h4>
        <div class="document-warning">
          <strong>⚠️ Importante:</strong> Solo cambia estos campos si te registraste con un tipo de documento incorrecto (ej: CC en lugar de TI).
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label for="profileDocumentType">Tipo de Documento</label>
              <select class="inputEstilo1" id="profileDocumentType" name="credential_type" required>
                <option value="">Seleccione...</option>
                <option value="CC">Cédula de Ciudadanía (CC)</option>
                <option value="TI">Tarjeta de Identidad (TI)</option>
                <option value="CE">Cédula de Extranjería (CE)</option>
                <option value="PP">Pasaporte (PP)</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label for="profileDocumentNumber">Número de Documento</label>
              <input type="text" class="inputEstilo1" id="profileDocumentNumber" name="credential_number" required>
            </div>
          </div>
        </div>
      </div>

      <!-- Información personal -->
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

  <script src="<?php echo url; ?>app/resources/js/profileSettings.js"></script>
</body>
</html>
