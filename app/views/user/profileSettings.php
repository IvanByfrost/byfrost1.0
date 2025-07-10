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
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f8f9fa;
      padding: 20px;
    }

    .profile-settings-container {
      max-width: 600px;
      margin: 2rem auto;
      background: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.07);
      padding: 2rem;
    }

    .upload-container {
      border: 1px solid #ccc;
      padding: 20px;
      width: 250px;
      margin: auto;
      border-radius: 8px;
      background-color: #f9f9f9;
      margin-bottom: 2rem;
    }

    .profile-placeholder {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      background: url('https://upload.wikimedia.org/wikipedia/commons/9/99/Sample_User_Icon.png') no-repeat center;
      background-size: cover;
      margin: auto;
      margin-bottom: 15px;
    }

    .btn-upload {
      background-color: #007bff;
      color: white;
      border: none;
      padding: 10px 16px;
      border-radius: 5px;
      cursor: pointer;
      margin-bottom: 10px;
    }

    .btn-upload:hover {
      background-color: #0056b3;
    }

    input[type="file"] {
      margin-top: 5px;
    }

    .inputEstilo1 {
      width: 100%;
      padding: 8px 12px;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 14px;
    }

    .inputEstilo1:focus {
      outline: none;
      border-color: #007bff;
      box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
    }

    .mb-3 {
      margin-bottom: 1rem;
    }

    .d-grid {
      display: grid;
    }

    .gap-2 {
      gap: 0.5rem;
    }

    .btn {
      display: inline-block;
      font-weight: 400;
      text-align: center;
      vertical-align: middle;
      cursor: pointer;
      padding: 0.375rem 0.75rem;
      font-size: 1rem;
      line-height: 1.5;
      border-radius: 0.25rem;
      transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .btn-primary {
      color: #fff;
      background-color: #007bff;
      border-color: #007bff;
    }

    .btn-primary:hover {
      color: #fff;
      background-color: #0056b3;
      border-color: #0056b3;
    }

    .document-section {
      background-color: #f8f9fa;
      border: 1px solid #dee2e6;
      border-radius: 8px;
      padding: 1rem;
      margin-bottom: 1rem;
    }

    .document-section h4 {
      color: #495057;
      margin-bottom: 1rem;
      font-size: 1.1rem;
    }

    .document-warning {
      background-color: #fff3cd;
      border: 1px solid #ffeaa7;
      color: #856404;
      padding: 0.75rem;
      border-radius: 4px;
      margin-bottom: 1rem;
      font-size: 0.9rem;
    }

    .row {
      display: flex;
      flex-wrap: wrap;
      margin-right: -0.5rem;
      margin-left: -0.5rem;
    }

    .col-md-6 {
      flex: 0 0 50%;
      max-width: 50%;
      padding-right: 0.5rem;
      padding-left: 0.5rem;
    }

    @media (max-width: 768px) {
      .col-md-6 {
        flex: 0 0 100%;
        max-width: 100%;
      }
    }
  </style>
</head>
<body>

  <div class="profile-settings-container">
    <h2 style="text-align:center;">⚙️ Mi perfil</h2>
    
    <!-- Sección de subida de foto -->
    <div class="upload-container">
      <div class="profile-placeholder" id="profileImage">
        <?php if (isset($_SESSION['user']['profile_photo']) && $_SESSION['user']['profile_photo'] && file_exists(ROOT . '/app/resources/img/profiles/' . $_SESSION['user']['profile_photo'])): ?>
          <img src="<?php echo url . 'app/resources/img/profiles/' . $_SESSION['user']['profile_photo']; ?>" alt="Foto de perfil" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover;">
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

</body>
</html>