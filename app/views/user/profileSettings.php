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
  </style>
</head>
<body>

  <div class="profile-settings-container">
    <h2 style="text-align:center;">⚙️ Mi perfil</h2>
    
    <!-- Sección de subida de foto -->
    <div class="upload-container">
      <div class="profile-placeholder" id="profileImage"></div>
      <button class="btn-upload" onclick="document.getElementById('fileInput').click()">Cargar foto</button><br>
      <input type="file" id="fileInput" accept="image/*" style="display: none;">
    </div>

    <!-- Formulario de configuración -->
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

  <script src="<?php echo url; ?>app/resources/js/jquery-3.3.1.min.js"></script>
  <script src="<?php echo url; ?>app/resources/js/bootstrap.bundle.js"></script>
  <script src="<?php echo url; ?>app/resources/js/profileSettings.js"></script>
</body>
</html>